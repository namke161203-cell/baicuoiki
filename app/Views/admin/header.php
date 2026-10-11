<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#34869c',
                        'primary-dark': '#1a5666',
                        bglight: '#eef5f6'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bglight flex h-screen overflow-hidden text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-white flex flex-col shadow-[2px_0_15px_rgba(0,0,0,0.03)] z-20 flex-shrink-0">
        <!-- Logo -->
        <div class="h-16 flex items-center justify-center border-b border-gray-100">
            <a href="index.php" class="text-3xl font-black tracking-tighter text-primary">hquie</a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="index.php?controller=Admin&action=index" class="flex items-center justify-between px-4 py-3 rounded-lg font-semibold shadow-md <?= (!isset($_GET['controller']) || $_GET['controller'] === 'Admin') ? 'bg-primary-dark text-white' : 'text-gray-600 hover:bg-gray-50' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    TỔNG QUAN
                </div>
            </a>
            
            <a href="index.php?controller=AdminProduct&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminProduct' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    SẢN PHẨM
                </div>
                <!-- Dropdown arrow icon có thể bỏ nếu không dùng dropdown sub-menu -->
            </a>
            
            <a href="index.php?controller=AdminOrder&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminOrder' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    ĐƠN HÀNG
                </div>
            </a>
            
            <a href="index.php?controller=AdminCustomer&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminCustomer' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    KHÁCH HÀNG
                </div>
            </a>
            
            <a href="index.php?controller=AdminCoupon&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminCoupon' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    MÃ GIẢM GIÁ
                </div>
            </a>
            
            <a href="index.php?controller=AdminReport&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminReport' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    BÁO CÁO
                </div>
                <!-- Dropdown arrow có thể bỏ -->
            </a>
            
            <a href="index.php?controller=AdminAd&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminAd' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    QUẢNG CÁO
                </div>
            </a>
            
            <a href="index.php?controller=AdminSetting&action=index" class="flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition <?= isset($_GET['controller']) && $_GET['controller'] === 'AdminSetting' ? 'bg-primary-dark text-white shadow-md hover:bg-primary-dark' : '' ?>">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    CÀI ĐẶT
                </div>
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
            <!-- Search -->
            <div class="flex-1 max-w-xl relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Tìm kiếm đơn hàng, sản phẩm..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-primary transition">
            </div>

            <!-- Right Icons -->
            <div class="flex items-center space-x-5 ml-4 text-gray-500">
                <button class="relative hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">3</span>
                </button>
                <button class="hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </button>
                <button class="hover:text-gray-800 transition border-r border-gray-200 pr-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </button>

                <!-- Profile -->
                <div class="flex items-center cursor-pointer pl-1">
                    <img src="https://ui-avatars.com/api/?name=Admin+Hquie&background=random" class="w-8 h-8 rounded-full object-cover mr-2">
                    <span class="text-sm font-semibold text-gray-700">Admin Hquie</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">