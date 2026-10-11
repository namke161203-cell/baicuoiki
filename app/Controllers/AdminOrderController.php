<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class AdminOrderController extends BaseController {
    
    public function __construct() {
        // Bảo vệ route: Chỉ Admin mới được truy cập
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
    }

    // Danh sách đơn hàng
    public function index() {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();

        $this->render('admin/orders', [
            'title' => 'Quản lý Đơn Hàng',
            'orders' => $orders
        ]);
    }

    // Cập nhật trạng thái
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($orderId && $status) {
                $orderModel = new OrderModel();
                $orderModel->updateOrderStatus($orderId, $status);
            }
        }
        header("Location: index.php?controller=AdminOrder&action=index");
        exit;
    }
}
