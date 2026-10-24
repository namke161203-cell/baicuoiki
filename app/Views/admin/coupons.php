<?php 
/**
 * @var array $coupons
 * @var string $title
 */
require_once __DIR__ . '/header.php'; 
?>

<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Quản lý Mã Giảm Giá (Voucher)</h2>
        <p class="text-xs text-gray-500 mt-0.5">Tạo và kiểm soát các mã khuyến mãi, chiết khấu phần trăm hoặc trừ tiền trực tiếp.</p>
    </div>
    <button onclick="openCouponModal()" class="bg-primary hover:bg-primary-dark text-white font-bold py-2.5 px-5 rounded-lg shadow-sm transition text-xs uppercase tracking-wider flex items-center justify-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        + Thêm Mã Mới
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

<!-- Danh Sách Mã Giảm Giá -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 font-semibold border-b border-gray-100 text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Mã Voucher</th>
                    <th class="py-3.5 px-4">Mô Tả & Loại</th>
                    <th class="py-3.5 px-4 text-center">Mức Giảm</th>
                    <th class="py-3.5 px-4 text-center">Đơn Tối Thiểu</th>
                    <th class="py-3.5 px-4 text-center">Lượt Sử Dụng</th>
                    <th class="py-3.5 px-4 text-center">Hạn Sử Dụng</th>
                    <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                    <th class="py-3.5 px-4 text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            Chưa có mã giảm giá nào. Hãy bấm <b>"+ Thêm Mã Mới"</b> để tạo mã đầu tiên!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): ?>
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Code -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-teal-50 text-primary font-mono font-black text-xs rounded border border-teal-200 tracking-wider">
                                        <?= htmlspecialchars($c['code']) ?>
                                    </span>
                                    <button onclick="copyToClipboard('<?= htmlspecialchars($c['code']) ?>')" class="text-gray-400 hover:text-gray-600 transition p-1" title="Sao chép mã">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Description -->
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-xs font-semibold text-gray-800 truncate"><?= htmlspecialchars($c['description'] ?: 'Không có ghi chú') ?></p>
                                <span class="text-[10px] text-gray-400 uppercase font-medium">
                                    Loại: <?= $c['type'] === 'percent' ? 'Chiết khấu %' : 'Giảm tiền trực tiếp' ?>
                                </span>
                            </td>

                            <!-- Value -->
                            <td class="py-4 px-4 text-center font-black text-emerald-600">
                                <?= $c['type'] === 'percent' ? $c['value'] . '%' : number_format($c['value'], 0, ',', '.') . 'đ' ?>
                            </td>

                            <!-- Min Order -->
                            <td class="py-4 px-4 text-center text-xs text-gray-600 font-medium">
                                <?= $c['min_order_amount'] > 0 ? number_format($c['min_order_amount'], 0, ',', '.') . 'đ' : 'Không giới hạn' ?>
                            </td>

                            <!-- Usage -->
                            <td class="py-4 px-4 text-center text-xs">
                                <span class="font-bold text-gray-900"><?= $c['used_count'] ?></span>
                                <span class="text-gray-400">/ <?= $c['usage_limit'] ?></span>
                                <div class="w-16 bg-gray-200 h-1.5 rounded-full mx-auto mt-1 overflow-hidden">
                                    <?php $pct = $c['usage_limit'] > 0 ? min(100, round(($c['used_count'] / $c['usage_limit']) * 100)) : 0; ?>
                                    <div class="bg-primary h-full" style="width: <?= $pct ?>%"></div>
                                </div>
                            </td>

                            <!-- Expiry -->
                            <td class="py-4 px-4 text-center text-xs text-gray-500 whitespace-nowrap">
                                <?= $c['end_date'] ? date('d/m/Y', strtotime($c['end_date'])) : 'Vĩnh viễn' ?>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="index.php?controller=AdminCoupon&action=toggle&id=<?= $c['id'] ?>" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold transition <?= $c['is_active'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>" title="Nhấn để Bật/Tắt">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= $c['is_active'] ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                                    <?= $c['is_active'] ? 'Hoạt động' : 'Đang tạm dừng' ?>
                                </a>
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="index.php?controller=AdminCoupon&action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')" class="text-gray-400 hover:text-red-500 p-1.5 inline-block transition" title="Xóa">
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

<!-- 🎟️ MODAL THÊM MÃ GIẢM GIÁ MỚI -->
<div id="couponModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto animate-fade-down text-gray-800">
        <button onclick="closeCouponModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            Tạo Mã Giảm Giá Mới
        </h3>

        <form action="index.php?controller=AdminCoupon&action=store" method="POST" class="space-y-4">
            <!-- Code Generator -->
            <div>
                <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Mã Giảm Giá (Code) *</label>
                <div class="flex gap-2">
                    <input type="text" id="couponCodeInput" name="code" required uppercase placeholder="VD: SALE20K, TET2026..." class="flex-1 border border-gray-300 p-2.5 rounded-lg text-sm font-mono font-bold uppercase tracking-wider focus:outline-none focus:border-primary">
                    <button type="button" onclick="generateRandomCode()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs font-bold transition flex items-center" title="Tạo mã ngẫu nhiên">
                        🎲 Random
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Mô Tả Mã Khuyến Mãi</label>
                <input type="text" name="description" placeholder="VD: Giảm 15% cho đơn hàng mùa đông từ 500k" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
            </div>

            <!-- Type & Value -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Loại Giảm Giá *</label>
                    <select name="type" id="couponTypeSelect" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary bg-white">
                        <option value="percent">Phần trăm (%)</option>
                        <option value="fixed">Số tiền cố định (VNĐ)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Mức Giảm *</label>
                    <input type="number" name="value" required min="1" step="any" placeholder="VD: 10 hoặc 50000" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm font-bold text-primary focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Min Order & Max Discount -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Đơn Tối Thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" value="0" min="0" placeholder="0 = Không giới hạn" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Giảm Tối Đa (VNĐ)</label>
                    <input type="number" name="max_discount_amount" min="0" placeholder="Để trống nếu không giới hạn" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Limits & Expiry -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Số Lượng Sử Dụng</label>
                    <input type="number" name="usage_limit" value="100" min="1" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Ngày Hết Hạn</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeCouponModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold uppercase hover:bg-gray-200 transition">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-primary-dark transition shadow-md">
                    Lưu Mã Khuyến Mãi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCouponModal() {
        const m = document.getElementById('couponModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeCouponModal() {
        const m = document.getElementById('couponModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function generateRandomCode() {
        const prefixes = ['HQUIE', 'VIBE', 'SALE', 'WINTER', 'VIP'];
        const randomPrefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('couponCodeInput').value = `${randomPrefix}${randomNum}`;
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert(`Đã sao chép mã "${text}" vào bộ nhớ tạm!`);
        });
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
