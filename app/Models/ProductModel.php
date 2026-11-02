<?php
require_once __DIR__ . '/BaseModel.php';

class ProductModel extends BaseModel {

    /**
     * Thêm mới một sản phẩm cùng với các biến thể (variants) và hình ảnh (images).
     * Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu: 
     * Nếu lỗi ở bước nào thì sẽ Rollback (hoàn tác) toàn bộ.
     */
    public function createProduct($data, $variants, $images) {
        try {
            // Bắt đầu Transaction
            $this->db->beginTransaction();

            // 1. Thêm vào bảng products
            $sql = "INSERT INTO products (category_id, name, slug, description, price, is_featured) 
                    VALUES (:category_id, :name, :slug, :description, :price, :is_featured)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_id' => $data['category_id'] ?? null,
                ':name' => $data['name'],
                ':slug' => $this->createSlug($data['name']),
                ':description' => $data['description'] ?? '',
                ':price' => $data['price'],
                ':is_featured' => $data['is_featured'] ?? 0
            ]);
            $productId = $this->db->lastInsertId(); // Lấy ID của sản phẩm vừa thêm

            // 2. Thêm vào bảng product_variants (Màu sắc, Size, Tồn kho)
            if (!empty($variants) && is_array($variants)) {
                $sqlVariant = "INSERT INTO product_variants (product_id, size, color, stock) 
                               VALUES (:product_id, :size, :color, :stock)";
                $stmtVariant = $this->db->prepare($sqlVariant);
                
                foreach ($variants as $variant) {
                    $stmtVariant->execute([
                        ':product_id' => $productId,
                        ':size' => trim($variant['size']),
                        ':color' => trim($variant['color']),
                        ':stock' => (int)($variant['stock'] ?? 0)
                    ]);
                }
            }

            // 3. Thêm vào bảng product_images (Upload nhiều ảnh)
            if (!empty($images) && is_array($images)) {
                $sqlImage = "INSERT INTO product_images (product_id, image_url, is_primary) 
                             VALUES (:product_id, :image_url, :is_primary)";
                $stmtImage = $this->db->prepare($sqlImage);
                
                foreach ($images as $index => $image) {
                    $stmtImage->execute([
                        ':product_id' => $productId,
                        ':image_url' => $image,
                        ':is_primary' => ($index === 0) ? 1 : 0 // Mặc định ảnh đầu tiên làm ảnh bìa
                    ]);
                }
            }

