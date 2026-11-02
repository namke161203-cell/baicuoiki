---
name: qa-testing
description: "Quy trình kiểm thử chất lượng toàn diện (Unit Test, Integration Test, Security/Penetration Test, UI Component Test) và công cụ kiểm thử tự động cho dự án PHP MVC."
---

# Skill: QA & Test Engineer (Kiểm Thử Toàn Diện)

Skill này định hình toàn bộ chuẩn mực kiểm thử phần mềm (Testing Strategy, Automated Test Suites, Security Auditing và Quality Gates) cho dự án Website Bán Hàng PHP MVC:

---

## 1. Kim Tự Tháp Kiểm Thử (Testing Pyramid)

```
        / \
       / E2E \         Kiểm thử luồng mua hàng trọn vẹn (Cart -> Checkout -> Order Success)
      /-------\
     /  Integ  \       Kiểm thử Controller, Routing, Transaction DB, API JSON Contracts
    /-----------\
   /   Unit &    \     Kiểm thử Logic mật khẩu, Validation dữ liệu, Model functions,
  /   Security    \    Quét lỗ hổng SQL Injection & XSS Escape trong View
 /-----------------\
```

---

## 2. Các Tầng Kiểm Thử Bắt Buộc (Test Levels)

### 2.1 Unit Tests (Kiểm thử đơn vị)
- **Kiểm thử logic xác thực (Auth Logic):**
  - Mật khẩu phải đủ 6 ký tự và có chứa ký tự `@`.
  - Mã hóa `password_hash` với thuật toán BCRYPT và kiểm chứng bằng `password_verify`.
- **Kiểm thử Model & Logic tính giá:**
  - Tính tổng giá trị đơn hàng, giảm trừ mã coupon (giảm theo % hoặc số tiền cố định).
  - Đảm bảo giá trị thanh toán cuối cùng không bao giờ âm (`final_total >= 0`).

### 2.2 Integration Tests (Kiểm thử tích hợp & Luồng nghiệp vụ)
- **Kiểm thử Giỏ hàng & Tồn kho:**
  - Thêm sản phẩm biến thể (Size/Color) vào session giỏ hàng.
  - Cập nhật số lượng, kiểm tra giới hạn tồn kho (`quantity <= stock`).
  - Xóa sản phẩm khỏi giỏ hàng.
- **Kiểm thử API Endpoint:**
  - Kiểm tra các action AJAX trả về đúng JSON format: `{ "success": bool, "message": string, "data": ... }`.
  - Mã trạng thái HTTP phù hợp (200 OK, 400 Bad Request, 403 Forbidden, 404 Not Found).

### 2.3 Security & Code Audit Tests (Kiểm thử bảo mật & Quét mã tĩnh)
- **Quét SQL Injection:** Đảm bảo không tồn tại câu lệnh nối chuỗi SQL (`$db->query("SELECT ... WHERE id = " . $id)`), 100% phải dùng `$stmt->prepare()`.
- **Quét XSS Vulnerabilities:** Kiểm tra các file trong `app/Views/` xuất biến động người dùng có được bọc qua `htmlspecialchars()`.
- **Kiểm tra Phân quyền Admin:** Xác minh tất cả các Controller trong `Admin*Controller` đều có cơ chế kiểm tra `$_SESSION['user_role'] === 'admin'`.

### 2.4 UI & Component Verification (Kiểm thử giao diện)
- Kiểm tra các View tuân thủ bảng màu chuẩn **Ocean Blue** (`#0284c7`, `#0369a1`, `#e0f2fe`).
- Kiểm tra sự hiện diện của các thành phần Atomic Design:
  - Component hiển thị thẻ sản phẩm: `app/Views/components/product_card.php`.
  - Component trạng thái rỗng: `app/Views/components/empty_state.php`.
- Kiểm tra tính responsive trên thiết bị di động (Mobile First).

---

## 3. Công Cụ Kiểm Thử Tự Động (Automated Test Suite)

Dự án cung cấp kịch bản kiểm thử tự động độc lập chạy trực tiếp qua PHP CLI:
```bash
# Lệnh thực thi toàn bộ test suite
C:\xampp2\php\php.exe tests/run_tests.php
```

Mỗi lần chạy sẽ kiểm tra tuần tự:
1. `TestArchitecture`: Tính toàn vẹn cấu trúc MVC, class inheritance (`BaseModel`, `BaseController`).
2. `TestSecurity`: Quét lỗ hổng SQLi, XSS escaping trong Views, kiểm tra bảo mật session và bcrypt.
3. `TestBusinessLogic`: Kiểm tra logic mật khẩu `@`, thuật toán tính giỏ hàng, áp dụng coupon.
4. `TestFrontendComponents`: Kiểm tra sự tồn tại và tuân thủ chuẩn của các Component, Design System Ocean Blue.
5. `TestApiContracts`: Kiểm tra cấu trúc JSON schema của các phản hồi AJAX.

---

## 4. Tiêu Chuẩn Quality Gate
- 100% các Test Suites trong `tests/run_tests.php` phải đạt trạng thái **PASS (Xanh)**.
- 0 cảnh báo bảo mật nghiêm trọng (SQL Injection hoặc thiếu Role Middleware).
- Báo cáo kết quả kiểm thử rõ ràng với số lượng assertions, thời gian thực thi và tỷ lệ thành công.

