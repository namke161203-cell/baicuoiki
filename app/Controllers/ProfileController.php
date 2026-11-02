<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class ProfileController extends BaseController {
    
    private $userModel;
    private $orderModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }
    
    // Giao diện Hồ sơ của tôi (Hiển thị thông tin user và đơn hàng)
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect("index.php?controller=Auth&action=login");
        }

        $userId = (int)$_SESSION['user_id'];
        
        // Lấy thông tin User & Danh sách đơn hàng
        $user = $this->userModel->getUserById($userId);
        $orders = $this->orderModel->getOrdersByUserId($userId);

        $this->render('profile', [
            'title' => 'Tài khoản của tôi',
            'user' => $user,
            'orders' => $orders
        ]);
    }

    // Xử lý cập nhật thông tin Profile
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect("index.php?controller=Auth&action=login");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)$_SESSION['user_id'];
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            $this->userModel->updateProfile($userId, $phone, $address);
            $_SESSION['flash_success'] = 'Cập nhật thông tin tài khoản thành công!';
        }
        
        $this->redirect("index.php?controller=Profile&action=index");
    }
}
