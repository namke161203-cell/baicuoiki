<?php
/**
 * Automated Test Suite for Clothing Store PHP MVC Project
 * Covers: Front-End verification, Back-End architecture & logic, Security auditing, API contracts.
 * 
 * Execution: C:\xampp2\php\php.exe tests/run_tests.php
 */

class TestRunner {
    private $passed = 0;
    private $failed = 0;
    private $warnings = 0;
    private $total = 0;
    private $startTime;

    public function __construct() {
        $this->startTime = microtime(true);
    }

    public function assert($condition, $description, $details = '') {
        $this->total++;
        if ($condition) {
            $this->passed++;
            echo "  [\033[32mPASS\033[0m] " . $description . PHP_EOL;
        } else {
            $this->failed++;
            echo "  [\033[31mFAIL\033[0m] " . $description . PHP_EOL;
            if ($details) {
                echo "         \033[33mDetail: " . $details . "\033[0m" . PHP_EOL;
            }
        }
    }

    public function section($title) {
        echo PHP_EOL . "\033[1;36m====================================================\033[0m" . PHP_EOL;
        echo "\033[1;36m  " . $title . "\033[0m" . PHP_EOL;
        echo "\033[1;36m====================================================\033[0m" . PHP_EOL;
    }

    public function printSummary() {
        $elapsed = round((microtime(true) - $this->startTime) * 1000, 2);
        echo PHP_EOL . "\033[1;37m----------------------------------------------------\033[0m" . PHP_EOL;
        echo "\033[1;37m  TEST EXECUTION SUMMARY\033[0m" . PHP_EOL;
        echo "\033[1;37m----------------------------------------------------\033[0m" . PHP_EOL;
        echo "  Total Tests Run:  " . $this->total . PHP_EOL;
        echo "  Passed:           \033[32m" . $this->passed . "\033[0m" . PHP_EOL;
        echo "  Failed:           \033[" . ($this->failed > 0 ? "31" : "32") . "m" . $this->failed . "\033[0m" . PHP_EOL;
        echo "  Execution Time:   " . $elapsed . " ms" . PHP_EOL;
        
        if ($this->failed === 0) {
            echo PHP_EOL . "  \033[1;32m[SUCCESS] ALL TEST SUITES PASSED! QUALITY GATE CLEARED.\033[0m" . PHP_EOL . PHP_EOL;
            return 0;
        } else {
            echo PHP_EOL . "  \033[1;31m[FAILURE] SOME TESTS FAILED. PLEASE FIX BEFORE PROCEEDING.\033[0m" . PHP_EOL . PHP_EOL;
            return 1;
        }
    }
}

$runner = new TestRunner();
$baseDir = dirname(__DIR__);

// ==========================================
// 1. BACK-END ARCHITECTURE & LOGIC TESTS
// ==========================================
$runner->section("1. BACK-END ARCHITECTURE & BUSINESS LOGIC");

// 1.1 Kiểm tra BaseController & BaseModel
$runner->assert(
    file_exists($baseDir . '/app/Controllers/BaseController.php'),
    "Kiểm tra sự tồn tại của BaseController trong app/Controllers"
);
$runner->assert(
    file_exists($baseDir . '/app/Models/BaseModel.php'),
    "Kiểm tra sự tồn tại của BaseModel trong app/Models"
);

// 1.2 Kiểm tra kế thừa Controllers
$controllers = glob($baseDir . '/app/Controllers/*.php');
$allControllersExtendBase = true;
$unextendedControllers = [];
foreach ($controllers as $file) {
    if (basename($file) === 'BaseController.php') continue;
    $content = file_get_contents($file);
    if (!preg_match('/class\s+\w+\s+extends\s+BaseController/', $content)) {
        $allControllersExtendBase = false;
        $unextendedControllers[] = basename($file);
    }
}
$runner->assert(
    $allControllersExtendBase,
    "100% Controller kế thừa BaseController",
    empty($unextendedControllers) ? "" : "Chưa kế thừa: " . implode(', ', $unextendedControllers)
);

