<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class CartController extends BaseController {
    
    // Bảng giá các mức vận chuyển
    private $shippingRates = [
        'tieuchuan' => [
            'id' => 'tieuchuan',
            'name' => 'Giao hàng Tiêu chuẩn',
            'desc' => '3 - 5 ngày làm việc',
            'fee' => 20000
        ],
        'nhanh' => [
            'id' => 'nhanh',
            'name' => 'Giao hàng Nhanh',
            'desc' => '1 - 2 ngày làm việc',
            'fee' => 40000
        ],
        'sieutoc' => [
            'id' => 'sieutoc',
            'name' => 'Giao Hỏa Tốc 2H',
            'desc' => 'Nhận ngay trong 2 - 4 giờ',
            'fee' => 65000
        ]
    ];

    // Thêm sản phẩm vào Giỏ (Session)
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // YÊU CẦU: Nếu chưa đăng nhập thì chuyển hướng tới trang đăng nhập
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['auth_message'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hoặc đặt mua hàng!';
                $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                header("Location: index.php?controller=Auth&action=login");
                exit;
            }

            $productId = $_POST['product_id'] ?? 1;
            $color = $_POST['color'] ?? 'Không chỉ định';
            $size = $_POST['size'] ?? 'Free';
            $quantity = (int)($_POST['quantity'] ?? 1);

            // Khóa giỏ hàng: 1 sản phẩm có thể có nhiều dòng nếu khác size/color
            $cartKey = $productId . '_' . $color . '_' . $size;

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            require_once __DIR__ . '/../Models/ProductModel.php';
            $pModel = new ProductModel();
            $pInfo = $pModel->getProductById($productId);

            $productPrice = $pInfo ? (float)$pInfo['price'] : 650000;
            $productName = $pInfo ? $pInfo['name'] : "Áo Khoác Bomber Lót Dù Cao Cấp"; 
            $productImage = ($pInfo && !empty($pInfo['images'])) ? $pInfo['images'][0] : "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=150&q=80";

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

            $_SESSION['cart_success'] = 'Đã thêm ' . $productName . ' vào giỏ hàng!';
            
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?controller=Product&action=index';
            header("Location: " . $redirectUrl);
            exit;
        }
    }

    /**
     * API CONTRACT: Thêm sản phẩm qua AJAX JSON (API Integration: Hỗ trợ 3 trạng thái mạng)
     */
    public function ajaxAdd() {
        header('Content-Type: application/json; charset=utf-8');

        // YÊU CẦU: Nếu chưa đăng nhập thì trả về mã yêu cầu đăng nhập
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'require_login' => true,
                'redirect' => 'index.php?controller=Auth&action=login',
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
            exit;
        }

        $rawInput = file_get_contents('php://input');
        $jsonData = json_decode($rawInput, true);

        $productId = (int)($jsonData['product_id'] ?? $_POST['product_id'] ?? 0);
        $color = trim($jsonData['color'] ?? $_POST['color'] ?? 'Không chỉ định');
        $size = trim($jsonData['size'] ?? $_POST['size'] ?? 'Free');
        $quantity = (int)($jsonData['quantity'] ?? $_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thông tin sản phẩm hoặc số lượng không hợp lệ.']);
            exit;
        }

        require_once __DIR__ . '/../Models/ProductModel.php';
        $pModel = new ProductModel();
        $pInfo = $pModel->getProductById($productId);

        if (!$pInfo) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong hệ thống.']);
            exit;
        }

        $cartKey = $productId . '_' . $color . '_' . $size;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $productPrice = (float)$pInfo['price'];
        $productName = $pInfo['name'];
        $productImage = (!empty($pInfo['images']) && is_array($pInfo['images'])) ? $pInfo['images'][0] : "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=150&q=80";

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

        // Tính tổng số lượng sản phẩm trong giỏ
        $totalItems = 0;
        foreach ($_SESSION['cart'] as $cItem) {
            $totalItems += (int)($cItem['quantity'] ?? 1);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm "' . $productName . '" vào giỏ hàng!',
            'cart_key' => $cartKey,
            'total_items' => $totalItems
        ]);
        exit;
    }

    /**
     * API CONTRACT: Lấy số lượng giỏ hàng hiện tại (JSON)
     */
    public function ajaxCount() {
        header('Content-Type: application/json; charset=utf-8');
        $totalItems = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cItem) {
                $totalItems += (int)($cItem['quantity'] ?? 1);
            }
        }
        echo json_encode(['success' => true, 'total_items' => $totalItems]);
        exit;
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

        // YÊU CẦU: Nếu chưa đăng nhập thì chuyển hướng tới trang đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['auth_message'] = 'Vui lòng đăng nhập để tiến hành đặt hàng và thanh toán!';
            $_SESSION['redirect_after_login'] = 'index.php?controller=Cart&action=checkout' . ($isBuyNow ? '&type=buynow' : '');
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if (empty($cart)) {
            header("Location: index.php?controller=Cart&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $selectedShipping = $_GET['shipping'] ?? 'nhanh';
            if (!isset($this->shippingRates[$selectedShipping])) {
                $selectedShipping = 'nhanh';
            }
            $shippingFee = $this->shippingRates[$selectedShipping]['fee'];

            $this->render('checkout', [
                'title' => 'Thanh toán đơn hàng',
                'cart' => $cart,
                'total' => $subtotal,
                'shippingRates' => $this->shippingRates,
                'selectedShipping' => $selectedShipping,
                'shippingFee' => $shippingFee,
                'finalTotal' => $subtotal + $shippingFee,
                'isBuyNow' => $isBuyNow
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $shippingKey = $_POST['shipping'] ?? 'nhanh';
            $shippingOption = $this->shippingRates[$shippingKey] ?? $this->shippingRates['nhanh'];
            $shippingFee = (int)$shippingOption['fee'];

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $finalTotalAmount = $subtotal + $shippingFee;

            $rawCity = trim($_POST['city'] ?? '');
            $rawAddress = trim($_POST['address'] ?? '');
            $fullDeliveryAddress = $rawAddress . ($rawCity ? ', ' . $rawCity : '') 
                . ' [Gói vận chuyển: ' . $shippingOption['name'] . ' - Cước: ' . number_format($shippingFee, 0, ',', '.') . 'đ]';

            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            $paymentMethodLabels = [
                'cod' => 'Thanh toán khi nhận hàng (COD)',
                'bank_transfer' => 'Chuyển khoản ngân hàng',
                'momo' => 'Ví MoMo',
                'zalopay' => 'Ví ZaloPay',
                'credit_card' => 'Thẻ tín dụng/ghi nợ'
            ];

            $customerInfo = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'name' => trim($_POST['fullname'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => $fullDeliveryAddress,
                'shipping_name' => $shippingOption['name'],
                'shipping_fee' => $shippingFee,
                'payment_method' => $paymentMethodLabels[$paymentMethod] ?? 'COD'
            ];

            // Validation an toàn (Backend Engineer Standard: Không dùng lệnh die() thô)
            if (empty($customerInfo['name']) || empty($customerInfo['phone'])) {
                $this->render('checkout', [
                    'title' => 'Thanh toán đơn hàng',
                    'cart' => $cart,
                    'total' => $subtotal,
                    'shippingRates' => $this->shippingRates,
                    'selectedShipping' => $shippingKey,
                    'shippingFee' => $shippingFee,
                    'finalTotal' => $finalTotalAmount,
                    'isBuyNow' => $isBuyNow,
                    'error' => 'Vui lòng điền đầy đủ Họ và tên cùng Số điện thoại nhận hàng!',
                    'customerInfo' => $customerInfo
                ]);
                return;
            }

            $orderModel = new OrderModel();
            try {
                // Lưu Order và Order_Items vào Database (Transaction & Check Stock an toàn)
                $orderId = $orderModel->createOrder($customerInfo, $cart, $finalTotalAmount);
                
                // Xoá Session Cart tương ứng sau khi thanh toán thành công
                if ($isBuyNow) {
                    unset($_SESSION['buy_now_cart']);
                } else {
                    unset($_SESSION['cart']);
                }
                
                // Hiển thị màn hình đặt hàng thành công
                $this->render('checkout_success', [
                    'title' => 'Đặt hàng thành công',
                    'orderId' => $orderId,
                    'customerInfo' => $customerInfo,
                    'shippingOption' => $shippingOption,
                    'shippingFee' => $shippingFee,
                    'finalTotal' => $finalTotalAmount
                ]);
            } catch (Exception $e) {
                // Bảo mật: Hiển thị thông báo thân thiện, không làm lộ chi tiết cấu trúc CSDL
                $this->render('checkout', [
                    'title' => 'Thanh toán đơn hàng',
                    'cart' => $cart,
                    'total' => $subtotal,
                    'shippingRates' => $this->shippingRates,
                    'selectedShipping' => $shippingKey,
                    'shippingFee' => $shippingFee,
                    'finalTotal' => $finalTotalAmount,
                    'isBuyNow' => $isBuyNow,
                    'error' => $e->getMessage(),
                    'customerInfo' => $customerInfo
                ]);
                return;
            }
        }
    }
}
