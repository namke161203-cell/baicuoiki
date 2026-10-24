<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class HomeController extends BaseController {
    public function index() {
        $productModel = new ProductModel();
        $featuredProducts = $productModel->getFeaturedProducts(16);
        $categories = $productModel->getCategoriesWithCount();
        
        // Truyền data ra view
        $this->render('home', [
            'title' => 'hquie - Thời trang cao cấp & Phong cách Mùa Đông 2026',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}

