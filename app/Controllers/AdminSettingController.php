<?php
require_once __DIR__ . '/BaseController.php';

class AdminSettingController extends BaseController {
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
    }

    public function index() {
        // Mock data hoặc đọc từ file JSON. Ở đây lấy session flash message nếu có
        $success = $_SESSION['settings_success'] ?? null;
        unset($_SESSION['settings_success']);

        $this->render('admin/settings', [
            'title' => 'Cài Đặt Hệ Thống',
            'success' => $success
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Thực tế sẽ lưu vào Database table settings hoặc config.json
            // Để demo tính năng, giả vờ lưu thành công
            $_SESSION['settings_success'] = "Đã lưu cấu hình hệ thống thành công!";
        }
        header("Location: index.php?controller=AdminSetting&action=index");
        exit;
    }
}
