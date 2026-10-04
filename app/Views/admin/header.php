<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex h-screen overflow-hidden selection:bg-indigo-600 selection:text-white">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl z-20">
        <div class="h-16 flex items-center justify-center border-b border-gray-800">
            <a href="index.php" class="text-2xl font-black tracking-widest text-white uppercase hover:text-indigo-400 transition">
                VIBE<span class="text-indigo-500">ADMIN</span>
            </a>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto hidden-scrollbar">
            <a href="index.php?controller=Admin&action=index" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl transition font-medium shadow-lg shadow-indigo-600/30">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 rounded-xl transition text-gray-400 hover:text-white font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Đơn hàng
            </a>
            <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 rounded-xl transition text-gray-400 hover:text-white font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Sản phẩm
            </a>
            <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 rounded-xl transition text-gray-400 hover:text-white font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Khách hàng
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800 bg-gray-900">
            <div class="flex items-center mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold mr-3 shadow-inner text-lg">
                    <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate"><?= $_SESSION['user_name'] ?? 'Admin' ?></p>
                    <p class="text-xs text-gray-400">Quản trị viên</p>
                </div>
            </div>
            <a href="index.php?controller=Auth&action=logout" class="flex items-center justify-center w-full bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white py-2.5 rounded-xl transition duration-300 text-sm font-semibold border border-red-500/20 hover:border-transparent">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Đăng xuất
            </a>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        <!-- Topbar -->
        <header class="h-16 bg-white/80 backdrop-blur-md shadow-sm flex items-center justify-between px-8 z-10 sticky top-0">
            <h2 class="text-xl font-black text-gray-800 tracking-tight"><?= $title ?? 'Tổng quan' ?></h2>
            <div class="flex items-center space-x-4">
                <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">