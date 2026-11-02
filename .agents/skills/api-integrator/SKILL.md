---
name: api-integrator
description: "Quy chuẩn tích hợp cổng thanh toán trực tuyến (VNPay, MoMo), dịch vụ Email giao dịch tự động (PHPMailer/SMTP) và xử lý Webhooks/IPN an toàn."
---

# Skill: Third-Party API & Payment Gateway Integrator

Skill này định hình toàn bộ chuẩn mực tích hợp dịch vụ bên thứ ba (Cổng thanh toán điện tử, gửi email tự động và xử lý thông báo IPN/Webhook) cho hệ thống Website Bán Hàng:

---

## 1. Tích Hợp Cổng Thanh Toán Trực Tuyến (VNPay / MoMo Gateway)

### 1.1 Khởi Tạo Giao Dịch Thanh Toán (Payment Request)
- Tạo mã đơn hàng duy nhất (`vnp_TxnRef` hoặc `orderId`).
- Chuẩn hóa số tiền thanh toán (Ví dụ: VNPay yêu cầu nhân 100 theo đơn vị đồng: `$vnp_Amount = $orderTotal * 100`).
- Ký bảo mật dữ liệu bằng mã băm HMAC-SHA512 với `vnp_HashSecret`:
  ```php
  // Quy chuẩn sắp xếp key theo alphabet và tạo chuỗi checksum
  ksort($inputData);
  $hashData = http_build_query($inputData);
  $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
  $paymentUrl = $vnp_Url . "?" . $hashData . '&vnp_SecureHash=' . $vnpSecureHash;
  ```

### 1.2 Xử Lý Phản Hồi IPN (Instant Payment Notification) & Return URL
- **Bắt buộc kiểm tra chữ ký (Signature Verification):** Tuyệt đối không cập nhật trạng thái đơn hàng nếu mã băm gửi về không khớp với tính toán nội bộ.
- **Tính Idempotency (Chống lặp giao dịch):**
  - Kiểm tra xem đơn hàng đã ở trạng thái "Đã thanh toán" (`paid`) từ trước hay chưa.
  - Nếu đã xử lý rồi, trả về mã xác nhận thành công ngay cho cổng thanh toán mà không trừ kho hoặc cộng dồn 2 lần.
- **Xác thực số tiền:** Đối chiếu `$vnp_Amount / 100` với tổng tiền thực tế trong bảng `orders` của hệ thống để chống gian lận thay đổi giá trị request.

---

## 2. Gửi Email Giao Dịch Tự Động (Transactional Emails)
- **Tích hợp PHPMailer qua giao thức SMTP an toàn:**
  - Cấu hình TLS/SSL trên cổng 587 hoặc 465.
  - Không hardcode mật khẩu hòm thư trong mã nguồn (đọc từ biến môi trường hoặc file config riêng biệt).
- **Mẫu Email chuẩn HTML cho ngành thời trang:**
  - **Email Xác nhận đơn hàng:** Đầy đủ mã đơn, danh sách chi tiết (Tên sản phẩm, size, màu, đơn giá, số lượng), phí ship, mã giảm giá và tổng tiền thanh toán.
  - **Email Đặt lại mật khẩu:** Chứa đường link kèm token ngẫu nhiên bảo mật cao và giới hạn thời gian hiệu lực (ví dụ: hết hạn sau 15–30 phút).
- **Thiết kế Responsive & Tối ưu Inbox:** Đảm bảo template hiển thị đẹp trên cả ứng dụng di động (Gmail app, Apple Mail, Outlook).

---

## 3. Quản Lý Lỗi & Ghi Nhật Ký Giao Dịch (API Logging & Fallbacks)
- Mọi request gửi đi và response nhận về từ cổng thanh toán bên thứ ba phải được ghi log kèm timestamp vào file riêng biệt (`storage/logs/payment_YYYY-MM-DD.log`).
- Che giấu các thông tin nhạy cảm (Secret key, số thẻ ngân hàng, CVV) trước khi ghi vào log.

