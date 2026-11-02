---
name: seo-analytics
description: "Quy chuẩn tối ưu hóa công cụ tìm kiếm (SEO On-Page), tạo dữ liệu có cấu trúc Schema.org JSON-LD cho sản phẩm thời trang, Open Graph meta tags và sitemap tự động."
---

# Skill: SEO Architect & E-commerce Analytics

Skill này định hình toàn bộ chuẩn mực tối ưu hóa công cụ tìm kiếm (Search Engine Optimization - SEO) và dữ liệu có cấu trúc cho hệ thống Website Bán Quần Áo:

---

## 1. Tối Ưu Thẻ Meta & Open Graph (Social Sharing)
Mọi trang chi tiết sản phẩm (`app/Views/product_detail.php`) và trang danh mục bắt buộc xuất đủ các thẻ meta chuẩn:
- **Title & Description chuẩn hóa:**
  - Tiêu đề: `[Tên Sản Phẩm] - Thời Trang Nam Nữ Cao Cấp | Tên Cửa Hàng` (Độ dài 50–60 ký tự).
  - Mô tả: Tóm tắt điểm nổi bật, chất liệu vải, ưu đãi giảm giá (Độ dài 150–160 ký tự).
- **Thẻ Canonical URL:** Tránh hiện tượng trùng lặp nội dung giữa các biến thể hoặc tham số lọc trên URL:
  ```html
  <link rel="canonical" href="https://domain.com/san-pham/<?= htmlspecialchars($product['slug'] ?? '') ?>">
  ```
- **Open Graph (Facebook / Zalo preview) & Twitter Cards:**
  ```html
  <meta property="og:type" content="product">
  <meta property="og:title" content="<?= htmlspecialchars($product['name']) ?>">
  <meta property="og:description" content="<?= htmlspecialchars(substr($product['description'], 0, 150)) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($product['image_url']) ?>">
  <meta property="product:price:amount" content="<?= htmlspecialchars($product['price']) ?>">
  <meta property="product:price:currency" content="VND">
  ```

---

## 2. Dữ Liệu Có Cấu Trúc Sản Phẩm (Schema.org JSON-LD)
Nhúng trực tiếp đoạn mã JSON-LD vào thẻ `<head>` của trang chi tiết sản phẩm để Google hiển thị Rich Snippets (Ảnh, giá tiền, tình trạng còn hàng, số sao đánh giá trên trang kết quả tìm kiếm):

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= htmlspecialchars($product['name']) ?>",
  "image": "<?= htmlspecialchars($product['image_url']) ?>",
  "description": "<?= htmlspecialchars(strip_tags($product['description'])) ?>",
  "sku": "SKU-<?= $product['id'] ?>",
  "brand": {
    "@type": "Brand",
    "name": "Clothing Store"
  },
  "offers": {
    "@type": "Offer",
    "url": "https://domain.com/san-pham/<?= $product['slug'] ?>",
    "priceCurrency": "VND",
    "price": "<?= $product['sale_price'] ?? $product['price'] ?>",
    "priceValidUntil": "2026-12-31",
    "availability": "<?= ($product['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
```

---

## 3. Cấu Trúc Đường Dẫn Thân Thiện (Friendly URLs / Slugs)
- URL sản phẩm bắt buộc dùng chuỗi định danh chữ thường không dấu, nối bằng dấu gạch ngang: `/san-pham/ao-khoac-bomber-nam-den`.
- Bảng `products` lưu trữ cột `slug` có đánh `UNIQUE INDEX`.
- Sinh slug tự động bằng hàm chuyển đổi tiếng Việt có dấu sang không dấu khi thêm hoặc sửa sản phẩm trong trang quản trị.

---

## 4. Robots.txt & Sitemap XML Tự Động
- Cung cấp file `robots.txt` cho phép các bot tìm kiếm thu thập thông tin trang chủ, danh mục và sản phẩm; chặn các trang quản trị nhạy cảm (`/admin/`, `/checkout/`, `/cart/`).
- Duy trì controller sinh `sitemap.xml` tự động nạp toàn bộ danh sách URL sản phẩm và danh mục kèm thuộc tính `<lastmod>` và `<priority>`.

