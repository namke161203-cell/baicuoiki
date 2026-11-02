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
            $sqlOrder = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, payment_method) 
                         VALUES (:user_id, :name, :email, :phone, :address, :total, :method)";
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                ':user_id' => $customerInfo['user_id'] ?? null,
                ':name' => $customerInfo['name'],
                ':email' => $customerInfo['email'],
                ':phone' => $customerInfo['phone'],
                ':address' => $customerInfo['address'],
                ':total' => $totalAmount,
                ':method' => 'COD' // Mặc định Thanh toán khi nhận hàng
            ]);
            
            $orderId = $this->db->lastInsertId(); // Lấy mã đơn vừa tạo

            // 2. Chuẩn bị trước các câu lệnh truy vấn bên ngoài vòng lặp (Performance Optimization: Chống lặp prepare)
            $sqlItem = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, price) 
                        VALUES (:order_id, :product_id, :variant_id, :quantity, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            // Truy vấn lấy variant kèm khóa dòng (FOR UPDATE) để chống Race Condition đặt trùng
            $stmtVariant = $this->db->prepare(
                "SELECT id, stock FROM product_variants 
                 WHERE product_id = :pid AND size = :size AND color = :color 
                 LIMIT 1 FOR UPDATE"
            );

            // Câu lệnh cập nhật trừ kho với điều kiện an toàn (stock >= :qty)
            $stmtUpdateStock = $this->db->prepare(
                "UPDATE product_variants 
                 SET stock = stock - :qty 
                 WHERE id = :vid AND stock >= :qty"
            );

            foreach ($cartItems as $item) {
                // Truy vấn tìm chính xác variant_id và kiểm tra số lượng tồn
                $stmtVariant->execute([
                    ':pid' => $item['product_id'],
                    ':size' => $item['size'],
                    ':color' => $item['color']
                ]);
                $variant = $stmtVariant->fetch();
                $variantId = $variant ? $variant['id'] : null;

                // Kiểm tra tồn kho thực tế: Chống bán vượt quá số lượng tồn (Negative Stock Prevention)
                if ($variant) {
                    if ((int)$variant['stock'] < (int)$item['quantity']) {
                        $pName = $item['name'] ?? 'Sản phẩm';
                        throw new Exception("Sản phẩm '{$pName}' (Size: {$item['size']}, Màu: {$item['color']}) chỉ còn {$variant['stock']} chiếc trong kho, không đủ số lượng bạn yêu cầu!");
                    }
                }

                // Thêm chi tiết đơn
                $stmtItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':variant_id' => $variantId,
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'] // Giá lưu chết tại thời điểm này
                ]);
                
                // 3. Trừ đi số lượng trong kho an toàn
                if ($variantId) {
                    $stmtUpdateStock->execute([
                        ':qty' => $item['quantity'],
                        ':vid' => $variantId
                    ]);

                    if ($stmtUpdateStock->rowCount() === 0) {
                        throw new Exception("Kho hàng vừa thay đổi do có giao dịch cùng lúc. Vui lòng thử lại!");
                    }
                }
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách đơn hàng theo ID User
     */
    public function getOrdersByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute([':uid' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // TỐI ƯU HIỆU NĂNG (Chống lỗi N+1 Query): Gom tất cả order_id để truy vấn chi tiết trong 1 câu SQL duy nhất
        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            $sqlItems = "
                SELECT oi.*, p.name as product_name, pv.size, pv.color 
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                WHERE oi.order_id IN ($placeholders)
            ";
            $stmtItems = $this->db->prepare($sqlItems);
            $stmtItems->execute($orderIds);
            $allItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // Gom nhóm items theo order_id trong bộ nhớ PHP
            $itemsByOrder = [];
            foreach ($allItems as $item) {
                $itemsByOrder[$item['order_id']][] = $item;
            }

            foreach ($orders as &$order) {
                $order['items'] = $itemsByOrder[$order['id']] ?? [];
            }
            unset($order);
        }

        return $orders;
    }

    /**
     * Lấy TẤT CẢ đơn hàng cho Admin (kèm hình ảnh sản phẩm trong giỏ và tổng số lượng món)
     * TỐI ƯU HIỆU NĂNG: Xóa bỏ hoàn toàn lỗi N+1 Query cho trang quản trị đơn hàng
     */
    public function getAllOrders() {
        $stmt = $this->db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy chi tiết toàn bộ đơn hàng chỉ bằng 1 câu lệnh SQL duy nhất
        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            $sqlItems = "
                SELECT oi.*, p.name as product_name, pv.size, pv.color,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                WHERE oi.order_id IN ($placeholders)
            ";
            $stmtItems = $this->db->prepare($sqlItems);
            $stmtItems->execute($orderIds);
            $allItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $itemsByOrder = [];
            foreach ($allItems as $item) {
                $itemsByOrder[$item['order_id']][] = $item;
            }

            foreach ($orders as &$order) {
                $order['items'] = $itemsByOrder[$order['id']] ?? [];
                // Tính tổng số lượng món hàng trong đơn
                $order['total_items_count'] = array_sum(array_column($order['items'], 'quantity'));
            }
            unset($order);
        }
        return $orders;
    }

    /**
     * Thống kê KPI pipeline đơn hàng cho Admin
     */
    public function getOrderStats() {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_orders,
                COALESCE(SUM(CASE WHEN status != 'cancelled' THEN total_amount ELSE 0 END), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as count_pending,
                COALESCE(SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END), 0) as count_processing,
                COALESCE(SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END), 0) as count_shipped,
                COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END), 0) as count_completed,
                COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END), 0) as count_cancelled
            FROM orders
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết 1 đơn hàng theo ID
     */
    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        $stmtItems = $this->db->prepare("
            SELECT oi.*, p.name as product_name, pv.size, pv.color,
                   (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE oi.order_id = :oid
        ");
        $stmtItems->execute([':oid' => $orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        $order['total_items_count'] = array_sum(array_column($order['items'], 'quantity'));

        return $order;
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($orderId, $status) {
        $validStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $orderId
        ]);
    }
}
