<?php 
/**
 * @var array $featuredProducts
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<style>
@keyframes fade-up {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fade-down {
  from { opacity: 0; transform: translateY(-12px); }
  to   { opacity: 1; transform: translateY(0); }
}
.animate-fade-up   { animation: fade-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
.animate-fade-down { animation: fade-down 0.7s cubic-bezier(0.16, 1, 0.3, 1) both; }
.stagger-1 { animation-delay: 0ms; }
.stagger-2 { animation-delay: 120ms; }
.stagger-3 { animation-delay: 240ms; }
.stagger-4 { animation-delay: 360ms; }
.stagger-5 { animation-delay: 480ms; }
.stagger-6 { animation-delay: 600ms; }
</style>

<!-- Hero Video Animation -->
<section class="relative w-full h-screen min-h-[700px] overflow-hidden bg-[#faf8f5]">
    <div class="absolute inset-0">
        <video
            src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260820_010308_b1636845-4c15-4ab6-b0c9-9a29bfb0c6e3.mp4"
            autoplay
            muted
            loop
            playsinline
            class="w-full h-full object-cover object-bottom"
        ></video>
    </div>
    <!-- Không thêm dark overlay theo đúng spec -->
    
    <div class="relative z-10 flex flex-col items-start max-w-7xl mx-auto pt-28 md:pt-36 px-6 lg:px-8">
        <!-- Pill -->
        <a href="#shop" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-900/15 bg-white/60 backdrop-blur-sm hover:bg-white/80 transition-colors mb-5 md:mb-6 animate-fade-up stagger-3">
            <span class="text-sm font-semibold text-gray-900">Mới nhất! BỘ SƯU TẬP MÙA ĐÔNG 2026</span>
            <svg class="w-3.5 h-3.5 text-gray-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
        
        <!-- Headline -->
        <h1 class="text-left text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-gray-900 font-bold leading-[1.05] tracking-tight max-w-4xl animate-fade-up stagger-4 drop-shadow-md">
            Ấm áp & Phong cách.<br class="hidden sm:block" /> Khám phá BST Mùa Đông 2026
        </h1>
        
        <div class="mt-8 animate-fade-up stagger-5">
            <a href="#shop" class="inline-block bg-primary text-white font-bold px-8 py-3.5 rounded-full uppercase tracking-wider hover:bg-primary-dark transition shadow-lg">
                Shop Now
            </a>
        </div>
    </div>
</section>

<!-- 🔥 HOT TRENDING - Marquee Animation -->
<div class="bg-gray-900 py-10 overflow-hidden relative">
    <!-- Tiêu đề -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="text-2xl">🔥</span>
            <h2 class="text-lg font-bold text-white uppercase tracking-widest">Đang Hot</h2>
            <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full uppercase animate-pulse">Trending</span>
        </div>
        <a href="#shop" class="text-sm text-gray-400 hover:text-white transition uppercase tracking-wider font-semibold">Xem tất cả →</a>
    </div>

    <!-- Gradient Fade trái phải -->
    <div class="absolute left-0 top-0 bottom-0 w-24 z-10 bg-gradient-to-r from-gray-900 to-transparent pointer-events-none"></div>
    <div class="absolute right-0 top-0 bottom-0 w-24 z-10 bg-gradient-to-l from-gray-900 to-transparent pointer-events-none"></div>

    <!-- Marquee Track -->
    <div class="marquee-track flex items-center gap-6" style="width: max-content;">
        <?php 
        // Lặp lại 3 lần để tạo hiệu ứng cuộn vô tận
        for($repeat = 0; $repeat < 3; $repeat++): 
            foreach ($featuredProducts as $item): 
        ?>
        <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="flex-shrink-0 w-[260px] group relative">
            <div class="relative aspect-[3/4] overflow-hidden rounded-lg shadow-lg bg-gray-800">
                <?php if (!empty($item['image'])): ?>
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                <?php else: ?>
                    <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-500 text-sm">No Image</div>
                <?php endif; ?>
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                <!-- Info -->
                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <p class="text-white text-sm font-bold uppercase truncate drop-shadow-lg"><?= htmlspecialchars($item['name']) ?></p>
                    <p class="text-primary-light text-sm font-black mt-1"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
                </div>
                <!-- Hover badge -->
                <div class="absolute top-3 right-3 bg-white/90 text-gray-900 text-[10px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 uppercase tracking-wider">
                    Xem ngay
                </div>
            </div>
        </a>
        <?php 
            endforeach; 
        endfor; 
        ?>
    </div>
</div>

<style>
    .marquee-track {
        animation: marquee-scroll 30s linear infinite;
    }
    .marquee-track:hover {
        animation-play-state: paused;
    }
    @keyframes marquee-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-33.333%); }
    }
    .text-primary-light {
        color: #5ec4d4;
    }
</style>

<!-- Shop By Category -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-xl font-bold text-gray-900 uppercase tracking-widest mb-8 animate-fade-up">Khám phá theo danh mục</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-up stagger-2">
        <!-- Category Card -->
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Áo Khoác<br>& Da</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1612462766564-28b7ab66606a?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Áo Len<br>Mùa Đông</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Giày Boots</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Phụ Kiện<br>Giữ Ấm</h3>
            </div>
        </a>
    </div>
</div>

<!-- Featured Products -->
<div id="shop" class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900 uppercase tracking-widest mb-8">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featuredProducts as $item): ?>
            <div class="group bg-white p-4 border border-gray-100 shadow-sm hover:shadow-lg transition">
                <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-gray-100">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 flex items-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="w-full bg-primary text-white text-center py-3 font-bold uppercase hover:bg-primary-dark transition text-sm tracking-wider">
                            Mua Ngay
                        </a>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 uppercase truncate">
                    <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="hover:text-primary"><?= $item['name'] ?></a>
                </h3>
                <p class="text-sm font-bold text-gray-600 mt-1"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

