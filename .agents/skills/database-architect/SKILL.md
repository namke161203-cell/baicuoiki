---
name: database-architect
description: "Quy chuẩn thiết kế CSDL quan hệ chuẩn 3NF, chiến lược đánh Index, phòng chống lỗi N+1 Query, quản lý Foreign Keys và Seeding dữ liệu cho Website Thời Trang."
---

# Skill: Database Architect & Performance Optimization

Skill này định hình toàn bộ chuẩn mực thiết kế, chuẩn hóa và tối ưu hóa cơ sở dữ liệu MySQL cho dự án Website Bán Quần Áo PHP MVC:

---

## 1. Chuẩn Hóa Lược Đồ Dữ Liệu (3NF Data Modeling)
Cơ sở dữ liệu bắt buộc tuân thủ dạng chuẩn 3 (Third Normal Form - 3NF):
- **Phân tách thực thể độc lập:**
  - `users`: Thông tin tài khoản, vai trò (`admin`, `customer`), mật khẩu mã hóa bcrypt.
  - `categories`: Danh mục sản phẩm (Áo khoác, Quần jean, Đầm váy, Áo len đông,...).
  - `products`: Thông tin cốt lõi (Tên, slug, mô tả, ảnh chính, giá niêm yết, danh mục).
  - `product_variants`: Biến thể kích thước (size: S, M, L, XL) và màu sắc (color), cùng số lượng tồn kho (`stock`) và ảnh biến thể riêng biệt.
  - `orders` & `order_items`: Lưu vết giao dịch lịch sử, lưu cứng giá bán tại thời điểm mua (`price_at_purchase`) tránh bị ảnh hưởng khi giá sản phẩm thay đổi sau này.
  - `coupons`: Mã giảm giá (theo % hoặc số tiền cố định, giới hạn lượt dùng, hạn sử dụng).
  - `advertisements`: Banner quảng cáo trang chủ, trạng thái hiển thị (`active`/`inactive`).

---

## 2. Chiến Lược Đánh Chỉ Mục (Indexing Strategy) & Khóa Ngoại
1. **Ràng buộc Khóa ngoại (Foreign Keys):**
   - Mọi quan hệ giữa các bảng phải định nghĩa rõ ràng ràng buộc toàn vẹn:
     - `order_items.order_id` -> `orders.id` (`ON DELETE CASCADE`).
     - `product_variants.product_id` -> `products.id` (`ON DELETE CASCADE`).
     - `products.category_id` -> `categories.id` (`ON DELETE SET NULL`).
2. **Đánh chỉ mục thông minh (Indexes):**
   - **Single-column Index:** Các cột dùng làm điều kiện lọc thường xuyên: `status`, `slug`, `category_id`, `user_id`.
   - **Composite Index (Chỉ mục kết hợp):**
     - Bảng `product_variants`: `INDEX idx_product_variant (product_id, size, color)` để tăng tốc độ truy vấn kiểm tra tồn kho tức thì khi khách chọn size và màu.
     - Bảng `orders`: `INDEX idx_user_created (user_id, created_at)` để tải nhanh lịch sử đơn hàng của người dùng theo thứ tự thời gian.

---

## 3. Chống Lỗi N+1 Query & Tối Ưu Truy Vấn (Performance Tuning)
- **Quy tắc tuyệt đối:** Không bao giờ gọi truy vấn SQL bên trong vòng lặp `foreach` hoặc `while`.
- **Giải pháp xử lý:**
  - Sử dụng phép nối bảng `LEFT JOIN` hoặc câu lệnh `WHERE IN (...)` để gom nhóm truy vấn.
  - Chuẩn bị trước câu lệnh Prepared Statement (`$stmt = $pdo->prepare(...)`) bên ngoài vòng lặp nếu bắt buộc phải lặp để import dữ liệu lớn.

```php
// Chuẩn mực truy vấn lấy danh sách sản phẩm kèm biến thể (Chống N+1)
public function getProductsWithVariants($limit = 10) {
    // 1. Lấy danh sách sản phẩm chính
    $stmt = $this->db->prepare("SELECT * FROM products WHERE status = 'active' ORDER BY id DESC LIMIT ?");
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

    if (empty($products)) return [];

    $productIds = array_column($products, 'id');
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    // 2. Lấy toàn bộ biến thể chỉ bằng 1 truy vấn duy nhất
    $stmtVariants = $this->db->prepare("SELECT * FROM product_variants WHERE product_id IN ($placeholders)");
    $stmtVariants->execute($productIds);
    $allVariants = $stmtVariants->fetchAll();

    // 3. Gom nhóm biến thể vào sản phẩm tương ứng trong bộ nhớ PHP
    $variantMap = [];
    foreach ($allVariants as $variant) {
        $variantMap[$variant['product_id']][] = $variant;
    }

    foreach ($products as &$prod) {
        $prod['variants'] = $variantMap[$prod['id']] ?? [];
    }

    return $products;
}
```

---

## 4. Quản Lý Migration & Data Seeding
- Mọi thay đổi cấu trúc bảng phải được ghi lại trong file SQL version hóa hoặc script migration.
- Duy trì các file nạp dữ liệu mẫu (`seed.php`, `seed_winter.php`) để lập trình viên và môi trường kiểm thử có thể khởi tạo nhanh CSDL với đầy đủ danh mục, sản phẩm thời trang và tài khoản admin/khách hàng.

