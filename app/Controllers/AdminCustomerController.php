<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminCustomerController extends BaseController {
    
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
        $this->userModel = new UserModel();
    }

    public function index() {
        $customers = $this->userModel->getAllCustomers();

        $this->render('admin/customers', [
            'title' => 'Quản lý Khách Hàng',
            'customers' => $customers
        ]);
    }
}
