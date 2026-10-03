<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class AdminProductController extends BaseController {
    
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // GET: Hiển thị form thêm sản phẩm
    public function create() {
        // Trong thực tế, bạn sẽ lấy danh sách category truyền vào view ở đây
        // $this->render('admin/product_create', ['categories' => $categories]);
        echo "Đây là giao diện Form thêm sản phẩm (sẽ code phần View sau).";
    }

    // POST: Xử lý submit form
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Lấy dữ liệu sản phẩm cơ bản
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];

            if (empty($data['name']) || empty($data['price'])) {
                die("Tên và giá sản phẩm không được để trống!");
            }

            // 2. Xử lý Biến thể (Variants)
            // Giả định form gửi lên là các mảng: sizes[], colors[], stocks[]
            $variants = [];
            if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
                $countVariants = count($_POST['sizes']);
                for ($i = 0; $i < $countVariants; $i++) {
                    // Chỉ lấy những dòng mà size và color được nhập
                    if (!empty($_POST['sizes'][$i]) && !empty($_POST['colors'][$i])) {
                        $variants[] = [
                            'size' => $_POST['sizes'][$i],
                            'color' => $_POST['colors'][$i],
                            'stock' => $_POST['stocks'][$i] ?? 0
                        ];
                    }
                }
            }

            // 3. Xử lý upload Thư viện ảnh (Nhiều ảnh cùng lúc)
            $images = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                // Tạo thư mục nếu chưa có
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileCount = count($_FILES['images']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = time() . '_' . basename($_FILES['images']['name'][$i]);
                    $targetFilePath = $uploadDir . $fileName;

                    // Di chuyển file được upload vào thư mục đích
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFilePath)) {
                        $images[] = 'public/uploads/' . $fileName; // Lưu đường dẫn tương đối vào DB
                    }
                }
            }

            // 4. Lưu vào Database
            try {
                $productId = $this->productModel->createProduct($data, $variants, $images);
                echo "Tuyệt vời! Đã thêm sản phẩm thành công với ID: " . $productId;
                // Thực tế: header("Location: index.php?controller=AdminProduct&action=index");
            } catch (Exception $e) {
                echo "Đã xảy ra lỗi hệ thống: " . $e->getMessage();
            }
        } else {
            die("Phương thức không hợp lệ!");
        }
    }
}
