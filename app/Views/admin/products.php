<?php
/**
 * @var array $products
 * @var array $categories
 * @var array $productKPIs
 * @var string $title
 * @var string $keyword
 */
require_once __DIR__ . '/header.php';

$productKPIs = $productKPIs ?? [
    'total_products' => count($products),
    'featured_products' => count(array_filter($products, fn($p) => !empty($p['is_featured']))),
    'low_stock' => count(array_filter($products, fn($p) => ($p['total_stock'] ?? 0) > 0 && ($p['total_stock'] ?? 0) <= 10)),
    'out_of_stock' => count(array_filter($products, fn($p) => ($p['total_stock'] ?? 0) == 0)),
    'inventory_value' => array_sum(array_map(fn($p) => $p['price'] * ($p['total_stock'] ?? 0), $products))
];
$categories = $categories ?? [];
?>

<div class="space-y-6">
    <!-- Top Bar: Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Kho Hàng & Sản Phẩm</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-gray-100 text-gray-800"><?= count($products) ?> items</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Quản lý toàn bộ danh mục, phân loại biến thể (Màu/Size), bảng giá và kiểm soát tồn kho tức thì.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="index.php?controller=AdminProduct&action=exportCSV" class="px-4 py-2.5 rounded-full border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold transition flex items-center shadow-sm">
                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Xuất Excel (CSV)
            </a>
            <a href="index.php?controller=AdminProduct&action=create" class="px-5 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider transition flex items-center shadow-md">
                <svg class="w-3.5 h-3.5 mr-1.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Thêm Sản Phẩm Mới
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards: Product Performance Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Tổng Sản Phẩm</span>
                <span class="text-2xl font-black text-gray-900"><?= $productKPIs['total_products'] ?></span>
                <span class="text-[11px] text-gray-500 font-semibold block mt-0.5">Trên toàn hệ thống</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-stone-100 text-gray-800 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-teal-600 block mb-1">Sản Phẩm Nổi Bật</span>
                <span class="text-2xl font-black text-teal-700"><?= $productKPIs['featured_products'] ?></span>
                <span class="text-[11px] text-teal-600 font-semibold block mt-0.5">Trang chủ Spotlight</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
        </div>

        <!-- KPI 3 -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-600 block mb-1">Cảnh Báo Tồn Kho</span>
                <span class="text-2xl font-black text-amber-600"><?= $productKPIs['low_stock'] + $productKPIs['out_of_stock'] ?></span>
                <span class="text-[11px] text-amber-700 font-semibold block mt-0.5"><?= $productKPIs['out_of_stock'] ?> hết hàng, <?= $productKPIs['low_stock'] ?> sắp hết</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>

        <!-- KPI 4 -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Tổng Giá Trị Kho</span>
                <span class="text-xl font-black text-gray-900"><?= number_format($productKPIs['inventory_value'] / 1000000, 1, ',', '.') ?>M đ</span>
                <span class="text-[11px] text-gray-500 font-semibold block mt-0.5">Tồn kho x Giá niêm yết</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter & Real-Time Search Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Live Search -->
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="adminProdSearch" oninput="filterAdminProductsList()" placeholder="Tìm tên sản phẩm, mã SKU, danh mục..." class="w-full pl-10 pr-4 py-2 bg-gray-50 rounded-full border border-gray-200 text-xs font-semibold focus:outline-none focus:bg-white focus:border-gray-900 transition">
        </div>

        <!-- Filter Selects -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Category Filter -->
            <select id="adminProdCatFilter" onchange="filterAdminProductsList()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-full px-3.5 py-2 focus:outline-none focus:border-gray-900 cursor-pointer">
                <option value="all">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Stock Filter -->
            <select id="adminProdStockFilter" onchange="filterAdminProductsList()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-full px-3.5 py-2 focus:outline-none focus:border-gray-900 cursor-pointer">
                <option value="all">Tất cả tồn kho</option>
                <option value="in_stock">Còn hàng dồi dào (>10)</option>
                <option value="low_stock">Sắp hết hàng (1 - 10)</option>
                <option value="out_of_stock">Hết hàng (0)</option>
            </select>

            <!-- Featured Filter -->
            <select id="adminProdFeaturedFilter" onchange="filterAdminProductsList()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-full px-3.5 py-2 focus:outline-none focus:border-gray-900 cursor-pointer">
                <option value="all">Tất cả trạng thái</option>
                <option value="featured">Chỉ sản phẩm Nổi Bật</option>
                <option value="standard">Sản phẩm Tiêu Chuẩn</option>
            </select>

            <!-- Reset Button -->
            <button type="button" onclick="resetAdminProductFilters()" class="p-2 text-gray-400 hover:text-gray-900 rounded-full hover:bg-gray-100 transition" title="Đặt lại bộ lọc">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- Products Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="adminProductsTable">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4 w-12 text-center">ID</th>
                        <th class="pb-4 w-20">Ảnh</th>
                        <th class="pb-4 min-w-[220px]">Tên Sản Phẩm & Phân Loại</th>
                        <th class="pb-4">Danh Mục</th>
                        <th class="pb-4 text-right">Giá Bán</th>
                        <th class="pb-4 text-center">Tồn Kho</th>
                        <th class="pb-4 text-center">Đã Bán</th>
                        <th class="pb-4 text-center">Nổi Bật</th>
                        <th class="pb-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="adminProductsTbody">
                    <?php foreach ($products as $p): 
                        $totalStock = (int)($p['total_stock'] ?? 0);
                        $isFeatured = !empty($p['is_featured']);
                        $catName = $p['category_name'] ?? 'Thời trang';
                    ?>
                    <tr class="admin-prod-row hover:bg-gray-50/70 transition" 
                        data-name="<?= strtolower(htmlspecialchars($p['name'])) ?>"
                        data-category="<?= htmlspecialchars($catName) ?>"
                        data-stock="<?= $totalStock ?>"
                        data-featured="<?= $isFeatured ? 'featured' : 'standard' ?>">
                        
                        <td class="py-3.5 text-center text-xs font-bold text-gray-400">
                            #<?= $p['id'] ?>
                        </td>

                        <td class="py-3.5">
                            <div class="w-12 h-14 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 relative group flex-shrink-0">
                                <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/100') ?>" 
                                     alt="<?= htmlspecialchars($p['name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                        </td>

                        <td class="py-3.5">
                            <a href="index.php?controller=Product&action=detail&id=<?= $p['id'] ?>" target="_blank" class="font-bold text-gray-900 hover:text-teal-700 transition block leading-snug">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-[11px] text-gray-400 font-medium truncate max-w-xs block">
                                    <?= htmlspecialchars($p['variants_info'] ?? 'Chưa cập nhật biến thể') ?>
                                </span>
                            </div>
                        </td>

                        <td class="py-3.5 text-xs text-gray-600 font-bold whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full bg-stone-100 text-gray-800">
                                <?= htmlspecialchars($catName) ?>
                            </span>
                        </td>

                        <td class="py-3.5 text-right font-black text-gray-900 whitespace-nowrap">
                            <?= number_format($p['price'], 0, ',', '.') ?>đ
                        </td>

                        <td class="py-3.5 text-center whitespace-nowrap">
                            <?php if ($totalStock > 10): ?>
                                <button type="button" onclick="openStockModalById(<?= $p['id'] ?>)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition cursor-pointer" title="Bấm để chỉnh sửa kho chi tiết">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    <?= $totalStock ?> chiếc
                                </button>
                            <?php elseif ($totalStock > 0): ?>
                                <button type="button" onclick="openStockModalById(<?= $p['id'] ?>)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition cursor-pointer" title="Bấm để chỉnh sửa kho chi tiết">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    <?= $totalStock ?> (Sắp hết)
                                </button>
                            <?php else: ?>
                                <button type="button" onclick="openStockModalById(<?= $p['id'] ?>)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 hover:bg-red-100 transition cursor-pointer" title="Bấm để chỉnh sửa kho chi tiết">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                    Hết hàng
                                </button>
                            <?php endif; ?>
                        </td>

                        <td class="py-3.5 text-center font-bold text-xs text-gray-700">
                            <?= (int)($p['total_sold'] ?? 0) ?>
                        </td>

                        <td class="py-3.5 text-center">
                            <button type="button" onclick="toggleProductFeatured(<?= $p['id'] ?>, this)" class="p-1.5 rounded-full hover:bg-gray-100 transition cursor-pointer" title="Bật/Tắt hiển thị nổi bật">
                                <svg class="w-5 h-5 <?= $isFeatured ? 'text-amber-500 fill-amber-500' : 'text-gray-300' ?>" fill="<?= $isFeatured ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </button>
                        </td>

                        <td class="py-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="index.php?controller=Product&action=detail&id=<?= $p['id'] ?>" target="_blank" class="p-1.5 text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition" title="Xem trên cửa hàng">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                <button type="button" onclick="openQuickEditProductById(<?= $p['id'] ?>)" class="px-2.5 py-1 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition cursor-pointer">
                                    Sửa
                                </button>

                                <a href="index.php?controller=AdminProduct&action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Thao tác không thể hoàn tác.');" class="p-1.5 text-red-500 hover:text-red-700 rounded-lg hover:bg-red-50 transition" title="Xóa sản phẩm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div id="adminProdEmptyState" class="hidden text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <p class="text-sm font-bold text-gray-800">Không tìm thấy sản phẩm phù hợp</p>
            <p class="text-xs text-gray-400 mt-1">Hãy thử tìm với từ khóa khác hoặc điều chỉnh lại bộ lọc.</p>
        </div>
    </div>
