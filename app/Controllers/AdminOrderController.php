<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class AdminOrderController extends BaseController {
    
    public function __construct() {
        // Bảo vệ route: Chỉ Admin mới được truy cập
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
    }

    // Danh sách đơn hàng
    public function index() {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();
        $orderStats = $orderModel->getOrderStats();

        $this->render('admin/orders', [
            'title' => 'Quản lý Đơn Hàng & Vận Chuyển',
            'orders' => $orders,
            'orderStats' => $orderStats
        ]);
    }

    // Cập nhật trạng thái
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($orderId && $status) {
                $orderModel = new OrderModel();
                $success = $orderModel->updateOrderStatus($orderId, $status);
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $success, 'order_id' => $orderId, 'status' => $status]);
                    exit;
                }
            }
        }
        $redirectUrl = !empty($_POST['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=orders' : 'index.php?controller=AdminOrder&action=index';
        header("Location: " . $redirectUrl);
        exit;
    }

    // Lấy chi tiết đơn hàng dạng JSON (cho Modal AJAX nếu cần)
    public function detail() {
        $orderId = $_GET['id'] ?? null;
        if ($orderId) {
            $orderModel = new OrderModel();
            $order = $orderModel->getOrderById($orderId);
            header('Content-Type: application/json');
            echo json_encode($order);
            exit;
        }
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // Xuất file CSV danh sách đơn hàng
    public function exportCSV() {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=hquie_orders_' . date('Ymd_His') . '.csv');

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Mã Đơn', 'Khách Hàng', 'Số Điện Thoại', 'Địa Chỉ Giao Hàng', 'Tổng Số Món', 'Tổng Tiền (VND)', 'Phương Thức', 'Trạng Thái', 'Ngày Đặt']);

        foreach ($orders as $ord) {
            fputcsv($output, [
                '#' . str_pad($ord['id'], 6, '0', STR_PAD_LEFT),
                $ord['customer_name'],
                $ord['customer_phone'],
                $ord['shipping_address'],
                $ord['total_items_count'] ?? count($ord['items']),
                number_format($ord['total_amount'], 0, '', ''),
                $ord['payment_method'] ?? 'COD',
                $ord['status'],
                $ord['created_at']
            ]);
        }
        fclose($output);
        exit;
    }
}
