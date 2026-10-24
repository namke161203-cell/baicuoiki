<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminReportController extends BaseController {
    
    private $adminModel;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        $this->adminModel = new AdminModel();
    }

    // GET: Trang Báo Cáo Thống Kê
    public function index() {
        $data = $this->adminModel->getReportOverview();
        
        $this->render('admin/reports', [
            'title' => 'Báo Cáo Doanh Thu & Thống Kê',
            'report' => $data
        ]);
    }

    // GET: Xuất file CSV Báo Cáo Hiệu Quả Bán Hàng
    public function export() {
        $data = $this->adminModel->getReportOverview();
        $products = $data['product_performance'] ?? [];

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=bao_cao_ban_hang_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        // Thêm UTF-8 BOM để Excel hiển thị đúng tiếng Việt có dấu
        fputs($output, "\xEF\xBB\xBF");

        // Header CSV
        fputcsv($output, ['ID Sản Phẩm', 'Tên Sản Phẩm', 'Đơn Giá (VNĐ)', 'Số Lượng Đã Bán', 'Tổng Doanh Thu (VNĐ)', 'Tồn Kho Hiện Tại']);

        foreach ($products as $row) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['price'],
                $row['sold_count'],
                $row['total_revenue'],
                $row['current_stock']
            ]);
        }

        fclose($output);
        exit;
    }
}
