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

    /**
     * Thống kê toàn diện thời gian thực cho Executive Dashboard (Cadra Larkspur Style)
     */
    public function getExecutiveDashboardStats() {
        // 1. Tổng tiền mặt / doanh thu tích lũy (không tính đơn hủy)
        $stmtTotal = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as total_cash, COUNT(*) as total_orders FROM orders WHERE status != 'cancelled'");
        $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalCash = (float)($totalRow['total_cash'] ?? 0);
        $totalOrders = (int)($totalRow['total_orders'] ?? 0);

        // 2. Doanh thu và số đơn trong tháng hiện tại
        $stmtMonth = $this->db->query("
            SELECT COALESCE(SUM(total_amount), 0) as month_revenue, COUNT(*) as month_orders 
            FROM orders 
            WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status != 'cancelled'
        ");
        $monthRow = $stmtMonth->fetch(PDO::FETCH_ASSOC);
        $monthRevenue = (float)($monthRow['month_revenue'] ?? 0);
        $monthOrders = (int)($monthRow['month_orders'] ?? 0);

        // 3. Doanh thu 14 ngày gần nhất
        $stmt14d = $this->db->query("
            SELECT COALESCE(SUM(total_amount), 0) as revenue_14d, COUNT(*) as orders_14d 
            FROM orders 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND status != 'cancelled'
        ");
        $row14d = $stmt14d->fetch(PDO::FETCH_ASSOC);
        $revenue14d = (float)($row14d['revenue_14d'] ?? 0);

        // 4. Số đơn hoàn thành (Completed)
        $stmtComp = $this->db->query("SELECT COUNT(*) as completed_count FROM orders WHERE status = 'completed'");
        $completedCount = (int)$stmtComp->fetchColumn();

        // 5. Đơn hàng đang mở (Open Order Book: Pending + Processing)
        $stmtOpen = $this->db->query("
            SELECT COUNT(*) as open_count, COALESCE(SUM(total_amount), 0) as open_total 
            FROM orders 
            WHERE status IN ('pending', 'processing')
        ");
        $openRow = $stmtOpen->fetch(PDO::FETCH_ASSOC);
        $openCount = (int)($openRow['open_count'] ?? 0);
        $openTotal = (float)($openRow['open_total'] ?? 0);

        // 6. Tỉ lệ hoàn thành / đúng hạn (On-time Delivery / Completion rate)
        $nonCancelledTotal = max(1, $totalOrders);
        $completionRate = $totalOrders > 0 ? round(($completedCount / $nonCancelledTotal) * 100) : 100;

        // 7. Chi tiết các giai đoạn bán hàng (Sales Funnel: Pending, Processing, Shipped, Completed)
        $stmtFunnel = $this->db->query("
            SELECT status, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total 
            FROM orders 
            GROUP BY status
        ");
        $funnelRows = $stmtFunnel->fetchAll(PDO::FETCH_ASSOC);
        $funnel = [
            'pending' => ['count' => 0, 'total' => 0],
            'processing' => ['count' => 0, 'total' => 0],
            'shipped' => ['count' => 0, 'total' => 0],
            'completed' => ['count' => 0, 'total' => 0],
            'cancelled' => ['count' => 0, 'total' => 0]
        ];
        foreach ($funnelRows as $f) {
            $st = strtolower($f['status']);
            if (isset($funnel[$st])) {
                $funnel[$st]['count'] = (int)$f['count'];
                $funnel[$st]['total'] = (float)$f['total'];
            }
        }

        // 8. Doanh thu theo tháng (Year at a glance)
        $stmtMonthly = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%m/%Y') as label, DATE_FORMAT(created_at, '%Y-%m') as ym, 
                   COALESCE(SUM(total_amount), 0) as revenue, COUNT(*) as count
            FROM orders 
            WHERE status != 'cancelled'
            GROUP BY ym 
            ORDER BY ym ASC 
            LIMIT 6
        ");
        $monthlyRevenue = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

        // 9. Dòng tiền gần đây (Recent Cashflow / Daily totals for mini waterfall chart)
        $stmtCash = $this->db->query("
            SELECT DATE(created_at) as date, COALESCE(SUM(total_amount), 0) as amount 
            FROM orders 
            WHERE status != 'cancelled' 
            GROUP BY date 
            ORDER BY date DESC 
            LIMIT 9
        ");
        $cashflow = $stmtCash->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_cash' => $totalCash,
            'total_orders' => $totalOrders,
            'month_revenue' => $monthRevenue,
            'month_orders' => $monthOrders,
            'revenue_14d' => $revenue14d,
            'completed_count' => $completedCount,
            'open_count' => $openCount,
            'open_total' => $openTotal,
            'completion_rate' => $completionRate,
            'funnel' => $funnel,
            'monthly_revenue' => $monthlyRevenue,
            'cashflow' => $cashflow
        ];
    }
    // PHẦN 1: MÃ GIẢM GIÁ (COUPONS)
    // ==========================================
    public function getAllCoupons() {
        $stmt = $this->db->prepare("SELECT * FROM coupons ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCoupon($data) {
        $sql = "INSERT INTO coupons (code, description, type, value, min_order_amount, max_discount_amount, usage_limit, end_date, is_active)
                VALUES (:code, :description, :type, :value, :min_order_amount, :max_discount_amount, :usage_limit, :end_date, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':code' => strtoupper(trim($data['code'])),
            ':description' => trim($data['description'] ?? ''),
            ':type' => $data['type'] ?? 'percent',
            ':value' => (float)$data['value'],
            ':min_order_amount' => (float)($data['min_order_amount'] ?? 0),
            ':max_discount_amount' => !empty($data['max_discount_amount']) ? (float)$data['max_discount_amount'] : null,
            ':usage_limit' => (int)($data['usage_limit'] ?? 100),
            ':end_date' => !empty($data['end_date']) ? $data['end_date'] : null
        ]);
    }

    public function deleteCoupon($id) {
        $stmt = $this->db->prepare("DELETE FROM coupons WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function toggleCouponStatus($id) {
        $stmt = $this->db->prepare("UPDATE coupons SET is_active = NOT is_active WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ==========================================
    // PHẦN 2: BÁO CÁO THỐNG KÊ (REPORTS)
    // ==========================================
    public function getReportOverview() {
        // Tổng doanh thu toàn thời gian
        $stmtRev = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'cancelled'");
        $totalRevenue = (float)$stmtRev->fetchColumn();

        // Tổng đơn hàng thành công
        $stmtOrders = $this->db->query("SELECT COUNT(*) FROM orders WHERE status != 'cancelled'");
        $totalOrders = (int)$stmtOrders->fetchColumn();

        // Giá trị đơn hàng trung bình (AOV)
        $aov = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        // Tổng khách hàng
        $stmtCust = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
        $totalCustomers = (int)$stmtCust->fetchColumn();

        // Thống kê doanh thu theo danh mục
        $sqlCat = "
            SELECT c.name as category_name, COALESCE(SUM(oi.price * oi.quantity), 0) as category_revenue, COUNT(oi.id) as total_items_sold
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            LEFT JOIN order_items oi ON p.id = oi.product_id
            GROUP BY c.id
            ORDER BY category_revenue DESC
        ";
        $stmtCat = $this->db->query($sqlCat);
        $revenueByCategory = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        // Bảng chi tiết sản phẩm bán chạy nhất
        $sqlProd = "
            SELECT p.id, p.name, p.price, COALESCE(SUM(oi.quantity), 0) as sold_count,
                   COALESCE(SUM(oi.price * oi.quantity), 0) as total_revenue,
                   (SELECT COALESCE(SUM(stock), 0) FROM product_variants WHERE product_id = p.id) as current_stock
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id
            ORDER BY total_revenue DESC, sold_count DESC
            LIMIT 10
        ";
        $stmtProd = $this->db->query($sqlProd);
        $productPerformance = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'aov' => $aov,
            'total_customers' => $totalCustomers,
            'revenue_by_category' => $revenueByCategory,
            'product_performance' => $productPerformance
        ];
    }

    // ==========================================
    // PHẦN 3: QUẢNG CÁO & BANNER (ADS)
    // ==========================================
    public function getAllAds() {
        $stmt = $this->db->prepare("SELECT * FROM ads ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAd($data) {
        $sql = "INSERT INTO ads (title, image_url, target_url, position, impressions, clicks, is_active, start_date, end_date)
                VALUES (:title, :image_url, :target_url, :position, 0, 0, 1, :start_date, :end_date)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => trim($data['title']),
            ':image_url' => trim($data['image_url']),
            ':target_url' => trim($data['target_url'] ?? ''),
            ':position' => $data['position'] ?? 'hero',
            ':start_date' => !empty($data['start_date']) ? $data['start_date'] : date('Y-m-d'),
            ':end_date' => !empty($data['end_date']) ? $data['end_date'] : null
        ]);
    }

    public function deleteAd($id) {
        $stmt = $this->db->prepare("DELETE FROM ads WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function toggleAdStatus($id) {
        $stmt = $this->db->prepare("UPDATE ads SET is_active = NOT is_active WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
