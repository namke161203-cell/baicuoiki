<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class ProductController extends BaseController {
    
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }
    
    // Xem chi tiết 1 sản phẩm
    public function detail() {
        $id = (int)($_GET['id'] ?? 1);
        if ($id <= 0) {
            $this->redirect('index.php');
        }
        
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $this->redirect('index.php');
        }

        $this->render('product_detail', [
            'title' => $product['name'] . ' - hquie',
            'product' => $product
        ]);
    }

    // Tìm kiếm sản phẩm theo từ khóa
    public function search() {
        $keyword = trim($_GET['keyword'] ?? $_GET['q'] ?? '');
        
        if (!empty($keyword)) {
            $products = $this->productModel->searchProducts($keyword);
            $categoryName = 'Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"';
        } else {
            $products = $this->productModel->getAllProducts();
            $categoryName = 'Tất cả sản phẩm';
        }

        $this->render('product_list', [
            'title' => 'Tìm kiếm sản phẩm - hquie',
            'products' => $products,
            'keyword' => $keyword,
            'categoryName' => $categoryName
        ]);
    }

    // Lọc theo danh mục hoặc nhóm sản phẩm
    public function category() {
        $cat = trim($_GET['cat'] ?? $_GET['name'] ?? '');

        if (!empty($cat)) {
            $products = $this->productModel->getProductsByCategoryOrKeyword($cat);
            $categoryName = 'Danh mục: ' . htmlspecialchars($cat);
        } else {
            $products = $this->productModel->getAllProducts();
            $categoryName = 'Tất cả sản phẩm';
        }

        $this->render('product_list', [
            'title' => $categoryName . ' - hquie',
            'products' => $products,
            'keyword' => '',
            'categoryName' => $categoryName
        ]);
    }

    // Danh sách tất cả sản phẩm
    public function list() {
        $products = $this->productModel->getAllProducts();

        $this->render('product_list', [
            'title' => 'Tất cả sản phẩm - hquie',
            'products' => $products,
            'keyword' => '',
            'categoryName' => 'Tất cả sản phẩm'
        ]);
    }
}
