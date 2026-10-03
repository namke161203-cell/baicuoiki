<?php require_once 'partials/header.php'; ?>

<!-- Hero Banner (Vibe Hiện Đại) -->
<div class="relative bg-black h-[80vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <!-- Ảnh banner chất lượng cao -->
        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=2000&q=80" alt="Banner Thời Trang" class="w-full h-full object-cover opacity-60 scale-105 transform origin-center animate-pulse-slow">
    </div>
    <div class="relative z-10 text-center text-white px-4">
        <span class="block text-indigo-400 font-semibold tracking-widest uppercase mb-3 drop-shadow-md">New Collection 2026</span>
        <h1 class="text-5xl md:text-8xl font-black tracking-tighter mb-6 drop-shadow-2xl">MÙA HÈ SÔI ĐỘNG</h1>
        <p class="text-lg md:text-2xl mb-10 font-light max-w-2xl mx-auto drop-shadow-md text-gray-200">Khám phá bộ sưu tập mới nhất giúp bạn tỏa sáng mọi khoảnh khắc với mức giá cực hời.</p>
        <a href="#trending" class="inline-block bg-white text-black font-bold px-10 py-4 rounded-full hover:bg-indigo-600 hover:text-white transition duration-300 transform hover:scale-105 shadow-[0_0_20px_rgba(255,255,255,0.3)]">
            MUA SẮM NGAY MÙA LỄ HỘI
        </a>
    </div>
</div>

<!-- Trending Products Section -->
<div id="trending" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12">
        <div>
            <h2 class="text-4xl font-black text-gray-900 tracking-tight">Sản phẩm Thịnh hành</h2>
            <div class="h-1.5 w-24 bg-indigo-600 mt-4 rounded-full"></div>
        </div>
        <a href="#" class="mt-4 md:mt-0 text-indigo-600 font-semibold hover:text-indigo-800 transition flex items-center group">
            Xem tất cả bộ sưu tập 
            <svg class="w-5 h-5 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($featuredProducts as $item): ?>
        <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
            <!-- Product Image -->
            <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="object-cover object-center w-full h-full group-hover:scale-110 transition duration-700 ease-in-out">
                
                <!-- Badge -->
                <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                    Hot
                </div>

                <!-- Hover Overlay & Quick Actions -->
                <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6 px-4">
                    <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="w-full bg-white bg-opacity-95 text-black text-center py-3 rounded-xl font-bold hover:bg-indigo-600 hover:text-white backdrop-blur-md transition-all shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300">
                        XEM CHI TIẾT
                    </a>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">
                        <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="hover:text-indigo-600 transition">
                            <?= $item['name'] ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-3">Thời trang nam/nữ</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xl text-indigo-700 font-black"><?= number_format($item['price'], 0, ',', '.') ?> đ</p>
                    <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Features Section (Trust Indicators) -->
<div class="bg-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-600 bg-opacity-20 rounded-2xl flex items-center justify-center mb-6 text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Chất lượng Cao cấp</h3>
                <p class="text-gray-400 text-sm">Cam kết 100% sản phẩm qua kiểm duyệt chất lượng gắt gao nhất.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-600 bg-opacity-20 rounded-2xl flex items-center justify-center mb-6 text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Giao hàng Hỏa tốc</h3>
                <p class="text-gray-400 text-sm">Nhận hàng chỉ từ 24h đối với khu vực nội thành, 2-3 ngày toàn quốc.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-600 bg-opacity-20 rounded-2xl flex items-center justify-center mb-6 text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Đổi trả Dễ dàng</h3>
                <p class="text-gray-400 text-sm">Miễn phí đổi trả trong 7 ngày nếu không vừa ý hoặc có lỗi sản xuất.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-pulse-slow {
        animation: pulse-slow 10s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1.05); }
        50% { transform: scale(1.1); }
    }
</style>

<?php require_once 'partials/footer.php'; ?>
