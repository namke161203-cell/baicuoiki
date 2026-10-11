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
                            <a href="#" class="text-xs text-primary underline font-medium uppercase">Bảng size</a>
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
                    <button type="submit" name="buy_now" value="1" class="w-full bg-[#1c5666] text-white font-bold py-4 uppercase tracking-wider hover:bg-gray-900 transition text-sm">
                        Mua ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = 'ĐANG THÊM...';
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
