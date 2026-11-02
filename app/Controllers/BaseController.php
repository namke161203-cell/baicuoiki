<?php
class BaseController {
    /**
     * Hàm hỗ trợ gọi và render View (Server-Side Rendering)
     */
    protected function render($view, $data = []) {
        // Biến mảng data thành các biến riêng biệt để view dễ sử dụng
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View không tồn tại: " . $view);
        }
    }

    /**
     * Phản hồi dữ liệu JSON chuẩn theo API Contract { success, message, data }
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Điều hướng trang an toàn (Safe Redirect)
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}
