---
name: graphify
description: "Trực quan hóa và phân tích cây phụ thuộc mã nguồn (Code Dependency Graph & AST Call Graph) cho dự án PHP MVC Website Bán Hàng."
---

# Skill: Graphify - Trực Quan Hóa & Phân Tích Cấu Trúc Mã Nguồn

Skill này cung cấp bản đồ kiến trúc toàn diện (Architecture Call Graph, Data Flow Graph, và Component Dependency Matrix) cho hệ thống Website Bán Quần Áo MVC (`clothing_store`), giúp lập trình viên và AI Agent kiểm soát chính xác 100% vị trí logic mã nguồn và đánh giá tác động trước khi sửa đổi.

---

## 1. Bản Đồ Tổng Thể Luồng Yêu Cầu (Global Request Lifecycle)

```mermaid
graph TD
    Client([Trình duyệt Người dùng]) -->|HTTP Request| Router[index.php - Front Controller]
    
    subgraph Routing & Middleware
        Router -->|Parse controller & action| Dispatcher{Dispatcher}
        Dispatcher -->|Kiểm tra quyền truy cập| AuthCheck[Session / Role Middleware]
    end

    subgraph Controller Layer [app/Controllers/]
        AuthCheck -->|controller=Auth| AuthC[AuthController.php]
        AuthCheck -->|controller=Home| HomeC[HomeController.php]
        AuthCheck -->|controller=Product| ProductC[ProductController.php]
        AuthCheck -->|controller=Cart| CartC[CartController.php]
        AuthCheck -->|controller=Admin*| AdminC[Admin*Controllers.php]
    end

    subgraph Model Layer [app/Models/]
        AuthC -->|Query / Verify| UserM[UserModel.php]
        HomeC -->|Get featured/winter| ProdM[ProductModel.php]
        ProductC -->|Get detail & variants| ProdM
        CartC -->|Validate stock| ProdM
        CartC -->|Create transaction| OrderM[OrderModel.php]
        AdminC -->|CRUD Analytics| AdminM[AdminModel.php]
        
        UserM -->|Extends| BaseM[BaseModel.php]
        ProdM -->|Extends| BaseM
        OrderM -->|Extends| BaseM
        AdminM -->|Extends| BaseM
    end

    subgraph Database Layer [MySQL: clothing_store]
        BaseM -->|PDO Connection| DB[(config/Database.php)]
        DB --> UsersT[(Bảng users)]
        DB --> ProdsT[(Bảng products & variants)]
        DB --> OrdersT[(Bảng orders & order_items)]
    end

    subgraph View Layer [app/Views/]
        AuthC -.->|Render| ViewLogin[login.php]
        HomeC -.->|Render| ViewHome[home.php]
        ProductC -.->|Render| ViewDetail[product_detail.php]
        CartC -.->|Render| ViewCart[cart.php / checkout.php]
        AdminC -.->|Render| ViewAdmin[admin/*.php]
        
        ViewHome -.-> HeaderPartial[partials/header.php]
        ViewHome -.-> FooterPartial[partials/footer.php]
    end
```

---

## 2. Chi Tiết Đồ Thị Tính Năng Xác Thực (Authentication Flow Graph)

Khi kiểm tra hoặc sửa đổi tính năng **Đăng nhập / Đăng ký**:

```mermaid
sequenceDiagram
    autonumber
    actor User as Người dùng
    participant View as app/Views/login.php
    participant Ctrl as app/Controllers/AuthController.php
    participant Model as app/Models/UserModel.php
    participant DB as MySQL (users)

    User->>View: Nhập email, password (kiểm tra pattern regex '@')
    View->>Ctrl: POST index.php?controller=Auth&action=register
    Note over Ctrl: Validate: email, name, password<br/>Ràng buộc: str_contains($password, '@')
    alt Thiếu ký tự @ hoặc để trống
        Ctrl-->>View: Render kèm biến $error ('Mật khẩu phải có @')
        View-->>User: Hiển thị box thông báo màu đỏ
    else Hợp lệ
        Ctrl->>Model: register($name, $email, $password)
        Note over Model: Hash bằng password_hash(PASSWORD_BCRYPT)
        Model->>DB: INSERT INTO users (name, email, password, role)
        DB-->>Model: Success / Error Duplicate
        Model-->>Ctrl: boolean
        Ctrl->>User: Set $_SESSION['user_id', 'user_role'] -> Điều hướng index.php
    end
```

---

## 3. Ma Trận Đánh Giá Tác Động (Impact Analysis Matrix)

Khi cần chỉnh sửa bất kỳ module nào, tra cứu ma trận sau để biết phạm vi ảnh hưởng (Blast Radius):

| File dự kiến sửa | Tầng phụ thuộc trực tiếp (Gọi tới) | Tầng bị ảnh hưởng (Cần test lại) | Điểm kiểm thử xung yếu |
| :--- | :--- | :--- | :--- |
| **`app/Controllers/AuthController.php`** | `UserModel.php`, `BaseController.php` | `app/Views/login.php`, `$_SESSION` | Form Login, Form Register, Chuyển hướng Role Admin/Customer |
| **`app/Models/UserModel.php`** | `BaseModel.php`, `config/Database.php` | `AuthController.php`, `ProfileController.php` | Hàm mã hóa `password_hash`, hàm `password_verify` |
| **`app/Controllers/CartController.php`** | `ProductModel.php`, `OrderModel.php` | `app/Views/cart.php`, `checkout.php` | Session giỏ hàng, trừ tồn kho trong Transaction PDO |
| **`app/Views/home.php`** | `HomeController.php`, Tailwind CSS | Toàn bộ trải nghiệm trang chủ | Hero Banner Animation, Responsive Grid sản phẩm, Link xem chi tiết |

---

## 4. Hướng Dẫn Sử Dụng Skill Này Cho AI & Developer

1. **Khi muốn thêm tính năng mới:**
   - Tra cứu sơ đồ ở Mục 1 để xác định tính năng thuộc Controller nào.
   - Viết logic Model kế thừa `BaseModel` trước, sau đó mới viết Controller và kết nối ra View.
2. **Khi debug lỗi:**
   - Đi ngược luồng Request: Xem `Error Log` ở dòng nào $\rightarrow$ Tra cứu Controller phụ trách $\rightarrow$ Kiểm tra Model và câu lệnh SQL Prepared Statement tương ứng.
