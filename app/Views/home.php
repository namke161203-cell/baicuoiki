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

<!-- ============================================================== -->
<!-- 🌟 BỘ SƯU TẬP TIÊU BIỂU (CURATED COLLECTION) - TAB FILTER & BENTO -->
<!-- ============================================================== -->
<section id="shop" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <!-- Section Header & Filter Pills -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-gray-200/80 pb-8">
        <div>
            <div class="flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-primary mb-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                <span>Winter Atelier &bull; Limited Edition 2026</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-950 tracking-tight">
                Bộ Sưu Tập Mùa Đông
            </h2>
            <p class="text-sm text-gray-500 font-medium mt-1 max-w-xl">
                Sự kết hợp hoàn hảo giữa sợi Cashmere tự nhiên, da cừu nhập khẩu và phom dáng đương đại.
            </p>
        </div>

        <!-- Filter Pill Tabs (Smooth Tab Switcher) -->
        <div class="flex items-center gap-1.5 bg-gray-100 p-1.5 rounded-full overflow-x-auto no-scrollbar shadow-inner max-w-full">
            <button type="button" onclick="filterHomeProducts('all', this)" class="product-filter-btn active px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 whitespace-nowrap bg-gray-950 text-white shadow-sm cursor-pointer">
                Tất Cả (<?= count($featuredProducts) ?>)
            </button>
            <button type="button" onclick="filterHomeProducts('Áo Khoác', this)" class="product-filter-btn px-4 py-2 rounded-full text-xs font-bold text-gray-600 hover:text-gray-950 transition-all duration-300 whitespace-nowrap cursor-pointer">
                Áo Khoác & Da
            </button>
            <button type="button" onclick="filterHomeProducts('Áo Len', this)" class="product-filter-btn px-4 py-2 rounded-full text-xs font-bold text-gray-600 hover:text-gray-950 transition-all duration-300 whitespace-nowrap cursor-pointer">
                Áo Len Cashmere
            </button>
            <button type="button" onclick="filterHomeProducts('Giày Boots', this)" class="product-filter-btn px-4 py-2 rounded-full text-xs font-bold text-gray-600 hover:text-gray-950 transition-all duration-300 whitespace-nowrap cursor-pointer">
                Giày Boots
            </button>
            <button type="button" onclick="filterHomeProducts('Phụ Kiện', this)" class="product-filter-btn px-4 py-2 rounded-full text-xs font-bold text-gray-600 hover:text-gray-950 transition-all duration-300 whitespace-nowrap cursor-pointer">
                Phụ Kiện Ấm
            </button>
        </div>
    </div>

    <!-- 🌟 BENTO PRODUCT SHOWCASE GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Spotlight Hero Product Card (Span 4) -->
        <?php 
        $spotlight = !empty($featuredProducts) ? $featuredProducts[0] : null; 
        if ($spotlight):
        ?>
        <div class="lg:col-span-4 bg-gray-950 text-white rounded-3xl p-7 flex flex-col justify-between relative overflow-hidden group shadow-xl border border-gray-800">
            <!-- Background Image with Ambient Glow -->
            <div class="absolute inset-0 z-0">
                <img src="<?= htmlspecialchars($spotlight['image']) ?>" alt="<?= htmlspecialchars($spotlight['name']) ?>" class="w-full h-full object-cover opacity-40 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/70 to-transparent"></div>
            </div>

            <!-- Top Floating Badges -->
            <div class="relative z-10 flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-primary text-white shadow-md">
                    ★ Spotlight of the Week
                </span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-white/10 backdrop-blur-md text-teal-300 border border-white/20">
                    -20% Mùa Đông
                </span>
            </div>

            <!-- Bottom Content -->
            <div class="relative z-10 mt-64 pt-6">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                    <?= htmlspecialchars($spotlight['category_name'] ?? 'BST Tiêu Điểm') ?>
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-white uppercase tracking-tight mt-1 mb-2 leading-tight">
                    <?= htmlspecialchars($spotlight['name']) ?>
                </h3>
                <p class="text-xs text-gray-300 font-medium line-clamp-2 mb-4 leading-relaxed">
                    <?= htmlspecialchars($spotlight['description'] ?: 'Thiết kế phom rộng đương đại, lót dù giữ nhiệt cùng đường may tỉ mỉ chuẩn Atelier.') ?>
                </p>

                <!-- Color Options & Price -->
                <div class="flex items-center justify-between pt-4 border-t border-white/10 mb-6">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Giá niêm yết</span>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-xl font-black text-white"><?= number_format($spotlight['price'], 0, ',', '.') ?>đ</span>
                            <span class="text-xs text-gray-500 line-through font-semibold"><?= number_format($spotlight['price'] * 1.25, 0, ',', '.') ?>đ</span>
                        </div>
                    </div>
                    <!-- Color Swatches -->
                    <div class="flex items-center space-x-1.5">
                        <span class="w-4 h-4 rounded-full bg-gray-900 border-2 border-white shadow-sm" title="Đen Tuyển"></span>
                        <span class="w-4 h-4 rounded-full bg-stone-400 border-2 border-white/80 shadow-sm" title="Beige"></span>
                        <span class="w-4 h-4 rounded-full bg-amber-800 border-2 border-white/80 shadow-sm" title="Camel"></span>
                    </div>
                </div>

                <!-- Action CTA -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="index.php?controller=Product&action=detail&id=<?= $spotlight['id'] ?>" class="text-center py-3 rounded-full bg-white text-gray-950 font-bold text-xs uppercase tracking-wider hover:bg-gray-100 transition shadow-lg">
                        Xem Chi Tiết
                    </a>
                    <button type="button" onclick="quickViewProduct(<?= htmlspecialchars(json_encode($spotlight)) ?>)" class="py-3 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md text-white font-bold text-xs uppercase tracking-wider border border-white/20 transition flex items-center justify-center cursor-pointer">
                        Xem Nhanh
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Right Column: Curated Product Grid (Span 8) -->
        <div class="lg:col-span-8">
            <div id="homeProductGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php 
                // Hiển thị danh sách sản phẩm sử dụng Atomic Design Component (Molecule)
                $restProducts = !empty($featuredProducts) ? array_slice($featuredProducts, 1, 9) : [];
                foreach ($restProducts as $index => $item) {
                    $product = $item;
                    include __DIR__ . '/components/product_card.php';
                }
                ?>
            </div>

            <!-- View All Products Button -->
            <div class="mt-10 text-center">
                <a href="index.php?controller=Product&action=list" class="inline-flex items-center px-8 py-3.5 rounded-full border-2 border-gray-900 text-gray-900 font-black text-xs uppercase tracking-widest hover:bg-gray-900 hover:text-white transition duration-300 shadow-sm">
                    Khám Phá Toàn Bộ BST (<?= count($featuredProducts) ?> Sản Phẩm) &rarr;
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================== -->
<!-- 🧥 WINTER LOOKBOOK 2026 - COMPLETE THE LOOK (PHỐI ĐỒ ĐẲNG CẤP) -->
<!-- ============================================================== -->
<section class="bg-stone-100/70 py-20 border-y border-stone-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left: Lookbook Full Outfit Image with Interactive Hotspots -->
            <div class="lg:col-span-6 relative rounded-3xl overflow-hidden shadow-2xl group aspect-[4/5] bg-gray-900">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=900&auto=format&fit=crop" 
                     alt="Winter Outfit Lookbook" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30"></div>

                <!-- Lookbook Label -->
                <div class="absolute top-6 left-6 z-10">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/90 text-gray-900 shadow-md">
                        Style Guide &bull; Winter 2026
                    </span>
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mt-2 drop-shadow-md">
                        Minimalist Urban Warmth
                    </h3>
                </div>

                <!-- Hotspot 1: Coat -->
                <div class="absolute top-[35%] left-[48%] z-20 group/pin cursor-pointer">
                    <span class="relative flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-white border-2 border-primary shadow-lg items-center justify-center text-[9px] font-black text-primary">+</span>
                    </span>
                    <!-- Tooltip -->
                    <div class="hidden group-hover/pin:block absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap shadow-xl">
                        Áo Khoác Phao Đại Hàn &bull; 1.250.000đ
                    </div>
                </div>

                <!-- Hotspot 2: Sweater -->
                <div class="absolute top-[55%] left-[52%] z-20 group/pin cursor-pointer">
                    <span class="relative flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-white border-2 border-primary shadow-lg items-center justify-center text-[9px] font-black text-primary">+</span>
                    </span>
                    <div class="hidden group-hover/pin:block absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap shadow-xl">
                        Áo Len Cổ Lọ Cashmere &bull; 550.000đ
                    </div>
                </div>

                <!-- Hotspot 3: Boots -->
                <div class="absolute bottom-[18%] left-[42%] z-20 group/pin cursor-pointer">
                    <span class="relative flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-white border-2 border-primary shadow-lg items-center justify-center text-[9px] font-black text-primary">+</span>
                    </span>
                    <div class="hidden group-hover/pin:block absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap shadow-xl">
                        Giày Boots Da Cao Cổ &bull; 850.000đ
                    </div>
                </div>

                <!-- Bottom Caption -->
                <div class="absolute bottom-6 left-6 right-6 z-10 flex items-center justify-between text-xs text-gray-300 font-medium">
                    <span>Phối đồ chuẩn phong cách Thu Đông Seoul 2026</span>
                    <span class="text-white font-bold">Chạm vào điểm tròn (+) để xem</span>
                </div>
            </div>

            <!-- Right: "Complete The Look" Products Box -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-primary">Gợi Ý Phối Đồ Trọn Bộ</span>
                    <h3 class="text-3xl font-black text-gray-950 uppercase tracking-tight mt-1">
                        Complete The Look
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1 leading-relaxed">
                        Sở hữu trọn bộ trang phục được các Stylist hàng đầu tại hquie tuyển chọn để tự tin dạo phố mà không cần đắn đo cách phối.
                    </p>
                </div>

                <!-- Set Products Mini List -->
                <div class="space-y-3.5">
                    <!-- Item 1 -->
                    <div class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-gray-900 transition">
                        <div class="flex items-center space-x-3.5">
                            <img src="https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=160&auto=format&fit=crop" class="w-14 h-16 object-cover rounded-xl bg-gray-100">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Lớp Ngoài</span>
                                <h4 class="text-xs font-bold text-gray-900 uppercase">Áo Khoác Phao Đại Hàn Siêu Ấm</h4>
                                <span class="text-xs font-black text-gray-950">1.250.000đ</span>
                            </div>
                        </div>
                        <a href="index.php?controller=Product&action=detail&id=2" class="px-4 py-1.5 rounded-full bg-gray-100 hover:bg-gray-900 hover:text-white text-xs font-bold transition">Xem</a>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-gray-900 transition">
                        <div class="flex items-center space-x-3.5">
                            <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=160&auto=format&fit=crop" class="w-14 h-16 object-cover rounded-xl bg-gray-100">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Lớp Trong</span>
                                <h4 class="text-xs font-bold text-gray-900 uppercase">Áo Len Cổ Lọ Cashmere Nam Nữ</h4>
                                <span class="text-xs font-black text-gray-950">550.000đ</span>
                            </div>
                        </div>
                        <a href="index.php?controller=Product&action=detail&id=3" class="px-4 py-1.5 rounded-full bg-gray-100 hover:bg-gray-900 hover:text-white text-xs font-bold transition">Xem</a>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-gray-900 transition">
                        <div class="flex items-center space-x-3.5">
                            <img src="https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=160&auto=format&fit=crop" class="w-14 h-16 object-cover rounded-xl bg-gray-100">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Giày & Phụ Kiện</span>
                                <h4 class="text-xs font-bold text-gray-900 uppercase">Giày Boots Da Cao Cổ Lót Lông</h4>
                                <span class="text-xs font-black text-gray-950">850.000đ</span>
                            </div>
                        </div>
                        <a href="index.php?controller=Product&action=detail&id=4" class="px-4 py-1.5 rounded-full bg-gray-100 hover:bg-gray-900 hover:text-white text-xs font-bold transition">Xem</a>
                    </div>
                </div>

                <!-- Bundle Box -->
                <div class="p-5 rounded-2xl bg-gray-950 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-teal-400">Combo Tiết Kiệm 15%</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black bg-red-500 text-white">SAVE 397K</span>
                        </div>
                        <div class="flex items-baseline space-x-2 mt-1">
                            <span class="text-2xl font-black text-white">2.253.000đ</span>
                            <span class="text-xs text-gray-400 line-through">2.650.000đ</span>
                        </div>
                    </div>
                    <a href="index.php?controller=Product&action=detail&id=2" class="w-full sm:w-auto px-6 py-3 rounded-full bg-teal-500 hover:bg-teal-400 text-gray-950 font-black text-xs uppercase tracking-wider transition text-center shadow-lg">
                        Mua Trọn Bộ Set Này &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- 🧭 KHÁM PHÁ THEO DANH MỤC (CATEGORY CARDS REDESIGNED) -->
