<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController extends BaseController {
    
    // GET/POST: Giao diện và Xử lý Đăng nhập
    public function login() {
        // Nếu đã đăng nhập, đẩy thẳng vào trong
        if (isset($_SESSION['user_id'])) {
            header("Location: " . ($_SESSION['user_role'] === 'admin' ? "index.php?controller=Admin&action=index" : "index.php"));
            exit;
        }
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->login($email, $password);

            if ($user) {
                // Đăng nhập thành công -> Lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role']; // 'admin' hoặc 'customer'

                // Điều hướng theo Role
                if ($user['role'] === 'admin') {
                    header("Location: index.php?controller=Admin&action=index");
                } else {
                    header("Location: index.php"); // Khách hàng về trang chủ
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không chính xác.';
            }
        }

        $this->render('login', ['title' => 'Đăng nhập', 'error' => $error]);
    }

    // Đăng xuất
    public function logout() {
        // Xóa thông tin đăng nhập trong Session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }
}
