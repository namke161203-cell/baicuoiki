<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Vibe Clothing Store' ?></title>
    <!-- Tích hợp TailwindCSS qua CDN (Dùng cho dev, production sẽ build file tĩnh) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hiệu ứng kính mờ cho Menu */
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased selection:bg-indigo-600 selection:text-white">
    <!-- Header / Navigation -->
    <nav class="glass-nav fixed w-full z-50 top-0 transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="text-2xl font-black tracking-tighter text-gray-900 uppercase">
                        VIBE<span class="text-indigo-600">STORE</span>
                    </a>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php" class="text-gray-900 hover:text-indigo-600 px-3 py-2 font-semibold transition">Trang chủ</a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600 px-3 py-2 font-medium transition">Sản phẩm</a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600 px-3 py-2 font-medium transition">Hàng Mới</a>
                    <a href="#" class="text-red-500 hover:text-red-600 px-3 py-2 font-semibold transition">Sale OFF</a>
                </div>
                <div class="flex items-center space-x-5">
                    <!-- Icon Search -->
                    <button class="text-gray-600 hover:text-indigo-600 transition transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <!-- Icon Giỏ hàng -->
                    <a href="index.php?controller=Cart&action=index" class="text-gray-600 hover:text-indigo-600 transition relative transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span id="cart-count" class="absolute -top-1.5 -right-2 bg-indigo-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center shadow-md">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <main class="pt-16 min-h-screen">
