<?php
require_once __DIR__ . '/BaseController.php';

class ProductController extends BaseController {
    public function detail() {
        $id = $_GET['id'] ?? 1;

        // Giả lập Dữ liệu Chi tiết Sản phẩm được trả về từ DB kèm các Biến Thể
        $product = [
            'id' => $id,
            'name' => 'Áo Khoác Bomber Lót Dù Cao Cấp',
            'price' => 650000,
            'description' => 'Chất liệu vải dù cao cấp, lót dù bên trong mát mẻ, form chuẩn nam tính tôn dáng. Phù hợp dạo phố, đi phượt mùa thu đông. Đường may tinh tế, chống thấm nước nhẹ.',
            'images' => [
                'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1550614000-4b95dd244959?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1520975954732-57dd22299614?auto=format&fit=crop&w=800&q=80'
            ],
            // Biến thể kèm số lượng (Quản lý kho)
            'variants' => [
                ['size' => 'M', 'color' => 'Đen', 'stock' => 10],
                ['size' => 'L', 'color' => 'Đen', 'stock' => 5],
                ['size' => 'M', 'color' => 'Xanh rêu', 'stock' => 0], // Hết hàng
                ['size' => 'L', 'color' => 'Xanh rêu', 'stock' => 15],
            ]
        ];

        $this->render('product_detail', [
            'title' => $product['name'] . ' - VibeStore',
            'product' => $product
        ]);
    }
}
