# Dự Án Bán Hàng (Bài Cuối Kỳ)

Dự án Website Bán Hàng được xây dựng theo mô hình **MVC (Model - View - Controller)** bằng ngôn ngữ **PHP thuần (Vanilla PHP)**.

## 🚀 Các chức năng đã hoàn thành trong dự án

Dự án đã triển khai và hoàn thiện các chức năng và cấu trúc sau:

### 1. Kiến trúc hệ thống (MVC)
- **Controllers (`app/Controllers/`)**: Điều hướng và xử lý logic các luồng người dùng (HomeController, ProductController, AdminProductController).
- **Models (`app/Models/`)**: Xử lý logic nghiệp vụ và tương tác với cơ sở dữ liệu MySQL thông qua PDO (ProductModel, BaseModel).
- **Views (`app/Views/`)**: Chứa giao diện người dùng hiển thị thông tin sản phẩm. 
- Xây dựng component giao diện tái sử dụng: tách biệt `header` và `footer` (`app/Views/partials/`).

### 2. Chức năng Người dùng (Client-side)
- **Trang chủ (`home.php`)**: Hiển thị danh sách các sản phẩm đang được bán.
- **Chi tiết sản phẩm (`product_detail.php`)**: Xem thông tin chi tiết về từng sản phẩm (Hình ảnh, giá bán, mô tả, v.v.).

### 3. Chức năng Quản trị (Admin-side)
- Quản lý sản phẩm (`AdminProductController.php`): Các chức năng thêm, sửa, xóa, và liệt kê các sản phẩm dành cho admin.
- **Trang chủ Admin Dashboard (`AdminController.php`)**: Giao diện thống kê doanh thu, đơn hàng, khách hàng.
- **Phân quyền và Bảo mật (Middleware)**: Ngăn chặn truy cập trái phép vào trang Admin, yêu cầu đăng nhập.

### 4. Chức năng Xác thực & Đơn hàng (Cập nhật Mới)
- **Xác thực Người Dùng (`AuthController.php`, `UserModel.php`)**: Đăng nhập, đăng xuất, phân quyền người dùng (Role-based access control). Form đăng nhập dạng Modal hiện đại.
- **Giỏ Hàng (`CartController.php`)**: Thêm, sửa, xoá sản phẩm trong giỏ hàng lưu trữ bằng Session.
- **Thanh Toán (Checkout)**: Lưu trữ đơn hàng (`OrderModel`) sử dụng Transaction PDO để đảm bảo tính toàn vẹn dữ liệu. Cập nhật trực tiếp số lượng tồn kho.

### 5. Cơ sở dữ liệu và Cấu hình
- Sử dụng **PDO (PHP Data Objects)** bảo mật chống lại SQL Injection.
- Có sẵn file export cơ sở dữ liệu `database.sql` để dễ dàng tạo bảng/dữ liệu mẫu khi cài đặt.
- Cấu hình file `config/Database.php` giúp kết nối CSDL linh hoạt và tiện dụng.
- File `seed.php` giúp tự động khởi tạo dữ liệu mẫu (Sản phẩm & Tài khoản Admin) dễ dàng.

## 🌟 Cập nhật ngày 04/10/2026
Hôm nay dự án đã hoàn thành thêm các hạng mục vô cùng quan trọng:
1. **Thiết kế UI/UX mới hoàn toàn**: Đồng bộ hóa toàn bộ giao diện theo chuẩn phong cách "hquie" hiện đại bằng **TailwindCSS** (Màu Teal/Cyan chủ đạo, Modal Login, Grid System 100% responsive).
2. **Xác thực Người dùng Nâng cao**: 
   - Hoàn thiện luồng Đăng nhập/Đăng ký.
   - Khi Đăng ký thành công, tự động Đăng nhập vào hệ thống.
3. **Admin Dashboard**: Cấu trúc thành phần layout Header/Footer, áp dụng thuật toán chặn người ngoài truy cập trái phép.
4. **Hệ thống Giỏ Hàng & Checkout chuyên nghiệp**: 
   - Tính năng **Mua Ngay** (bỏ qua giỏ hàng hiện tại, tạo session thanh toán riêng).
   - Trang **Thanh Toán (Checkout)** 2 cột tách biệt, bao gồm form thu thập địa chỉ chi tiết và Select box 63 Tỉnh/Thành Việt Nam.
   - Thao tác thay đổi số lượng, chọn Size trực tiếp trong Giỏ hàng (không cần nút Cập nhật).
   - Truyền dữ liệu chi tiết giao hàng qua trang Thành công (Checkout Success).
5. **Hồ sơ Người dùng (Profile)**:
   - Giao diện Tài khoản cá nhân hiển thị Thông tin người dùng.
   - Lịch sử đặt hàng chi tiết (liên kết với dữ liệu Order và Order_Items thực).
   - Form cập nhật Số điện thoại và Địa chỉ mặc định trực tiếp.
6. **Bảo mật và Dữ liệu**: Tạo file **`seed.php`** hỗ trợ Gen dữ liệu một click tránh lỗi Foreign Key Constraint. Sử dụng PDO an toàn.

## 🛠 Hướng dẫn cài đặt

1. Import file `database.sql` vào MySQL (thông qua phpMyAdmin, XAMPP, v.v.).
2. Cập nhật thông tin kết nối CSDL trong file `config/Database.php`.
3. Chạy source code thông qua Apache (thư mục `htdocs` của XAMPP) và truy cập trên trình duyệt.