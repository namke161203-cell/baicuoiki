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
}
