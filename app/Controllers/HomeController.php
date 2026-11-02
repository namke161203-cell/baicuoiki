<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class HomeController extends BaseController {
    
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index() {
        $featuredProducts = $this->productModel->getFeaturedProducts(16);
        $categories = $this->productModel->getCategoriesWithCount();
        
        // Truyền data ra view
        $this->render('home', [
            'title' => 'hquie - Thời trang cao cấp & Phong cách Mùa Đông 2026',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}