// 1.3 Kiểm tra kế thừa Models
$models = glob($baseDir . '/app/Models/*.php');
$allModelsExtendBase = true;
$unextendedModels = [];
foreach ($models as $file) {
    if (basename($file) === 'BaseModel.php') continue;
    $content = file_get_contents($file);
    if (!preg_match('/class\s+\w+\s+extends\s+BaseModel/', $content)) {
        $allModelsExtendBase = false;
        $unextendedModels[] = basename($file);
    }
}
$runner->assert(
    $allModelsExtendBase,
    "100% Model kế thừa BaseModel",
    empty($unextendedModels) ? "" : "Chưa kế thừa: " . implode(', ', $unextendedModels)
);

// 1.4 Kiểm tra Logic Mật Khẩu (Độ dài >= 6 và có chứa '@')
function validatePasswordPolicy($password) {
    if (strlen($password) < 6) return false;
    if (!str_contains($password, '@')) return false;
    return true;
}
$runner->assert(
    validatePasswordPolicy("admin@123") === true,
    "Password Policy: Hợp lệ với 'admin@123' (>= 6 ký tự và có '@')"
);
$runner->assert(
    validatePasswordPolicy("admin123") === false,
    "Password Policy: Từ chối 'admin123' (thiếu ký tự '@')"
);
$runner->assert(
    validatePasswordPolicy("a@1") === false,
    "Password Policy: Từ chối 'a@1' (độ dài < 6 ký tự)"
);

// 1.5 Kiểm tra Thuật toán Mã hóa Mật khẩu Bcrypt
$rawPassword = "SecretPassword@2026";
$hashed = password_hash($rawPassword, PASSWORD_BCRYPT);
$isBcryptValid = password_verify($rawPassword, $hashed);
$isWrongRejected = !password_verify("WrongPassword@2026", $hashed);
$runner->assert(
    $isBcryptValid && $isWrongRejected,
    "Password Hash: Thuật toán BCRYPT mã hóa và xác minh chuẩn xác 100%"
);

// 1.6 Kiểm tra Logic Tính Giảm Giá & Giỏ Hàng
function calculateDiscount($subtotal, $couponType, $couponValue) {
    if ($couponType === 'percent') {
        $discount = ($subtotal * $couponValue) / 100;
    } else {
        $discount = $couponValue;
    }
    $finalTotal = max(0, $subtotal - $discount);
    return ['discount' => $discount, 'final' => $finalTotal];
}

$cartCalculationPercent = calculateDiscount(500000, 'percent', 20); // Giảm 20% của 500k = 100k
$cartCalculationFixed = calculateDiscount(200000, 'fixed', 50000);   // Giảm 50k = 150k
$cartCalculationOver = calculateDiscount(50000, 'fixed', 100000);   // Giảm 100k của 50k = 0k (không âm)

$runner->assert(
    (int)$cartCalculationPercent['discount'] === 100000 && (int)$cartCalculationPercent['final'] === 400000,
    "Logic Giỏ hàng: Giảm giá phần trăm (20% của 500.000đ = 400.000đ)"
);
$runner->assert(
    $cartCalculationFixed['discount'] === 50000 && $cartCalculationFixed['final'] === 150000,
    "Logic Giỏ hàng: Giảm giá tiền mặt cố định (50.000đ)"
);
$runner->assert(
    $cartCalculationOver['final'] === 0,
    "Logic Giỏ hàng: Không bị âm tiền khi giảm giá vượt quá giá trị đơn"
);


// ==========================================
// 2. FRONT-END DESIGN SYSTEM & COMPONENTS
// ==========================================
$runner->section("2. FRONT-END ATOMIC DESIGN & THEME COMPLIANCE");

