<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminAdController extends BaseController {
    
    private $adminModel;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
        $this->adminModel = new AdminModel();
    }

    // GET: Danh sách banner quảng cáo
    public function index() {
        $ads = $this->adminModel->getAllAds();
        $this->render('admin/ads', [
            'title' => 'Quản lý Quảng Cáo & Banner',
            'ads' => $ads
        ]);
    }

    // POST: Thêm mới banner quảng cáo
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $imageUrl = trim($_POST['image_url'] ?? '');

            if (empty($title) || empty($imageUrl)) {
                $_SESSION['flash_error'] = 'Tiêu đề chiến dịch và URL hình ảnh không được để trống!';
            } else {
                $this->adminModel->createAd($_POST);
                $_SESSION['flash_success'] = 'Thêm banner quảng cáo "' . htmlspecialchars($title) . '" thành công!';
            }
        }
        $this->redirect("index.php?controller=AdminAd&action=index");
    }

    // GET/POST: Đổi trạng thái Bật/Tắt banner
    public function toggle() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->adminModel->toggleAdStatus($id);
            $_SESSION['flash_success'] = 'Cập nhật trạng thái hiển thị banner thành công!';
        }
        $this->redirect("index.php?controller=AdminAd&action=index");
    }

    // GET/POST: Xóa banner
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->adminModel->deleteAd($id);
            $_SESSION['flash_success'] = 'Đã xóa banner quảng cáo thành công!';
        }
        $this->redirect("index.php?controller=AdminAd&action=index");
    }
}
