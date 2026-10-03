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

### 4. Cơ sở dữ liệu và Cấu hình
- Sử dụng **PDO (PHP Data Objects)** bảo mật chống lại SQL Injection.
- Có sẵn file export cơ sở dữ liệu `database.sql` để dễ dàng tạo bảng/dữ liệu mẫu khi cài đặt.
- Cấu hình file `config/Database.php` giúp kết nối CSDL linh hoạt và tiện dụng.

## 🛠 Hướng dẫn cài đặt

1. Import file `database.sql` vào MySQL (thông qua phpMyAdmin, XAMPP, v.v.).
2. Cập nhật thông tin kết nối CSDL trong file `config/Database.php`.
3. Chạy source code thông qua Apache (thư mục `htdocs` của XAMPP) và truy cập trên trình duyệt.