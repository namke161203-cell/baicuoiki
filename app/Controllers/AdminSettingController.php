<?php
require_once __DIR__ . '/BaseController.php';

class AdminSettingController extends BaseController {
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
    }

    public function index() {
        $success = $_SESSION['settings_success'] ?? null;
        unset($_SESSION['settings_success']);

        $this->render('admin/settings', [
            'title' => 'Cài Đặt Hệ Thống',
            'success' => $success
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['settings_success'] = "Đã lưu cấu hình hệ thống thành công!";
        }
        $this->redirect("index.php?controller=AdminSetting&action=index");
    }
}
