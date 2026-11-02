<?php
/**
 * COMPONENT: Product Card (Molecule - Atomic Design)
 * @param array $product Dữ liệu sản phẩm (id, name, price, image, category_name, is_featured, variants...)
 * @param bool $showQuickView Có hiển thị nút xem nhanh hay không (mặc định true)
 */
$product = $product ?? [];
if (empty($product)) return;

$origPrice = ($product['price'] ?? 0) * 1.2;
$catTerm = $product['category_name'] ?? 'Thời trang';
$imageUrl = $product['image'] ?? 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=500&q=80';
$isFeatured = !empty($product['is_featured']);
?>
<div class="product-item-card group bg-white rounded-3xl p-3.5 border border-gray-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
     data-category="<?= htmlspecialchars($catTerm) ?>">
    
    <!-- Top Media Box -->
    <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-gray-100 mb-3.5">
        <img src="<?= htmlspecialchars($imageUrl) ?>" 
             alt="<?= htmlspecialchars($product['name'] ?? '') ?>" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">

        <!-- Badge Top Left -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10 pointer-events-none">
            <?php if ($isFeatured): ?>
                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-gray-900 text-white shadow-sm">
                    ★ Nổi Bật
                </span>
            <?php else: ?>
                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-teal-600 text-white shadow-sm">
                    Mới 2026
                </span>
            <?php endif; ?>
        </div>

        <!-- Wishlist Heart Button Top Right -->
        <button type="button" 
                onclick="toggleWishlist(<?= $product['id'] ?>, '<?= addslashes(htmlspecialchars($product['name'] ?? '')) ?>', '<?= addslashes(htmlspecialchars($imageUrl)) ?>', <?= (float)($product['price'] ?? 0) ?>)" 
                class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-red-500 shadow-md backdrop-blur-sm transition cursor-pointer" 
                title="Lưu vào yêu thích">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </button>

        <!-- Hover Action Bar (Slide-up Glassmorphism) -->
        <div class="absolute inset-x-3 bottom-3 flex gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-10">
            <button type="button" onclick="quickViewProduct(<?= htmlspecialchars(json_encode($product)) ?>)" class="flex-1 py-2.5 rounded-xl bg-white/95 text-gray-900 hover:bg-white text-[11px] font-extrabold uppercase tracking-wider shadow-lg backdrop-blur-md transition flex items-center justify-center cursor-pointer">
                Xem Nhanh
            </button>
            <a href="index.php?controller=Product&action=detail&id=<?= $product['id'] ?>" class="w-10 h-10 rounded-xl bg-gray-900 hover:bg-black text-white flex items-center justify-center shadow-lg transition" title="Xem chi tiết">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <!-- Bottom Info Box -->
    <div class="px-1.5 pb-1">
        <!-- Category & Rating -->
        <div class="flex items-center justify-between text-[11px] font-semibold text-gray-400 mb-1">
            <span class="uppercase tracking-wider"><?= htmlspecialchars($catTerm) ?></span>
            <div class="flex items-center text-amber-500 text-xs">
                <span>★</span>
                <span class="text-gray-600 font-bold ml-0.5 text-[10px]">4.9</span>
            </div>
        </div>

        <!-- Product Title -->
        <h4 class="text-sm font-bold text-gray-900 uppercase truncate group-hover:text-primary transition leading-tight mb-2">
            <a href="index.php?controller=Product&action=detail&id=<?= $product['id'] ?>">
                <?= htmlspecialchars($product['name'] ?? '') ?>
            </a>
        </h4>

        <!-- Price & Color Dots -->
        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
            <div class="flex items-baseline space-x-1.5">
                <span class="text-sm font-black text-gray-950"><?= number_format($product['price'] ?? 0, 0, ',', '.') ?>đ</span>
                <span class="text-[10px] text-gray-400 line-through font-semibold"><?= number_format($origPrice, 0, ',', '.') ?>đ</span>
            </div>
            
            <!-- Variant Colors Indicator -->
            <div class="flex items-center -space-x-1">
                <span class="w-3 h-3 rounded-full bg-gray-900 border border-white ring-1 ring-gray-200"></span>
                <span class="w-3 h-3 rounded-full bg-stone-400 border border-white ring-1 ring-gray-200"></span>
                <span class="w-3 h-3 rounded-full bg-blue-900 border border-white ring-1 ring-gray-200"></span>
            </div>
        </div>
    </div>

</div>