</div>

<!-- Quick Edit Modal -->
<div id="quickEditProductModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-gray-100 transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Chỉnh Sửa Nhanh Sản Phẩm</h3>
            </div>
            <button type="button" onclick="closeQuickEditProduct()" class="text-gray-400 hover:text-gray-900 text-lg font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <form action="index.php?controller=AdminProduct&action=quickUpdate" method="POST" class="py-4 space-y-4">
            <input type="hidden" name="id" id="qeProductId">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tên Sản Phẩm *</label>
                <input type="text" name="name" id="qeProductName" required class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-bold text-gray-900 focus:outline-none focus:border-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Danh Mục *</label>
                    <select name="category_id" id="qeProductCategory" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500 bg-white">
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Giá Bán (VNĐ) *</label>
                    <input type="number" name="price" id="qeProductPrice" required min="1000" step="1000" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-black text-teal-700 focus:outline-none focus:border-teal-500">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_featured" id="qeProductFeatured" value="1" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-gray-300">
                <label for="qeProductFeatured" class="text-xs font-bold text-gray-800 cursor-pointer">
                    Đặt làm sản phẩm Nổi Bật (Featured Spotlight)
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mô Tả Sản Phẩm</label>
                <textarea name="description" id="qeProductDesc" rows="3" class="w-full border border-gray-300 p-2.5 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-teal-500 leading-relaxed"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeQuickEditProduct()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-full text-xs font-bold hover:bg-gray-200 transition cursor-pointer">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-full text-xs font-bold uppercase tracking-wider hover:bg-black transition shadow-md cursor-pointer">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Variants Modal -->