            // Xác nhận thành công tất cả truy vấn
            $this->db->commit();
            return $productId;

        } catch (Exception $e) {
            // Có lỗi xảy ra, hoàn tác lại toàn bộ (không để lại dữ liệu rác)
            $this->db->rollBack();
            throw $e;
        }
    }

    // Hàm tạo Slug tự động (VD: Áo Thun -> ao-thun-123456)
    private function createSlug($string) {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($string)));
        $slug = trim($slug, '-');
        return $slug . '-' . time(); // Nối thêm thời gian để tránh trùng lặp tuyệt đối
    }

    /**
     * Lấy danh sách toàn bộ sản phẩm (kèm ảnh bìa, tồn kho, số lượng bán, biến thể chi tiết) cho Admin
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.name as category_name, 
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                       (SELECT GROUP_CONCAT(CONCAT(color, ' - ', size, ' (', stock, ')') SEPARATOR ', ') FROM product_variants WHERE product_id = p.id) as variants_info,
                       COALESCE((SELECT SUM(stock) FROM product_variants WHERE product_id = p.id), 0) as total_stock,
                       COALESCE((SELECT COUNT(*) FROM product_variants WHERE product_id = p.id), 0) as variants_count,
                       COALESCE((SELECT SUM(oi.quantity) FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE oi.product_id = p.id AND o.status != 'cancelled'), 0) as total_sold
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // TỐI ƯU HIỆU NĂNG (Chống lỗi N+1 Query): Gom tất cả ID sản phẩm và truy vấn biến thể chỉ trong 1 câu lệnh duy nhất
        if (!empty($products)) {
            $productIds = array_column($products, 'id');
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $sqlVariants = "SELECT id, product_id, size, color, stock FROM product_variants WHERE product_id IN ($placeholders) ORDER BY id ASC";
            $stmtVar = $this->db->prepare($sqlVariants);
            $stmtVar->execute($productIds);
            $allVariants = $stmtVar->fetchAll(PDO::FETCH_ASSOC);

            // Gom nhóm biến thể theo product_id trong bộ nhớ PHP
            $variantsByProduct = [];
            foreach ($allVariants as $variant) {
                $variantsByProduct[$variant['product_id']][] = [
                    'id' => $variant['id'],
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'stock' => $variant['stock']
                ];
            }

            foreach ($products as &$prod) {
                $prod['variants_list'] = $variantsByProduct[$prod['id']] ?? [];
            }
            unset($prod);
        }

        return $products;
    }

    /**
     * Lấy chỉ số KPI sản phẩm cho Admin (Được tối ưu hóa Subquery chính xác)
     */
    public function getProductKPIs() {
        $totalProducts = (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $featuredProducts = (int)$this->db->query("SELECT COUNT(*) FROM products WHERE is_featured = 1")->fetchColumn();
        
        // Tồn kho thấp (dưới 10 chiếc) - Tối ưu truy vấn đếm chính xác qua subquery
        $lowStock = (int)$this->db->query("
            SELECT COUNT(*) FROM (
                SELECT p.id FROM products p
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                GROUP BY p.id
                HAVING COALESCE(SUM(pv.stock), 0) > 0 AND COALESCE(SUM(pv.stock), 0) <= 10
            ) as tbl_low_stock
        ")->fetchColumn();

        // Hết hàng hoàn toàn (0 chiếc hoặc chưa nhập biến thể)
        $outOfStock = (int)$this->db->query("
            SELECT COUNT(*) FROM (
                SELECT p.id FROM products p
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                GROUP BY p.id
                HAVING COALESCE(SUM(pv.stock), 0) = 0
            ) as tbl_out_of_stock
        ")->fetchColumn();

        // Tổng giá trị hàng tồn kho (Giá bán * Tồn kho)
        $inventoryValue = (float)$this->db->query("
            SELECT COALESCE(SUM(p.price * pv.stock), 0)
            FROM products p
            JOIN product_variants pv ON p.id = pv.product_id
        ")->fetchColumn();

        return [
            'total_products' => $totalProducts,
            'featured_products' => $featuredProducts,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'inventory_value' => $inventoryValue
        ];
    }

    /**
     * Cập nhật nhanh thông tin sản phẩm (Quick Edit)
     */
    public function updateProductQuick($id, $data) {
        $sql = "UPDATE products 
                SET name = :name, 
                    category_id = :category_id, 
                    price = :price, 
                    is_featured = :is_featured,
                    description = :description,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => trim($data['name']),
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':price' => (float)$data['price'],
            ':is_featured' => !empty($data['is_featured']) ? 1 : 0,
            ':description' => trim($data['description'] ?? ''),
            ':id' => $id
        ]);
    }

    /**
     * Đổi trạng thái Nổi bật (Toggle Featured) 1 Click
     */
    public function toggleFeatured($id) {
        $stmt = $this->db->prepare("UPDATE products SET is_featured = NOT is_featured, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $stmtStatus = $this->db->prepare("SELECT is_featured FROM products WHERE id = :id");
        $stmtStatus->execute([':id' => $id]);
        return (int)$stmtStatus->fetchColumn();
    }

    /**
     * Cập nhật số lượng tồn kho biến thể
     */
    public function updateVariantStock($variantId, $stock) {
        $stmt = $this->db->prepare("UPDATE product_variants SET stock = :stock, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':stock' => max(0, (int)$stock),
            ':id' => $variantId
        ]);
    }

    /**
     * Xóa sản phẩm an toàn
     */
    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lấy danh sách danh mục
     */
    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sản phẩm nổi bật (kèm tên danh mục, biến thể và hình ảnh) cho trang chủ
     */
    public function getFeaturedProducts($limit = 12) {
        $sql = "SELECT p.id, p.name, p.price, p.description, p.is_featured, p.category_id,
                       c.name as category_name, c.slug as category_slug,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                       (SELECT GROUP_CONCAT(DISTINCT color SEPARATOR ', ') FROM product_variants WHERE product_id = p.id) as colors,
                       (SELECT GROUP_CONCAT(DISTINCT size SEPARATOR ', ') FROM product_variants WHERE product_id = p.id) as sizes,
                       (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) as total_stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.is_featured DESC, p.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh mục kèm số lượng sản phẩm và ảnh bìa đại diện
     */
    public function getCategoriesWithCount() {
        $sql = "SELECT c.*, COUNT(p.id) as product_count,
                       (SELECT pi.image_url FROM products p2 
                        JOIN product_images pi ON pi.product_id = p2.id 
                        WHERE p2.category_id = c.id AND pi.is_primary = 1 LIMIT 1) as cover_image
                FROM categories c
                LEFT JOIN products p ON p.category_id = c.id
                GROUP BY c.id
                ORDER BY product_count DESC, c.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết 1 sản phẩm theo ID kèm theo hình ảnh và biến thể
     */
    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) return null;

        // Hình ảnh
        $stmtImg = $this->db->prepare("SELECT image_url FROM product_images WHERE product_id = :id ORDER BY is_primary DESC");
        $stmtImg->execute([':id' => $id]);
        $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);
        $product['images'] = array_column($images, 'image_url');
        
        // Nếu không có ảnh nào trong DB, đưa một mảng rỗng để view không bị lỗi
        if (empty($product['images'])) {
            $product['images'] = [$product['image_url']];
        }

        // Biến thể
        $stmtVar = $this->db->prepare("SELECT id, size, color, stock FROM product_variants WHERE product_id = :id");
        $stmtVar->execute([':id' => $id]);
        $product['variants'] = $stmtVar->fetchAll(PDO::FETCH_ASSOC);

        return $product;
    }

    /**
     * Tìm kiếm sản phẩm theo từ khóa (tên hoặc mô tả)
     */
    public function searchProducts($keyword, $limit = 50) {
        $sql = "SELECT p.id, p.name, p.price, p.description, p.is_featured,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                WHERE p.name LIKE :kw OR p.description LIKE :kw
                ORDER BY p.id DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $searchParam = '%' . trim($keyword) . '%';
        $stmt->bindParam(':kw', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sản phẩm theo danh mục hoặc từ khóa phân loại
     */
    public function getProductsByCategoryOrKeyword($term, $limit = 50) {
        $sql = "SELECT p.id, p.name, p.price, p.description, p.is_featured,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.name LIKE :term 
                   OR p.description LIKE :term 
                   OR c.name LIKE :term
                   OR c.slug LIKE :term
                ORDER BY p.id DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $param = '%' . trim($term) . '%';
        $stmt->bindParam(':term', $param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