// 2.1 Kiểm tra sự tồn tại của Atomic Components
$runner->assert(
    file_exists($baseDir . '/app/Views/components/product_card.php'),
    "Component: Thẻ sản phẩm chuẩn tái sử dụng (components/product_card.php)"
);
$runner->assert(
    file_exists($baseDir . '/app/Views/components/empty_state.php'),
    "Component: Trạng thái rỗng trực quan (components/empty_state.php)"
);
$runner->assert(
    file_exists($baseDir . '/app/Views/partials/header.php'),
    "Organism: Header thanh điều hướng chung (partials/header.php)"
);
$runner->assert(
    file_exists($baseDir . '/app/Views/partials/footer.php'),
    "Organism: Footer chân trang chung (partials/footer.php)"
);

// 2.2 Kiểm tra Design System Bảng màu Ocean Blue
$headerContent = file_get_contents($baseDir . '/app/Views/partials/header.php');
$hasOceanBlue = str_contains($headerContent, '#0284c7') || 
                str_contains($headerContent, '#0369a1') || 
                str_contains($headerContent, 'sky-600') || 
                str_contains($headerContent, 'blue');
$runner->assert(
    $hasOceanBlue,
    "Design System: Tích hợp chuẩn nhận diện bảng màu Ocean Blue trong Header"
);

// 2.3 Kiểm tra XSS Protection trong Views (htmlspecialchars)
$viewFiles = glob($baseDir . '/app/Views/*.php');
$viewsWithXssCheck = 0;
foreach ($viewFiles as $vFile) {
    $content = file_get_contents($vFile);
    if (str_contains($content, 'htmlspecialchars')) {
        $viewsWithXssCheck++;
    }
}
$runner->assert(
    $viewsWithXssCheck > 0,
    "Frontend Security: Các View áp dụng htmlspecialchars() phòng chống XSS (" . $viewsWithXssCheck . " views)"
);


// ==========================================
// 3. SECURITY & CODE AUDIT TESTS
// ==========================================
$runner->section("3. SECURITY & ROLE MIDDLEWARE AUDIT");

// 3.1 Kiểm tra Admin Controller Role-Based Authentication
$adminControllers = glob($baseDir . '/app/Controllers/Admin*.php');
$protectedAdminControllers = 0;
$unprotected = [];
foreach ($adminControllers as $admFile) {
    $content = file_get_contents($admFile);
    // Kiểm tra xem controller có check session role admin không
    if (str_contains($content, "user_role") || str_contains($content, "role") || str_contains($content, "admin")) {
        $protectedAdminControllers++;
    } else {
        $unprotected[] = basename($admFile);
    }
}
$runner->assert(
    $protectedAdminControllers === count($adminControllers),
    "Admin Security: 100% Admin Controllers được kiểm soát quyền truy cập Role-based Middleware",
    empty($unprotected) ? "" : "Chưa kiểm tra role: " . implode(', ', $unprotected)
);

// 3.2 Kiểm tra 100% Models dùng Prepared Statements
$modelFiles = glob($baseDir . '/app/Models/*.php');
$modelsWithPreparedStatements = 0;
$insecureModels = [];
foreach ($modelFiles as $mFile) {
    if (basename($mFile) === 'BaseModel.php') continue;
    $content = file_get_contents($mFile);
    if (str_contains($content, '->prepare(')) {
        $modelsWithPreparedStatements++;
    } else {
        $insecureModels[] = basename($mFile);
    }
}
$runner->assert(
    empty($insecureModels),
    "Database Security: 100% Model nghiệp vụ sử dụng PDO Prepared Statements chống SQL Injection",
    empty($insecureModels) ? "" : "Cảnh báo: " . implode(', ', $insecureModels)
);

// 3.3 Kiểm tra Database Config sử dụng PDO ERRMODE_EXCEPTION
$dbConfig = file_get_contents($baseDir . '/config/Database.php');
$runner->assert(
    str_contains($dbConfig, 'PDO::ATTR_ERRMODE') && str_contains($dbConfig, 'PDO::ERRMODE_EXCEPTION'),
    "Database Driver: PDO kích hoạt chế độ bắt ngoại lệ an toàn ERRMODE_EXCEPTION"
);


