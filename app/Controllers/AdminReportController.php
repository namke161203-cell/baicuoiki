<?php
require_once __DIR__ . '/BaseController.php';

class AdminReportController extends BaseController {
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
    }

    public function index() {
        $this->render('admin/reports', ['title' => 'Báo Cáo Thống Kê']);
    }
}
