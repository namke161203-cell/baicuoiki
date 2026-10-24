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
            <span id="breadcrumb-current-tab" class="text-gray-800">Dashboard</span>
        </div>
        <h1 id="page-current-title" class="text-3xl font-black text-gray-900 tracking-tight">
            Dashboard
        </h1>
    </div>

    <!-- Live Clock & Status (Cadra Inspired) -->
    <div class="flex items-center space-x-4 bg-white px-4 py-2 rounded-full border border-gray-200/80 shadow-sm text-xs font-semibold text-gray-700">
        <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 live-dot"></span>
            <span id="liveClock" class="font-bold text-gray-900 tracking-wider">3:36 PM</span>
        </div>
        <div class="h-4 w-px bg-gray-200"></div>
        <div class="flex items-center space-x-2 text-[11px]">
            <span class="text-gray-900 font-bold">5 on the floor</span>
            <span class="text-gray-400">&bull;</span>
            <span class="text-emerald-600 font-bold">4 on track</span>
            <span class="text-orange-500 font-bold">1 behind</span>
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
                        <?= strtoupper(date('l, F j')) ?>
                    </span>
                    <!-- Action Pills -->
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="openScheduleModal()" class="px-4 py-1.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center shadow-sm cursor-pointer">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Open schedule
                        </button>
                        <button type="button" onclick="openPipelineModal()" class="px-4 py-1.5 rounded-full border border-gray-300 hover:bg-gray-50 text-gray-800 text-xs font-bold transition flex items-center cursor-pointer">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Pipeline
                        </button>
                    </div>
                </div>

                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mb-2">
                    Good afternoon, Noor.
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 font-medium leading-relaxed max-w-2xl">
                    5 operations on the floor. 2 shipments on the road and 2 showroom sales so far. 1 work order needs a look.
                </p>
            </div>

            <!-- Timeline Tracker Line (Cadra Style with Interactive Tooltips) -->
            <div class="mt-8 pt-4 border-t border-gray-100">
                <div class="relative py-4">
                    <!-- Base Track -->
                    <div class="h-1.5 bg-gray-100 rounded-full w-full relative">
                        <!-- Progress Track -->
                        <div class="h-1.5 bg-gradient-to-r from-teal-400 via-teal-500 to-emerald-500 rounded-full" style="width: 68%;"></div>
                    </div>

                    <!-- Event Dots on Timeline with Interactive Tooltips -->
                    <div class="absolute top-2.5 left-0 right-0 flex justify-between px-2">
                        <!-- 7a -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-teal-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2">7a</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                07:00 AM &bull; Mở kho & kiểm tra chất lượng ca sáng
                            </div>
                        </div>

                        <!-- 9a -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-blue-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2">9a</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                09:15 AM &bull; Xuất 2 chuyến giao hàng nội thành
                            </div>
                        </div>

                        <!-- 11a -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-amber-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2">11a</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                11:30 AM &bull; Duyệt 2 đơn thanh toán showroom
                            </div>
                        </div>

                        <!-- 1p -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-purple-500 border-2 border-white shadow group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2">1p</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                01:00 PM &bull; Nhập đợt vải Cashmere mới
                            </div>
                        </div>

                        <!-- 3p (Current Position Marker) -->
                        <div class="flex flex-col items-center relative -top-3.5 group cursor-pointer">
                            <span class="px-2 py-0.5 rounded-full bg-orange-600 text-white font-black text-[9px] shadow-sm mb-0.5 animate-pulse">3:36 PM</span>
                            <div class="w-4 h-4 rounded-full bg-orange-600 border-2 border-white shadow-md ring-2 ring-orange-200"></div>
                            <span class="text-[10px] font-black text-gray-900 mt-1">3p</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-12 left-1/2 -translate-x-1/2 bg-orange-600 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                Hiện tại &bull; 5 đơn hàng đang xử lý tại xưởng
                            </div>
                        </div>

                        <!-- 5p -->
                        <div class="flex flex-col items-center group relative cursor-pointer">
                            <div class="w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white group-hover:scale-125 transition"></div>
                            <span class="text-[10px] font-bold text-gray-400 mt-2">5p</span>
                            <!-- Tooltip -->
                            <div class="hidden group-hover:block absolute bottom-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-xl z-20">
                                05:00 PM &bull; Đối soát doanh thu cuối ngày
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
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wide flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Cash on hand
                    </span>
                    <button type="button" onclick="switchCadraTab('orders')" class="text-xs font-bold text-gray-400 hover:text-gray-900 underline cursor-pointer">Payments</button>
                </div>

                <div class="flex items-baseline space-x-3 mb-4">
                    <span class="text-3xl font-black text-gray-900 tracking-tight">đ221.553.000</span>
                    <span class="text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                        +đ49.438.000 in 14 days
                    </span>
                </div>

                <!-- Mini Waterfall/Bar Chart (Cadra Style) -->
                <div class="h-20 flex items-end justify-between gap-1.5 py-2">
                    <div class="w-3 bg-emerald-500 rounded-t h-[60%]"></div>
                    <div class="w-3 bg-emerald-500 rounded-t h-[80%]"></div>
                    <div class="w-3 bg-emerald-500 rounded-t h-[45%]"></div>
                    <div class="w-3 bg-rose-400 rounded-b h-[40%] self-start mt-8"></div>
                    <div class="w-3 bg-emerald-500 rounded-t h-[95%]"></div>
                    <div class="w-3 bg-emerald-500 rounded-t h-[70%]"></div>
                    <div class="w-3 bg-rose-400 rounded-b h-[50%] self-start mt-8"></div>
                    <div class="w-3 bg-emerald-500 rounded-t h-[85%]"></div>
                    <div class="w-3 bg-emerald-600 rounded-t h-[100%]"></div>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex items-center space-x-4 pt-3 border-t border-gray-100 text-[11px] font-bold text-gray-500">
                <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Money in</span>
                <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5"></span> Money out</span>
            </div>
        </div>
    </div>

    <!-- 🌟 ROW 2: 4 METRIC CARDS WITH SPARKLINE (BENTO GRID CADRA) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Revenue this month (Dark Card) -->
        <div onclick="switchCadraTab('reports')" class="cursor-pointer bg-gray-950 text-white rounded-3xl p-6 border border-gray-800 shadow-md flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide group-hover:text-teal-400 transition">Revenue this month</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-white">đ93.474.000</span>
                    <!-- Sparkline SVG (Glowing Line) -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,30 Q25,5 50,25 T100,5" fill="none" stroke="#2dd4bf" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 font-medium mt-4 pt-3 border-t border-gray-800 flex items-center justify-between">
                <span>↗ 11 days in &bull; on pace for đ256M</span>
                <span class="text-teal-400 text-[10px] font-bold group-hover:underline">Báo cáo &rarr;</span>
            </p>
        </div>

        <!-- Card 2: Bikes built (Pastel Blue Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-[#dbeafe] text-blue-950 rounded-3xl p-6 border border-blue-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-blue-800 uppercase tracking-wide group-hover:text-blue-950 transition">Orders completed (14d)</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-blue-950">57</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,20 Q20,35 40,10 T70,30 T100,5" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-blue-700 font-bold mt-4 pt-3 border-t border-blue-200 flex items-center justify-between">
                <span>↗ 3 so far today</span>
                <span class="text-blue-900 text-[10px] font-bold group-hover:underline">Đơn hàng &rarr;</span>
            </p>
        </div>

        <!-- Card 3: Open order book (Clean White Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-white rounded-3xl p-6 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide group-hover:text-gray-900 transition">Open order book</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-gray-900">đ131.360.000</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,25 Q30,5 60,30 T100,10" fill="none" stroke="#0f766e" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 font-medium mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span>&bull; 9 orders confirmed or building</span>
                <span class="text-teal-600 text-[10px] font-bold group-hover:underline">Chi tiết &rarr;</span>
            </p>
        </div>

        <!-- Card 4: On-time delivery (Clean White Card) -->
        <div onclick="switchCadraTab('orders')" class="cursor-pointer bg-white rounded-3xl p-6 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide group-hover:text-gray-900 transition">On-time delivery</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black tracking-tight text-gray-900">91%</span>
                    <!-- Sparkline SVG -->
                    <svg class="w-20 h-8" viewBox="0 0 100 40">
                        <path d="M0,15 Q30,25 60,10 T100,5" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-amber-600 font-bold mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span>&Delta; down 2 pts from October</span>
                <span class="text-gray-600 text-[10px] font-bold group-hover:underline">Xem KPI &rarr;</span>
            </p>
        </div>
    </div>

    <!-- 🌟 ROW 3: YEAR AT A GLANCE + RECENT OPERATIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Year at a glance Chart (Col-span 2) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Year at a glance</h3>
                    <p class="text-xs text-gray-400">Doanh thu và hiệu suất vận hành theo chu kỳ</p>
                </div>
                <div class="flex items-center space-x-2 bg-gray-100 p-1 rounded-full text-xs font-bold text-gray-600">
                    <button type="button" id="btn-chart-rev" onclick="toggleYearChart('rev')" class="px-3 py-1 bg-white text-gray-900 rounded-full shadow-sm transition cursor-pointer">Revenue & cost</button>
                    <button type="button" id="btn-chart-units" onclick="toggleYearChart('units')" class="px-3 py-1 text-gray-500 hover:text-gray-900 rounded-full transition cursor-pointer">Units built</button>
                </div>
            </div>

            <!-- Curved Area Chart SVG Mock -->
            <div class="h-64 w-full relative">
                <svg viewBox="0 0 800 240" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#2dd4bf;stop-opacity:0.3" />
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
                <div class="flex justify-between text-[11px] font-bold text-gray-400 mt-2 px-2">
                    <span>Tháng 1</span><span>Tháng 3</span><span>Tháng 5</span><span>Tháng 7</span><span>Tháng 9</span><span>Tháng 11</span>
                </div>
            </div>
        </div>

        <!-- Sales Funnel / Quick Actions (Col-span 1) -->
        <div class="bg-white rounded-3xl p-7 border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Sales Funnel</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span>Quote (Báo giá)</span>
                            <span>4 orders &bull; $33.4k</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-teal-500 h-full rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span>Confirmed (Xác nhận)</span>
                            <span>9 orders &bull; $131.3k</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                            <span>In Fulfillment (Đang giao)</span>
                            <span>3 orders &bull; $42.0k</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-purple-600 h-full rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                <button type="button" onclick="switchCadraTab('orders')" class="text-xs font-bold text-teal-700 hover:underline">Xem danh sách đơn &rarr;</button>
                <button type="button" onclick="switchCadraTab('reports')" class="text-xs font-bold text-gray-400 hover:text-gray-900">Chi tiết Funnel</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 🛍️ TAB 2: QUẢN LÝ SẢN PHẨM -->
<!-- ============================================================== -->
<div id="tab-products" class="tab-pane hidden-tab space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Kho Hàng & Sản Phẩm</h2>
            <p class="text-xs text-gray-400">Quản lý toàn bộ danh mục, biến thể và số lượng tồn kho.</p>
        </div>
        <a href="index.php?controller=AdminProduct&action=create" class="px-5 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider transition shadow-sm">
            + Thêm Sản Phẩm Mới
        </a>
    </div>

    <!-- Table Sản phẩm -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4">Hình ảnh</th>
                        <th class="pb-4">Tên sản phẩm</th>
                        <th class="pb-4">Danh mục</th>
                        <th class="pb-4 text-right">Đơn giá</th>
                        <th class="pb-4 text-center">Nổi bật</th>
                        <th class="pb-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php foreach ($products as $p): ?>
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3">
                            <img src="<?= htmlspecialchars($p['image'] ?? 'https://via.placeholder.com/80') ?>" class="w-12 h-14 object-cover rounded-xl bg-gray-100 border border-gray-200">
                        </td>
                        <td class="py-3 font-bold text-gray-900 max-w-xs truncate"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="py-3 text-xs text-gray-500 font-semibold"><?= htmlspecialchars($p['category_name'] ?? 'Thời trang') ?></td>
                        <td class="py-3 text-right font-black text-gray-900"><?= number_format($p['price'], 0, ',', '.') ?>đ</td>
                        <td class="py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $p['is_featured'] ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-400' ?>">
                                <?= $p['is_featured'] ? 'Featured' : 'Standard' ?>
                            </span>
                        </td>
                        <td class="py-3 text-center">
                            <a href="index.php?controller=Product&action=detail&id=<?= $p['id'] ?>" target="_blank" class="px-3 py-1 rounded-full text-xs font-bold text-teal-700 hover:bg-teal-50">Xem</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 📦 TAB 3: QUẢN LÝ ĐƠN HÀNG -->
<!-- ============================================================== -->
<div id="tab-orders" class="tab-pane hidden-tab space-y-6">
    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Quản Lý Đơn Hàng (<?= count($orders) ?>)</h2>
            <p class="text-xs text-gray-400">Kiểm tra thông tin giao nhận và điều chỉnh trạng thái đơn hàng.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4">Mã đơn</th>
                        <th class="pb-4">Khách hàng</th>
                        <th class="pb-4">Ngày đặt</th>
                        <th class="pb-4 text-right">Tổng tiền</th>
                        <th class="pb-4 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php foreach ($orders as $ord): ?>
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 font-bold text-gray-900">#<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td class="py-3.5">
                            <p class="font-bold text-gray-800"><?= htmlspecialchars($ord['customer_name']) ?></p>
                            <p class="text-[11px] text-gray-400"><?= htmlspecialchars($ord['customer_phone']) ?></p>
                        </td>
                        <td class="py-3.5 text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                        <td class="py-3.5 text-right font-black text-gray-900">đ<?= number_format($ord['total_amount'], 0, ',', '.') ?></td>
                        <td class="py-3.5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-800">
                                <?= $ord['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Pipeline Vận Hành Đơn Hàng</h3>
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
<!-- ⚡ JAVASCRIPT: SIÊU CHUYỂN TAB MƯỢT MÀ (CADRA LARKSPUR SPA) -->
<!-- ============================================================== -->
<script>
    // 1. Chuyển Tab cực kỳ mượt mà
    const tabTitles = {
        dashboard: { title: 'Dashboard', breadcrumb: 'Dashboard' },
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
    function toggleAdminNotifications() {
        alert('Thông báo: 1 đơn hàng mới đang chờ duyệt, 1 sản phẩm sắp hết hàng!');
    }
    document.addEventListener('click', function(e) {
        const profileMenu = document.getElementById('adminProfileMenu');
        if (profileMenu && !e.target.closest('#adminProfileMenu') && !e.target.closest('button[onclick="toggleAdminProfileMenu()"]')) {
            profileMenu.classList.add('hidden');
        }
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>