<!-- ============================================================== -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
        <div>
            <span class="text-xs font-black uppercase tracking-widest text-primary">Danh Mục Phong Cách</span>
            <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tight mt-1">
                Khám Phá Theo Nhóm Đồ
            </h2>
        </div>
        <a href="index.php?controller=Product&action=list" class="text-xs font-bold text-gray-900 hover:text-primary transition uppercase tracking-wider">
            Xem Tất Cả Danh Mục &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Cat 1 -->
        <a href="index.php?controller=Product&action=search&keyword=Áo Khoác" class="group relative aspect-[3/4] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200/80">
            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 text-white">
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-wider block mb-1">5 Sản phẩm</span>
                <h3 class="text-xl font-black uppercase tracking-tight leading-tight">Áo Khoác & Da</h3>
                <span class="inline-flex items-center text-xs font-bold text-teal-400 mt-2 group-hover:translate-x-1 transition-transform">
                    Khám phá &rarr;
                </span>
            </div>
        </a>

        <!-- Cat 2 -->
        <a href="index.php?controller=Product&action=search&keyword=Áo Len" class="group relative aspect-[3/4] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200/80">
            <img src="https://images.unsplash.com/photo-1612462766564-28b7ab66606a?w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 text-white">
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-wider block mb-1">3 Sản phẩm</span>
                <h3 class="text-xl font-black uppercase tracking-tight leading-tight">Áo Len Mùa Đông</h3>
                <span class="inline-flex items-center text-xs font-bold text-teal-400 mt-2 group-hover:translate-x-1 transition-transform">
                    Khám phá &rarr;
                </span>
            </div>
        </a>

        <!-- Cat 3 -->
        <a href="index.php?controller=Product&action=search&keyword=Giày Boots" class="group relative aspect-[3/4] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200/80">
            <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 text-white">
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-wider block mb-1">2 Sản phẩm</span>
                <h3 class="text-xl font-black uppercase tracking-tight leading-tight">Giày Boots Da</h3>
                <span class="inline-flex items-center text-xs font-bold text-teal-400 mt-2 group-hover:translate-x-1 transition-transform">
                    Khám phá &rarr;
                </span>
            </div>
        </a>

        <!-- Cat 4 -->
        <a href="index.php?controller=Product&action=search&keyword=Phụ Kiện" class="group relative aspect-[3/4] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-200/80">
            <img src="https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 text-white">
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-wider block mb-1">3 Sản phẩm</span>
                <h3 class="text-xl font-black uppercase tracking-tight leading-tight">Phụ Kiện Giữ Ấm</h3>
                <span class="inline-flex items-center text-xs font-bold text-teal-400 mt-2 group-hover:translate-x-1 transition-transform">
                    Khám phá &rarr;
                </span>
            </div>
        </a>
    </div>
