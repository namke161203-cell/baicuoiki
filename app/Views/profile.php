<?php 
/**
 * @var array $user
 * @var array $orders
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<div class="bg-[#eaf4f6] min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow-xl p-8 lg:p-10 flex flex-col md:flex-row md:space-x-10">
            
            <!-- Sidebar: User Info -->
            <div class="md:w-1/3 mb-10 md:mb-0 border-r border-gray-200 pr-10">
                <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-6">Tài khoản của tôi</h2>
                
                <form action="index.php?controller=Profile&action=update" method="POST" class="space-y-4 text-sm text-gray-700">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Họ tên</label>
                        <input type="text" value="<?= htmlspecialchars($user['name']) ?>" class="w-full bg-gray-50 border border-gray-200 p-3 text-sm focus:outline-none text-gray-500 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full bg-gray-50 border border-gray-200 p-3 text-sm focus:outline-none text-gray-500 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Điện thoại</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Chưa cập nhật" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition text-gray-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Địa chỉ mặc định</label>
                        <textarea name="address" rows="3" placeholder="Chưa cập nhật" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition text-gray-900 font-medium"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#1c5666] text-white font-bold py-3 uppercase tracking-wider hover:bg-gray-900 transition text-sm shadow-md mt-4">
                        Lưu thay đổi
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100">
                    <a href="index.php?controller=Auth&action=logout" class="block w-full text-center border border-primary text-primary font-bold py-3 uppercase tracking-wider hover:bg-primary hover:text-white transition text-sm">
                        Đăng xuất
                    </a>
                </div>
            </div>

            <!-- Main Content: Order History -->
            <div class="md:w-2/3">
                <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-6">Lịch sử đơn hàng</h2>

                <?php if (empty($orders)): ?>
                    <div class="text-center py-12 bg-gray-50 border border-gray-100">
                        <p class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào.</p>
                        <a href="index.php" class="inline-block bg-primary text-white font-bold px-6 py-3 uppercase tracking-wider text-sm hover:bg-primary-dark transition">Mua sắm ngay</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($orders as $order): ?>
                            <div class="border border-gray-200 bg-white">
                                <!-- Order Header -->
                                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center border-b border-gray-200">
                                    <div class="mb-2 sm:mb-0">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block">Mã đơn hàng</span>
                                        <span class="font-bold text-primary">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                    </div>
                                    <div class="mb-2 sm:mb-0 text-center sm:text-left">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block">Ngày đặt</span>
                                        <span class="font-medium text-gray-900"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                                    </div>
                                    <div class="mb-2 sm:mb-0 text-center sm:text-left">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block">Tổng tiền</span>
                                        <span class="font-bold text-gray-900"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 bg-gray-200 text-gray-800 text-xs font-bold uppercase tracking-wider">
                                            <?= $order['status'] == 'pending' ? 'Chờ xử lý' : ($order['status'] == 'completed' ? 'Hoàn thành' : 'Đang giao') ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Order Items -->
                                <div class="p-6 space-y-4">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 flex items-center justify-center text-xs text-gray-400">
                                                    <!-- Mockup img icon if real image is not joined -->
                                                    Img
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-bold text-gray-900 uppercase truncate max-w-[200px] sm:max-w-md"><?= $item['product_name'] ?? 'Sản phẩm đã xóa' ?></p>
                                                    <p class="text-xs text-gray-500 mt-1">Size: <?= $item['size'] ?? 'Free' ?> | Màu: <?= $item['color'] ?? 'N/A' ?></p>
                                                </div>
                                            </div>
                                            <div class="text-right text-sm">
                                                <span class="text-gray-500">x<?= $item['quantity'] ?></span>
                                                <span class="ml-4 font-bold text-gray-900"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
