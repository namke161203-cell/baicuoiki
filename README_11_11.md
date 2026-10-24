### 1. Bản chất quy trình
Lập trình viên đóng vai trò là **Kiến trúc sư trưởng (Architect)** và **Người kiểm duyệt (Reviewer)**, không phụ thuộc mù quáng vào AI. Quy trình chuẩn 4 bước đã được áp dụng trong dự án:

1. **Giai đoạn 1: Context Engineering (Chuẩn bị ngữ cảnh & Kiến trúc nền tảng)**
   - Cung cấp trước cho Agent: Đặc tả nghiệp vụ (PRD), lược đồ CSDL (`database.sql`), và kiến trúc hệ thống (Mô hình MVC thuần trong thư mục `app/`).
   - Thiết lập cấu hình luật bắt buộc trong dự án qua tệp [`.cursorrules`](.cursorrules) và [`php-mvc-agent`](.agents/skills/php-mvc-agent/SKILL.md) nhằm ép AI:
     - 100% truy vấn CSDL phải dùng PDO Prepared Statement (chống SQL Injection).
     - Mật khẩu phải mã hóa bằng `password_hash()` BCRYPT.
     - Phân tầng nghiêm ngặt: Controller điều hướng, Model truy vấn DB, View render giao diện.

2. **Giai đoạn 2: Chia nhỏ bài toán (Divide & Conquer / Spec-Driven)**
   - Không yêu cầu Agent làm "toàn bộ website cùng một lúc" (tránh tràn context và hallucination).
   - Tách thành các chặng độc lập: Thiết kế Database $\rightarrow$ Xây dựng Base MVC $\rightarrow$ Module Xác thực (Auth) $\rightarrow$ Module Sản phẩm/Bộ lọc $\rightarrow$ Module Giỏ hàng & Checkout $\rightarrow$ Giao diện Admin.

3. **Giai đoạn 3: Prompting có cấu trúc (Iterative Prompting)**
   - Áp dụng cấu trúc: **[Role] + [Context] + [Task] + [Constraints] + [Output Format]**.
   - *Ví dụ Prompt:* "Đóng vai trò Senior PHP Dev, hãy viết phương thức `login()` trong `AuthController.php`, tiếp nhận dữ liệu POST, gọi `UserModel.php` kiểm tra bằng `password_verify`, lưu session `user_id` và phân quyền điều hướng admin/khách hàng."

4. **Giai đoạn 4: Human-in-the-loop Review & Debugging**
   - Đọc kỹ `git diff` trước khi chấp nhận mã nguồn do AI sinh ra.
   - Khi có lỗi runtime từ Apache/PHP, copy log lỗi nguyên bản để AI phân tích nguyên nhân gốc rễ (Root Cause Analysis).

---

# Ý 2: Kỹ năng kiểm soát code (5 điểm)

---

### 2a. Kiểm soát tính năng
*(Định vị mã nguồn & Hướng dẫn sửa tính năng: Đăng nhập & Ràng buộc mật khẩu có `@`)*

#### 1. Vị trí mã nguồn tính năng Đăng nhập / Đăng ký trong dự án:
Dự án được phân tầng chuẩn MVC, tính năng nằm tại:
- **Tầng Điều hướng & Kiểm tra (Controller):** [`app/Controllers/AuthController.php`](app/Controllers/AuthController.php)
  - Phương thức `login()` (dòng 8 - 42): Tiếp nhận email, password, gọi Model xác thực và lưu session.
  - Phương thức `register()` (dòng 45 - 77): Kiểm tra hợp lệ dữ liệu, đăng ký tài khoản mới.
- **Tầng Dữ liệu & Nghiệp vụ (Model):** [`app/Models/UserModel.php`](app/Models/UserModel.php)
  - Phương thức `login($email, $password)`: Dùng Prepared Statement truy vấn theo email, dùng `password_verify()` so khớp mật khẩu hash.
  - Phương thức `register($name, $email, $password)`: Hash mật khẩu bằng `password_hash(..., PASSWORD_BCRYPT)`.
- **Tầng Giao diện (View):** [`app/Views/login.php`](app/Views/login.php)
  - Chứa Form nhập liệu Đăng nhập / Đăng ký (Modal tab).

### 2b. Kỹ năng dùng skill: Graphify & AI Skills

Dự án đã được tích hợp đầy đủ hệ thống Skill chuyên nghiệp:

