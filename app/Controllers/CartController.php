<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class CartController extends BaseController {
    
    // Thêm sản phẩm vào Giỏ (Session)
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? 1;
            $color = $_POST['color'] ?? 'Không chỉ định';
            $size = $_POST['size'] ?? 'Free';
            $quantity = (int)($_POST['quantity'] ?? 1);

            // Khóa giỏ hàng: 1 sản phẩm có thể có nhiều dòng nếu khác size/color
            $cartKey = $productId . '_' . $color . '_' . $size;

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // NOTE: Thực tế ta cần query ProductModel để lấy Tên, Giá, Ảnh chuẩn. 
            // Ở prototype này ta giả lập để nhanh chóng hoàn thiện flow.
            $productPrice = 650000;
            $productName = "Áo Khoác Bomber Lót Dù Cao Cấp"; 
            $productImage = "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=150&q=80";

            // Nếu sp cùng size cùng màu đã có, chỉ tăng số lượng
            if (isset($_SESSION['cart'][$cartKey])) {
                $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'product_id' => $productId,
                    'name' => $productName,
                    'image' => $productImage,
                    'color' => $color,
                    'size' => $size,
                    'price' => $productPrice,
                    'quantity' => $quantity
                ];
            }

            header("Location: index.php?controller=Cart&action=index");
            exit;
        }
    }

    // Hiển thị Giỏ hàng & Form Thanh toán
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->render('cart', [
            'title' => 'Giỏ hàng & Thanh toán',
            'cart' => $cart,
            'total' => $total
        ]);
    }

    // Xoá sản phẩm khỏi giỏ
    public function remove() {
        $key = $_GET['key'] ?? '';
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
        }
        header("Location: index.php?controller=Cart&action=index");
        exit;
    }

    // Submit thanh toán
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                die("Giỏ hàng của bạn đang trống!");
            }

            $customerInfo = [
                'name' => trim($_POST['fullname'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim(($_POST['address'] ?? '') . ', ' . ($_POST['city'] ?? ''))
            ];

            // Validation cơ bản
            if(empty($customerInfo['name']) || empty($customerInfo['phone'])) {
                die("Vui lòng điền đủ Tên và Số điện thoại!");
            }

            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            $orderModel = new OrderModel();
            try {
                // Lưu Order và Order_Items vào Database
                $orderId = $orderModel->createOrder($customerInfo, $cart, $totalAmount);
                
                // Xoá Session Cart sau khi thanh toán thành công
                unset($_SESSION['cart']);
                
                // Hiển thị màn hình cám ơn
                $this->render('checkout_success', [
                    'title' => 'Đặt hàng thành công',
                    'orderId' => $orderId
                ]);
            } catch (Exception $e) {
                die("Hệ thống quá tải, đặt hàng thất bại: " . $e->getMessage());
            }
        }
    }
}
