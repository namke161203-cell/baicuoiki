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
     * Lấy danh sách toàn bộ sản phẩm (kèm ảnh bìa) cho Admin
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.name as category_name, 
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                       (SELECT GROUP_CONCAT(CONCAT(color, ' - ', size, ' (', stock, ')') SEPARATOR ', ') FROM product_variants WHERE product_id = p.id) as variants_info
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($id) {
        // Xóa sản phẩm sẽ tự động xóa product_images và product_variants nếu dùng ON DELETE CASCADE trong SQL
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
     * Lấy sản phẩm nổi bật (is_featured = 1 hoặc mới nhất) cho trang chủ
     */
    public function getFeaturedProducts($limit = 8) {
        $sql = "SELECT p.id, p.name, p.price, p.is_featured,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                ORDER BY p.is_featured DESC, p.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
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
}