</section>

<!-- ============================================================== -->
<!-- 🛡️ CAM KẾT GIÁ TRỊ & DỊCH VỤ ĐẶC QUYỀN (BRAND TRUST PILLARS) -->
<!-- ============================================================== -->
<section class="border-t border-gray-200/80 bg-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Giao Hàng Hỏa Tốc</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Miễn phí vận chuyển toàn quốc cho đơn hàng từ 500.000đ.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Đổi Hàng 30 Ngày</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Thử đồ tại nhà, đổi size và mẫu tận nơi hoàn toàn miễn phí.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Chất Liệu Tuyển Chọn</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Len Cashmere, dạ ép và da bò nguyên bản nhập khẩu 100%.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Stylist Tư Vấn 24/7</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Đội ngũ tư vấn phối đồ và hướng dẫn chọn size chuẩn form cá nhân.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- 🔍 QUICK VIEW MODAL (XEM NHANH SẢN PHẨM TRỰC QUAN) -->
<!-- ============================================================== -->
<div id="quickViewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border border-gray-100 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeQuickViewModal()" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition cursor-pointer">
            &times;
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Modal Image Preview -->
            <div class="bg-gray-100 aspect-[3/4] md:aspect-auto">
                <img id="qvImage" src="" alt="" class="w-full h-full object-cover">
            </div>

            <!-- Modal Info & Form -->
            <div class="p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <span id="qvCategory" class="text-[10px] font-bold text-primary uppercase tracking-widest block mb-1">Áo Khoác</span>
                    <h3 id="qvTitle" class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Tên Sản Phẩm</h3>
                    <div class="flex items-center space-x-2 mb-4">
                        <span id="qvPrice" class="text-2xl font-black text-gray-950">1.250.000đ</span>
                        <span id="qvOrigPrice" class="text-sm text-gray-400 line-through font-semibold">1.500.000đ</span>
                    </div>

                    <p id="qvDesc" class="text-xs text-gray-500 leading-relaxed mb-6">
                        Chất liệu vải cao cấp giữ nhiệt, đường cắt may tỉ mỉ tôn vinh phong thái thời thượng.
                    </p>

                    <!-- Form Add to Cart -->
                    <form action="index.php?controller=Cart&action=add" method="POST" id="quickViewForm" class="space-y-4">
                        <input type="hidden" id="qvProductId" name="product_id" value="">

                        <!-- Size Selector -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Chọn Size</label>
                            <div class="flex gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="S" class="peer sr-only">
                                    <span class="px-3.5 py-1.5 rounded-xl border border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white text-xs font-bold transition inline-block">S</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="M" checked class="peer sr-only">
                                    <span class="px-3.5 py-1.5 rounded-xl border border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white text-xs font-bold transition inline-block">M</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="L" class="peer sr-only">
                                    <span class="px-3.5 py-1.5 rounded-xl border border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white text-xs font-bold transition inline-block">L</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="XL" class="peer sr-only">
                                    <span class="px-3.5 py-1.5 rounded-xl border border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white text-xs font-bold transition inline-block">XL</span>
                                </label>
                            </div>
                        </div>

                        <!-- Color Selector -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Màu Sắc</label>
                            <select name="color" class="w-full border border-gray-300 p-2.5 rounded-xl text-xs font-semibold focus:outline-none focus:border-gray-900 bg-white">
                                <option value="Đen Tuyển">Đen Tuyển (Black)</option>
                                <option value="Nâu Camel">Nâu Camel</option>
                                <option value="Xám Tro">Xám Tro (Grey)</option>
                                <option value="Beige">Beige Sữa</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="flex items-center space-x-3 pt-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Số lượng:</label>
                            <input type="number" name="quantity" value="1" min="1" max="10" class="w-16 border border-gray-300 p-2 rounded-xl text-xs font-bold text-center">
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100">
                            <button type="submit" name="buy_now" value="1" class="py-3 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider transition text-center shadow-md">
                                Mua Ngay
                            </button>
                            <button type="submit" class="py-3 rounded-full bg-primary hover:bg-primary-dark text-white text-xs font-bold uppercase tracking-wider transition text-center shadow-md">
                                + Thêm Vào Giỏ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- ⚡ JAVASCRIPT TƯƠNG TÁC LỌC DANH MỤC VÀ QUICK VIEW -->
