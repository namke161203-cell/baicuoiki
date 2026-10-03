<?php
session_start();

// Một router siêu cơ bản để điều hướng (Routing)
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'Home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Định dạng lại tên Controller (VD: home -> HomeController)
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = 'app/Controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerClass();
    
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        echo "404 - Không tìm thấy action!";
    }
} else {
    echo "404 - Không tìm thấy trang (Controller not found)!";
}
