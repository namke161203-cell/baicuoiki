<?php 
/**
 * @var array $product
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<div class="bg-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
            
            <!-- Hình ảnh -->
            <div class="mb-10 lg:mb-0">
                <div class="w-full bg-gray-100 aspect-[3/4] mb-4 border border-gray-100">
                    <img id="mainImage" src="<?= $product['images'][0] ?? '' ?>" class="w-full h-full object-cover">
                </div>
                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-4">
                    <?php if(!empty($product['images'])) foreach ($product['images'] as $index => $img): ?>
                    <button onclick="document.getElementById('mainImage').src = this.querySelector('img').src;" class="aspect-[3/4] bg-gray-100 border border-gray-200 hover:border-primary focus:outline-none focus:border-primary transition">
                        <img src="<?= $img ?>" class="w-full h-full object-cover p-1">
                    </button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Description dưới hình (theo design) -->
                <div class="mt-8">
                    <h3 class="font-bold text-gray-900 mb-2">Description</h3>
                    <div class="text-sm text-gray-600 space-y-4">
                        <p><?= nl2br($product['description']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Thông tin -->
            <div>
                <?php if (isset($_SESSION['cart_success'])): ?>
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-semibold"><?= htmlspecialchars($_SESSION['cart_success']) ?></span>
                        <a href="index.php?controller=Cart&action=index" class="ml-auto text-sm underline font-bold hover:text-green-900">Xem giỏ hàng</a>
                    </div>
                    <?php unset($_SESSION['cart_success']); ?>
                <?php endif; ?>

                <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-tight mb-2"><?= $product['name'] ?></h1>
                <p class="text-lg font-bold text-gray-900 mb-6">- <?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                
                <p class="text-sm text-gray-600 mb-8"><?= nl2br($product['description']) ?></p>

                <form action="index.php?controller=Cart&action=add" method="POST" id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <!-- Lựa chọn Cỡ -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Chọn cỡ</h3>
                            <button type="button" onclick="openSizeGuideModal()" class="text-xs text-primary underline font-medium uppercase hover:text-primary-dark cursor-pointer flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Bảng size
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <?php 
                            $sizes = array_unique(array_column($product['variants'], 'size'));
                            foreach ($sizes as $index => $size): 
                            ?>
                            <div>
                                <input type="radio" id="size-<?= $index ?>" name="size" value="<?= $size ?>" class="peer hidden" required>
                                <label for="size-<?= $index ?>" class="block cursor-pointer px-6 py-2 border border-gray-300 text-sm font-bold text-gray-700 hover:border-gray-900 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white transition uppercase text-center min-w-[3rem]">
                                    <?= $size ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Lựa chọn Màu sắc ngầm định (Vì Design không vẽ chọn màu rõ ràng, giả lập input hidden cho đủ dữ liệu giỏ hàng) -->
                    <?php 
                    $colors = array_unique(array_column($product['variants'], 'color'));
                    $firstColor = reset($colors);
                    ?>
                    <input type="hidden" name="color" value="<?= $firstColor ?>">
                    <input type="hidden" name="quantity" value="1">

                    <button type="submit" class="w-full bg-primary text-white font-bold py-4 mb-3 uppercase tracking-wider hover:bg-primary-dark transition text-sm">
                        Thêm vào giỏ
                    </button>
                    <button type="submit" name="buy_now" value="1" class="w-full bg-[#1c5666] text-white font-bold py-4 mb-3 uppercase tracking-wider hover:bg-gray-900 transition text-sm">
                        Mua ngay
                    </button>
                    <button type="button" onclick="toggleWishlist(<?= $product['id'] ?>, '<?= addslashes(htmlspecialchars($product['name'])) ?>', '<?= addslashes(htmlspecialchars($product['images'][0] ?? '')) ?>', <?= $product['price'] ?>)" class="w-full border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3 uppercase tracking-wider transition text-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        Thêm vào danh sách yêu thích
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 📐 SIZE GUIDE MODAL -->
<div id="sizeGuideModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto animate-fade-down">
        <button onclick="closeSizeGuideModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="flex items-center space-x-2 text-primary font-bold text-lg uppercase tracking-wide mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3>Bảng Hướng Dẫn Chọn Size Chuẩn (hquie)</h3>
        </div>

        <p class="text-xs text-gray-500 mb-4">Bảng thông số kích thước cơ thể tương ứng cho các dòng áo khoác, áo len và thời trang mùa đông:</p>

        <div class="overflow-x-auto border border-gray-200 rounded-lg mb-6">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-800 font-bold uppercase">
                        <th class="p-3 border-b border-gray-200">Size</th>
                        <th class="p-3 border-b border-gray-200">Chiều cao (cm)</th>
                        <th class="p-3 border-b border-gray-200">Cân nặng (kg)</th>
                        <th class="p-3 border-b border-gray-200">Vòng ngực (cm)</th>
                        <th class="p-3 border-b border-gray-200">Vòng eo (cm)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700 font-medium">
                    <tr class="hover:bg-teal-50/50">
                        <td class="p-3 font-bold text-primary">S</td>
                        <td class="p-3">155 - 165</td>
                        <td class="p-3">48 - 56</td>
                        <td class="p-3">84 - 88</td>
                        <td class="p-3">68 - 72</td>
                    </tr>
                    <tr class="hover:bg-teal-50/50">
                        <td class="p-3 font-bold text-primary">M</td>
                        <td class="p-3">165 - 172</td>
                        <td class="p-3">57 - 65</td>
                        <td class="p-3">88 - 92</td>
                        <td class="p-3">72 - 76</td>
                    </tr>
                    <tr class="hover:bg-teal-50/50">
                        <td class="p-3 font-bold text-primary">L</td>
                        <td class="p-3">170 - 178</td>
                        <td class="p-3">66 - 74</td>
                        <td class="p-3">92 - 96</td>
                        <td class="p-3">76 - 80</td>
                    </tr>
                    <tr class="hover:bg-teal-50/50">
                        <td class="p-3 font-bold text-primary">XL</td>
                        <td class="p-3">175 - 183</td>
                        <td class="p-3">75 - 83</td>
                        <td class="p-3">96 - 100</td>
                        <td class="p-3">80 - 84</td>
                    </tr>
                    <tr class="hover:bg-teal-50/50">
                        <td class="p-3 font-bold text-primary">XXL</td>
                        <td class="p-3">180 - 190</td>
                        <td class="p-3">84 - 95</td>
                        <td class="p-3">100 - 106</td>
                        <td class="p-3">84 - 90</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg text-xs text-gray-600 space-y-2 mb-6">
            <h4 class="font-bold text-gray-800 uppercase">💡 Mẹo chọn size chuẩn:</h4>
            <p>&bull; Nếu bạn phân vân giữa 2 kích cỡ, hãy chọn <b>size lớn hơn</b> để mặc thoải mái và kết hợp nhiều lớp áo bên trong vào mùa đông.</p>
            <p>&bull; Nếu bạn thích phong cách mặc vừa vặn ôm dáng, hãy chọn đúng size theo bảng thông số trên.</p>
            <p>&bull; hquie hỗ trợ <b>đổi size miễn phí trong 7 ngày</b> nếu sản phẩm chưa vừa ý!</p>
        </div>

        <button onclick="closeSizeGuideModal()" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded text-xs uppercase tracking-wider transition">
            Đã hiểu, quay lại chọn size
        </button>
    </div>
</div>

<script>
    function openSizeGuideModal() {
        const m = document.getElementById('sizeGuideModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeSizeGuideModal() {
        const m = document.getElementById('sizeGuideModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = 'ĐANG THÊM...';
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
