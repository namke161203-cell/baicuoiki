<?php
require_once __DIR__ . '/config/Database.php';

$db = (new Database())->getConnection();

try {
    $db->beginTransaction();

    // 1. Thêm Admin user
    $db->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@vibestore.com', '123456', 'admin') ON DUPLICATE KEY UPDATE name=name");

    // 2. Thêm danh mục
    $db->exec("INSERT INTO categories (id, name, slug, description) VALUES (1, 'Áo Khoác', 'ao-khoac', 'Áo khoác nam nữ') ON DUPLICATE KEY UPDATE name=name");

    // 2. Thêm sản phẩm 1
    $stmt = $db->prepare("INSERT INTO products (id, category_id, name, slug, price) VALUES (1, 1, 'Áo Khoác Bomber Lót Dù Cao Cấp', 'ao-khoac-bomber-lot-du', 650000) ON DUPLICATE KEY UPDATE name=name");
    $stmt->execute();

    // 3. Thêm variants
    $variants = [
        [1, 'M', 'Đen', 10], [1, 'L', 'Đen', 5], [1, 'M', 'Xanh rêu', 0], [1, 'L', 'Xanh rêu', 15]
    ];
    $stmtVar = $db->prepare("INSERT INTO product_variants (product_id, size, color, stock) VALUES (?, ?, ?, ?)");
    foreach ($variants as $v) {
        // Tránh lỗi duplicate thủ công (vì chưa có unique constraint cho product_id+size+color)
        $db->exec("DELETE FROM product_variants WHERE product_id={$v[0]} AND size='{$v[1]}' AND color='{$v[2]}'");
        $stmtVar->execute($v);
    }

    $db->commit();
    echo "<h1>Khởi tạo dữ liệu mẫu THÀNH CÔNG!</h1>";
    echo "<p>Giờ bạn có thể test chức năng Thêm vào giỏ hàng và Đặt hàng mà không bị lỗi Database (Foreign Key).</p>";
    echo "<a href='index.php'>Quay lại Trang chủ</a>";

} catch (Exception $e) {
    $db->rollBack();
    echo "Lỗi: " . $e->getMessage();
}
