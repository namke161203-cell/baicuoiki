<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'hquie Cadra Studio - Executive Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1c1d1f',
                        'cadra-bg': '#f4f5f6',
                        'cadra-dark': '#18191b',
                        'cadra-blue': '#2563eb',
                        'cadra-teal': '#0f766e',
                        'cadra-orange': '#ea580c'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f5f6;
            color: #1c1d1f;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Smooth Tab Animations (Ultra silky Cadra curve) */
        @keyframes cadraTabEnter {
            0% {
                opacity: 0;
                transform: translateY(8px) scale(0.996);
                filter: blur(1.5px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }
        .tab-pane.active-tab {
            display: block !important;
            animation: cadraTabEnter 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .tab-pane.hidden-tab {
            display: none !important;
        }

        /* Pill Tab Active State */
        .cadra-nav-btn {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
            white-space: nowrap;
        }
        .cadra-nav-btn:active {
            transform: scale(0.94);
        }
        .cadra-nav-btn.active {
            background-color: #18191b !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }
        .cadra-nav-btn:not(.active):hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: #18191b;
        }

        /* Hide scrollbar for clean horizontal scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Pulse Dot */
        @keyframes livePulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .live-dot {
            animation: livePulse 2s infinite ease-in-out;
        }
    </style>
</head>
<?php
$reqController = $_GET['controller'] ?? 'Admin';
$reqTab = $_GET['tab'] ?? '';

$currentActivePill = 'dashboard';
if ($reqController === 'Admin') {
    $currentActivePill = !empty($reqTab) ? $reqTab : 'dashboard';
} elseif ($reqController === 'AdminProduct') {
    $currentActivePill = 'products';
} elseif ($reqController === 'AdminOrder') {
    $currentActivePill = 'orders';
} elseif ($reqController === 'AdminCustomer') {
    $currentActivePill = 'customers';
} elseif ($reqController === 'AdminCoupon') {
    $currentActivePill = 'coupons';
} elseif ($reqController === 'AdminReport') {
    $currentActivePill = 'reports';
} elseif ($reqController === 'AdminAd') {
    $currentActivePill = 'ads';
} elseif ($reqController === 'AdminSetting') {
    $currentActivePill = 'settings';
}
?>

<body class="bg-cadra-bg text-gray-900 min-h-screen flex flex-col antialiased selection:bg-gray-900 selection:text-white">

    <!-- 🌟 TOP NAVBAR (Capsule Pill Tabs Style) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200/70 px-4 sm:px-6 py-2.5 transition-all duration-300">
        <div class="max-w-[1700px] mx-auto flex items-center justify-between gap-3 sm:gap-4">
            
            <!-- Left: Brand Logo (Cadra Larkspur Inspired) -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <a href="index.php?controller=Admin&action=index" class="flex items-center space-x-2.5 group">
                    <div class="w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center font-black text-sm shadow-sm group-hover:scale-105 transition">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-extrabold tracking-tight text-gray-900 group-hover:text-teal-600 transition leading-tight">Cadra Studio</span>
                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest leading-none">hquie Atelier</span>
                    </div>
                </a>
            </div>

            <!-- Middle: Horizontal Pill Tabs Navigation (Smooth Switcher & Responsive Scroll) -->
            <nav class="flex items-center space-x-1 bg-gray-100/90 p-1.5 rounded-full border border-gray-200/70 shadow-inner overflow-x-auto no-scrollbar max-w-full">
                <button type="button" onclick="switchCadraTab('dashboard')" id="btn-tab-dashboard" class="cadra-nav-btn <?= $currentActivePill === 'dashboard' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Dashboard
                </button>
                <button type="button" onclick="switchCadraTab('products')" id="btn-tab-products" class="cadra-nav-btn <?= $currentActivePill === 'products' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Sản phẩm
                </button>
                <button type="button" onclick="switchCadraTab('orders')" id="btn-tab-orders" class="cadra-nav-btn <?= $currentActivePill === 'orders' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Đơn hàng
                    <span class="ml-1.5 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-orange-500 text-white">4</span>
                </button>
                <button type="button" onclick="switchCadraTab('customers')" id="btn-tab-customers" class="cadra-nav-btn <?= $currentActivePill === 'customers' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Khách hàng
                </button>
                <button type="button" onclick="switchCadraTab('coupons')" id="btn-tab-coupons" class="cadra-nav-btn <?= $currentActivePill === 'coupons' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Mã giảm giá
                    <span class="ml-1.5 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-teal-500 text-white">New</span>
                </button>
                <button type="button" onclick="switchCadraTab('reports')" id="btn-tab-reports" class="cadra-nav-btn <?= $currentActivePill === 'reports' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Báo cáo
                </button>
                <button type="button" onclick="switchCadraTab('ads')" id="btn-tab-ads" class="cadra-nav-btn <?= $currentActivePill === 'ads' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Quảng cáo
                </button>
                <button type="button" onclick="switchCadraTab('settings')" id="btn-tab-settings" class="cadra-nav-btn <?= $currentActivePill === 'settings' ? 'active' : 'text-gray-600' ?> px-3.5 sm:px-4 py-1.5 rounded-full text-xs font-bold flex items-center cursor-pointer">
                    Cài đặt
                </button>
            </nav>

            <!-- Right: Actions & Profile -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <!-- Nút xem Shop -->
                <a href="index.php" target="_blank" class="hidden sm:flex items-center text-xs font-bold bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-full border border-gray-200 shadow-sm transition">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Xem Cửa Hàng
                </a>

                <!-- Notification Bell -->
                <button onclick="toggleAdminNotifications()" class="relative p-2 text-gray-500 hover:text-gray-900 transition rounded-full hover:bg-gray-100 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-orange-500"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleAdminProfileMenu()" class="flex items-center space-x-2 pl-2 focus:outline-none cursor-pointer">
                        <img src="https://ui-avatars.com/api/?name=Admin+Noor&background=18191b&color=fff&bold=true" class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-gray-200">
                    </button>
                    <!-- Profile Menu Dropdown -->
                    <div id="adminProfileMenu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-sm">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="font-bold text-gray-900">Admin Noor</p>
                            <p class="text-xs text-gray-400">noor@hquie.studio</p>
                        </div>
                        <a href="index.php?controller=Profile&action=index" class="block px-4 py-2 hover:bg-gray-50 text-gray-700 text-xs font-semibold">Tài khoản quản trị</a>
                        <a href="index.php?controller=Auth&action=logout" class="block px-4 py-2 hover:bg-red-50 text-red-600 text-xs font-semibold">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Tab Switcher Script (Works on ALL Admin Views) -->
    <script>
        function switchCadraTab(tabKey, pushHistory = true) {
            const tabPanes = document.querySelectorAll('.tab-pane');
            const targetPane = document.getElementById('tab-' + tabKey);

            if (tabPanes.length > 0 && targetPane) {
                // 1. Nếu đang ở trang Dashboard SPA (có sẵn các pane) -> Chuyển tab mượt mà ngay trên trang
                document.querySelectorAll('.cadra-nav-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('text-gray-600');
                });
                const activeBtn = document.getElementById('btn-tab-' + tabKey);
                if (activeBtn) {
                    activeBtn.classList.add('active');
                    activeBtn.classList.remove('text-gray-600');
                    activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }

                tabPanes.forEach(pane => {
                    pane.classList.remove('active-tab');
                    pane.classList.add('hidden-tab');
                });

                targetPane.classList.remove('hidden-tab');
                targetPane.classList.add('active-tab');

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
                const info = tabTitles[tabKey] || { title: 'Dashboard', breadcrumb: 'Dashboard' };
                const pageTitle = document.getElementById('page-current-title');
                const breadcrumbTitle = document.getElementById('breadcrumb-current-tab');
                if (pageTitle) pageTitle.innerText = info.title;
                if (breadcrumbTitle) breadcrumbTitle.innerText = info.breadcrumb;

                if (pushHistory) {
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.set('controller', 'Admin');
                    currentUrl.searchParams.set('action', 'index');
                    currentUrl.searchParams.set('tab', tabKey);
                    window.history.pushState({ tab: tabKey }, '', currentUrl);
                }
            } else {
                // 2. Nếu đang ở trang con riêng biệt (coupons, reports, ads...) -> Điều hướng về Dashboard mở đúng tab đó!
                window.location.href = 'index.php?controller=Admin&action=index&tab=' + encodeURIComponent(tabKey);
            }
        }

        // Global dropdowns
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

    <!-- Main Content Container -->
    <main class="flex-1 max-w-[1700px] w-full mx-auto px-4 sm:px-6 py-6 overflow-x-hidden">