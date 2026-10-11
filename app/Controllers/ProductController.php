<?php
require_once __DIR__ . '/BaseController.php';

class ProductController extends BaseController {
    public function detail() {
        $id = $_GET['id'] ?? 1;

        require_once __DIR__ . '/../Models/ProductModel.php';
        $productModel = new ProductModel();
        
        $product = $productModel->getProductById($id);

        if (!$product) {
            // Hiển thị lỗi hoặc chuyển hướng về trang chủ
            header("Location: index.php");
            exit;
        }

        $this->render('product_detail', [
            'title' => $product['name'] . ' - VibeStore',
            'product' => $product
        ]);
    }
}
