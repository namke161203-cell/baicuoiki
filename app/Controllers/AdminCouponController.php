<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminCouponController extends BaseController {
    
    private $adminModel;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
        $this->adminModel = new AdminModel();
    }

    // GET: Danh sách mã giảm giá
    public function index() {
        $coupons = $this->adminModel->getAllCoupons();
        $this->render('admin/coupons', [
            'title' => 'Quản lý Mã Giảm Giá',
            'coupons' => $coupons
        ]);
    }

    // POST: Thêm mới mã giảm giá
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $value = (float)($_POST['value'] ?? 0);

            if (empty($code) || $value <= 0) {
                $_SESSION['flash_error'] = 'Mã giảm giá và giá trị ưu đãi không được để trống!';
            } else {
                $this->adminModel->createCoupon($_POST);
                $_SESSION['flash_success'] = 'Tạo mã giảm giá "' . htmlspecialchars($code) . '" thành công!';
            }
        }
        $this->redirect("index.php?controller=AdminCoupon&action=index");
    }

    // GET/POST: Đổi trạng thái Bật/Tắt
    public function toggle() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->adminModel->toggleCouponStatus($id);
            $_SESSION['flash_success'] = 'Cập nhật trạng thái mã giảm giá thành công!';
        }
        $this->redirect("index.php?controller=AdminCoupon&action=index");
    }

    // GET/POST: Xóa mã giảm giá
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->adminModel->deleteCoupon($id);
            $_SESSION['flash_success'] = 'Đã xóa mã giảm giá thành công!';
        }
        $this->redirect("index.php?controller=AdminCoupon&action=index");
    }
}
