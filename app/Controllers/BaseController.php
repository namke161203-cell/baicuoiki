<?php
class BaseController {
    // Hàm hỗ trợ gọi View
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
}
