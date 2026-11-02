---
name: security-auditor
description: "Quy chuẩn kiểm toán bảo mật ứng dụng web theo OWASP Top 10: Phòng chống CSRF, SQL Injection, XSS, kiểm soát tải tệp tin an toàn và bảo vệ phiên làm việc (Session)."
---

# Skill: Security Auditor & OWASP Top 10 Compliance

Skill này định hình toàn bộ chuẩn mực an ninh mạng, kiểm toán mã nguồn và phòng thủ theo chiều sâu (Defense in Depth) cho hệ thống Website Bán Quần Áo PHP MVC:

---

## 1. Phòng Chống Tấn Công Giả Mạo Yêu Cầu (CSRF Protection)
Mọi biểu mẫu (form) hoặc yêu cầu thay đổi trạng thái (POST, PUT, DELETE) bắt buộc phải có mã xác thực CSRF Token:
1. **Sinh CSRF Token trong Session:**
   ```php
   if (empty($_SESSION['csrf_token'])) {
       $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }
   ```
2. **Nhúng vào View/Form:**
   ```html
   <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
   ```
3. **Kiểm tra tại Controller trước khi xử lý:**
   ```php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $token = $_POST['csrf_token'] ?? '';
       if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
           http_response_code(403);
           die("Yêu cầu không hợp lệ (CSRF Token mismatch)!");
       }
   }
   ```

---

## 2. Kiểm Soát Tải Lên Tệp Tin An Toàn (Secure File Uploads)
Khi tải ảnh sản phẩm, banner hoặc ảnh đại diện:
- **Kiểm tra đuôi tệp và MIME-type thực tế:** Không dựa vào phần mở rộng do client gửi lên; bắt buộc dùng `finfo_file` (Fileinfo extension) hoặc `getimagesize()` để xác minh định dạng ảnh hợp lệ (`image/jpeg`, `image/png`, `image/webp`).
- **Đổi tên tệp ngẫu nhiên:** Không dùng tên file gốc của người dùng để tránh directory traversal hoặc ghi đè file hệ thống:
  ```php
  $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
  $safeFileName = bin2hex(random_bytes(16)) . '.' . strtolower($extension);
  ```
- **Chống thực thi mã trong thư mục upload:** Cấu hình file `.htaccess` trong thư mục `public/uploads/`:
  ```apache
  # Vô hiệu hóa thực thi mã PHP trong thư mục tải ảnh
  php_flag engine off
  <FilesMatch "\.(php|phtml|php3|php4|php5|php7|phps)$">
      Deny from all
  </FilesMatch>
  ```

---

## 3. Quản Lý Phiên Làm Việc & Chống Đánh Cắp Session (Session Hardening)
- **Chống Session Fixation:** Bắt buộc gọi `session_regenerate_id(true)` ngay sau khi người dùng đăng nhập thành công để hủy bỏ ID phiên cũ.
- **Cấu hình Cookie an toàn:**
  ```php
  ini_set('session.cookie_httponly', 1); // Chống JavaScript đọc cắp Cookie (Anti-XSS Cookie theft)
  ini_set('session.use_only_cookies', 1);
  ini_set('session.cookie_samesite', 'Lax'); // Chống CSRF cross-origin
  ```
- **Hủy phiên triệt để khi Đăng xuất:** Xóa sạch `$_SESSION`, hủy Cookie session và gọi `session_destroy()`.

---

## 4. Chống Tấn Công Dò Quét Mật Khẩu (Brute-Force & Rate Limiting)
- Theo dõi số lần đăng nhập sai theo IP hoặc username trong session hoặc bảng cache.
- Tạm khóa hoặc bắt buộc chờ 5–15 phút nếu người dùng nhập sai quá 5 lần liên tiếp.
- Mật khẩu mã hóa 1 chiều bằng thuật toán an toàn `PASSWORD_BCRYPT` với độ dài tối thiểu 6 ký tự và có chứa `@`.

