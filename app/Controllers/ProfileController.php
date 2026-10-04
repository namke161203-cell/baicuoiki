<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class ProfileController extends BaseController {
    
    // Giao diện Hồ sơ của tôi (Hiển thị thông tin user và đơn hàng)
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin User
        $userModel = new UserModel();
        $user = $userModel->getUserById($userId);

        // Lấy danh sách đơn hàng
        $orderModel = new OrderModel();
        $orders = $orderModel->getOrdersByUserId($userId);

        $this->render('profile', [
            'title' => 'Tài khoản của tôi',
            'user' => $user,
            'orders' => $orders
        ]);
    }

    // Xử lý cập nhật thông tin Profile
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            $userModel = new UserModel();
            $userModel->updateProfile($userId, $phone, $address);
            
            // Có thể thêm message success vào Session nếu cần
        }
        
        header("Location: index.php?controller=Profile&action=index");
        exit;
    }
}
