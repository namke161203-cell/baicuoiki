<?php 
/**
 * @var array $products
 * @var string $title
 * @var string $keyword
 * @var string $categoryName
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<div class="bg-gray-50 py-10 min-h-[70vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="mb-8 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    <a href="index.php" class="hover:text-primary transition">Trang chủ</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900"><?= htmlspecialchars($categoryName ?? 'Sản phẩm') ?></span>
                </nav>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 uppercase tracking-tight">
                    <?= htmlspecialchars($categoryName ?? 'Danh sách sản phẩm') ?>
                </h1>
                <?php if (!empty($keyword)): ?>
                    <p class="text-sm text-gray-500 mt-1">Kết quả tìm kiếm cho: <span class="font-bold text-primary">"<?= htmlspecialchars($keyword) ?>"</span> (<?= count($products) ?> sản phẩm)</p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 mt-1">Tìm thấy <?= count($products) ?> sản phẩm phù hợp</p>
                <?php endif; ?>
            </div>

            <!-- Thanh tìm kiếm nhanh tại trang danh sách -->
            <form action="index.php" method="GET" class="flex max-w-md w-full">
                <input type="hidden" name="controller" value="Product">
                <input type="hidden" name="action" value="search">
                <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Tìm tên sản phẩm, áo khoác, quần..." class="w-full px-4 py-2 text-sm border border-gray-300 rounded-l focus:outline-none focus:border-primary">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 text-sm font-bold uppercase rounded-r transition">
                    Tìm
                </button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <?php if (!empty($products)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $item): ?>
                <div class="group bg-white p-4 border border-gray-200 rounded shadow-sm hover:shadow-xl transition duration-300 flex flex-col justify-between relative">
                    
                    <!-- Nút Thêm Yêu Thích -->
                    <button onclick="toggleWishlist(<?= $item['id'] ?>, '<?= addslashes(htmlspecialchars($item['name'])) ?>', '<?= addslashes(htmlspecialchars($item['image'] ?? '')) ?>', <?= $item['price'] ?>)" class="absolute top-6 right-6 z-10 p-2 bg-white/90 hover:bg-white rounded-full shadow hover:text-red-500 text-gray-400 transition" title="Yêu thích">
                        <svg class="w-5 h-5 wishlist-icon-<?= $item['id'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>

                    <div>
                        <!-- Ảnh sản phẩm -->
                        <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-gray-100 rounded">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-sm">Chưa có ảnh</div>
                            <?php endif; ?>
                            
                            <div class="absolute inset-0 flex items-end opacity-0 group-hover:opacity-100 transition-opacity p-2">
                                <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="w-full bg-primary text-white text-center py-2.5 font-bold uppercase hover:bg-primary-dark transition text-xs tracking-wider rounded shadow">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>

                        <!-- Tên & Giá -->
                        <h3 class="text-sm font-bold text-gray-900 uppercase truncate mb-1">
                            <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="hover:text-primary transition"><?= htmlspecialchars($item['name']) ?></a>
                        </h3>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-base font-extrabold text-primary"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                        <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="text-xs font-semibold text-gray-600 hover:text-primary flex items-center">
                            Mua ngay →
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-gray-200 my-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Chưa tìm thấy sản phẩm nào!</h3>
                <p class="text-sm text-gray-500 mb-6">Hãy thử tìm với từ khóa khác như "Áo khoác", "Áo len", "Mùa đông" hoặc khám phá toàn bộ bộ sưu tập.</p>
                <a href="index.php" class="inline-block bg-primary text-white font-bold px-6 py-2.5 rounded text-sm uppercase tracking-wider hover:bg-primary-dark transition">
                    ← Trở về Trang chủ
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
