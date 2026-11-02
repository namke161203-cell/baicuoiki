<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class AdminProductController extends BaseController {
    
    private $productModel;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect("index.php?controller=Auth&action=login");
        }
        $this->productModel = new ProductModel();
    }

    // GET: Danh sách sản phẩm (có hỗ trợ tìm kiếm và KPI)
    public function index() {
        $keyword = trim($_GET['keyword'] ?? '');
        if (!empty($keyword)) {
            $products = $this->productModel->searchProducts($keyword);
        } else {
            $products = $this->productModel->getAllProducts();
        }

        $categories = $this->productModel->getAllCategories();
        $productKPIs = $this->productModel->getProductKPIs();

        $this->render('admin/products', [
            'title' => 'Quản lý Sản Phẩm & Kho Hàng',
            'products' => $products,
            'categories' => $categories,
            'productKPIs' => $productKPIs,
            'keyword' => $keyword
        ]);
    }

    // GET: Hiển thị form thêm sản phẩm
    public function create() {
        $categories = $this->productModel->getAllCategories();
        $this->render('admin/product_create', [
            'title' => 'Thêm Sản Phẩm Mới',
            'categories' => $categories
        ]);
    }

    // POST: Xử lý submit form thêm sản phẩm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $variants = [];
            if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
                $countVariants = count($_POST['sizes']);
                for ($i = 0; $i < $countVariants; $i++) {
                    if (!empty($_POST['sizes'][$i]) && !empty($_POST['colors'][$i])) {
                        $variants[] = [
                            'size' => $_POST['sizes'][$i],
                            'color' => $_POST['colors'][$i],
                            'stock' => $_POST['stocks'][$i] ?? 0
                        ];
                    }
                }
            }

            $images = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Bảo mật: Danh sách định dạng ảnh hợp lệ
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $fileCount = count($_FILES['images']['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    $originalName = $_FILES['images']['name'][$i];
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    // Chỉ chấp nhận các file ảnh hợp lệ, chặn file thực thi .php/.phtml/shell
                    if (in_array($fileExt, $allowedExtensions) && is_uploaded_file($tmpName)) {
                        $safeFileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $fileExt;
                        $targetFilePath = $uploadDir . $safeFileName;

                        if (move_uploaded_file($tmpName, $targetFilePath)) {
                            $images[] = 'public/uploads/' . $safeFileName;
                        }
                    }
                }
            }

            try {
                $productId = $this->productModel->createProduct($data, $variants, $images);
                $redirectUrl = !empty($_POST['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=products' : 'index.php?controller=AdminProduct&action=index';
                header("Location: " . $redirectUrl);
                exit;
            } catch (Exception $e) {
                die("Đã xảy ra lỗi hệ thống: " . $e->getMessage());
            }
        }
    }

    // POST: Cập nhật nhanh sản phẩm (Quick Edit)
    public function quickUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                    'price' => $_POST['price'] ?? 0,
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                    'description' => $_POST['description'] ?? ''
                ];
                $this->productModel->updateProductQuick($id, $data);
            }

            $redirectUrl = !empty($_POST['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=products' : 'index.php?controller=AdminProduct&action=index';
            header("Location: " . $redirectUrl);
            exit;
        }
    }

    // POST/GET: Bật/Tắt Nổi Bật (Toggle Featured)
    public function toggleFeatured() {
        $id = $_REQUEST['id'] ?? null;
        if ($id) {
            $newStatus = $this->productModel->toggleFeatured($id);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'is_featured' => $newStatus]);
                exit;
            }
        }
        $redirectUrl = !empty($_GET['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=products' : 'index.php?controller=AdminProduct&action=index';
        header("Location: " . $redirectUrl);
        exit;
    }

    // POST: Cập nhật tồn kho cho biến thể
    public function updateVariantStock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $variantId = $_POST['variant_id'] ?? null;
            $stock = $_POST['stock'] ?? 0;
            if ($variantId) {
                $this->productModel->updateVariantStock($variantId, $stock);
            }
            $redirectUrl = !empty($_POST['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=products' : 'index.php?controller=AdminProduct&action=index';
            header("Location: " . $redirectUrl);
            exit;
        }
    }

    // GET: Xuất file CSV danh sách sản phẩm
    public function exportCSV() {
        $products = $this->productModel->getAllProducts();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=hquie_products_' . date('Ymd_His') . '.csv');
        
        // Output BOM for Excel UTF-8 display
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Tên Sản Phẩm', 'Danh Mục', 'Giá Bán (VND)', 'Tổng Tồn Kho', 'Biến Thể (Màu - Size)', 'Đã Bán', 'Nổi Bật', 'Ngày Tạo']);
        
        foreach ($products as $p) {
            fputcsv($output, [
                $p['id'],
                $p['name'],
                $p['category_name'] ?? 'Không rõ',
                number_format($p['price'], 0, '', ''),
                $p['total_stock'] ?? 0,
                $p['variants_info'] ?? 'Chưa có',
                $p['total_sold'] ?? 0,
                $p['is_featured'] ? 'Có' : 'Không',
                $p['created_at']
            ]);
        }
        fclose($output);
        exit;
    }

    // GET: Xóa sản phẩm
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->productModel->deleteProduct($id);
        }
        $redirectUrl = !empty($_GET['redirect_tab']) ? 'index.php?controller=Admin&action=index&tab=products' : 'index.php?controller=AdminProduct&action=index';
        header("Location: " . $redirectUrl);
        exit;
    }
}