<div id="stockVariantsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-gray-100 transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Quản Lý Tồn Kho & Biến Thể</h3>
            </div>
            <button type="button" onclick="closeStockModal()" class="text-gray-400 hover:text-gray-900 text-lg font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <div class="py-4">
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-2xl mb-4">
                <img id="smProductImg" src="" class="w-12 h-14 object-cover rounded-xl bg-gray-200 border border-gray-200">
                <div>
                    <h4 id="smProductName" class="font-bold text-gray-900 text-sm"></h4>
                    <p id="smProductPrice" class="text-xs font-black text-teal-700"></p>
                </div>
            </div>

            <h5 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Danh Sách Biến Thể & Số Lượng Hiện Có</h5>
            <div id="smVariantsList" class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeStockModal()" class="px-5 py-2 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition cursor-pointer">
                Đóng
            </button>
        </div>
    </div>
</div>

<script>
    const adminProductsData = <?= json_encode($products ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    function openQuickEditProductById(id) {
        const p = adminProductsData.find(item => item.id == id);
        if (p) openQuickEditProduct(p);
    }

    function openStockModalById(id) {
        const p = adminProductsData.find(item => item.id == id);
        if (p) openStockModal(p);
    }

    function filterAdminProductsList() {
        const query = (document.getElementById('adminProdSearch')?.value || '').toLowerCase().trim();
        const catFilter = document.getElementById('adminProdCatFilter')?.value || 'all';
        const stockFilter = document.getElementById('adminProdStockFilter')?.value || 'all';
        const featFilter = document.getElementById('adminProdFeaturedFilter')?.value || 'all';

        const rows = document.querySelectorAll('.admin-prod-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const category = row.getAttribute('data-category') || '';
            const stock = parseInt(row.getAttribute('data-stock') || '0', 10);
            const featured = row.getAttribute('data-featured') || 'standard';

            let matchQuery = !query || name.includes(query) || category.toLowerCase().includes(query);
            let matchCat = catFilter === 'all' || category === catFilter;
            let matchStock = true;
            if (stockFilter === 'in_stock') matchStock = stock > 10;
            else if (stockFilter === 'low_stock') matchStock = stock > 0 && stock <= 10;
            else if (stockFilter === 'out_of_stock') matchStock = stock === 0;

            let matchFeat = featFilter === 'all' || featured === featFilter;

            if (matchQuery && matchCat && matchStock && matchFeat) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('adminProdEmptyState');
        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function resetAdminProductFilters() {
        if (document.getElementById('adminProdSearch')) document.getElementById('adminProdSearch').value = '';
        if (document.getElementById('adminProdCatFilter')) document.getElementById('adminProdCatFilter').value = 'all';
        if (document.getElementById('adminProdStockFilter')) document.getElementById('adminProdStockFilter').value = 'all';
        if (document.getElementById('adminProdFeaturedFilter')) document.getElementById('adminProdFeaturedFilter').value = 'all';
        filterAdminProductsList();
    }

    function openQuickEditProduct(product) {
        if (!product) return;
        document.getElementById('qeProductId').value = product.id;
        document.getElementById('qeProductName').value = product.name;
        document.getElementById('qeProductCategory').value = product.category_id || '';
        document.getElementById('qeProductPrice').value = product.price;
        document.getElementById('qeProductFeatured').checked = product.is_featured == 1;
        document.getElementById('qeProductDesc').value = product.description || '';

        const m = document.getElementById('quickEditProductModal');
        if (m) m.classList.remove('hidden');
    }

    function closeQuickEditProduct() {
        const m = document.getElementById('quickEditProductModal');
        if (m) m.classList.add('hidden');
    }

    function openStockModal(product) {
        if (!product) return;
        document.getElementById('smProductName').innerText = product.name;
        document.getElementById('smProductPrice').innerText = Number(product.price).toLocaleString('vi-VN') + 'đ';
        document.getElementById('smProductImg').src = product.image || 'https://via.placeholder.com/100';

        const listContainer = document.getElementById('smVariantsList');
        listContainer.innerHTML = '';

        const variants = product.variants_list || [];
        if (variants.length === 0) {
            listContainer.innerHTML = `
                <div class="p-3 bg-gray-50 rounded-xl text-center text-xs text-gray-500">
                    Sản phẩm này chưa được thiết lập bảng biến thể chi tiết (Size/Màu).
                </div>
            `;
        } else {
            variants.forEach(v => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100';
                item.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-stone-200 border border-gray-300 flex items-center justify-center text-[10px] font-bold text-gray-800 uppercase">
                            ${v.size || 'M'}
                        </span>
                        <div>
                            <p class="text-xs font-bold text-gray-900">${v.color || 'Tiêu chuẩn'} &bull; Size ${v.size || 'M'}</p>
                            <span class="text-[10px] text-gray-400">ID Biến Thể: #${v.id}</span>
                        </div>
                    </div>
                    <form action="index.php?controller=AdminProduct&action=updateVariantStock" method="POST" class="flex items-center space-x-2">
                        <input type="hidden" name="variant_id" value="${v.id}">
                        <input type="number" name="stock" value="${v.stock}" min="0" class="w-16 py-1 px-2 text-xs font-bold text-center border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500">
                        <button type="submit" class="px-2.5 py-1 bg-gray-900 hover:bg-black text-white text-[11px] font-bold rounded-lg transition cursor-pointer">
                            Lưu
                        </button>
                    </form>
                `;
                listContainer.appendChild(item);
            });
        }

        const m = document.getElementById('stockVariantsModal');
        if (m) m.classList.remove('hidden');
    }

    function closeStockModal() {
        const m = document.getElementById('stockVariantsModal');
        if (m) m.classList.add('hidden');
    }

    function toggleProductFeatured(productId, btnElement) {
        fetch(`index.php?controller=AdminProduct&action=toggleFeatured&id=${productId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const svg = btnElement.querySelector('svg');
                if (data.is_featured == 1) {
                    svg.className = 'w-5 h-5 text-amber-500 fill-amber-500';
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    svg.className = 'w-5 h-5 text-gray-300';
                    svg.setAttribute('fill', 'none');
                }
                const row = btnElement.closest('.admin-prod-row');
                if (row) {
                    row.setAttribute('data-featured', data.is_featured == 1 ? 'featured' : 'standard');
                }
            }
        })
        .catch(() => {
            window.location.href = `index.php?controller=AdminProduct&action=toggleFeatured&id=${productId}`;
        });
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
