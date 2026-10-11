# KẾT QUẢ ĐÃ THỰC HIỆN TRONG DỰ ÁN (11/10/2026)
**Dự án:** Website Bán Hàng PHP MVC (`clothing_store`)  
**Báo cáo thực tế:** Tổng hợp toàn bộ các phần nhóm đã triển khai và hoàn thành trong mã nguồn.

---

## 1. Ý 1: Xây dựng hệ thống với sự trợ giúp của AI Agent (Đã hoàn thành)

- [x] **Cấu hình chỉ đạo Agent:** Đã thiết lập tệp cấu hình [`.cursorrules`](.cursorrules) ở thư mục gốc để Cursor / Copilot / Claude tự động tuân thủ kiến trúc dự án.
- [x] **Tích hợp Skill phát triển MVC:** Đã tạo [`.agents/skills/php-mvc-agent/SKILL.md`](.agents/skills/php-mvc-agent/SKILL.md) định nghĩa bộ quy chuẩn code (bắt buộc dùng PDO Prepared Statement, BCRYPT, phân tầng MVC).
- [x] **Xây dựng trọn vẹn hệ thống PHP MVC bằng Agent:**
  - **14 Controllers (`app/Controllers/`):** `AuthController`, `HomeController`, `ProductController`, `CartController`, `ProfileController`, hệ thống `Admin*Controllers`.
  - **4 Models (`app/Models/`):** `BaseModel` (PDO), `UserModel`, `ProductModel`, `OrderModel`, `AdminModel`.
  - **Hệ thống Giao diện (`app/Views/`):** Đầy đủ trang chủ mùa đông, chi tiết sản phẩm, giỏ hàng, thanh toán, tài khoản, và giao diện quản trị Admin.
  - **Cơ sở dữ liệu:** File export [`database.sql`](database.sql) cùng các file tự động nạp dữ liệu mẫu [`seed.php`](seed.php), [`seed_winter.php`](seed_winter.php).

---

## 2. Ý 2: Kỹ năng kiểm soát code (Đã hoàn thành)

### a) Kiểm soát tính năng (Đăng nhập & Ràng buộc mật khẩu có `@`)
- [x] **Định vị chính xác mã nguồn:**
  - Xử lý điều hướng & validate: [`app/Controllers/AuthController.php`](app/Controllers/AuthController.php) (hàm `login()`, `register()`).
  - Xử lý truy vấn CSDL & hash mật khẩu: [`app/Models/UserModel.php`](app/Models/UserModel.php).
  - Giao diện form: [`app/Views/login.php`](app/Views/login.php).
- [x] **Đã viết code ràng buộc mật khẩu phải chứa ký tự `@`:**
  - **Phía Server ([`AuthController.php`](app/Controllers/AuthController.php#L58-L61)):** Đã thêm điều kiện `!str_contains($password, '@')`, trả về lỗi thông báo nếu thiếu ký tự `@`.
  - **Phía Client ([`app/Views/login.php`](app/Views/login.php#L65)):** Đã thêm thuộc tính `pattern=".*@.*"` và `title` cảnh báo trực tiếp trên input.

### b) Kỹ năng dùng skill phục vụ viết code
- [x] **Đã tạo và tích hợp Skill `graphify` ([`.agents/skills/graphify/SKILL.md`](.agents/skills/graphify/SKILL.md)):**
  - Chứa toàn bộ cây phụ thuộc mã nguồn (Code Dependency Graph).
  - Có ma trận phân tích tác động (Impact Analysis Matrix) khi sửa Controller/Model.
- [x] **Đã xây dựng trang trực quan hóa tương tác ([`public/code_graph.html`](public/code_graph.html)):**
  - Xem trực tiếp trên trình duyệt tại: `http://localhost/duan/public/code_graph.html`.
  - Hiển thị trực quan toàn bộ sơ đồ liên kết giữa các file và các tầng MVC.
- [x] **Đã tích hợp Skill `php-mvc-agent` ([`.agents/skills/php-mvc-agent/SKILL.md`](.agents/skills/php-mvc-agent/SKILL.md)):**
  - Chứa bộ quy chuẩn kiểm soát an toàn code và chuẩn mực Git.

### c) Quản lý phiên bản, Log, Diff và Đánh giá nhóm (Git)
- [x] **Khởi tạo và quản lý mã nguồn bằng Git:** Dự án có đầy đủ repo Git, nhánh `main`.
- [x] **Kiểm soát lịch sử qua `git log`:** Theo dõi tiến độ commit, tác giả, và nội dung công việc.
- [x] **Kiểm soát thay đổi qua `git diff`:** Rà soát từng dòng code thêm/bớt trước khi đẩy lên hệ thống, tránh commit code thừa hoặc rác debug (`var_dump`).
- [x] **Quy chuẩn đánh giá thành viên theo vai trò Leader:**
  - Đánh giá qua tính chất **Atomic Commits** (chia nhỏ task độc lập).
  - Đánh giá qua thông điệp commit chuẩn (`feat:`, `fix:`, `refactor:`).
  - Đánh giá qua độ ổn định mã nguồn và tỉ lệ lỗi phát sinh (Defect Density), không đánh giá theo số dòng code (LOC).

---

## 3. Ý 3: Kết hợp các công cụ test tính năng (Đã hoàn thành)

- [x] **Test tính năng Đăng nhập / Đăng ký:**
  - Test case hợp lệ: Mật khẩu có `@` $\rightarrow$ Đăng ký thành công, tự động đăng nhập vào hệ thống.
  - Test case vi phạm: Mật khẩu không có `@` $\rightarrow$ Chặn lại ở cả giao diện HTML5 và Controller với thông báo lỗi màu đỏ.
- [x] **Test phân quyền người dùng (Role Middleware):**
  - Tài khoản khách hàng: Truy cập vào trang chủ và mua hàng.
  - Tài khoản admin: Tự động điều hướng và truy cập trang quản trị `AdminController`. Khách hàng thường cố vào trang admin bị chặn và chuyển hướng.
- [x] **Test tự động dữ liệu (Seeding & Regression Test):**
  - Chạy [`seed_winter.php`](seed_winter.php) và [`seed.php`](seed.php) khởi tạo lại dữ liệu mẫu sạch để test luồng giỏ hàng, áp mã giảm giá, và thanh toán (Checkout PDO Transaction).
- [x] **Kiểm thử giao diện & luồng hoạt động:** Đã test trực tiếp trên môi trường XAMPP Localhost với các luồng mua hàng, thêm giỏ hàng, và xem chi tiết sản phẩm.
