---
name: backend-developer
description: "Quy chuẩn kiến trúc PHP MVC thuần, bảo mật cơ sở dữ liệu PDO Prepared Statements, ACID Transactions, quản lý Authentication/Authorization và API JSON Contract."
---

# Skill: Backend Developer & Database Architect

Skill này định hình toàn bộ chuẩn mực phát triển nghiệp vụ phía Server (PHP 8.x, MVC Architecture, MySQL Database, Security & API Endpoints) cho dự án Website Bán Quần Áo:

---

## 1. Phân Tầng Kiến Trúc MVC Chuẩn (Strict Separation of Concerns)

```
app/
├── Controllers/   --> Điều phối Request, kiểm tra Middleware, gọi Model, render View/JSON
├── Models/        --> Kế thừa BaseModel, thực thi câu lệnh SQL PDO, xử lý nghiệp vụ CSDL
└── Views/         --> Hiển thị giao diện, không chứa logic nghiệp vụ hay truy vấn SQL
```

### 1.1 Controller Layer (`app/Controllers/`)
- Kế thừa `BaseController`.
- Chịu trách nhiệm:
  - Nhận và làm sạch dữ liệu từ `$_GET`, `$_POST` hoặc `php://input`.
  - Validate dữ liệu đầu vào (Ví dụ: kiểm tra trường bắt buộc, định dạng email, mật khẩu tối thiểu 6 ký tự và chứa ký tự `@`).
  - Gọi phương thức tương ứng từ Model.
  - Phản hồi bằng `$this->render('view_name', $data)` (đối với SSR) hoặc `echo json_encode($response)` kèm HTTP Header `Content-Type: application/json` (đối với AJAX/API).
- **CẤM:** Không được nhúng trực tiếp câu lệnh SQL hoặc kết nối CSDL trực tiếp trong Controller.

### 1.2 Model Layer (`app/Models/`)
- Kế thừa `BaseModel` để tái sử dụng kết nối `$this->db` (PDO instance).
- Toàn bộ truy vấn SQL bắt buộc phải sử dụng **Prepared Statements** với dấu giữ chỗ (`?` hoặc `:named_param`).
- Chuẩn bị câu lệnh (`prepare`) bên ngoài vòng lặp `foreach` để tối ưu hiệu năng và tránh lặp kết nối.

---

## 2. Chuẩn Hóa Cơ Sở Dữ Liệu & Giao Dịch ACID (Database & Transactions)

1. **Chuẩn hóa Schema (3NF):**
   - Phân tách rõ ràng thực thể: `users`, `categories`, `products`, `product_variants` (size, color, stock), `orders`, `order_items`, `coupons`, `advertisements`.
   - Bắt buộc khai báo Khóa ngoại (Foreign Keys) kèm ràng buộc toàn vẹn dữ liệu (`ON DELETE CASCADE` hoặc `ON DELETE SET NULL`).
   - Đánh chỉ mục (Index) trên các cột thường xuyên tìm kiếm và join: `category_id`, `slug`, `status`, `user_id`, composite index `(product_id, size, color)`.

2. **Giao dịch an toàn chống âm kho (ACID Transactions):**
   - Khi tạo đơn hàng hoặc trừ kho, bắt buộc sử dụng PDO Transactions:
   ```php
   try {
       $this->db->beginTransaction();

       // 1. Kiểm tra tồn kho và khóa dòng (SELECT ... FOR UPDATE)
       $stmtStock = $this->db->prepare("SELECT stock FROM product_variants WHERE id = ? FOR UPDATE");
       $stmtStock->execute([$variantId]);
       $variant = $stmtStock->fetch();

       if (!$variant || $variant['stock'] < $quantity) {
           throw new Exception("Sản phẩm không đủ số lượng tồn kho!");
       }

       // 2. Trừ tồn kho
       $stmtUpdate = $this->db->prepare("UPDATE product_variants SET stock = stock - ? WHERE id = ?");
       $stmtUpdate->execute([$quantity, $variantId]);

       // 3. Tạo order và order items...
       // ...

       $this->db->commit();
   } catch (Exception $e) {
       $this->db->rollBack();
       throw $e;
   }
   ```

---

## 3. Xác Thực, Phân Quyền & Bảo Mật Hệ Thống (Auth & Security)

1. **Quản lý mật khẩu:**
   - Mã hóa 1 chiều bằng thuật toán an toàn: `password_hash($password, PASSWORD_BCRYPT)`.
   - Xác minh đăng nhập bằng: `password_verify($inputPassword, $user['password'])`.
   - Quy tắc mật khẩu: Tối thiểu 6 ký tự và bắt buộc chứa ký tự `@` (kiểm tra bằng `str_contains($password, '@')`).
2. **Quản lý phiên làm việc & Phân quyền (Role-Based Middleware):**
   - Kiểm tra `$_SESSION['user_id']` và `$_SESSION['user_role']`.
   - Khu vực quản trị (`AdminController`, `Admin*Controller`): Bắt buộc kiểm tra `if ($_SESSION['user_role'] !== 'admin') { header('Location: /index.php?controller=Auth&action=login'); exit; }`.
3. **Phòng chống các lỗ hổng OWASP Top 10:**
   - **SQL Injection:** 100% Prepared Statements qua PDO. Không nối chuỗi SQL.
   - **XSS:** Dùng `htmlspecialchars()` khi xuất dữ liệu ra View.
   - **IDOR / Bypassing:** Luôn kiểm tra `user_id` sở hữu đối với các request xem chi tiết đơn hàng hoặc cập nhật thông tin cá nhân.

---

## 4. Chuẩn Hóa API Contract (JSON Response Standard)
Tất cả các endpoint AJAX/JSON phục vụ cho Frontend phải tuân thủ cấu trúc thống nhất:
```json
{
  "success": true,
  "message": "Cập nhật giỏ hàng thành công",
  "data": {
    "cart_total": 450000,
    "total_items": 3
  }
}
```
Khi xảy ra lỗi:
```json
{
  "success": false,
  "message": "Mã giảm giá đã hết hạn sử dụng",
  "data": null
}
```

