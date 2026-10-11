<?php
require_once __DIR__ . '/BaseModel.php';

class AdminModel extends BaseModel {
    
    // 1. Doanh thu hôm nay
    public function getTodayRevenue() {
        $stmt = $this->db->prepare("SELECT SUM(total_amount) as revenue FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['revenue'] ? $result['revenue'] : 0;
    }

    // 2. Đơn hàng mới hôm nay
    public function getNewOrdersCount() {
        $stmt = $this->db->prepare("SELECT COUNT(id) as count FROM orders WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    // 3. Khách hàng mới (trong tháng)
    public function getNewCustomersCount() {
        $stmt = $this->db->prepare("SELECT COUNT(id) as count FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    // 4. Sản phẩm hết hàng (tồn kho = 0)
    public function getOutOfStockCount() {
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT product_id) as count FROM product_variants WHERE stock = 0");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    // 5. Đơn hàng mới nhất
    public function getLatestOrders($limit = 4) {
        $stmt = $this->db->prepare("SELECT id, customer_name, created_at, status, total_amount FROM orders ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Sản phẩm bán chạy
    public function getBestSellers($limit = 6) {
        $sql = "
            SELECT p.id, p.name, p.price, SUM(oi.quantity) as total_sold,
                   (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status != 'cancelled'
            GROUP BY p.id
            ORDER BY total_sold DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
