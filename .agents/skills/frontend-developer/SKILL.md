---
name: frontend-developer
description: "Quy chuẩn thiết kế giao diện Atomic Design, Design System Ocean Blue, xử lý trạng thái AJAX/Fetch 3-states và tối ưu trải nghiệm người dùng (UX/UI) cho Website Bán Hàng PHP MVC."
---

# Skill: Frontend Developer & UI Component Architect

Skill này định hình toàn bộ chuẩn mực phát triển giao diện phía Client (HTML, CSS/Tailwind, JavaScript, View Components) cho dự án Website Bán Quần Áo:

---

## 1. Kiến Trúc Giao Diện Thành Phần (Atomic Design)
Tất cả giao diện được chuẩn hóa và tái sử dụng thông qua cấu trúc phân tầng trong `app/Views/`:
- **Atoms (Nguyên tử):** Các thành phần cơ bản nhất không thể chia nhỏ.
  - Buttons (`btn-primary`, `btn-outline`), Badges (`badge-sale`, `badge-stock`), Input fields, Price tags (`formatted_price`).
- **Molecules (Phân tử):** Tổ hợp các nguyên tử tạo thành khối tính năng độc lập.
  - `app/Views/components/product_card.php`: Thẻ hiển thị sản phẩm với ảnh, tên, giá sale, nút chọn biến thể (size/màu) và nút "Thêm vào giỏ".
  - Floating cart badge (`cart-counter`), Khung tìm kiếm gợi ý nhanh (`quick-search-box`).
- **Organisms (Sinh vật):** Tổ hợp các phân tử tạo nên cụm giao diện hoàn chỉnh.
  - Header & Navbar (`app/Views/partials/header.php`), Footer (`app/Views/partials/footer.php`).
  - Checkout summary panel (`app/Views/checkout.php`), Bộ lọc đa tiêu chí (Lọc danh mục, size, màu, khoảng giá).
- **Templates & Pages:** Giao diện trang đầy đủ (`home.php`, `product_detail.php`, `cart.php`, `admin/*.php`).
- **Empty States (`app/Views/components/empty_state.php`):** Bắt buộc hiển thị minh họa trực quan khi giỏ hàng trống, không tìm thấy sản phẩm, hoặc chưa có lịch sử đơn hàng.

---

## 2. Design System & Bảng Màu Nhất Quán (Ocean Blue Theme)
Giao diện áp dụng 100% bảng màu thương hiệu **Ocean Blue** chuẩn thời trang cao cấp:
- **Primary Color:** `#0284c7` (Sky-600 / Xanh đại dương chủ đạo) - Dùng cho nút kêu gọi hành động (CTA), liên kết nổi bật, active tabs.
- **Dark Primary:** `#0369a1` (Sky-700) và `#0f172a` (Slate-900) - Dùng cho Header, Footer, chữ tiêu đề, hover states.
- **Light Tint / Background:** `#e0f2fe` (Sky-100) và `#f8fafc` (Slate-50) - Dùng cho nền card, hover item, tag khuyến mãi.
- **Accent & Status:**
  - Success: `#10b981` (Emerald-500) - Thông báo đặt hàng thành công, còn hàng.
  - Warning/Sale: `#f59e0b` (Amber-500) - Tag giảm giá, flash sale.
  - Danger: `#ef4444` (Red-500) - Báo hết hàng, xóa sản phẩm, lỗi form.
- **Typography & Localization:** 100% hiển thị tiếng Việt chuẩn mực, font sans-serif hiện đại (Inter, Roboto, hoặc Tailwind default sans).

---

## 3. Quy Chuẩn Tích Hợp AJAX / Fetch (Xử Lý 3 Trạng Thái - 3 States Handling)
Mọi thao tác bất đồng bộ (Thêm giỏ hàng, cập nhật số lượng, áp mã coupon, tìm kiếm) bắt buộc xử lý triệt để 3 trạng thái:

```javascript
// Chuẩn mực xử lý fetch API trong frontend
async function addToCart(productId, variantId, quantity) {
  const btn = document.querySelector(`#btn-add-${productId}`);
  
  // 1. LOADING STATE
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Đang xử lý...`;

  try {
    const response = await fetch('/index.php?controller=Cart&action=addAjax', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: quantity })
    });
    const result = await response.json();

    if (result.success) {
      // 2. SUCCESS STATE
      showToast('success', result.message || 'Đã thêm vào giỏ hàng thành công!');
      updateCartBadge(result.total_items);
    } else {
      // 3. ERROR STATE (Business error)
      showToast('error', result.message || 'Không thể thêm sản phẩm vào giỏ!');
    }
  } catch (err) {
    // 3. ERROR STATE (Network / System error)
    showToast('error', 'Lỗi kết nối máy chủ! Vui lòng thử lại sau.');
  } finally {
    // Khôi phục nút bấm
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
}
```

---

## 4. Bảo Mật Phía Giao Diện (Frontend Security)
1. **Chống XSS (Cross-Site Scripting):** Mọi biến PHP render ra HTML bắt buộc bọc qua `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
2. **Sanitize DOM Insertion:** Khi render dữ liệu JSON từ server lên DOM bằng JS, ưu tiên dùng `textContent` hoặc tạo element an toàn thay vì gán trực tiếp chuỗi vào `innerHTML`.
3. **Form Validation Phía Client:** Kiểm tra tính hợp lệ của email, số điện thoại, mật khẩu (độ dài >= 6, chứa `@`) ngay khi người dùng nhập (Real-time Feedback) để nâng cao trải nghiệm trước khi gửi request.

