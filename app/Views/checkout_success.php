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

    <p class="text-lg text-gray-600 mb-8 text-center max-w-md">
        Đơn hàng của bạn đã được tiếp nhận. Mã đơn hàng là <span class="font-bold text-indigo-600">#<?= str_pad($orderId ?? '0', 6, '0', STR_PAD_LEFT) ?></span>.
        Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận.
    </p>

    <div class="flex space-x-4">
        <a href="index.php" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-1">
            Tiếp tục mua sắm
        </a>
        <a href="#" class="bg-white text-indigo-600 border border-indigo-200 font-bold py-3 px-8 rounded-xl hover:bg-indigo-50 transition">
            Theo dõi đơn
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>