<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminController extends BaseController {
    
    public function __construct() {
        // MIDDLEWARE: Chặn không cho người chưa đăng nhập hoặc không phải admin vào
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit; // Dừng mã ngay lập tức để bảo mật
        }
    }

    // Trang chủ Dashboard của Admin
    public function index() {
        $adminModel = new AdminModel();
        
        $stats = [
            'today_revenue' => $adminModel->getTodayRevenue(),
            'new_orders' => $adminModel->getNewOrdersCount(),
            'new_customers' => $adminModel->getNewCustomersCount(),
            'out_of_stock' => $adminModel->getOutOfStockCount()
        ];
        
        $latestOrders = $adminModel->getLatestOrders(4);
        $bestSellers = $adminModel->getBestSellers(6);
        
        $this->render('admin/dashboard', [
            'title' => 'Tổng quan Hệ thống',
            'stats' => $stats,
            'latestOrders' => $latestOrders,
            'bestSellers' => $bestSellers
        ]);
    }
}
