<?php
require_once __DIR__ . '/BaseController.php';

class HomeController extends BaseController {
    public function index() {
        // Trong thực tế sẽ gọi Model (vd: ProductModel->getFeaturedProducts()) để lấy từ CSDL
        // Ở đây giả lập dữ liệu tĩnh để xây dựng UI thật nhanh trước
        $featuredProducts = [
            ['id' => 1, 'name' => 'Áo Thun Basic Oversize', 'price' => 250000, 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80'],
            ['id' => 2, 'name' => 'Quần Jeans Denim Rách', 'price' => 450000, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=600&q=80'],
            ['id' => 3, 'name' => 'Áo Khoác Bomber Lót Dù', 'price' => 650000, 'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&q=80'],
            ['id' => 4, 'name' => 'Kính Mát Retro VUÔNG', 'price' => 150000, 'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=600&q=80'],
        ];

        // Truyền data ra view
        $this->render('home', [
            'title' => 'Trang chủ - VibeStore',
            'featuredProducts' => $featuredProducts
        ]);
    }
}
