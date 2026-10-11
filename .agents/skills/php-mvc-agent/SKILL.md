---
name: php-mvc-agent
description: "Quy chuẩn kiểm soát code, bảo mật dữ liệu và phong cách lập trình chuẩn MVC cho AI Agent và nhóm phát triển."
---

# Skill: PHP MVC Code Control & Security Standards

Skill này định hình toàn bộ chuẩn mực viết code và kiểm soát tính năng cho dự án Website Bán Quần Áo:

---

## 1. Nguyên Tắc Kiến Trúc MVC Thuần (Strict MVC Separation)

1. **Controller (`app/Controllers/`):**
   - Chỉ đảm nhiệm: Nhận request `$_GET`, `$_POST`, validate sơ bộ, gọi Model, và gọi `$this->render('view_name', $data)`.
   - **CẤM:** Không viết trực tiếp các câu lệnh SQL (`SELECT`, `INSERT`) trong Controller.
2. **Model (`app/Models/`):**
   - Kế thừa `BaseModel` để tái sử dụng kết nối `$this->db`.
   - Đảm nhiệm toàn bộ logic truy vấn CSDL và tính toán nghiệp vụ nặng.
   - **BẮT BUỘC:** 100% câu truy vấn phải dùng Prepared Statements (`$stmt->prepare()` và `$stmt->execute()`) để triệt tiêu lỗ hổng SQL Injection.
3. **View (`app/Views/`):**
   - Chỉ hiển thị giao diện HTML và dữ liệu từ Controller truyền sang thông qua `extract()`.
   - **BẮT BUỘC:** Luôn bọc dữ liệu người dùng qua `htmlspecialchars()` để chống lỗ hổng XSS (Cross-Site Scripting).

---

## 2. Quy Chuẩn Xác Thực & Mật Khẩu (Security Checklist)

- **Mật khẩu:** Bắt buộc dùng `password_hash($password, PASSWORD_BCRYPT)` khi tạo tài khoản, và `password_verify($password, $user['password'])` khi đăng nhập.
- **Ràng buộc:** Mật khẩu bắt buộc có tối thiểu 6 ký tự và chứa ký tự đặc biệt (ví dụ ký tự `@`). Kiểm tra bằng `str_contains($password, '@')` ở Controller trước khi chuyển sang Model.
- **Phiên làm việc (Session):**
  - Lưu `$_SESSION['user_id']`, `$_SESSION['user_role']`.
  - Luôn kiểm tra quyền truy cập (Role Middleware) trước khi cho phép vào các trang quản trị (`AdminController`).

---

## 3. Quy Chuẩn Git & Đánh Giá Nhóm (Git Standards)

- **Atomic Commits:** Mỗi commit chỉ giải quyết 1 tính năng hoặc 1 bug duy nhất.
- **Quy chuẩn thông điệp (Conventional Commits):**
  - `feat: thêm ràng buộc mật khẩu phải chứa ký tự @`
  - `fix: sửa lỗi tính tổng tiền giỏ hàng khi có mã giảm giá`
  - `refactor: tối ưu hàm lấy sản phẩm mùa đông trong ProductModel`
- **Diff Review:** Kiểm tra `git diff` trước khi commit, không commit file thừa hoặc debug log (`var_dump()`, `print_r()`).
