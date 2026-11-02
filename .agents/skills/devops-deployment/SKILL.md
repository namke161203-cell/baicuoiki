---
name: devops-deployment
description: "Quy chuẩn triển khai môi trường máy chủ Apache/XAMPP, cấu hình .htaccess URL Rewrite, tối ưu nén Gzip, Browser Caching và quy trình sao lưu CSDL tự động."
---

# Skill: DevOps & Production Deployment Engineer

Skill này định hình toàn bộ chuẩn mực cấu hình máy chủ web, tối ưu hóa tốc độ tải trang và quy trình vận hành triển khai (Deployment & Operations) cho hệ thống Website Bán Quần Áo PHP MVC:

---

## 1. Cấu Hình Điều Hướng Máy Chủ Web (Apache .htaccess Rewrite)
Hệ thống sử dụng mô hình Front Controller (`index.php`), toàn bộ request gửi đến máy chủ phải được định tuyến an toàn:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Ngăn chặn truy cập trực tiếp vào các file cấu hình và thư mục nhạy cảm
    RewriteRule ^(\.git|\.env|\.cursorrules|config/|storage/) - [F,L]

    # Nếu tệp hoặc thư mục thực tế không tồn tại, chuyển hướng về index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

---

## 2. Tối Ưu Tốc Độ Tải Trang (Gzip Compression & Browser Caching)
Bật nén tài nguyên và lưu bộ nhớ đệm trình duyệt cho các file tĩnh (CSS, JS, Hình ảnh sản phẩm) để tăng tốc độ tải trang (Core Web Vitals):

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css application/xml application/xhtml+xml application/rss+xml application/javascript application/x-javascript application/json
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 3. Phân Tách Môi Trường (Development vs. Production Hardening)
- **Môi trường Phát triển (Local / XAMPP):**
  - Bật hiển thị lỗi chi tiết để lập trình viên và AI Agent dễ dàng gỡ lỗi:
    ```php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ```
- **Môi trường Triển khai Thực tế (Production):**
  - Tắt hoàn toàn việc in lỗi ra màn hình người dùng để tránh rò rỉ cấu trúc thư mục và thông tin CSDL (Information Disclosure):
    ```php
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../storage/logs/php_error.log');
    ```

---

## 4. Kịch Bản Sao Lưu Cơ Sở Dữ Liệu Tự Động (Database Backup)
Duy trì script sao lưu CSDL định kỳ hàng ngày bằng tiện ích `mysqldump`:
```bash
# Lệnh sao lưu CSDL nén gzip kèm timestamp
mysqldump -u root -p clothing_store | gzip > backup_clothing_store_$(date +\%Y\%m\%d_\%H\%M\%S).sql.gz
```
- Phân quyền thư mục: Chỉ cấp quyền ghi (Write Permission) cho thư mục chứa tệp tải lên `public/uploads/` và thư mục lưu nhật ký `storage/logs/`. Các thư mục mã nguồn (`app/`, `config/`) đặt ở chế độ chỉ đọc (Read-only) đối với tiến trình web server.