1. **Skill `graphify` ([`.agents/skills/graphify/SKILL.md`](.agents/skills/graphify/SKILL.md)):**
   - **Tác dụng:** Phân tích tĩnh toàn bộ mã nguồn để xây dựng **Code Dependency Graph (Cây phụ thuộc mã nguồn)**.
   - **Ứng dụng:**
     - Cho thấy rõ quan hệ: `Router (index.php)` $\rightarrow$ `Controllers` $\rightarrow$ `Models` $\rightarrow$ `MySQL Database`.
     - Chứa **Ma trận đánh giá tác động (Impact Analysis Matrix)**: Khi sửa một file (như `UserModel.php`), biết chính xác những file nào bị ảnh hưởng (`AuthController`, `ProfileController`) để kiểm thử lại, tránh làm vỡ hệ thống.
     - Giúp AI Agent tra cứu ngữ cảnh chính xác, không bị ảo giác (hallucination).

2. **Trang trực quan hóa tương tác ([`public/code_graph.html`](public/code_graph.html)):**
   - Truy cập trực tiếp tại: `http://localhost/duan/public/code_graph.html`.
   - Hiển thị trực quan toàn bộ sơ đồ kiến trúc hệ thống bằng Mermaid.js, có highlight riêng luồng tính năng Xác thực.

3. **Skill quy chuẩn kiến trúc [`php-mvc-agent`](.agents/skills/php-mvc-agent/SKILL.md) & [`.cursorrules`](.cursorrules):**
   - Bắt buộc tuân thủ nguyên tắc MVC: Không viết SQL trong Controller, bảo mật CSRF/XSS/SQLi, và chuẩn hóa commit Git.

---

### 2c. Hiểu về Log, Diff & Vai trò Leader đánh giá hiệu quả

#### 1. Ý nghĩa của `git log` và `git diff`:
- **`git log`:** Là **dòng thời gian lịch sử** của dự án. Cho biết: Ai làm? Vào lúc nào? Với mục đích gì? (qua commit message). Cho thấy tính liên tục, kỷ luật và tính mạch lạc của lập trình viên.
- **`git diff`:** Là **chi tiết kỹ thuật của sự thay đổi**. Hiển thị chính xác từng dòng thêm (`+`), bớt (`-`), sửa trong mã nguồn. Cho thấy chất lượng kỹ thuật, tính tập trung của code, và phạm vi tác động.

#### 2. Vai trò Tech Leader: Cách nhận biết thành viên làm việc hiệu quả hay không:

> **LƯU Ý CỐT LÕI:** Tech Leader KHÔNG BAO GIỜ đánh giá dựa trên **Số dòng code (LOC)** hoặc **Số lượng commit**. Đếm dòng code là thước đo sai lệch, dễ bị gian lận bằng cách copy thư viện ngoài, định dạng khoảng trắng hoặc spam commit rác.

Leader sử dụng `git log` và `git diff` kết hợp Pull Request để đánh giá qua các yếu tố:

| Tiêu chí qua Git | Dấu hiệu thành viên HIỆU QUẢ | Dấu hiệu thành viên KÉM / GIAN LẬN |
| :--- | :--- | :--- |
| **Kỷ luật qua `git log`** | Commit đều đặn mỗi ngày. Sử dụng **Atomic Commits** (mỗi commit giải quyết trọn vẹn 1 việc). | Cả tuần im lặng, sát deadline mới đẩy 1 commit khổng lồ ("Dump code"). |
| **Thông điệp qua `git log`** | Tuân thủ Conventional Commits (`feat:`, `fix:`, `refactor:`) kèm giải thích rõ lý do. | Commit cẩu thả: `update`, `fix`, `abc`, `test`, `ok`. |
| **Độ tập trung qua `git diff`** | Diff súc tích, chỉ thay đổi đúng phạm vi task được giao. Code tối ưu, sạch sẽ. | Diff lan man sang các file không liên quan, xóa nhầm code đồng đội, để sót `var_dump()` rác. |
| **Độ ổn định (Code Churn)** | Code merge vào chạy mượt mà, hiếm khi phải revert hoặc sửa lại. | Vừa merge xong liên tục xuất hiện hàng loạt commit `fix lại lỗi vừa push`, tỉ lệ phát sinh bug cao. |

#### 3. Bộ tiêu chí hiệu quả toàn diện:
1. **Delivery:** Hoàn thành đúng hạn và đáp ứng đúng 100% đặc tả yêu cầu, xử lý đầy đủ edge-cases.
2. **Code Quality:** Mã nguồn sạch, dễ đọc, tuân thủ kiến trúc MVC, có chú thích khi cần thiết.
3. **Collaboration:** Tốc độ phản hồi và sửa đổi theo feedback của đồng đội khi Review Pull Request.
4. **Defect Density:** Tỉ lệ phát sinh lỗi trên môi trường thực tế thấp.
