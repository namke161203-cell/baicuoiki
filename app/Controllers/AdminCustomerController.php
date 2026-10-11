<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminCustomerController extends BaseController {
    
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
    }

    public function index() {
        $userModel = new UserModel();
        // Lấy danh sách khách hàng thông qua model thay vì truy cập $db trực tiếp
        $customers = $userModel->getAllCustomers();

        $this->render('admin/customers', [
            'title' => 'Quản lý Khách Hàng',
            'customers' => $customers
        ]);
    }
}