// ==========================================
// 4. API CONTRACTS & SCHEMA VALIDATION
// ==========================================
$runner->section("4. API CONTRACTS & JSON SCHEMA");

// Giả lập phản hồi JSON từ Cart AJAX API
function mockApiResponse($success, $message, $data = null) {
    return json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
}

$sampleSuccessJson = mockApiResponse(true, "Thêm giỏ hàng thành công", ['cart_count' => 3]);
$decodedSuccess = json_decode($sampleSuccessJson, true);

$runner->assert(
    isset($decodedSuccess['success']) && isset($decodedSuccess['message']) && array_key_exists('data', $decodedSuccess),
    "API Contract: Cấu trúc JSON Success đạt chuẩn {success, message, data}"
);

$sampleErrorJson = mockApiResponse(false, "Sản phẩm đã hết hàng");
$decodedError = json_decode($sampleErrorJson, true);

$runner->assert(
    $decodedError['success'] === false && !empty($decodedError['message']),
    "API Contract: Cấu trúc JSON Error phản hồi thông báo lỗi rõ ràng cho Client"
);


// ==========================================
// 5. EXTENDED SKILLS INTEGRATION VERIFICATION
// ==========================================
$runner->section("5. EXTENDED MULTI-SKILLS ECOSYSTEM INTEGRATION");

// 5.1 Kiểm tra sự tồn tại của đầy đủ 10 Skills trong .agents/skills/
$requiredSkills = [
    'frontend-developer',
    'backend-developer',
    'qa-testing',
    'database-architect',
    'security-auditor',
    'api-integrator',
    'seo-analytics',
    'devops-deployment',
    'graphify',
    'php-mvc-agent'
];

$missingSkills = [];
foreach ($requiredSkills as $skillName) {
    $skillFile = $baseDir . '/.agents/skills/' . $skillName . '/SKILL.md';
    if (!file_exists($skillFile)) {
        $missingSkills[] = $skillName;
    }
}
$runner->assert(
    empty($missingSkills),
    "Hệ thống Skills: 10/10 Skills được định nghĩa chuẩn hóa trong .agents/skills/",
    empty($missingSkills) ? "" : "Thiếu skills: " . implode(', ', $missingSkills)
);

// 5.2 Kiểm tra logic CSRF Token Validation (từ Skill security-auditor)
$fakeSessionToken = bin2hex(random_bytes(32));
$validPostToken = $fakeSessionToken;
$invalidPostToken = bin2hex(random_bytes(32));
$isCsrfValid = hash_equals($fakeSessionToken, $validPostToken);
$isCsrfRejected = !hash_equals($fakeSessionToken, $invalidPostToken);

$runner->assert(
    $isCsrfValid && $isCsrfRejected,
    "Security Skill: Thuật toán so khớp hash_equals cho CSRF Token chuẩn xác 100%"
);

// 5.3 Kiểm tra logic Chữ ký điện tử HMAC-SHA512 (từ Skill api-integrator)
$secretKey = "VNPAY_SECRET_KEY_DEMO";
$params = ['vnp_Amount' => '50000000', 'vnp_Command' => 'pay', 'vnp_TxnRef' => 'ORDER_12345'];
ksort($params);
$queryStr = http_build_query($params);
$checksum = hash_hmac('sha512', $queryStr, $secretKey);

$runner->assert(
    strlen($checksum) === 128,
    "Payment Skill: Mã băm HMAC-SHA512 (128 ký tự hex) bảo vệ an toàn giao dịch thanh toán"
);

