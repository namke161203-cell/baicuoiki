<?php 
/**
 * @var array $report
 * @var string $title
 */
require_once __DIR__ . '/header.php'; 
?>

<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Báo Cáo Doanh Thu & Thống Kê</h2>
        <p class="text-xs text-gray-500 mt-0.5">Phân tích hiệu suất bán hàng, cơ cấu doanh thu theo ngành hàng và tồn kho sản phẩm.</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="index.php?controller=AdminReport&action=export" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-sm transition text-xs uppercase tracking-wider flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Xuất File Excel (CSV)
        </a>
    </div>
</div>

<!-- 4 Thẻ KPI Tăng Trưởng -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- KPI 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tổng Doanh Thu</p>
            <p class="text-2xl font-black text-gray-900 tracking-tight">đ<?= number_format($report['total_revenue'], 0, ',', '.') ?></p>
            <span class="text-[11px] text-emerald-600 font-bold flex items-center mt-1">
                <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                +14.8% so với kỳ trước
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- KPI 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Đơn Hàng Thành Công</p>
            <p class="text-2xl font-black text-gray-900 tracking-tight"><?= $report['total_orders'] ?></p>
            <span class="text-[11px] text-blue-600 font-bold flex items-center mt-1">
                <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Tỷ lệ hoàn thành 94.2%
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
    </div>

    <!-- KPI 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Giá Trị Đơn TB (AOV)</p>
            <p class="text-2xl font-black text-gray-900 tracking-tight">đ<?= number_format($report['aov'], 0, ',', '.') ?></p>
            <span class="text-[11px] text-teal-600 font-bold flex items-center mt-1">
                Trung bình mỗi giỏ hàng
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        </div>
    </div>

    <!-- KPI 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tổng Khách Hàng</p>
            <p class="text-2xl font-black text-gray-900 tracking-tight"><?= $report['total_customers'] ?></p>
            <span class="text-[11px] text-purple-600 font-bold flex items-center mt-1">
                Tài khoản đăng ký
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>
</div>

<!-- Layout 2 Cột Biểu Đồ & Cơ Cấu Danh Mục -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Cột 1 & 2: Biểu đồ đường doanh thu -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide flex items-center">
                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                Biểu Đồ Doanh Thu 6 Tháng Gần Nhất (VNĐ)
            </h3>
            <span class="text-xs bg-teal-50 text-primary font-bold px-3 py-1 rounded-full border border-teal-200">Năm 2026</span>
        </div>
        
        <div class="h-64 w-full bg-gradient-to-b from-teal-50/40 to-white rounded-lg border border-teal-100/60 p-4 relative flex items-end justify-between gap-4">
            <!-- Mock Bar/Column Chart with Tailwind -->
            <?php 
            $months = [
                ['name' => 'Tháng 5', 'val' => 1250000, 'pct' => 45],
                ['name' => 'Tháng 6', 'val' => 1890000, 'pct' => 60],
                ['name' => 'Tháng 7', 'val' => 2100000, 'pct' => 70],
                ['name' => 'Tháng 8', 'val' => 1650000, 'pct' => 55],
                ['name' => 'Tháng 9', 'val' => 2450000, 'pct' => 82],
                ['name' => 'Tháng 10 (Hiện tại)', 'val' => 3120000, 'pct' => 95],
            ];
            foreach ($months as $m):
            ?>
            <div class="flex-1 flex flex-col items-center h-full justify-end group">
                <span class="text-[10px] font-bold text-primary mb-1 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                    <?= number_format($m['val'], 0, ',', '.') ?>đ
                </span>
                <div class="w-full max-w-[45px] bg-primary group-hover:bg-primary-dark rounded-t-md transition-all duration-500 shadow-sm" style="height: <?= $m['pct'] ?>%"></div>
                <span class="text-[11px] font-bold text-gray-600 mt-2 truncate max-w-full"><?= $m['name'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Cột 3: Tỷ Trọng Danh Mục Sản Phẩm -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">
                Doanh Thu Theo Danh Mục
            </h3>
            <p class="text-xs text-gray-500 mb-6">Tỷ lệ đóng góp doanh thu của từng ngành hàng trong cửa hàng:</p>

            <div class="space-y-4">
                <?php if (empty($report['revenue_by_category'])): ?>
                    <p class="text-xs text-gray-400 text-center py-6">Chưa có số liệu danh mục.</p>
                <?php else: ?>
                    <?php 
                    $catColors = ['bg-primary', 'bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-purple-500'];
                    foreach ($report['revenue_by_category'] as $idx => $cat): 
                        $color = $catColors[$idx % count($catColors)];
                    ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-800 mb-1">
                            <span><?= htmlspecialchars($cat['category_name'] ?: 'Khác') ?></span>
                            <span class="text-primary font-black"><?= number_format($cat['category_revenue'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <?php 
                            $ratio = $report['total_revenue'] > 0 ? min(100, round(($cat['category_revenue'] / $report['total_revenue']) * 100)) : 25;
                            ?>
                            <div class="<?= $color ?> h-full" style="width: <?= max(5, $ratio) ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="index.php?controller=AdminProduct&action=index" class="text-xs text-primary font-bold hover:underline block text-center uppercase tracking-wider">
                Xem chi tiết danh mục sản phẩm →
            </a>
        </div>
    </div>
</div>

<!-- Bảng Hiệu Quả Từng Sản Phẩm -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hiệu Quả Kinh Doanh Từng Sản Phẩm</h3>
            <p class="text-xs text-gray-500 mt-0.5">Top sản phẩm mang lại doanh thu cao nhất cho cửa hàng hquie.</p>
        </div>
        <a href="index.php?controller=AdminReport&action=export" class="text-xs font-bold text-emerald-600 hover:underline flex items-center uppercase">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Tải bảng này (.csv)
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 font-semibold border-b border-gray-100 text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Tên Sản Phẩm</th>
                    <th class="py-3.5 px-4 text-center">Đơn Giá</th>
                    <th class="py-3.5 px-4 text-center">Số Lượng Đã Bán</th>
                    <th class="py-3.5 px-4 text-right">Tổng Doanh Thu</th>
                    <th class="py-3.5 px-4 text-center">Tồn Kho</th>
                    <th class="py-3.5 px-4 text-center">Tình Trạng</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                <?php if (empty($report['product_performance'])): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Chưa có dữ liệu giao dịch sản phẩm.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['product_performance'] as $p): ?>
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-4 px-4 font-bold text-gray-900">
                            <?= htmlspecialchars($p['name']) ?>
                        </td>
                        <td class="py-4 px-4 text-center font-medium text-gray-600">
                            <?= number_format($p['price'], 0, ',', '.') ?>đ
                        </td>
                        <td class="py-4 px-4 text-center font-bold text-gray-900">
                            <?= $p['sold_count'] ?> cái
                        </td>
                        <td class="py-4 px-4 text-right font-black text-primary">
                            <?= number_format($p['total_revenue'], 0, ',', '.') ?>đ
                        </td>
                        <td class="py-4 px-4 text-center text-xs font-semibold">
                            <?= $p['current_stock'] ?>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <?php if ($p['current_stock'] == 0): ?>
                                <span class="px-2.5 py-1 bg-red-100 text-red-600 rounded-full text-[11px] font-bold">Hết hàng</span>
                            <?php elseif ($p['current_stock'] <= 5): ?>
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[11px] font-bold">Sắp hết</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[11px] font-bold">Sẵn hàng</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
