<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class ProductController extends BaseController {
    
    // Xem chi tiết 1 sản phẩm
    public function detail() {
        $id = $_GET['id'] ?? 1;
        $productModel = new ProductModel();
        
        $product = $productModel->getProductById($id);

        if (!$product) {
            header("Location: index.php");
            exit;
        }

        $this->render('product_detail', [
            'title' => $product['name'] . ' - hquie',
            'product' => $product
        ]);
    }

    // Tìm kiếm sản phẩm theo từ khóa
    public function search() {
        $keyword = trim($_GET['keyword'] ?? $_GET['q'] ?? '');
        $productModel = new ProductModel();
        
        if (!empty($keyword)) {
            $products = $productModel->searchProducts($keyword);
            $categoryName = 'Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"';
        } else {
            $products = $productModel->getAllProducts();
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
        $productModel = new ProductModel();

        if (!empty($cat)) {
            $products = $productModel->getProductsByCategoryOrKeyword($cat);
            $categoryName = 'Danh mục: ' . htmlspecialchars($cat);
        } else {
            $products = $productModel->getAllProducts();
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
        $productModel = new ProductModel();
        $products = $productModel->getAllProducts();

        $this->render('product_list', [
            'title' => 'Tất cả sản phẩm - hquie',
            'products' => $products,
            'keyword' => '',
            'categoryName' => 'Tất cả sản phẩm'
        ]);
    }
}
