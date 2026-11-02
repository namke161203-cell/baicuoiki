<?php
// BẢO MẬT PHIÊN LÀM VIỆC (SESSION HARDENING - OWASP STANDARD)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');     // Ngăn chặn mã JavaScript đọc Cookie (chống XSS đánh cắp Session)
    ini_set('session.use_only_cookies', '1');    // Chỉ truyền Session ID qua Cookie, không truyền qua URL
    ini_set('session.cookie_samesite', 'Lax');   // Chống tấn công giả mạo yêu cầu (CSRF)
    session_start();
}

// BỘ ĐIỀU HƯỚNG ROUTING (HỖ TRỢ CẢ CLEAN URL VÀ QUERY PARAMS)
$controllerName = 'Home';
$actionName = 'index';

// 1. Phân tích Clean URL từ .htaccess (VD: /san-pham, /gio-hang, /admin)
if (!empty($_GET['url']) && !isset($_GET['controller'])) {
    $urlParts = explode('/', trim($_GET['url'], '/'));
    $slug = strtolower($urlParts[0] ?? '');

    $routeMap = [
        'san-pham'   => ['Product', 'list'],
        'gio-hang'   => ['Cart', 'index'],
        'thanh-toan' => ['Cart', 'checkout'],
        'tai-khoan'  => ['Profile', 'index'],
        'dang-nhap'  => ['Auth', 'login'],
        'dang-ky'    => ['Auth', 'register'],
        'dang-xuat'  => ['Auth', 'logout'],
        'admin'      => ['Admin', 'index']
    ];

    if (isset($routeMap[$slug])) {
        $controllerName = $routeMap[$slug][0];
        $actionName = $routeMap[$slug][1];
    } else {
        $controllerName = ucfirst($slug);
        $actionName = $urlParts[1] ?? 'index';
    }
} else {
    // 2. Phân tích qua Query Params truyền thống (?controller=...&action=...)
    if (isset($_GET['controller'])) {
        $controllerName = $_GET['controller'];
    }
    if (isset($_GET['action'])) {
        $actionName = $_GET['action'];
    }
}

// LÀM SẠCH INPUT (Chống tấn công Path Traversal / LFI)
$cleanController = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
$cleanAction = preg_replace('/[^a-zA-Z0-9_]/', '', $actionName);

// Định dạng tên Class Controller (VD: home -> HomeController)
$controllerClass = ucfirst($cleanController) . 'Controller';
$controllerFile = __DIR__ . '/app/Controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        
        if (method_exists($controller, $cleanAction)) {
            $controller->$cleanAction();
        } else {
            http_response_code(404);
            echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
            echo "<h1 style='color: #ef4444;'>404 - Không tìm thấy hành động (Action not found)</h1>";
            echo "<p>Hành động <strong>" . htmlspecialchars($cleanAction) . "</strong> không tồn tại trong Controller <strong>" . htmlspecialchars($controllerClass) . "</strong>.</p>";
            echo "<a href='index.php' style='color: #0284c7; text-decoration: underline;'>← Quay lại Trang Chủ</a>";
            echo "</div>";
        }
    } else {
        http_response_code(404);
        echo "404 - Class controller không tồn tại!";
    }
} else {
    http_response_code(404);
    echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
    echo "<h1 style='color: #ef4444;'>404 - Không tìm thấy trang (Controller not found)</h1>";
    echo "<p>Trang bạn yêu cầu không tồn tại trên hệ thống.</p>";
    echo "<a href='index.php' style='color: #0284c7; text-decoration: underline;'>← Quay lại Trang Chủ</a>";
    echo "</div>";
}