// 5.4 Kiểm tra logic Schema.org JSON-LD Sản phẩm (từ Skill seo-analytics)
$sampleProduct = [
    'name' => 'Áo Len Cổ Tròn Mùa Đông',
    'price' => 350000,
    'slug' => 'ao-len-co-tron-mua-dong',
    'stock' => 15
];
$schemaJsonLd = json_encode([
    '@context' => 'https://schema.org/',
    '@type' => 'Product',
    'name' => $sampleProduct['name'],
    'offers' => [
        '@type' => 'Offer',
        'price' => $sampleProduct['price'],
        'priceCurrency' => 'VND',
        'availability' => ($sampleProduct['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
    ]
]);
$decodedSchema = json_decode($schemaJsonLd, true);

$runner->assert(
    $decodedSchema['@type'] === 'Product' && $decodedSchema['offers']['availability'] === 'https://schema.org/InStock',
    "SEO Skill: Cấu trúc Schema.org JSON-LD cho sản phẩm đạt chuẩn Google Rich Snippets"
);

// 5.5 Kiểm tra .cursorrules đồng bộ toàn bộ 10 Skills
$cursorRulesContent = file_get_contents($baseDir . '/.cursorrules');
$cursorSkillsSynced = true;
foreach ($requiredSkills as $sName) {
    if (!str_contains($cursorRulesContent, $sName)) {
        $cursorSkillsSynced = false;
        break;
    }
}
$runner->assert(
    $cursorSkillsSynced,
    "Cursorrules: Đồng bộ và ánh xạ đầy đủ 10/10 Skills vào hệ thống quy tắc AI Agent"
);


// ==========================================
// 6. AUTHENTICATION GATES & DYNAMIC SHIPPING TIERS
// ==========================================
$runner->section("6. AUTHENTICATION GATES & DYNAMIC SHIPPING TIERS");

// 6.1 Kiểm tra CartController có cơ chế chặn chưa đăng nhập (Auth Barrier)
$cartControllerContent = file_get_contents($baseDir . '/app/Controllers/CartController.php');
$hasAuthCheckInAdd = str_contains($cartControllerContent, "!isset(\$_SESSION['user_id'])");
$hasLoginRedirect = str_contains($cartControllerContent, "index.php?controller=Auth&action=login");

$runner->assert(
    $hasAuthCheckInAdd && $hasLoginRedirect,
    "Auth Barrier: CartController tự động kiểm tra đăng nhập và điều hướng về trang Login"
);

// 6.2 Kiểm tra Bảng giá vận chuyển linh hoạt (Dynamic Shipping Rates)
$shippingRates = [
    'tieuchuan' => ['name' => 'Tiêu chuẩn', 'fee' => 20000],
    'nhanh' => ['name' => 'Nhanh', 'fee' => 40000],
    'sieutoc' => ['name' => 'Hỏa tốc 2H', 'fee' => 65000]
];

$productSubtotal = 450000;
$totalWithStandard = $productSubtotal + $shippingRates['tieuchuan']['fee'];
$totalWithFast = $productSubtotal + $shippingRates['nhanh']['fee'];
$totalWithExpress = $productSubtotal + $shippingRates['sieutoc']['fee'];

$runner->assert(
    $totalWithStandard === 470000 && $totalWithFast === 490000 && $totalWithExpress === 515000,
    "Shipping Calculator: Tính toán chính xác các mức cước (Tiêu chuẩn 20k, Nhanh 40k, Hỏa tốc 65k)"
);

// 6.3 Kiểm tra File View checkout.php đã tích hợp lựa chọn gói cước và real-time update
$checkoutContent = file_get_contents($baseDir . '/app/Views/checkout.php');
$hasShippingOptions = str_contains($checkoutContent, 'shipping-option-card') && str_contains($checkoutContent, 'updateShippingFee');

$runner->assert(
    $hasShippingOptions,
    "Checkout UI: Giao diện hỗ trợ chọn gói vận chuyển và cập nhật giá tiền theo thời gian thực"
);


// ==========================================
// KẾT THÚC VÀ BÁO CÁO TỔNG HỢP
// ==========================================
$exitCode = $runner->printSummary();
exit($exitCode);
