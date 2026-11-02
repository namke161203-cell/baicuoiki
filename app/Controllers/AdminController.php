<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminController extends BaseController {
    
    public function __construct() {
        // MIDDLEWARE: Chặn không cho người chưa đăng nhập hoặc không phải admin vào
        $role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
        if (!isset($_SESSION['user_id']) || $role !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
    }

    // Trang chủ Dashboard phong cách Cadra Larkspur Cycles cao cấp
    public function index() {
        $adminModel = new AdminModel();
        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        $userModel = new UserModel();
        
        $stats = [
            'today_revenue' => $adminModel->getTodayRevenue(),
            'new_orders' => $adminModel->getNewOrdersCount(),
            'new_customers' => $adminModel->getNewCustomersCount(),
            'out_of_stock' => $adminModel->getOutOfStockCount()
        ];
        
        $latestOrders = $adminModel->getLatestOrders(6);
        $bestSellers = $adminModel->getBestSellers(6);
        
        // Dữ liệu cho tất cả các tab chuyển đổi mượt mà (SPA)
        $products = $productModel->getAllProducts();
        $categories = $productModel->getAllCategories();
        $productKPIs = $productModel->getProductKPIs();
        $orders = $orderModel->getAllOrders();
        $orderStats = $orderModel->getOrderStats();
        $customers = $userModel->getAllCustomers();
        $coupons = $adminModel->getAllCoupons();
        $report = $adminModel->getReportOverview();
        $ads = $adminModel->getAllAds();
        $executiveStats = $adminModel->getExecutiveDashboardStats();
        
        $activeTab = $_GET['tab'] ?? 'dashboard';

        $this->render('admin/dashboard', [
            'title' => 'Cadra Studio - Tổng Quan Hệ Thống',
            'stats' => $stats,
            'executiveStats' => $executiveStats,
            'latestOrders' => $latestOrders,
            'bestSellers' => $bestSellers,
            'products' => $products,
            'categories' => $categories,
            'productKPIs' => $productKPIs,
            'orders' => $orders,
            'orderStats' => $orderStats,
            'customers' => $customers,
            'coupons' => $coupons,
            'report' => $report,
            'ads' => $ads,
            'activeTab' => $activeTab
        ]);
    }
}
