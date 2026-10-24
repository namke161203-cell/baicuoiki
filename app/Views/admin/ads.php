<?php 
/**
 * @var array $ads
 * @var string $title
 */
require_once __DIR__ . '/header.php'; 
?>

<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Quản lý Quảng Cáo & Banner</h2>
        <p class="text-xs text-gray-500 mt-0.5">Kiểm soát các chiến dịch truyền thông, banner nổi bật tại Trang chủ, Popup và Chân trang.</p>
    </div>
    <button onclick="openAdModal()" class="bg-primary hover:bg-primary-dark text-white font-bold py-2.5 px-5 rounded-lg shadow-sm transition text-xs uppercase tracking-wider flex items-center justify-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        + Thêm Banner Mới
    </button>
</div>

<!-- Flash Alerts -->
<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-xs font-semibold flex items-center justify-between shadow-sm">
        <span>✅ <?= htmlspecialchars($_SESSION['flash_success']) ?></span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-xs font-semibold flex items-center justify-between shadow-sm">
        <span>⚠️ <?= htmlspecialchars($_SESSION['flash_error']) ?></span>
        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700">&times;</button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<!-- Danh Sách Banner Quảng Cáo -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 font-semibold border-b border-gray-100 text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Banner / Preview</th>
                    <th class="py-3.5 px-4">Chiến Dịch & Link</th>
                    <th class="py-3.5 px-4 text-center">Vị Trí</th>
                    <th class="py-3.5 px-4 text-center">Hiển Thị (Views)</th>
                    <th class="py-3.5 px-4 text-center">Click (CTR)</th>
                    <th class="py-3.5 px-4 text-center">Thời Hạn</th>
                    <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                    <th class="py-3.5 px-4 text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                <?php if (empty($ads)): ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            Chưa có banner nào. Hãy bấm <b>"+ Thêm Banner Mới"</b> để bắt đầu chiến dịch!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ads as $ad): ?>
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Preview Image -->
                            <td class="py-3.5 px-4">
                                <div class="w-24 h-14 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm relative group">
                                    <img src="<?= htmlspecialchars($ad['image_url']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" onerror="this.src='https://via.placeholder.com/150x80?text=Banner'">
                                </div>
                            </td>

                            <!-- Title & Target URL -->
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-sm font-bold text-gray-900 mb-0.5 truncate"><?= htmlspecialchars($ad['title']) ?></p>
                                <a href="<?= htmlspecialchars($ad['target_url'] ?: '#') ?>" target="_blank" class="text-xs text-primary hover:underline truncate block max-w-[220px]">
                                    🔗 <?= htmlspecialchars($ad['target_url'] ?: 'Mặc định (Trang chủ)') ?>
                                </a>
                            </td>

                            <!-- Position Badge -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php
                                $posLabels = [
                                    'hero' => ['Hero Trang Chủ', 'bg-blue-50 text-blue-700 border-blue-200'],
                                    'marquee' => ['Hot Trending', 'bg-red-50 text-red-600 border-red-200'],
                                    'popup' => ['Popup Ưu Đãi', 'bg-purple-50 text-purple-700 border-purple-200'],
                                    'sidebar' => ['Cột Sidebar', 'bg-teal-50 text-teal-700 border-teal-200'],
                                    'footer' => ['Chân Trang', 'bg-gray-100 text-gray-700 border-gray-200']
                                ];
                                $pos = $posLabels[$ad['position']] ?? ['Khác', 'bg-gray-100 text-gray-600 border-gray-200'];
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border <?= $pos[1] ?>">
                                    <?= $pos[0] ?>
                                </span>
                            </td>

                            <!-- Impressions -->
                            <td class="py-3.5 px-4 text-center text-xs font-bold text-gray-800">
                                <?= number_format($ad['impressions']) ?>
                            </td>

                            <!-- Clicks & CTR -->
                            <td class="py-3.5 px-4 text-center text-xs">
                                <span class="font-bold text-primary"><?= number_format($ad['clicks']) ?></span>
                                <?php 
                                $ctr = $ad['impressions'] > 0 ? round(($ad['clicks'] / $ad['impressions']) * 100, 1) : 0; 
                                ?>
                                <span class="text-[10px] text-gray-400 block font-semibold">(CTR: <?= $ctr ?>%)</span>
                            </td>

                            <!-- Date Range -->
                            <td class="py-3.5 px-4 text-center text-xs text-gray-500 whitespace-nowrap">
                                <div><?= $ad['start_date'] ? date('d/m/y', strtotime($ad['start_date'])) : 'Nay' ?></div>
                                <div class="text-gray-400">đến <?= $ad['end_date'] ? date('d/m/y', strtotime($ad['end_date'])) : 'Vô thời hạn' ?></div>
                            </td>

                            <!-- Status Toggle Button -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a href="index.php?controller=AdminAd&action=toggle&id=<?= $ad['id'] ?>" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold transition <?= $ad['is_active'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>" title="Nhấn để Bật/Tắt hiển thị">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= $ad['is_active'] ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                                    <?= $ad['is_active'] ? 'Đang chạy' : 'Tạm dừng' ?>
                                </a>
                            </td>

                            <!-- Delete Action -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a href="index.php?controller=AdminAd&action=delete&id=<?= $ad['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa banner này?')" class="text-gray-400 hover:text-red-500 p-1.5 inline-block transition" title="Xóa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 📢 MODAL THÊM BANNER / QUẢNG CÁO MỚI -->
<div id="adModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto animate-fade-down text-gray-800">
        <button onclick="closeAdModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            Tạo Banner Quảng Cáo Mới
        </h3>

        <form action="index.php?controller=AdminAd&action=store" method="POST" class="space-y-4">
            <!-- Campaign Title -->
            <div>
                <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Tên Chiến Dịch / Banner *</label>
                <input type="text" name="title" required placeholder="VD: Khuyến Mãi Mùa Đông Giảm 15%..." class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
            </div>

            <!-- Image URL -->
            <div>
                <label class="block text-xs font-bold text-gray-800 uppercase mb-1">URL Hình Ảnh Banner *</label>
                <input type="url" name="image_url" id="adImageUrlInput" onchange="previewAdImage()" required placeholder="https://images.unsplash.com/photo-..." class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                
                <!-- Image Preview Area -->
                <div id="adPreviewContainer" class="hidden mt-2 border border-gray-200 rounded-lg p-2 bg-gray-50 text-center">
                    <p class="text-[10px] text-gray-400 mb-1 uppercase font-bold">Xem trước ảnh:</p>
                    <img id="adPreviewImg" class="w-full h-32 object-cover rounded shadow-sm">
                </div>
            </div>

            <!-- Target URL & Position -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Vị Trí Hiển Thị</label>
                    <select name="position" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary bg-white">
                        <option value="hero">Hero Trang Chủ (Top)</option>
                        <option value="marquee">Hot Trending (Marquee)</option>
                        <option value="popup">Popup Ưu Đãi (Modal)</option>
                        <option value="sidebar">Thanh Sidebar</option>
                        <option value="footer">Chân Trang (Footer)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Link Chuyển Hướng</label>
                    <input type="text" name="target_url" placeholder="VD: index.php#shop" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Ngày Bắt Đầu</label>
                    <input type="date" name="start_date" value="<?= date('Y-m-d') ?>" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Ngày Kết Thúc</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeAdModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold uppercase hover:bg-gray-200 transition">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-primary-dark transition shadow-md">
                    Lưu Chiến Dịch
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdModal() {
        const m = document.getElementById('adModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeAdModal() {
        const m = document.getElementById('adModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function previewAdImage() {
        const url = document.getElementById('adImageUrlInput').value.trim();
        const container = document.getElementById('adPreviewContainer');
        const img = document.getElementById('adPreviewImg');
        if (url) {
            img.src = url;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