<!-- ============================================================== -->
<script>
    // 1. Lọc sản phẩm theo danh mục mượt mà không reload
    function filterHomeProducts(category, btnElement) {
        // Cập nhật trạng thái nút bấm
        document.querySelectorAll('.product-filter-btn').forEach(btn => {
            btn.classList.remove('bg-gray-950', 'text-white', 'shadow-sm', 'active');
            btn.classList.add('text-gray-600');
        });
        if (btnElement) {
            btnElement.classList.add('bg-gray-950', 'text-white', 'shadow-sm', 'active');
            btnElement.classList.remove('text-gray-600');
        }

        // Lọc các thẻ sản phẩm trong lưới
        const cards = document.querySelectorAll('.product-item-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category') || '';
            if (category === 'all' || cardCat.toLowerCase().includes(category.toLowerCase())) {
                card.style.display = 'flex';
                card.style.animation = 'fade-up 0.4s ease forwards';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 2. Mở Quick View Modal
    function quickViewProduct(item) {
        if (!item) return;
        document.getElementById('qvProductId').value = item.id;
        document.getElementById('qvTitle').innerText = item.name;
        document.getElementById('qvCategory').innerText = item.category_name || 'Bộ sưu tập mùa đông';
        document.getElementById('qvImage').src = item.image;
        document.getElementById('qvImage').alt = item.name;
        
        const priceFormatted = Number(item.price).toLocaleString('vi-VN') + 'đ';
        const origPriceFormatted = Number(item.price * 1.2).toLocaleString('vi-VN') + 'đ';
        document.getElementById('qvPrice').innerText = priceFormatted;
        document.getElementById('qvOrigPrice').innerText = origPriceFormatted;

        if (item.description) {
            document.getElementById('qvDesc').innerText = item.description;
        }

        const modal = document.getElementById('quickViewModal');
        modal.classList.remove('hidden');
    }

    // 3. Kiểm tra đăng nhập khi submit Quick View form (Mua ngay / Thêm giỏ)
    const IS_USER_LOGGED_IN = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    document.getElementById('quickViewForm')?.addEventListener('submit', function(e) {
        if (!IS_USER_LOGGED_IN) {
            e.preventDefault();
            showToast('Vui lòng đăng nhập để thêm vào giỏ hoặc mua ngay!', 'warning');
            setTimeout(() => {
                window.location.href = 'index.php?controller=Auth&action=login';
            }, 800);
        }
    });

    function closeQuickViewModal() {
        const modal = document.getElementById('quickViewModal');
        modal.classList.add('hidden');
    }

    // Đóng khi click ngoài modal
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('quickViewModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            closeQuickViewModal();
        }
    });

    // Đóng khi bấm phím Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQuickViewModal();
        }
    });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

