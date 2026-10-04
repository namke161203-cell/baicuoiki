<?php
require_once __DIR__ . '/BaseController.php';

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
        // Mock data thống kê
        $stats = [
            'total_orders' => 124,
            'revenue' => 45000000,
            'total_products' => 32,
            'new_customers' => 12
        ];
        
        $this->render('admin/dashboard', [
            'title' => 'Tổng quan Hệ thống',
            'stats' => $stats
        ]);
    }
}
