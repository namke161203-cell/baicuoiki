<?php 
/**
 * @var array $cart
 * @var int $total
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<div class="bg-[#eaf4f6] min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow-xl p-8 lg:p-10">
            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-8">Giỏ hàng của bạn [<?= array_sum(array_column($cart, 'quantity')) ?> mặt hàng]</h1>

            <?php if (empty($cart)): ?>
                <div class="text-center py-10 border-t border-gray-100">
                    <p class="text-gray-500 mb-4">Giỏ hàng trống.</p>
                    <a href="index.php" class="inline-block bg-primary text-white font-bold px-8 py-3 uppercase tracking-wider">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($cart as $key => $item): ?>
                <div class="flex border-b border-gray-100 pb-6">
                    <!-- Hình ảnh -->
                    <div class="w-24 h-32 bg-gray-100 flex-shrink-0 border border-gray-200">
                        <img src="<?= $item['image'] ?>" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Thông tin -->
                    <div class="ml-6 flex-1 flex justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase mb-2"><?= $item['name'] ?> - <?= number_format($item['price'], 0, ',', '.') ?>đ</h3>
                            
                            <!-- Chọn Size (Theo design hiển thị S M L) -->
                            <div class="flex space-x-2 mb-4">
                                <span class="border w-8 h-8 flex items-center justify-center text-xs <?= $item['size']=='S'?'border-primary text-primary font-bold':'border-gray-300 text-gray-500' ?>">S</span>
                                <span class="border w-8 h-8 flex items-center justify-center text-xs <?= $item['size']=='M'?'border-primary text-primary font-bold':'border-gray-300 text-gray-500' ?>">M</span>
                                <span class="border w-8 h-8 flex items-center justify-center text-xs <?= $item['size']=='L'?'border-primary text-primary font-bold':'border-gray-300 text-gray-500' ?>">L</span>
                            </div>
                            
                            <!-- Nút thao tác -->
                            <div class="flex items-center space-x-4">
                                <button class="bg-primary text-white text-xs font-bold px-4 py-2 uppercase hover:bg-primary-dark transition">Cập nhật</button>
                                <a href="index.php?controller=Cart&action=remove&key=<?= $key ?>" class="text-xs text-primary font-bold uppercase hover:underline">Xóa</a>
                            </div>
                        </div>

                        <!-- Số lượng & Tổng giá -->
                        <div class="text-right flex space-x-6 items-start">
                            <div class="flex items-center border border-gray-300 h-8">
                                <button class="w-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">-</button>
                                <input type="text" value="<?= $item['quantity'] ?>" class="w-8 text-center text-xs font-bold focus:outline-none" readonly>
                                <button class="w-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                            <p class="text-sm font-bold text-gray-900 w-24"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tóm tắt đơn hàng (Góc phải dưới) -->
            <div class="mt-8 ml-auto w-full lg:w-1/3">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-900 font-medium">Tổng tiền mặt hàng</span>
                    <span class="text-gray-900 font-bold"><?= number_format($total, 0, ',', '.') ?> đ</span>
                </div>
                <div class="flex justify-between text-sm mb-4 pb-4 border-b border-gray-200">
                    <span class="text-gray-900 font-medium">Phí vận chuyển dự kiến</span>
                    <span class="text-gray-900 font-bold">25.000 đ</span>
                </div>
                <div class="flex justify-between text-base mb-6">
                    <span class="font-bold text-gray-900">Thành tiền</span>
                    <span class="font-bold text-gray-900"><?= number_format($total + 25000, 0, ',', '.') ?> đ</span>
                </div>
                
                <!-- Bổ sung form Checkout nhanh ngay dưới giỏ hàng để dễ test -->
                <form action="index.php?controller=Cart&action=checkout" method="POST" class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="font-bold text-gray-900 mb-4 uppercase">Thông tin nhận hàng nhanh</h3>
                    <input type="text" name="fullname" placeholder="Họ và tên" required class="w-full border border-gray-300 p-2 text-sm mb-3 focus:border-primary focus:outline-none">
                    <input type="text" name="phone" placeholder="Số điện thoại" required class="w-full border border-gray-300 p-2 text-sm mb-3 focus:border-primary focus:outline-none">
                    <input type="text" name="address" placeholder="Địa chỉ giao hàng" required class="w-full border border-gray-300 p-2 text-sm mb-4 focus:border-primary focus:outline-none">
                    
                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 uppercase tracking-wider hover:bg-primary-dark transition">
                        Thanh toán ngay
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>