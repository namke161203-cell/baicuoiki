<?php
/**
 * @var array $stats
 * @var array $latestOrders
 * @var array $bestSellers
 * @var array $products
 * @var array $categories
 * @var array $orders
 * @var array $customers
 * @var array $coupons
 * @var array $report
 * @var array $ads
 * @var string $activeTab
 */
require_once __DIR__ . '/header.php';
?>

<!-- 📌 SUB-BAR: BREADCRUMBS & LIVE STATUS -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="flex items-center space-x-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">
            <span>hquie Atelier</span>
            <span>&rsaquo;</span>
            <span id="breadcrumb-current-tab" class="text-gray-800">Tổng quan hệ thống</span>
        </div>
        <h1 id="page-current-title" class="text-3xl font-black text-gray-900 tracking-tight">
            Tổng quan hệ thống
        </h1>
    </div>

    <!-- Live Clock & Status (Cadra Inspired with Real Order Counts) -->
    <?php
    $exec = $executiveStats ?? [];
    $totalCash = (float)($exec['total_cash'] ?? $orderStats['total_revenue'] ?? 0);
    $monthRev = (float)($exec['month_revenue'] ?? 0);
    $monthOrders = (int)($exec['month_orders'] ?? 0);
    $rev14d = (float)($exec['revenue_14d'] ?? 0);
    $completedOrders = (int)($exec['completed_count'] ?? $orderStats['count_completed'] ?? 0);
    $openOrders = (int)($exec['open_count'] ?? (($orderStats['count_pending'] ?? 0) + ($orderStats['count_processing'] ?? 0)));
    $openTotal = (float)($exec['open_total'] ?? 0);
    $compRate = (int)($exec['completion_rate'] ?? 100);
    $funnel = $exec['funnel'] ?? [
        'pending' => ['count' => 0, 'total' => 0],
        'processing' => ['count' => 0, 'total' => 0],
        'shipped' => ['count' => 0, 'total' => 0],
        'completed' => ['count' => 0, 'total' => 0],
        'cancelled' => ['count' => 0, 'total' => 0]
    ];
    $monthlyRev = $exec['monthly_revenue'] ?? [];
    $cashflow = $exec['cashflow'] ?? [];

    $adminName = !empty($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Quản trị viên';
    $hour = (int)date('G');
    if ($hour < 12) {
        $timeGreeting = 'Chào buổi sáng';
    } elseif ($hour < 18) {
        $timeGreeting = 'Chào buổi chiều';
    } else {
        $timeGreeting = 'Chào buổi tối';
    }

    $daysOfWeekVi = [
        'Sunday' => 'Chủ Nhật',
        'Monday' => 'Thứ Hai',
        'Tuesday' => 'Thứ Ba',
        'Wednesday' => 'Thứ Tư',
        'Thursday' => 'Thứ Năm',
        'Friday' => 'Thứ Sáu',
        'Saturday' => 'Thứ Bảy'
    ];
    $dayVi = $daysOfWeekVi[date('l')] ?? date('l');
    $todayDateStr = mb_strtoupper("{$dayVi}, NGÀY " . date('d/m/Y'), 'UTF-8');

    $pendingCount = (int)($orderStats['count_pending'] ?? 0);
    $processingCount = (int)($orderStats['count_processing'] ?? 0);
    $shippedCount = (int)($orderStats['count_shipped'] ?? 0);
    $completedCount = (int)($orderStats['count_completed'] ?? 0);
    $totalOrderCount = (int)($orderStats['total_orders'] ?? 0);
    ?>
    <div class="flex items-center space-x-4 bg-white px-4 py-2 rounded-full border border-gray-200/80 shadow-sm text-xs font-semibold text-gray-700">
        <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 live-dot"></span>
            <span id="liveClock" class="font-bold text-gray-900 tracking-wider"><?= date('h:i A') ?></span>
        </div>
        <div class="h-4 w-px bg-gray-200"></div>
        <div class="flex items-center space-x-2 text-[11px] font-bold">
            <span class="text-gray-900"><?= $totalOrderCount ?> đơn tổng</span>
            <span class="text-gray-400">&bull;</span>
            <span class="text-emerald-600"><?= $processingCount ?> đang xử lý</span>
            <span class="text-gray-400">&bull;</span>
            <span class="text-orange-500"><?= $pendingCount ?> cần duyệt</span>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🚀 TAB 1: EXECUTIVE DASHBOARD (BENTO GRID - CADRA LARKSPUR) -->
<!-- ============================================================== -->
<div id="tab-dashboard" class="tab-pane active-tab space-y-6">

    <!-- 🌟 TOP ROW: HERO GREETING CARD + CASH ON HAND -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Hero Greeting Bento Card (Col-span 2) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Date & Header -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-widest text-orange-600">
                        <?= $todayDateStr ?>
                    </span>
                    <!-- Action Pills -->
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="openScheduleModal()" class="px-4 py-1.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center shadow-sm cursor-pointer tracking-wider">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Lịch trình ca làm
                        </button>
                        <button type="button" onclick="openPipelineModal()" class="px-4 py-1.5 rounded-full border border-gray-300 hover:bg-gray-50 text-gray-800 text-xs font-bold transition flex items-center cursor-pointer tracking-wider">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Quy trình vận đơn
                        </button>
                    </div>
                </div>

                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mb-2">
                    <?= $timeGreeting ?>, <?= $adminName ?>.
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 font-medium leading-relaxed max-w-2xl">
                    <?php if ($totalOrderCount > 0): ?>
                        Hệ thống đang vận hành với <?= $processingCount ?> đơn hàng đang xử lý tại xưởng, <?= $shippedCount ?> đơn đang trên đường giao và <?= $pendingCount ?> đơn mới cần kiểm duyệt.
                    <?php else: ?>
                        Hệ thống sẵn sàng tiếp nhận đơn hàng mới. Hiện có <?= count($products ?? []) ?> sản phẩm đang được phân phối trên cửa hàng.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Timeline Tracker Line (Cadra Style with Interactive Tooltips) -->
            <div class="mt-8 pt-4 border-t border-gray-100">
                <div class="relative py-4">
                    <!-- Base Track -->
                    <div class="h-1.5 bg-gray-100 rounded-full w-full relative">
                        <!-- Progress Track -->
                        <?php
                        $currentHourFraction = max(0.1, min(1, ($hour + (int)date('i') / 60 - 7) / 11));
                        $timelinePercent = round($currentHourFraction * 100);
                        ?>
                        <div class="h-1.5 bg-gradient-to-r from-teal-400 via-teal-500 to-emerald-500 rounded-full" style="width: <?= max(15, min(95, $timelinePercent)) ?>%;"></div>
                    </div>

                    <!-- Event Dots on Timeline with Interactive Tooltips -->
                    <div class="absolute top-2.5 left-0 right-0 flex justify-between px-2">
                        <!-- 07:00 -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-teal-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-wider">07:00</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                07:00 &bull; Mở kho & kiểm tra chất lượng ca sáng
                            </div>
                        </div>

                        <!-- 09:00 -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-blue-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-wider">09:00</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                09:15 &bull; Đóng gói <?= $processingCount ?> đơn hàng ca sáng
                            </div>
                        </div>

                        <!-- 11:00 -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-amber-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-wider">11:00</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                11:30 &bull; Duyệt <?= $pendingCount ?> đơn hàng mới & đối soát
                            </div>
                        </div>

                        <!-- 13:00 -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-purple-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-wider">13:00</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                13:00 &bull; Kiểm kê kho vải & cập nhật biến thể
                            </div>
                        </div>

                        <!-- Current Position Marker -->
                        <div class="flex flex-col items-center relative -top-3.5 group cursor-pointer">
                            <span class="px-2 py-0.5 rounded-full bg-orange-600 text-white font-black text-[9px] shadow-sm mb-0.5 animate-pulse tracking-wider"><?= date('H:i') ?></span>
                            <div class="w-4 h-4 rounded-full bg-orange-600 border-2 border-white shadow-md ring-2 ring-orange-200"></div>
                            <span class="text-[10px] font-black text-gray-900 mt-1 tracking-wider"><?= date('H:i') ?></span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-12 left-1/2 -translate-x-1/2 bg-orange-600 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                Hiện tại &bull; <?= $totalOrderCount ?> đơn trong hệ thống (Doanh thu <?= number_format($totalCash, 0, ',', '.') ?>đ)
                            </div>
                        </div>

                        <!-- 17:00 -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-wider">17:00</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                17:00 &bull; Đối soát doanh thu cuối ngày
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash on Hand Card (Col-span 1) -->
        <div class="bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Quỹ Tiền Mặt & Doanh Thu
                    </span>
                    <button type="button" onclick="switchCadraTab('orders')" class="text-xs font-bold text-gray-400 hover:text-gray-900 underline cursor-pointer tracking-wider">Thanh toán</button>
                </div>

                <div class="flex items-baseline space-x-3 mb-4">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">đ<?= number_format($totalCash, 0, ',', '.') ?></span>
                    <span class="text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200 whitespace-nowrap tracking-wide">
                        +<?= number_format($monthRev, 0, ',', '.') ?>đ Tháng <?= date('m') ?>
                    </span>
                </div>

                <!-- Mini Waterfall/Bar Chart (Cadra Style based on Real Volume) -->
                <?php
                $defaultBars = [50, 75, 40, 30, 85, 65, 45, 90, 100];
                if (!empty($cashflow)) {
                    $maxAmount = max(1, max(array_column($cashflow, 'amount')));
                    $barHeights = [];
                    foreach (array_slice($cashflow, 0, 9) as $cf) {
                        $barHeights[] = max(25, min(100, round(($cf['amount'] / $maxAmount) * 100)));
                    }
                    while (count($barHeights) < 9) {
                        $barHeights[] = 30;
                    }
                } else {
                    $barHeights = $defaultBars;
                }
                ?>
                <div class="h-20 flex items-end justify-between gap-1.5 py-2">
                    <?php foreach ($barHeights as $idx => $bh): ?>
                        <?php if ($idx === 3 || $idx === 6): ?>
                            <div class="w-3 bg-rose-400 rounded-b self-start mt-8" style="height: <?= round($bh * 0.4) ?>%;" title="Chi phí vận hành"></div>
                        <?php else: ?>
                            <div class="w-3 bg-emerald-500 hover:bg-emerald-600 transition rounded-t" style="height: <?= $bh ?>%;" title="Doanh thu thực tế"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex items-center space-x-4 pt-3 border-t border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Thu vào</span>
                <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5"></span> Chi ra</span>
            </div>
        </div>
    </div>

    <!-- 🌟 ROW 2: 4 METRIC CARDS WITH SPARKLINE (BENTO GRID CADRA) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Revenue this month (Dark Card) -->
        <div onclick="switchCadraTab('reports')" class="cursor-pointer bg-gray-950 text-white rounded-3xl p-6 border border-gray-800 shadow-md flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest group-hover:text-teal-400 transition">Doanh thu tháng này</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-white">đ<?= number_format($monthRev, 0, ',', '.') ?></span>
                    <!-- Sparkline SVG (Glowing Line) -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,30 Q25,5 50,25 T100,5" fill="none" stroke="#2dd4bf" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 font-medium mt-4 pt-3 border-t border-gray-800 flex items-center justify-between">
                <span>↗ <?= $monthOrders ?> đơn phát sinh &bull; Tháng <?= date('m/Y') ?></span>
                <span class="text-teal-400 text-[10px] font-bold group-hover:underline uppercase tracking-wider">Báo cáo &rarr;</span>
            </p>
        </div>

        <!-- Card 2: Orders completed (Pastel Blue Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-[#dbeafe] text-blue-950 rounded-3xl p-6 border border-blue-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-blue-800 uppercase tracking-widest group-hover:text-blue-950 transition">Đơn hàng hoàn thành</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-blue-950"><?= $completedOrders ?></span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,20 Q20,35 40,10 T70,30 T100,5" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-blue-700 font-bold mt-4 pt-3 border-t border-blue-200 flex items-center justify-between">
                <span>↗ <?= $completedOrders ?> đơn giao thành công</span>
                <span class="text-blue-900 text-[10px] font-bold group-hover:underline uppercase tracking-wider">Đơn hàng &rarr;</span>
            </p>
        </div>

        <!-- Card 3: Open order book (Clean White Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-white rounded-3xl p-6 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest group-hover:text-gray-900 transition">Giá trị đơn đang xử lý</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-gray-900">đ<?= number_format($openTotal, 0, ',', '.') ?></span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,25 Q30,5 60,30 T100,10" fill="none" stroke="#0f766e" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 font-medium mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span>&bull; <?= $openOrders ?> đơn đang chờ duyệt hoặc đóng gói</span>
                <span class="text-teal-600 text-[10px] font-bold group-hover:underline uppercase tracking-wider">Chi tiết &rarr;</span>
            </p>
        </div>

        <!-- Card 4: On-time delivery (Clean White Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-white rounded-3xl p-6 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest group-hover:text-gray-900 transition">Tỉ lệ giao đúng hạn</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-gray-900"><?= $compRate ?>%</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,15 Q30,25 60,10 T100,5" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-emerald-700 font-bold mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span>&bull; Tỉ lệ giao hàng hoàn tất đạt chuẩn</span>
                <span class="text-gray-600 text-[10px] font-bold group-hover:underline uppercase tracking-wider">Xem KPI &rarr;</span>
            </p>
        </div>
    </div>

    <!-- 🌟 ROW 3: YEAR AT A GLANCE + RECENT OPERATIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Year at a glance Chart (Col-span 2) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Tổng quan doanh thu năm</h3>
                    <p class="text-xs text-gray-400">Doanh thu và hiệu suất vận hành theo chu kỳ</p>
                </div>
                <div class="flex items-center space-x-2 bg-gray-100 p-1 rounded-full text-xs font-bold text-gray-600">
                    <button type="button" id="btn-chart-rev" onclick="toggleYearChart('rev')" class="px-3 py-1 bg-white text-gray-900 rounded-full shadow-sm transition cursor-pointer">Doanh thu & Chi phí</button>
                    <button type="button" id="btn-chart-units" onclick="toggleYearChart('units')" class="px-3 py-1 text-gray-500 hover:text-gray-900 rounded-full transition cursor-pointer">Số lượng bán</button>
                </div>
            </div>

            <!-- Curved Area Chart SVG with Real Data Points -->
            <div class="h-64 w-full relative">
                <svg viewBox="0 0 800 240" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#2dd4bf;stop-opacity:0.35" />
                            <stop offset="100%" style="stop-color:#2dd4bf;stop-opacity:0" />
                        </linearGradient>
                        <linearGradient id="grad2" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.25" />
                            <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0" />
                        </linearGradient>
                    </defs>
                    <path d="M0,180 C150,140 250,60 400,100 C550,140 650,20 800,70 L800,240 L0,240 Z" fill="url(#grad1)" />
                    <path d="M0,180 C150,140 250,60 400,100 C550,140 650,20 800,70" fill="none" stroke="#0f766e" stroke-width="3" />

                    <path d="M0,200 C150,180 300,120 450,140 C600,160 700,90 800,110 L800,240 L0,240 Z" fill="url(#grad2)" />
                    <path d="M0,200 C150,180 300,120 450,140 C600,160 700,90 800,110" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-dasharray="4" />
                </svg>
                <div class="flex justify-between text-[11px] font-bold text-gray-400 mt-2 px-2 uppercase tracking-widest">
                    <?php if (!empty($monthlyRev)): ?>
                        <?php foreach ($monthlyRev as $mr): ?>
                            <span>Tháng <?= htmlspecialchars($mr['label']) ?></span>
                        <?php endforeach; ?>
                        <?php if (count($monthlyRev) < 6): ?>
                            <span>Tháng 11</span><span>Tháng 12</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span>Tháng 1</span><span>Tháng 3</span><span>Tháng 5</span><span>Tháng 7</span><span>Tháng 9</span><span>Tháng 11</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sales Funnel / Real Stages (Col-span 1) -->
        <div class="bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Phễu Tiến Trình Đơn Hàng</h3>
                <div class="space-y-4">
                    <!-- Stage 1: Pending -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span class="tracking-wide">Tiếp nhận & Chờ duyệt</span>
                            <span><?= $funnel['pending']['count'] ?> đơn &bull; <?= number_format($funnel['pending']['total'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <?php $pW = $totalOrderCount > 0 ? max(12, round(($funnel['pending']['count'] / $totalOrderCount) * 100)) : 15; ?>
                            <div class="bg-teal-500 h-full rounded-full transition-all duration-500" style="width: <?= $pW ?>%"></div>
                        </div>
                    </div>

                    <!-- Stage 2: Processing -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span class="tracking-wide">Đã xác nhận & Đóng gói</span>
                            <span><?= $funnel['processing']['count'] ?> đơn &bull; <?= number_format($funnel['processing']['total'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <?php $prW = $totalOrderCount > 0 ? max(12, round(($funnel['processing']['count'] / $totalOrderCount) * 100)) : 20; ?>
                            <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: <?= $prW ?>%"></div>
                        </div>
                    </div>

                    <!-- Stage 3: Shipped -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span class="tracking-wide">Đang vận chuyển giao hàng</span>
                            <span><?= $funnel['shipped']['count'] ?> đơn &bull; <?= number_format($funnel['shipped']['total'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <?php $sW = $totalOrderCount > 0 ? max(12, round(($funnel['shipped']['count'] / $totalOrderCount) * 100)) : 15; ?>
                            <div class="bg-purple-600 h-full rounded-full transition-all duration-500" style="width: <?= $sW ?>%"></div>
                        </div>
                    </div>

                    <!-- Stage 4: Completed -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span class="tracking-wide">Giao thành công & Đã nhận</span>
                            <span><?= $funnel['completed']['count'] ?> đơn &bull; <?= number_format($funnel['completed']['total'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <?php $cW = $totalOrderCount > 0 ? max(12, round(($funnel['completed']['count'] / $totalOrderCount) * 100)) : 25; ?>
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: <?= $cW ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                <button type="button" onclick="switchCadraTab('orders')" class="text-xs font-bold text-teal-700 hover:underline uppercase tracking-wider cursor-pointer">Xem danh sách đơn &rarr;</button>
                <button type="button" onclick="switchCadraTab('reports')" class="text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider cursor-pointer">Chi tiết phễu bán</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🛍️ TAB 2: QUẢN LÝ SẢN PHẨM & KHO HÀNG (PROFESSIONAL SUITE) -->
<!-- ============================================================== -->
<?php
$productKPIs = $productKPIs ?? [
    'total_products' => count($products),
    'featured_products' => count(array_filter($products, fn($p) => !empty($p['is_featured']))),
    'low_stock' => count(array_filter($products, fn($p) => ($p['total_stock'] ?? 0) > 0 && ($p['total_stock'] ?? 0) <= 10)),
    'out_of_stock' => count(array_filter($products, fn($p) => ($p['total_stock'] ?? 0) == 0)),
    'inventory_value' => array_sum(array_map(fn($p) => $p['price'] * ($p['total_stock'] ?? 0), $products))
];
?>
<div id="tab-products" class="tab-pane hidden-tab space-y-6">
    
    <!-- Top Bar: Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Kho Hàng & Sản Phẩm</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-gray-100 text-gray-800"><?= count($products) ?> sản phẩm</span>
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
                        
                        <!-- ID -->
                        <td class="py-3.5 text-center text-xs font-bold text-gray-400">
                            #<?= $p['id'] ?>
                        </td>

                        <!-- Thumbnail -->
                        <td class="py-3.5">
                            <div class="w-12 h-14 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 relative group flex-shrink-0">
                                <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/100') ?>" 
                                     alt="<?= htmlspecialchars($p['name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                        </td>

                        <!-- Product Name & Variants Pill -->
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

                        <!-- Category -->
                        <td class="py-3.5 text-xs text-gray-600 font-bold whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full bg-stone-100 text-gray-800">
                                <?= htmlspecialchars($catName) ?>
                            </span>
                        </td>

                        <!-- Price -->
                        <td class="py-3.5 text-right font-black text-gray-900 whitespace-nowrap">
                            <?= number_format($p['price'], 0, ',', '.') ?>đ
                        </td>

                        <!-- Stock Level with Badge & Clickable Manager -->
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

                        <!-- Units Sold -->
                        <td class="py-3.5 text-center font-bold text-xs text-gray-700">
                            <?= (int)($p['total_sold'] ?? 0) ?>
                        </td>

                        <!-- Toggle Featured Star -->
                        <td class="py-3.5 text-center">
                            <button type="button" onclick="toggleProductFeatured(<?= $p['id'] ?>, this)" class="p-1.5 rounded-full hover:bg-gray-100 transition cursor-pointer" title="Bật/Tắt hiển thị nổi bật">
                                <svg class="w-5 h-5 <?= $isFeatured ? 'text-amber-500 fill-amber-500' : 'text-gray-300' ?>" fill="<?= $isFeatured ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </button>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1.5">
                                <!-- Preview Store -->
                                <a href="index.php?controller=Product&action=detail&id=<?= $p['id'] ?>" target="_blank" class="p-1.5 text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition" title="Xem trên cửa hàng">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                <!-- Quick Edit -->
                                <button type="button" onclick="openQuickEditProductById(<?= $p['id'] ?>)" class="px-2.5 py-1 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition cursor-pointer">
                                    Sửa
                                </button>

                                <!-- Delete -->
                                <a href="index.php?controller=AdminProduct&action=delete&id=<?= $p['id'] ?>&redirect_tab=products" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Thao tác không thể hoàn tác.');" class="p-1.5 text-red-500 hover:text-red-700 rounded-lg hover:bg-red-50 transition" title="Xóa sản phẩm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State in filter -->
        <div id="adminProdEmptyState" class="hidden text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <p class="text-sm font-bold text-gray-800">Không tìm thấy sản phẩm phù hợp</p>
            <p class="text-xs text-gray-400 mt-1">Hãy thử tìm với từ khóa khác hoặc điều chỉnh lại bộ lọc.</p>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 📦 TAB 3: QUẢN LÝ ĐƠN HÀNG & VẬN CHUYỂN (ENTERPRISE FULFILLMENT) -->
<!-- ============================================================== -->
<?php
$orderStats = $orderStats ?? [
    'total_orders' => count($orders),
    'total_revenue' => array_sum(array_map(fn($o) => $o['status'] !== 'cancelled' ? $o['total_amount'] : 0, $orders)),
    'count_pending' => count(array_filter($orders, fn($o) => $o['status'] === 'pending')),
    'count_processing' => count(array_filter($orders, fn($o) => $o['status'] === 'processing')),
    'count_shipped' => count(array_filter($orders, fn($o) => $o['status'] === 'shipped')),
    'count_completed' => count(array_filter($orders, fn($o) => $o['status'] === 'completed')),
    'count_cancelled' => count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'))
];
?>
<div id="tab-orders" class="tab-pane hidden-tab space-y-6">
    
    <!-- Top Bar: Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Quản Lý Đơn Hàng & Vận Chuyển</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-orange-100 text-orange-800"><?= count($orders) ?> đơn hàng</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Theo dõi hành trình đơn, xác nhận bưu tá, đối soát doanh thu COD và in phiếu xuất kho chuẩn hóa đơn.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="index.php?controller=AdminOrder&action=exportCSV" class="px-4 py-2.5 rounded-full border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold transition flex items-center shadow-sm">
                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Xuất File Đơn (CSV)
            </a>
        </div>
    </div>

    <!-- 5 Pipeline Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <!-- 1. Total Revenue -->
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Tổng Doanh Thu</span>
            <span class="text-lg font-black text-gray-900"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?>đ</span>
            <span class="text-[10px] text-gray-400 block mt-0.5 font-semibold"><?= count($orders) ?> đơn phát sinh</span>
        </div>

        <!-- 2. Pending -->
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-amber-400 transition" onclick="filterAdminOrdersTab('pending')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-600">Chờ Duyệt</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <span class="text-xl font-black text-amber-600"><?= $orderStats['count_pending'] ?></span>
            <span class="text-[10px] text-amber-600 font-semibold block mt-0.5">Cần xử lý ngay</span>
        </div>

        <!-- 3. Processing -->
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-blue-400 transition" onclick="filterAdminOrdersTab('processing')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">Đang Đóng Gói</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <span class="text-xl font-black text-blue-600"><?= $orderStats['count_processing'] ?></span>
            <span class="text-[10px] text-blue-600 font-semibold block mt-0.5">Chuẩn bị kiện</span>
        </div>

        <!-- 4. Shipped -->
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-purple-400 transition" onclick="filterAdminOrdersTab('shipped')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-purple-600">Đang Giao Hàng</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <span class="text-xl font-black text-purple-600"><?= $orderStats['count_shipped'] ?></span>
            <span class="text-[10px] text-purple-600 font-semibold block mt-0.5">Đã bàn giao ship</span>
        </div>

        <!-- 5. Completed -->
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-emerald-400 transition" onclick="filterAdminOrdersTab('completed')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Hoàn Thành</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <span class="text-xl font-black text-emerald-600"><?= $orderStats['count_completed'] ?></span>
            <span class="text-[10px] text-emerald-600 font-semibold block mt-0.5">Giao thành công</span>
        </div>
    </div>

    <!-- Status Tabs Filter & Live Search Toolbar -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Status Tabs (Pills) -->
        <div class="flex items-center space-x-1 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
            <button type="button" onclick="filterAdminOrdersTab('all', this)" class="admin-order-status-tab active px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-gray-900 text-white shadow-sm cursor-pointer">
                Tất cả (<?= count($orders) ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('pending', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Chờ duyệt (<?= $orderStats['count_pending'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('processing', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đang đóng gói (<?= $orderStats['count_processing'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('shipped', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đang giao (<?= $orderStats['count_shipped'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('completed', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Hoàn thành (<?= $orderStats['count_completed'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('cancelled', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đã hủy (<?= $orderStats['count_cancelled'] ?>)
            </button>
        </div>

        <!-- Search Order Input -->
        <div class="relative w-full lg:w-80">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="adminOrderSearch" oninput="filterAdminOrdersList()" placeholder="Tìm theo mã đơn #000..., tên, SĐT..." class="w-full pl-10 pr-4 py-2 bg-gray-50 rounded-full border border-gray-200 text-xs font-semibold focus:outline-none focus:bg-white focus:border-gray-900 transition">
        </div>
    </div>

    <!-- Orders Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="adminOrdersTable">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4">Mã Đơn</th>
                        <th class="pb-4">Khách Hàng</th>
                        <th class="pb-4 min-w-[200px]">Chi Tiết Sản Phẩm</th>
                        <th class="pb-4">Thời Gian Đặt</th>
                        <th class="pb-4 text-right">Tổng Tiền</th>
                        <th class="pb-4 text-center">Trạng Thái</th>
                        <th class="pb-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="adminOrdersTbody">
                    <?php foreach ($orders as $ord): 
                        $status = strtolower($ord['status']);
                        $items = $ord['items'] ?? [];
                        $firstItem = $items[0] ?? null;
                        $otherCount = count($items) - 1;
                    ?>
                    <tr class="admin-order-row hover:bg-gray-50/70 transition"
                        data-id="#<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?>"
                        data-customer="<?= strtolower(htmlspecialchars($ord['customer_name'])) ?>"
                        data-phone="<?= htmlspecialchars($ord['customer_phone']) ?>"
                        data-status="<?= $status ?>">
                        
                        <!-- Order ID -->
                        <td class="py-4 font-black text-gray-900 whitespace-nowrap">
                            <button type="button" onclick="openOrderDetailById(<?= $ord['id'] ?>)" class="hover:text-teal-600 transition underline underline-offset-4 decoration-gray-300">
                                #<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?>
                            </button>
                        </td>

                        <!-- Customer Info -->
                        <td class="py-4">
                            <p class="font-bold text-gray-900 text-xs"><?= htmlspecialchars($ord['customer_name']) ?></p>
                            <p class="text-[11px] text-gray-500 font-semibold"><?= htmlspecialchars($ord['customer_phone']) ?></p>
                            <p class="text-[10px] text-gray-400 truncate max-w-[180px]"><?= htmlspecialchars($ord['shipping_address']) ?></p>
                        </td>

                        <!-- Items Snapshot -->
                        <td class="py-4">
                            <?php if ($firstItem): ?>
                            <div class="flex items-center space-x-2.5">
                                <img src="<?= htmlspecialchars($firstItem['product_image'] ?? 'https://via.placeholder.com/60') ?>" class="w-9 h-11 object-cover rounded-lg bg-gray-100 border border-gray-200 flex-shrink-0">
                                <div class="text-xs">
                                    <p class="font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($firstItem['product_name']) ?></p>
                                    <span class="text-[11px] text-gray-500">
                                        <?= htmlspecialchars($firstItem['size']) ?> / <?= htmlspecialchars($firstItem['color']) ?> &times; <?= $firstItem['quantity'] ?>
                                    </span>
                                    <?php if ($otherCount > 0): ?>
                                    <span class="text-[10px] font-bold text-teal-600 block">+<?= $otherCount ?> sản phẩm khác</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">Không có dữ liệu món</span>
                            <?php endif; ?>
                        </td>

                        <!-- Created At -->
                        <td class="py-4 text-xs text-gray-500 whitespace-nowrap font-medium">
                            <?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?>
                        </td>

                        <!-- Total Amount -->
                        <td class="py-4 text-right font-black text-gray-900 whitespace-nowrap">
                            <?= number_format($ord['total_amount'], 0, ',', '.') ?>đ
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 text-center whitespace-nowrap">
                            <?php
                            $badgeMap = [
                                'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Chờ duyệt'],
                                'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Đang đóng gói'],
                                'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Đang giao'],
                                'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'label' => 'Hoàn thành'],
                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Đã hủy']
                            ];
                            $currBadge = $badgeMap[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status)];
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider <?= $currBadge['bg'] ?> <?= $currBadge['text'] ?>">
                                <?= $currBadge['label'] ?>
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1.5">
                                <!-- Print Invoice -->
                                <button type="button" onclick="printOrderInvoiceById(<?= $ord['id'] ?>)" class="p-1.5 text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition cursor-pointer" title="In Hóa Đơn / Phiếu Giao Hàng">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                 </button>

                                 <!-- Quick Change Status Form -->
                                 <form action="index.php?controller=AdminOrder&action=updateStatus" method="POST" class="inline-block">
                                     <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                     <input type="hidden" name="redirect_tab" value="orders">
                                     <select name="status" onchange="this.form.submit()" class="text-xs font-bold border border-gray-200 rounded-xl px-2 py-1 bg-gray-50 text-gray-800 focus:outline-none focus:border-gray-900 cursor-pointer">
                                         <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                                         <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>Đang gói</option>
                                         <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Đang giao</option>
                                         <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                         <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                     </select>
                                 </form>

                                 <!-- View Details Modal Trigger -->
                                 <button type="button" onclick="openOrderDetailById(<?= $ord['id'] ?>)" class="px-2.5 py-1 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition cursor-pointer">
                                     Chi tiết &rarr;
                                 </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Empty State in Order Filter -->
        <div id="adminOrderEmptyState" class="hidden text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-sm font-bold text-gray-800">Không tìm thấy đơn hàng phù hợp</p>
            <p class="text-xs text-gray-400 mt-1">Hãy thử tìm theo từ khóa hoặc mã đơn khác.</p>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 👤 TAB 4: QUẢN LÝ KHÁCH HÀNG -->
<!-- ============================================================== -->
<div id="tab-customers" class="tab-pane hidden-tab space-y-6">
    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Danh Sách Thành Viên (<?= count($customers) ?>)</h2>
        <p class="text-xs text-gray-400">Thông tin người dùng đã đăng ký tài khoản tại hquie Atelier.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4">Họ và Tên</th>
                        <th class="pb-4">Email</th>
                        <th class="pb-4">Số điện thoại</th>
                        <th class="pb-4">Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php foreach ($customers as $c): ?>
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 font-bold text-gray-900"><?= htmlspecialchars($c['name']) ?></td>
                        <td class="py-3.5 text-xs text-gray-600 font-mono"><?= htmlspecialchars($c['email']) ?></td>
                        <td class="py-3.5 text-xs text-gray-500"><?= htmlspecialchars($c['phone'] ?: 'Chưa cập nhật') ?></td>
                        <td class="py-3.5 text-xs text-gray-400"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🎟️ TAB 5: MÃ GIẢM GIÁ (VOUCHERS) -->
<!-- ============================================================== -->
<div id="tab-coupons" class="tab-pane hidden-tab space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Mã Giảm Giá & Voucher</h2>
            <p class="text-xs text-gray-400">Các chương trình khuyến mãi đang áp dụng trên hệ thống.</p>
        </div>
        <a href="index.php?controller=AdminCoupon&action=index" class="px-5 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase transition">
            Mở Trình Quản Lý Voucher &rarr;
        </a>
    </div>

    <!-- Thẻ Tổng quan Voucher -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($coupons as $coup): ?>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded bg-teal-50 text-teal-800 font-mono font-black text-xs border border-teal-200"><?= htmlspecialchars($coup['code']) ?></span>
                    <span class="text-[10px] font-bold <?= $coup['is_active'] ? 'text-emerald-600' : 'text-gray-400' ?>"><?= $coup['is_active'] ? '● Hoạt động' : '○ Tạm dừng' ?></span>
                </div>
                <p class="text-xs text-gray-500 mb-2 truncate"><?= htmlspecialchars($coup['description'] ?: 'Không có ghi chú') ?></p>
                <p class="text-lg font-black text-gray-900">
                    <?= $coup['type'] === 'percent' ? $coup['value'] . '%' : number_format($coup['value'], 0, ',', '.') . 'đ' ?>
                </p>
            </div>
            <p class="text-[10px] text-gray-400 mt-3 pt-2 border-t border-gray-100">Đã dùng: <?= $coup['used_count'] ?> / <?= $coup['usage_limit'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Bảng Chi Tiết Voucher & Thao Tác -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Danh Sách Mã Ưu Đãi</h3>
            <button onclick="openCouponModal()" class="px-4 py-2 rounded-full bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Thêm Mã Mới
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-3 px-3">Mã Voucher</th>
                        <th class="pb-3 px-3">Mô tả & Loại</th>
                        <th class="pb-3 px-3 text-center">Mức giảm</th>
                        <th class="pb-3 px-3 text-center">Đơn tối thiểu</th>
                        <th class="pb-3 px-3 text-center">Lượt dùng</th>
                        <th class="pb-3 px-3 text-center">Hạn dùng</th>
                        <th class="pb-3 px-3 text-center">Trạng thái</th>
                        <th class="pb-3 px-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php foreach ($coupons as $c): ?>
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3 px-3 font-mono font-bold text-teal-800">
                            <span class="px-2 py-0.5 rounded bg-teal-50 border border-teal-200"><?= htmlspecialchars($c['code']) ?></span>
                        </td>
                        <td class="py-3 px-3 max-w-xs truncate text-xs font-semibold text-gray-800">
                            <?= htmlspecialchars($c['description'] ?: 'Ưu đãi') ?>
                            <span class="block text-[10px] text-gray-400 font-normal"><?= $c['type'] === 'percent' ? 'Chiết khấu %' : 'Giảm tiền' ?></span>
                        </td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-600">
                            <?= $c['type'] === 'percent' ? $c['value'] . '%' : number_format($c['value'], 0, ',', '.') . 'đ' ?>
                        </td>
                        <td class="py-3 px-3 text-center text-xs text-gray-500">
                            <?= $c['min_order_amount'] > 0 ? number_format($c['min_order_amount'], 0, ',', '.') . 'đ' : 'Không giới hạn' ?>
                        </td>
                        <td class="py-3 px-3 text-center text-xs font-bold text-gray-800">
                            <?= $c['used_count'] ?> / <?= $c['usage_limit'] ?>
                        </td>
                        <td class="py-3 px-3 text-center text-xs text-gray-400">
                            <?= $c['end_date'] ? date('d/m/Y', strtotime($c['end_date'])) : 'Vĩnh viễn' ?>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <a href="index.php?controller=AdminCoupon&action=toggle&id=<?= $c['id'] ?>" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $c['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' ?>">
                                <?= $c['is_active'] ? 'Hoạt động' : 'Tạm dừng' ?>
                            </a>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <a href="index.php?controller=AdminCoupon&action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa mã này?')" class="text-xs text-red-500 hover:underline">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 📊 TAB 6: BÁO CÁO THỐNG KÊ (REPORTS) -->
<!-- ============================================================== -->
<div id="tab-reports" class="tab-pane hidden-tab space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Báo Cáo Doanh Thu & Hiệu Quả</h2>
            <p class="text-xs text-gray-400">Phân tích chuyên sâu về tình hình kinh doanh.</p>
        </div>
        <a href="index.php?controller=AdminReport&action=export" class="px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase transition flex items-center shadow-sm">
            Xuất File Excel (CSV)
        </a>
    </div>

    <!-- 4 Thẻ Báo Cáo Nhanh -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80">
            <span class="text-xs font-bold text-gray-400 uppercase">Tổng doanh thu</span>
            <p class="text-2xl font-black text-gray-900 mt-1">đ<?= number_format($report['total_revenue'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80">
            <span class="text-xs font-bold text-gray-400 uppercase">Đơn thành công</span>
            <p class="text-2xl font-black text-gray-900 mt-1"><?= $report['total_orders'] ?></p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80">
            <span class="text-xs font-bold text-gray-400 uppercase">Giá trị đơn TB (AOV)</span>
            <p class="text-2xl font-black text-gray-900 mt-1">đ<?= number_format($report['aov'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200/80">
            <span class="text-xs font-bold text-gray-400 uppercase">Khách hàng</span>
            <p class="text-2xl font-black text-gray-900 mt-1"><?= $report['total_customers'] ?></p>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 📢 TAB 7: QUẢNG CÁO (ADS & BANNERS) -->
<!-- ============================================================== -->
<div id="tab-ads" class="tab-pane hidden-tab space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Chiến Dịch Quảng Cáo & Banner</h2>
            <p class="text-xs text-gray-400">Các banner quảng bá chiến dịch mùa đông.</p>
        </div>
        <a href="index.php?controller=AdminAd&action=index" class="px-5 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase transition">
            Mở Trình Quản Lý Banner &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($ads as $ad): ?>
        <div class="bg-white p-5 rounded-3xl border border-gray-200/80 shadow-sm flex items-center space-x-4">
            <img src="<?= htmlspecialchars($ad['image_url']) ?>" class="w-28 h-20 object-cover rounded-2xl bg-gray-100 border">
            <div class="flex-1">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700"><?= $ad['position'] ?></span>
                <h4 class="text-sm font-bold text-gray-900 mt-1"><?= htmlspecialchars($ad['title']) ?></h4>
                <p class="text-xs text-gray-400 mt-1">Lượt xem: <?= number_format($ad['impressions']) ?> &bull; Click: <?= number_format($ad['clicks']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================== -->
<!-- ⚙️ TAB 8: CÀI ĐẶT HỆ THỐNG -->
<!-- ============================================================== -->
<div id="tab-settings" class="tab-pane hidden-tab space-y-6">
    <div class="bg-white p-8 rounded-3xl border border-gray-200/80 shadow-sm max-w-3xl">
        <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-4">Cài Đặt Cửa Hàng</h2>
        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tên thương hiệu</label>
                <input type="text" value="hquie Larkspur Atelier" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-semibold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email liên hệ CSKH</label>
                <input type="email" value="cskh@hquie.vn" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-semibold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Hotline CSKH</label>
                <input type="text" value="1900 8888" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-semibold">
            </div>
            <button type="button" onclick="alert('Đã lưu cấu hình hệ thống!')" class="px-6 py-2.5 rounded-full bg-gray-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-black transition">
                Lưu Thay Đổi
            </button>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 📅 MODAL: OPEN SCHEDULE (CADRA OPERATIONAL SCHEDULE) -->
<!-- ============================================================== -->
<div id="scheduleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Lịch Trình Vận Hành & Ca Làm Việc</h3>
            </div>
            <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-900 text-lg font-bold p-1">&times;</button>
        </div>
        <div class="py-4 space-y-3">
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-2xl">
                <span class="text-xs font-black text-teal-600 bg-teal-100 px-2 py-0.5 rounded-full mt-0.5">07:00</span>
                <div>
                    <p class="text-xs font-bold text-gray-900">Mở kho nguyên vật liệu</p>
                    <p class="text-[11px] text-gray-500">Kiểm tra kiện hàng nhập 50 áo khoác phao đại hàn.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-2xl">
                <span class="text-xs font-black text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full mt-0.5">09:15</span>
                <div>
                    <p class="text-xs font-bold text-gray-900">Xuất 2 chuyến giao bưu cục</p>
                    <p class="text-[11px] text-gray-500">Giao hàng tiết kiệm & ViettelPost nhận 12 đơn đã đóng gói.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-2xl">
                <span class="text-xs font-black text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full mt-0.5">14:00</span>
                <div>
                    <p class="text-xs font-bold text-gray-900">Họp điều phối tồn kho xưởng</p>
                    <p class="text-[11px] text-gray-500">Đánh giá sản lượng hoàn thiện & bổ sung size M/L đang hot.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-2xl">
                <span class="text-xs font-black text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full mt-0.5">17:00</span>
                <div>
                    <p class="text-xs font-bold text-gray-900">Đối soát COD & tổng kết doanh thu ngày</p>
                    <p class="text-[11px] text-gray-500">Đồng bộ dữ liệu tài chính với ngân hàng và cổng thanh toán.</p>
                </div>
            </div>
        </div>
        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button onclick="closeScheduleModal()" class="px-5 py-2 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition">Đóng</button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- ⚡ MODAL: PIPELINE (SALES & FULFILLMENT PIPELINE) -->
<!-- ============================================================== -->
<div id="pipelineModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Quy Trình Vận Hành Đơn Hàng</h3>
            </div>
            <button onclick="closePipelineModal()" class="text-gray-400 hover:text-gray-900 text-lg font-bold p-1">&times;</button>
        </div>
        <div class="py-4 space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold text-gray-800 mb-1">
                    <span>1. Tiếp nhận & Xác nhận đơn</span>
                    <span class="text-teal-600">85% (Duyệt nhanh)</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-teal-500 h-full rounded-full" style="width: 85%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold text-gray-800 mb-1">
                    <span>2. Đóng gói & In vận đơn</span>
                    <span class="text-blue-600">92% (Đúng quy cách)</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full rounded-full" style="width: 92%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold text-gray-800 mb-1">
                    <span>3. Giao hàng bưu tá & COD</span>
                    <span class="text-emerald-600">91% (Đúng hạn)</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 91%;"></div>
                </div>
            </div>
        </div>
        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button onclick="closePipelineModal()" class="px-5 py-2 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition">Đóng</button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🎟️ MODAL THÊM MÃ GIẢM GIÁ MỚI (CADRA STYLE) -->
<!-- ============================================================== -->
<div id="couponModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Tạo Mã Giảm Giá Mới</h3>
            </div>
            <button onclick="closeCouponModal()" class="text-gray-400 hover:text-gray-900 text-lg font-bold p-1">&times;</button>
        </div>

        <form action="index.php?controller=AdminCoupon&action=store" method="POST" class="py-4 space-y-4">
            <!-- Code Generator -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mã Giảm Giá (Code) *</label>
                <div class="flex gap-2">
                    <input type="text" id="couponCodeInput" name="code" required placeholder="VD: CADRA20K, SALE2026..." class="flex-1 border border-gray-300 p-2.5 rounded-xl text-sm font-mono font-bold uppercase tracking-wider focus:outline-none focus:border-teal-500">
                    <button type="button" onclick="generateRandomCode()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-xl text-xs font-bold transition flex items-center cursor-pointer">
                        🎲 Random
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mô Tả Mã Khuyến Mãi</label>
                <input type="text" name="description" placeholder="VD: Giảm 15% cho đơn hàng mùa đông từ 500k" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500">
            </div>

            <!-- Type & Value -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Loại Giảm Giá *</label>
                    <select name="type" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500 bg-white">
                        <option value="percent">Phần trăm (%)</option>
                        <option value="fixed">Số tiền cố định (VNĐ)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mức Giảm *</label>
                    <input type="number" name="value" required min="1" step="any" placeholder="VD: 10 hoặc 50000" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-bold text-teal-700 focus:outline-none focus:border-teal-500">
                </div>
            </div>

            <!-- Min Order & Max Discount -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Đơn Tối Thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" value="0" min="0" placeholder="0 = Không giới hạn" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Giảm Tối Đa (VNĐ)</label>
                    <input type="number" name="max_discount_amount" min="0" placeholder="Để trống nếu không giới hạn" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500">
                </div>
            </div>

            <!-- Limits & Expiry -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Số Lượng Sử Dụng</label>
                    <input type="number" name="usage_limit" value="100" min="1" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ngày Hết Hạn</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm focus:outline-none focus:border-teal-500">
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeCouponModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-full text-xs font-bold hover:bg-gray-200 transition">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-full text-xs font-bold uppercase tracking-wider hover:bg-black transition shadow-md">
                    Lưu Mã Khuyến Mãi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- ✏️ MODAL 1: QUICK EDIT PRODUCT (CHỈNH SỬA NHANH SẢN PHẨM) -->
<!-- ============================================================== -->
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
            <input type="hidden" name="redirect_tab" value="products">

            <!-- Product Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tên Sản Phẩm *</label>
                <input type="text" name="name" id="qeProductName" required class="w-full border border-gray-300 p-2.5 rounded-xl text-sm font-bold text-gray-900 focus:outline-none focus:border-teal-500">
            </div>

            <!-- Category & Price -->
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

            <!-- Featured Checkbox -->
            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_featured" id="qeProductFeatured" value="1" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-gray-300">
                <label for="qeProductFeatured" class="text-xs font-bold text-gray-800 cursor-pointer">
                    Đặt làm sản phẩm Nổi Bật (Featured Spotlight trên Trang Chủ)
                </label>
            </div>

            <!-- Short Description -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mô Tả Sản Phẩm</label>
                <textarea name="description" id="qeProductDesc" rows="3" class="w-full border border-gray-300 p-2.5 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-teal-500 leading-relaxed"></textarea>
            </div>

            <!-- Submit Buttons -->
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

<!-- ============================================================== -->
<!-- 📦 MODAL 2: STOCK & VARIANTS MANAGER (QUẢN LÝ TỒN KHO BIẾN THỂ) -->
<!-- ============================================================== -->
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
            <!-- Product Header Summary -->
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-2xl mb-4">
                <img id="smProductImg" src="" class="w-12 h-14 object-cover rounded-xl bg-gray-200 border border-gray-200">
                <div>
                    <h4 id="smProductName" class="font-bold text-gray-900 text-sm"></h4>
                    <p id="smProductPrice" class="text-xs font-black text-teal-700"></p>
                </div>
            </div>

            <!-- Variants List -->
            <h5 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Danh Sách Biến Thể & Số Lượng Hiện Có</h5>
            <div id="smVariantsList" class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                <!-- Injected via JavaScript -->
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeStockModal()" class="px-5 py-2 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition cursor-pointer">
                Đóng
            </button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🔍 MODAL 3: ORDER DETAIL DRAWER (CHI TIẾT ĐƠN HÀNG TOÀN DIỆN) -->
<!-- ============================================================== -->
<div id="orderDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-3xl rounded-3xl p-6 sm:p-8 shadow-2xl border border-gray-100 transform transition-all max-h-[92vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                    <h3 class="font-black text-xl text-gray-900 tracking-tight" id="odModalId">Đơn Hàng #000000</h3>
                    <span id="odModalStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase">Pending</span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5" id="odModalDate">Ngày đặt: --/--/----</p>
            </div>
            <button type="button" onclick="closeOrderDetail()" class="text-gray-400 hover:text-gray-900 text-2xl font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <!-- Order Stepper Tracking Bar -->
        <div class="py-6 border-b border-gray-100">
            <div class="relative">
                <div class="h-1 bg-gray-100 rounded-full w-full absolute top-3.5 left-0 z-0"></div>
                <div id="odStepperProgress" class="h-1 bg-teal-500 rounded-full absolute top-3.5 left-0 z-0 transition-all duration-500" style="width: 25%;"></div>
                
                <div class="relative z-10 flex justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div id="step-dot-pending" class="w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md">1</div>
                        <span class="text-[11px] font-bold text-gray-800 mt-1.5">Đặt Hàng</span>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div id="step-dot-processing" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">2</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Đóng Gói</span>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center">
                        <div id="step-dot-shipped" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">3</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Đang Giao</span>
                    </div>
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center">
                        <div id="step-dot-completed" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">4</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Hoàn Thành</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Columns: Customer Info & Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-b border-gray-100">
            <!-- Left: Customer & Shipping Details -->
            <div class="space-y-3 bg-gray-50/70 p-4 rounded-2xl">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Thông Tin Khách Hàng
                </h4>
                <div class="text-xs space-y-1.5 text-gray-600">
                    <p><span class="font-bold text-gray-800">Họ và tên:</span> <span id="odCustomerName" class="font-semibold text-gray-900"></span></p>
                    <p><span class="font-bold text-gray-800">Số điện thoại:</span> <a id="odCustomerPhone" href="#" class="text-teal-600 font-bold hover:underline"></a></p>
                    <p><span class="font-bold text-gray-800">Email:</span> <span id="odCustomerEmail"></span></p>
                    <p><span class="font-bold text-gray-800">Địa chỉ nhận hàng:</span> <span id="odCustomerAddress" class="text-gray-900 font-medium"></span></p>
                    <p><span class="font-bold text-gray-800">Phương thức:</span> <span id="odPaymentMethod" class="px-2 py-0.5 rounded bg-gray-200 font-bold text-[10px] text-gray-800 uppercase">COD</span></p>
                </div>
            </div>

            <!-- Right: Order Financial Summary -->
            <div class="space-y-3 bg-gray-50/70 p-4 rounded-2xl flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                        Tổng Kết Đơn Hàng
                    </h4>
                    <div class="text-xs space-y-2 text-gray-600 mt-2">
                        <div class="flex justify-between">
                            <span>Tạm tính hàng hóa:</span>
                            <span id="odSubtotal" class="font-bold text-gray-900">0đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span class="font-bold text-emerald-600">Miễn phí (Free)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ưu đãi voucher:</span>
                            <span class="font-bold text-orange-600">-0đ</span>
                        </div>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between items-baseline">
                    <span class="text-xs font-black uppercase tracking-wider text-gray-900">Tổng Thanh Toán:</span>
                    <span id="odTotalAmount" class="text-xl font-black text-gray-950">0đ</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6">
            <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 mb-3">Danh Sách Món Hàng (<span id="odItemsCount">0</span>)</h4>
            <div class="border border-gray-100 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-400 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="py-2.5 px-3">Sản phẩm</th>
                            <th class="py-2.5 px-3 text-center">Biến thể</th>
                            <th class="py-2.5 px-3 text-right">Đơn giá</th>
                            <th class="py-2.5 px-3 text-center">Số lượng</th>
                            <th class="py-2.5 px-3 text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="odItemsTbody" class="divide-y divide-gray-100 text-gray-700">
                        <!-- Injected via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <!-- Update Status in Modal -->
            <form action="index.php?controller=AdminOrder&action=updateStatus" method="POST" class="flex items-center space-x-2">
                <input type="hidden" name="order_id" id="odFormOrderId">
                <input type="hidden" name="redirect_tab" value="orders">
                <label class="text-xs font-bold text-gray-700 whitespace-nowrap">Đổi trạng thái:</label>
                <select name="status" id="odFormStatusSelect" class="text-xs font-bold border border-gray-300 rounded-xl px-3 py-2 bg-white text-gray-900 focus:outline-none focus:border-teal-500">
                    <option value="pending">Chờ duyệt (Pending)</option>
                    <option value="processing">Đang đóng gói (Processing)</option>
                    <option value="shipped">Đang giao hàng (Shipped)</option>
                    <option value="completed">Hoàn thành (Completed)</option>
                    <option value="cancelled">Đã hủy (Cancelled)</option>
                </select>
                <button type="submit" class="px-3.5 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition cursor-pointer">
                    Cập nhật
                </button>
            </form>

            <div class="flex items-center space-x-2.5">
                <button type="button" id="odPrintInvoiceBtn" onclick="triggerPrintCurrentOrder()" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-full text-xs font-bold transition flex items-center shadow-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    In Hóa Đơn
                </button>
                <button type="button" onclick="closeOrderDetail()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs font-bold transition cursor-pointer">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🖨️ MODAL 4: ORDER INVOICE PRINT VIEW (HÓA ĐƠN XUẤT KHO CHUẨN A4) -->
<!-- ============================================================== -->
<div id="orderInvoicePrintModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-3xl p-8 shadow-2xl relative my-auto">
        <!-- Print / Close Controls (Hidden when printing) -->
        <div class="flex items-center justify-between pb-6 border-b border-gray-200 print:hidden">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Xem Trước Bản In Hóa Đơn Khách Hàng</span>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="px-5 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-full transition flex items-center shadow-md cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    In Ngay (Print)
                </button>
                <button type="button" onclick="closePrintInvoice()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-full transition cursor-pointer">
                    Đóng
                </button>
            </div>
        </div>

        <!-- Printable Bill Content Area -->
        <div id="printableInvoiceArea" class="py-6 text-gray-900 font-sans">
            <!-- Header Logo & Store Info -->
            <div class="flex justify-between items-start pb-6 border-b-2 border-gray-900">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900">hquie Atelier</h2>
                    <p class="text-xs text-gray-500 font-medium">Thời Trang Cao Cấp &bull; Winter Studio 2026</p>
                    <p class="text-[11px] text-gray-500 mt-1">Hotline: 1900 8888 &bull; Website: hquie.studio</p>
                    <p class="text-[11px] text-gray-500">Địa chỉ: 124 Phố Huế, Hoàn Kiếm, Hà Nội</p>
                </div>
                <div class="text-right">
                    <h3 class="text-lg font-black uppercase text-gray-900 tracking-wider">Hóa Đơn Bán Lẻ</h3>
                    <p class="text-xs font-bold text-gray-700 mt-1" id="invOrderId">Mã Đơn: #000000</p>
                    <p class="text-[11px] text-gray-500" id="invOrderDate">Ngày: --/--/----</p>
                    <!-- Simulated Barcode -->
                    <div class="mt-2 inline-flex flex-col items-end">
                        <div class="h-6 w-32 bg-gradient-to-r from-gray-900 via-gray-400 to-gray-900 flex items-center justify-around px-1">
                            <div class="w-1 h-full bg-white"></div>
                            <div class="w-0.5 h-full bg-white"></div>
                            <div class="w-1.5 h-full bg-white"></div>
                            <div class="w-0.5 h-full bg-white"></div>
                        </div>
                        <span class="text-[9px] font-mono text-gray-400 tracking-widest" id="invBarcode">HQ-2026-0000</span>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="grid grid-cols-2 gap-4 py-4 text-xs border-b border-gray-200">
                <div>
                    <span class="font-bold text-gray-500 uppercase text-[10px] block mb-1">Khách Hàng:</span>
                    <p class="font-extrabold text-sm text-gray-900" id="invCustomerName">---</p>
                    <p class="text-gray-600 font-semibold" id="invCustomerPhone">---</p>
                </div>
                <div>
                    <span class="font-bold text-gray-500 uppercase text-[10px] block mb-1">Địa Chỉ Nhận Hàng:</span>
                    <p class="text-gray-800 font-medium" id="invCustomerAddress">---</p>
                    <p class="text-[11px] text-gray-500 mt-0.5"><span class="font-bold">Phương thức:</span> Thanh toán khi nhận hàng (COD)</p>
                </div>
            </div>

            <!-- Products Table -->
            <div class="py-4">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-300 font-black text-gray-700 uppercase">
                            <th class="py-2">Sản Phẩm</th>
                            <th class="py-2 text-center">Phân Loại</th>
                            <th class="py-2 text-right">Đơn Giá</th>
                            <th class="py-2 text-center">SL</th>
                            <th class="py-2 text-right">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody id="invItemsTbody" class="divide-y divide-gray-200">
                        <!-- Injected via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="py-3 border-t-2 border-gray-900 text-xs flex justify-end">
                <div class="w-64 space-y-1.5">
                    <div class="flex justify-between text-gray-600">
                        <span>Tiền hàng:</span>
                        <span id="invSubtotal" class="font-bold text-gray-900">0đ</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Phí vận chuyển:</span>
                        <span class="font-bold text-emerald-600">0đ (Miễn phí)</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-gray-950 pt-2 border-t border-gray-300">
                        <span>Tổng Thanh Toán:</span>
                        <span id="invTotalAmount">0đ</span>
                    </div>
                </div>
            </div>

            <!-- Signatures & Notes -->
            <div class="pt-8 grid grid-cols-2 text-center text-xs text-gray-600">
                <div>
                    <p class="font-bold text-gray-900">Người Lập Phiếu</p>
                    <p class="text-[10px] text-gray-400 italic mt-1">(Ký và ghi rõ họ tên)</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-gray-800">Bộ phận kho hquie</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Người Nhận Hàng</p>
                    <p class="text-[10px] text-gray-400 italic mt-1">(Kiểm tra hàng trước khi ký)</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-gray-800" id="invSignCustomerName"></p>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-dashed border-gray-300 text-center text-[11px] text-gray-500 italic">
                Cảm ơn Quý khách đã tin tưởng lựa chọn hquie Atelier! Hỗ trợ đổi trả trong vòng 30 ngày.
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- ⚡ JAVASCRIPT: SIÊU CHUYỂN TAB MƯỢT MÀ (CADRA LARKSPUR SPA) -->
<!-- ============================================================== -->
<script>
    // 1. Chuyển Tab cực kỳ mượt mà
    const tabTitles = {
        dashboard: { title: 'Tổng Quan Hệ Thống', breadcrumb: 'Tổng Quan' },
        products: { title: 'Sản Phẩm', breadcrumb: 'Sản Phẩm & Kho' },
        orders: { title: 'Đơn Hàng', breadcrumb: 'Quản Lý Đơn Hàng' },
        customers: { title: 'Khách Hàng', breadcrumb: 'Quản Lý Khách Hàng' },
        coupons: { title: 'Mã Giảm Giá', breadcrumb: 'Mã Khuyến Mãi' },
        reports: { title: 'Báo Cáo', breadcrumb: 'Báo Cáo Doanh Thu' },
        ads: { title: 'Quảng Cáo', breadcrumb: 'Quảng Cáo & Banner' },
        settings: { title: 'Cài Đặt', breadcrumb: 'Cài Đặt Hệ Thống' }
    };

    function switchCadraTab(tabKey, pushHistory = true) {
        if (!tabTitles[tabKey]) tabKey = 'dashboard';

        // A. Cập nhật Buttons
        document.querySelectorAll('.cadra-nav-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.getElementById(`btn-tab-${tabKey}`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            // Cuộn nhẹ button vào tầm nhìn nếu trên màn hình nhỏ
            activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        // B. Cập nhật Panes với hiệu ứng mờ và trượt êm
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active-tab');
            pane.classList.add('hidden-tab');
        });

        const targetPane = document.getElementById(`tab-${tabKey}`);
        if (targetPane) {
            targetPane.classList.remove('hidden-tab');
            targetPane.classList.add('active-tab');
        }

        // C. Cập nhật Breadcrumb & Tiêu đề trang
        const info = tabTitles[tabKey];
        const pageTitle = document.getElementById('page-current-title');
        const breadcrumbTitle = document.getElementById('breadcrumb-current-tab');
        if (pageTitle) pageTitle.innerText = info.title;
        if (breadcrumbTitle) breadcrumbTitle.innerText = info.breadcrumb;

        // D. Cập nhật URL Parameter mà không reload trang (history.pushState)
        if (pushHistory) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('tab', tabKey);
            window.history.pushState({ tab: tabKey }, '', currentUrl);
        }
    }

    // 2. Lắng nghe nút Back/Forward của trình duyệt để đổi tab mượt mà
    window.addEventListener('popstate', function(e) {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'dashboard';
        switchCadraTab(tab, false);
    });

    // 3. Đồng hồ Live Clock thời gian thực
    function updateLiveClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // giờ 0 thành 12
        const timeString = `${hours}:${minutes} ${ampm}`;
        const clockEl = document.getElementById('liveClock');
        if (clockEl) clockEl.innerText = timeString;
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    // 4. Khởi tạo Tab khi load trang (đọc từ URL ?tab=...)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab') || '<?= $activeTab ?? 'dashboard' ?>';
        switchCadraTab(initialTab, false);
    });

    // 5. Tương tác Biểu đồ Year at a glance
    function toggleYearChart(type) {
        const btnRev = document.getElementById('btn-chart-rev');
        const btnUnits = document.getElementById('btn-chart-units');
        if (type === 'rev') {
            btnRev.className = "px-3 py-1 bg-white text-gray-900 rounded-full shadow-sm transition cursor-pointer";
            btnUnits.className = "px-3 py-1 text-gray-500 hover:text-gray-900 rounded-full transition cursor-pointer";
        } else {
            btnUnits.className = "px-3 py-1 bg-white text-gray-900 rounded-full shadow-sm transition cursor-pointer";
            btnRev.className = "px-3 py-1 text-gray-500 hover:text-gray-900 rounded-full transition cursor-pointer";
        }
    }

    // 6. Modals
    function openScheduleModal() {
        const m = document.getElementById('scheduleModal');
        if (m) m.classList.remove('hidden');
    }
    function closeScheduleModal() {
        const m = document.getElementById('scheduleModal');
        if (m) m.classList.add('hidden');
    }

    function openPipelineModal() {
        const m = document.getElementById('pipelineModal');
        if (m) m.classList.remove('hidden');
    }
    function closePipelineModal() {
        const m = document.getElementById('pipelineModal');
        if (m) m.classList.add('hidden');
    }

    function openCouponModal() {
        const m = document.getElementById('couponModal');
        if (m) m.classList.remove('hidden');
    }
    function closeCouponModal() {
        const m = document.getElementById('couponModal');
        if (m) m.classList.add('hidden');
    }

    function generateRandomCode() {
        const prefixes = ['HQUIE', 'CADRA', 'SALE', 'WINTER', 'VIP'];
        const randomPrefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        const input = document.getElementById('couponCodeInput');
        if (input) input.value = `${randomPrefix}${randomNum}`;
    }

    // 7. Dropdowns
    function toggleAdminProfileMenu() {
        const m = document.getElementById('adminProfileMenu');
        if (m) m.classList.toggle('hidden');
    }
    // ==============================================================
    // 🛍️ QUẢN LÝ SẢN PHẨM & ĐƠN HÀNG: DATASETS & ID LOOKUP HELPERS
    // ==============================================================
    const adminProductsData = <?= json_encode($products ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const adminOrdersData = <?= json_encode($orders ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    function openQuickEditProductById(id) {
        const p = adminProductsData.find(item => item.id == id);
        if (p) openQuickEditProduct(p);
    }

    function openStockModalById(id) {
        const p = adminProductsData.find(item => item.id == id);
        if (p) openStockModal(p);
    }

    function openOrderDetailById(id) {
        const ord = adminOrdersData.find(item => item.id == id);
        if (ord) openOrderDetail(ord);
    }

    function printOrderInvoiceById(id) {
        const ord = adminOrdersData.find(item => item.id == id);
        if (ord) printOrderInvoice(ord);
    }

    // ==============================================================
    // 🛍️ QUẢN LÝ SẢN PHẨM: TÌM KIẾM, LỌC REAL-TIME & MODALS
    // ==============================================================
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

    // Modal Sửa Nhanh Sản Phẩm
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

    // Modal Quản Lý Biến Thể Tồn Kho
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
                        <input type="hidden" name="redirect_tab" value="products">
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

    // Toggle Nổi Bật (Featured) AJAX 1 Click
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
            window.location.href = `index.php?controller=AdminProduct&action=toggleFeatured&id=${productId}&redirect_tab=products`;
        });
    }

    // ==============================================================
    // 📦 QUẢN LÝ ĐƠN HÀNG: LỌC TRẠNG THÁI, CHI TIẾT & IN HÓA ĐƠN
    // ==============================================================
    let currentActiveOrderStatus = 'all';
    window.currentViewingOrder = null;

    function filterAdminOrdersTab(status, btnElement) {
        currentActiveOrderStatus = status;
        document.querySelectorAll('.admin-order-status-tab').forEach(btn => {
            btn.classList.remove('bg-gray-900', 'text-white', 'shadow-sm', 'active');
            btn.classList.add('text-gray-600');
        });
        if (btnElement) {
            btnElement.classList.add('bg-gray-900', 'text-white', 'shadow-sm', 'active');
            btnElement.classList.remove('text-gray-600');
        }
        filterAdminOrdersList();
    }

    function filterAdminOrdersList() {
        const query = (document.getElementById('adminOrderSearch')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.admin-order-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const id = (row.getAttribute('data-id') || '').toLowerCase();
            const customer = row.getAttribute('data-customer') || '';
            const phone = row.getAttribute('data-phone') || '';
            const status = row.getAttribute('data-status') || '';

            let matchQuery = !query || id.includes(query) || customer.includes(query) || phone.includes(query);
            let matchStatus = currentActiveOrderStatus === 'all' || status === currentActiveOrderStatus;

            if (matchQuery && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('adminOrderEmptyState');
        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    // Modal Chi Tiết Đơn Hàng Hoàn Chỉnh
    function openOrderDetail(order) {
        if (!order) return;
        window.currentViewingOrder = order;

        const orderIdPadded = '#' + String(order.id).padStart(6, '0');
        document.getElementById('odModalId').innerText = `Đơn Hàng ${orderIdPadded}`;
        document.getElementById('odModalDate').innerText = `Ngày đặt: ${order.created_at || ''}`;
        
        // Status Badge & Stepper
        const status = (order.status || 'pending').toLowerCase();
        const badgeEl = document.getElementById('odModalStatusBadge');
        badgeEl.innerText = status.toUpperCase();
        
        const statusColors = {
            pending: 'bg-amber-100 text-amber-800',
            processing: 'bg-blue-100 text-blue-800',
            shipped: 'bg-purple-100 text-purple-800',
            completed: 'bg-emerald-100 text-emerald-800',
            cancelled: 'bg-red-100 text-red-800'
        };
        badgeEl.className = `px-2.5 py-0.5 rounded-full text-xs font-black uppercase ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;

        // Stepper Progress
        const progressEl = document.getElementById('odStepperProgress');
        const dots = {
            pending: document.getElementById('step-dot-pending'),
            processing: document.getElementById('step-dot-processing'),
            shipped: document.getElementById('step-dot-shipped'),
            completed: document.getElementById('step-dot-completed')
        };
        
        // Reset dots
        Object.values(dots).forEach(d => {
            if (d) d.className = 'w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center';
        });

        if (status === 'pending') {
            if (progressEl) progressEl.style.width = '15%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'processing') {
            if (progressEl) progressEl.style.width = '48%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.processing) dots.processing.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'shipped') {
            if (progressEl) progressEl.style.width = '78%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.processing) dots.processing.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.shipped) dots.shipped.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'completed') {
            if (progressEl) progressEl.style.width = '100%';
            Object.values(dots).forEach(d => {
                if (d) d.className = 'w-7 h-7 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            });
        } else if (status === 'cancelled') {
            if (progressEl) progressEl.style.width = '0%';
        }

        // Customer details
        document.getElementById('odCustomerName').innerText = order.customer_name || 'Khách vãng lai';
        const phoneEl = document.getElementById('odCustomerPhone');
        phoneEl.innerText = order.customer_phone || '---';
        phoneEl.href = `tel:${order.customer_phone || ''}`;
        document.getElementById('odCustomerEmail').innerText = order.customer_email || 'Chưa cung cấp';
        document.getElementById('odCustomerAddress').innerText = order.shipping_address || 'Nhận tại cửa hàng';
        document.getElementById('odPaymentMethod').innerText = order.payment_method || 'COD';

        // Amounts
        const total = Number(order.total_amount || 0);
        document.getElementById('odSubtotal').innerText = total.toLocaleString('vi-VN') + 'đ';
        document.getElementById('odTotalAmount').innerText = total.toLocaleString('vi-VN') + 'đ';

        // Items Table
        const items = order.items || [];
        document.getElementById('odItemsCount').innerText = items.length;
        const tbody = document.getElementById('odItemsTbody');
        tbody.innerHTML = '';

        items.forEach(it => {
            const lineTotal = Number(it.price * it.quantity);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2.5 px-3">
                    <div class="flex items-center space-x-2">
                        <img src="${it.product_image || 'https://via.placeholder.com/60'}" class="w-8 h-10 object-cover rounded bg-gray-100 border border-gray-200">
                        <span class="font-bold text-gray-900">${it.product_name || 'Sản phẩm'}</span>
                    </div>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <span class="px-2 py-0.5 rounded bg-stone-100 font-bold text-[10px] text-gray-700">
                        ${it.size || 'M'} / ${it.color || 'Đen'}
                    </span>
                </td>
                <td class="py-2.5 px-3 text-right font-medium">${Number(it.price).toLocaleString('vi-VN')}đ</td>
                <td class="py-2.5 px-3 text-center font-bold">${it.quantity}</td>
                <td class="py-2.5 px-3 text-right font-black text-gray-900">${lineTotal.toLocaleString('vi-VN')}đ</td>
            `;
            tbody.appendChild(tr);
        });

        // Form change status
        document.getElementById('odFormOrderId').value = order.id;
        document.getElementById('odFormStatusSelect').value = status;

        const m = document.getElementById('orderDetailModal');
        if (m) m.classList.remove('hidden');
    }

    function closeOrderDetail() {
        const m = document.getElementById('orderDetailModal');
        if (m) m.classList.add('hidden');
    }

    // In Hóa Đơn Khách Hàng
    function triggerPrintCurrentOrder() {
        if (window.currentViewingOrder) {
            printOrderInvoice(window.currentViewingOrder);
        }
    }

    function printOrderInvoice(order) {
        if (!order) return;
        const orderIdPadded = '#' + String(order.id).padStart(6, '0');
        document.getElementById('invOrderId').innerText = `Mã Đơn: ${orderIdPadded}`;
        document.getElementById('invOrderDate').innerText = `Ngày: ${order.created_at || ''}`;
        document.getElementById('invBarcode').innerText = `HQ-2026-${String(order.id).padStart(4, '0')}`;
        
        document.getElementById('invCustomerName').innerText = order.customer_name || 'Khách hàng';
        document.getElementById('invCustomerPhone').innerText = order.customer_phone || '';
        document.getElementById('invCustomerAddress').innerText = order.shipping_address || 'Địa chỉ giao hàng';
        document.getElementById('invSignCustomerName').innerText = order.customer_name || '';

        const total = Number(order.total_amount || 0);
        document.getElementById('invSubtotal').innerText = total.toLocaleString('vi-VN') + 'đ';
        document.getElementById('invTotalAmount').innerText = total.toLocaleString('vi-VN') + 'đ';

        const tbody = document.getElementById('invItemsTbody');
        tbody.innerHTML = '';
        (order.items || []).forEach(it => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2 font-bold">${it.product_name || 'Sản phẩm'}</td>
                <td class="py-2 text-center">${it.size || 'M'} / ${it.color || 'Tiêu chuẩn'}</td>
                <td class="py-2 text-right">${Number(it.price).toLocaleString('vi-VN')}đ</td>
                <td class="py-2 text-center font-bold">${it.quantity}</td>
                <td class="py-2 text-right font-bold">${Number(it.price * it.quantity).toLocaleString('vi-VN')}đ</td>
            `;
            tbody.appendChild(tr);
        });

        const m = document.getElementById('orderInvoicePrintModal');
        if (m) m.classList.remove('hidden');
    }

    function closePrintInvoice() {
        const m = document.getElementById('orderInvoicePrintModal');
        if (m) m.classList.add('hidden');
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>