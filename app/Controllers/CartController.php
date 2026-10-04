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

            $itemData = [
                'product_id' => $productId,
                'name' => $productName,
                'image' => $productImage,
                'color' => $color,
                'size' => $size,
                'price' => $productPrice,
                'quantity' => $quantity
            ];

            if (isset($_POST['buy_now'])) {
                // Tạo một session riêng biệt cho việc Mua ngay, không gộp với giỏ hàng
                $_SESSION['buy_now_cart'] = [
                    $cartKey => $itemData
                ];
                header("Location: index.php?controller=Cart&action=checkout&type=buynow");
                exit;
            }

            // Nếu sp cùng size cùng màu đã có, chỉ tăng số lượng
            if (isset($_SESSION['cart'][$cartKey])) {
                $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cartKey] = $itemData;
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

    // Cập nhật giỏ hàng (Số lượng, Size)
    public function update() {
        $key = $_GET['key'] ?? '';
        if (isset($_SESSION['cart'][$key])) {
            $item = $_SESSION['cart'][$key];
            
            $newSize = $_GET['size'] ?? $item['size'];
            $newQuantity = isset($_GET['qty']) ? (int)$_GET['qty'] : $item['quantity'];
            
            if ($newQuantity <= 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                $newCartKey = $item['product_id'] . '_' . $item['color'] . '_' . $newSize;
                
                unset($_SESSION['cart'][$key]); // xoá key cũ
                
                $item['size'] = $newSize;
                $item['quantity'] = $newQuantity;
                
                // Nếu đổi size trùng với 1 món đã có sẵn thì gộp chung số lượng
                if (isset($_SESSION['cart'][$newCartKey]) && $newCartKey !== $key) {
                    $_SESSION['cart'][$newCartKey]['quantity'] += $newQuantity;
                } else {
                    $_SESSION['cart'][$newCartKey] = $item;
                }
            }
        }
        header("Location: index.php?controller=Cart&action=index");
        exit;
    }

    // Giao diện & Submit thanh toán
    public function checkout() {
        $isBuyNow = isset($_GET['type']) && $_GET['type'] === 'buynow';
        $cart = $isBuyNow ? ($_SESSION['buy_now_cart'] ?? []) : ($_SESSION['cart'] ?? []);

        if (empty($cart)) {
            header("Location: index.php?controller=Cart&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $this->render('checkout', [
                'title' => 'Thanh toán',
                'cart' => $cart,
                'total' => $total
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerInfo = [
                'user_id' => $_SESSION['user_id'] ?? null,
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
                
                // Xoá Session Cart tương ứng sau khi thanh toán thành công
                if ($isBuyNow) {
                    unset($_SESSION['buy_now_cart']);
                } else {
                    unset($_SESSION['cart']);
                }
                
                // Hiển thị màn hình cám ơn
                $this->render('checkout_success', [
                    'title' => 'Đặt hàng thành công',
                    'orderId' => $orderId,
                    'customerInfo' => $customerInfo
                ]);
            } catch (Exception $e) {
                die("Hệ thống quá tải, đặt hàng thất bại: " . $e->getMessage());
            }
        }
    }
}
