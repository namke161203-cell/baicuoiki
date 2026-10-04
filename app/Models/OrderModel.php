<?php
require_once __DIR__ . '/BaseModel.php';

class OrderModel extends BaseModel {
    
    /**
     * Tạo đơn hàng mới và lưu chi tiết đơn hàng
     * Sử dụng Transaction để an toàn: nếu lưu order_items lỗi thì hủy luôn order
     */
    public function createOrder($customerInfo, $cartItems, $totalAmount) {
        try {
            $this->db->beginTransaction();

            // 1. Thêm thông tin vào bảng orders
            $sqlOrder = "INSERT INTO orders (customer_name, customer_email, customer_phone, shipping_address, total_amount, payment_method) 
                         VALUES (:name, :email, :phone, :address, :total, :method)";
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                ':name' => $customerInfo['name'],
                ':email' => $customerInfo['email'],
                ':phone' => $customerInfo['phone'],
                ':address' => $customerInfo['address'],
                ':total' => $totalAmount,
                ':method' => 'COD' // Mặc định Thanh toán khi nhận hàng
            ]);
            
            $orderId = $this->db->lastInsertId(); // Lấy mã đơn vừa tạo

            // 2. Thêm từng sản phẩm vào bảng order_items
            $sqlItem = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, price) 
                        VALUES (:order_id, :product_id, :variant_id, :quantity, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            foreach ($cartItems as $item) {
                // Truy vấn tìm chính xác variant_id dựa theo product_id, size và color khách đã chọn
                $stmtVariant = $this->db->prepare("SELECT id FROM product_variants WHERE product_id = :pid AND size = :size AND color = :color LIMIT 1");
                $stmtVariant->execute([
                    ':pid' => $item['product_id'],
                    ':size' => $item['size'],
                    ':color' => $item['color']
                ]);
                $variant = $stmtVariant->fetch();
                $variantId = $variant ? $variant['id'] : null;

                // Thêm chi tiết đơn
                $stmtItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':variant_id' => $variantId,
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'] // Giá lưu chết tại thời điểm này
                ]);
                
                // 3. Trừ đi số lượng trong kho nếu variant đó tồn tại
                if ($variantId) {
                    $this->db->prepare("UPDATE product_variants SET stock = stock - :qty WHERE id = :vid")->execute([
                        ':qty' => $item['quantity'],
                        ':vid' => $variantId
                    ]);
                }
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
