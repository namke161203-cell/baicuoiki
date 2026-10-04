<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="bg-white min-h-[70vh] flex flex-col justify-center items-center py-20 px-4 sm:px-6 lg:px-8">

    <!-- Icon Success -->
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-8 relative">
        <!-- Vòng tỏa ra animate -->
        <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-20"></div>
        <svg class="w-12 h-12 text-green-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-4 text-center">Cảm ơn bạn đã mua sắm!</h1>

    <p class="text-lg text-gray-600 mb-6 text-center max-w-md">
        Đơn hàng của bạn đã được tiếp nhận. Mã đơn hàng là <span class="font-bold text-primary">#<?= str_pad($orderId ?? '0', 6, '0', STR_PAD_LEFT) ?></span>.
        Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận.
    </p>

    <?php if (isset($customerInfo)): ?>
    <div class="bg-gray-50 border border-gray-200 p-6 max-w-md w-full mb-8 text-center text-sm">
        <h3 class="font-bold text-gray-900 mb-2 uppercase tracking-wider">Địa chỉ giao hàng</h3>
        <p class="text-gray-700 font-medium"><?= htmlspecialchars($customerInfo['name']) ?> - <?= htmlspecialchars($customerInfo['phone']) ?></p>
        <p class="text-gray-600 mt-1"><?= htmlspecialchars($customerInfo['address']) ?></p>
    </div>
    <?php endif; ?>

    <div class="flex space-x-4">
        <a href="index.php" class="bg-primary text-white font-bold py-3 px-8 uppercase tracking-wider hover:bg-primary-dark transition shadow-md">
            Tiếp tục mua sắm
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>