<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminController extends BaseController {
    
    public function __construct() {
        // MIDDLEWARE: Chặn không cho người chưa đăng nhập hoặc không phải admin vào
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
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
        $orders = $orderModel->getAllOrders();
        $customers = $userModel->getAllCustomers();
        $coupons = $adminModel->getAllCoupons();
        $report = $adminModel->getReportOverview();
        $ads = $adminModel->getAllAds();
        
        $activeTab = $_GET['tab'] ?? 'dashboard';

        $this->render('admin/dashboard', [
            'title' => 'hquie Cadra Studio - Executive Dashboard',
            'stats' => $stats,
            'latestOrders' => $latestOrders,
            'bestSellers' => $bestSellers,
            'products' => $products,
            'categories' => $categories,
            'orders' => $orders,
            'customers' => $customers,
            'coupons' => $coupons,
            'report' => $report,
            'ads' => $ads,
            'activeTab' => $activeTab
        ]);
    }
}
