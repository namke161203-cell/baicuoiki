<!DOCTYPE html>
<html lang="vi">
<?php
// Tính tổng số sản phẩm trong giỏ
$cartTotalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartTotalItems += $item['quantity'];
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'hquie - Thời trang cao cấp' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#34869c',
                        'primary-dark': '#236070',
                    }
                }
            }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-primary text-white text-xs font-semibold text-center py-2 uppercase tracking-wider">
        FREE SHIPPING ON ORDERS OVER $100 | LIMITED TIME: 15% OFF NEW CUSTOMERS
    </div>

    <!-- Main Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left: Logo & Nav -->
                <div class="flex items-center">
                    <a href="index.php" class="text-3xl font-black text-primary tracking-tighter mr-10">hquie</a>
                    
                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex space-x-6 text-sm font-bold text-gray-800 uppercase tracking-wide">
                        <a href="#" class="hover:text-primary transition">Phụ nữ</a>
                        <a href="#" class="hover:text-primary transition">Nam</a>
                        <a href="#" class="hover:text-primary transition">Hàng mới về</a>
                        <a href="#" class="hover:text-primary transition">Bộ sưu tập</a>
                        <a href="#" class="hover:text-primary transition">Thương hiệu</a>
                        <a href="#" class="hover:text-primary transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Tìm kiếm
                        </a>
                    </nav>
                </div>
                
                <!-- Right: Icons -->
                <div class="flex items-center space-x-6 text-sm font-bold text-gray-800 uppercase">
                    <a href="index.php?controller=Auth&action=login" class="hover:text-primary transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Tài khoản
                    </a>
                    <a href="#" class="hover:text-primary transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Yêu thích
                    </a>
                    <a href="index.php?controller=Cart&action=index" class="hover:text-primary transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Giỏ hàng [<span id="cart-count"><?= $cartTotalItems ?></span>]
                    </a>
                </div>
            </div>
        </div>
    </header>
