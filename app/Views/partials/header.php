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
                    <a href="index.php" class="text-3xl font-black text-primary tracking-tighter mr-10 animate-fade-down stagger-2">hquie</a>
                    
                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex space-x-6 text-sm font-bold text-gray-800 uppercase tracking-wide animate-fade-down stagger-1">
                        <a href="index.php?controller=Product&action=category&cat=nữ" class="hover:text-primary transition">Phụ nữ</a>
                        <a href="index.php?controller=Product&action=category&cat=nam" class="hover:text-primary transition">Nam</a>
                        <a href="index.php?controller=Product&action=list" class="hover:text-primary transition">Hàng mới về</a>
                        <a href="index.php#shop" class="hover:text-primary transition">Bộ sưu tập</a>
                        <button onclick="openBrandModal()" class="hover:text-primary transition uppercase font-bold text-sm">Thương hiệu</button>
                        <button onclick="openSearchModal()" class="hover:text-primary transition flex items-center uppercase font-bold text-sm">
                            <svg class="w-4 h-4 mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Tìm kiếm
                        </button>
                    </nav>
                </div>
                
                <!-- Right: Icons -->
                <div class="flex items-center space-x-6 text-sm font-bold text-gray-800 uppercase animate-fade-down stagger-3">
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="index.php?controller=Admin&action=index" class="hover:text-primary transition flex items-center text-red-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Admin Panel
                    </a>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?controller=Profile&action=index" class="hover:text-primary transition flex items-center text-primary">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </a>
                    <?php else: ?>
                    <a href="index.php?controller=Auth&action=login" class="hover:text-primary transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Tài khoản
                    </a>
                    <?php endif; ?>

                    <button onclick="openWishlistModal()" class="hover:text-primary transition flex items-center uppercase font-bold text-sm">
                        <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        Yêu thích [<span id="wishlist-count">0</span>]
                    </button>

                    <a href="index.php?controller=Cart&action=index" class="hover:text-primary transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Giỏ hàng [<span id="cart-count"><?= $cartTotalItems ?></span>]
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- 🔍 SEARCH MODAL -->
    <div id="searchModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-6 relative animate-fade-down">
            <button onclick="closeSearchModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Tìm kiếm sản phẩm
            </h3>
            <form action="index.php" method="GET" class="relative">
                <input type="hidden" name="controller" value="Product">
                <input type="hidden" name="action" value="search">
                <input type="text" name="keyword" id="searchKeywordInput" placeholder="Nhập tên áo khoác, áo len, quần jeans, phụ kiện..." autofocus class="w-full pl-4 pr-24 py-3.5 border-2 border-primary/30 rounded-lg text-base focus:outline-none focus:border-primary">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-primary hover:bg-primary-dark text-white px-5 rounded-md font-bold uppercase text-xs tracking-wider transition">
                    Tìm kiếm
                </button>
            </form>
            <div class="mt-4">
                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Từ khóa gợi ý:</span>
                <div class="flex flex-wrap gap-2 mt-2">
                    <a href="index.php?controller=Product&action=search&keyword=Áo Khoác" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Áo Khoác</a>
                    <a href="index.php?controller=Product&action=search&keyword=Áo Len" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Áo Len Mùa Đông</a>
                    <a href="index.php?controller=Product&action=search&keyword=Quần Jeans" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Quần Jeans</a>
                    <a href="index.php?controller=Product&action=search&keyword=Giày Boots" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Giày Boots</a>
                    <a href="index.php?controller=Product&action=category&cat=nam" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Thời Trang Nam</a>
                    <a href="index.php?controller=Product&action=category&cat=nữ" class="px-3 py-1 bg-gray-100 hover:bg-primary hover:text-white rounded text-xs text-gray-700 font-medium transition">Thời Trang Nữ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ❤️ WISHLIST MODAL -->
    <div id="wishlistModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-xl shadow-2xl p-6 relative max-h-[85vh] flex flex-col animate-fade-down">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    Sản phẩm yêu thích (<span id="wishlistModalCount">0</span>)
                </h3>
                <button onclick="closeWishlistModal()" class="text-gray-400 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="wishlistItemsContainer" class="flex-1 overflow-y-auto py-4 space-y-4 divide-y divide-gray-100">
                <!-- Danh sách items sẽ được render bằng JS -->
            </div>
            <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                <button onclick="clearWishlist()" class="text-xs text-red-500 hover:underline font-semibold">Xóa tất cả</button>
                <button onclick="closeWishlistModal()" class="bg-primary text-white text-xs font-bold px-5 py-2.5 rounded uppercase hover:bg-primary-dark transition">Tiếp tục mua sắm</button>
            </div>
        </div>
    </div>

    <!-- 🏛 BRAND / THƯƠNG HIỆU MODAL -->
    <div id="brandModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6 relative animate-fade-down text-center">
            <button onclick="closeBrandModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="w-16 h-16 bg-teal-50 text-primary mx-auto rounded-full flex items-center justify-center mb-4">
                <span class="text-2xl font-black">h</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">hquie - Contemporary Fashion</h3>
            <p class="text-xs text-primary font-bold uppercase tracking-widest mb-4">Định hình phong cách thời trang đương đại</p>
            <p class="text-sm text-gray-600 leading-relaxed text-justify mb-6">
                Ra đời với sứ mệnh mang đến vẻ đẹp tối giản, thanh lịch nhưng không kém phần phóng khoáng. Bộ sưu tập Mùa Đông 2026 của hquie kết hợp giữa chất liệu len cashmere, dạ cao cấp và da thuộc thủ công, đem đến trải nghiệm giữ ấm tuyệt đối cùng tính thẩm mỹ dẫn đầu xu hướng.
            </p>
            <div class="grid grid-cols-3 gap-3 border-t border-b border-gray-100 py-4 mb-6">
                <div>
                    <span class="block text-xl font-bold text-gray-900">100%</span>
                    <span class="text-[11px] text-gray-500 uppercase font-semibold">Chính hãng</span>
                </div>
                <div>
                    <span class="block text-xl font-bold text-gray-900">7 Ngày</span>
                    <span class="text-[11px] text-gray-500 uppercase font-semibold">Đổi trả tự do</span>
                </div>
                <div>
                    <span class="block text-xl font-bold text-gray-900">24/7</span>
                    <span class="text-[11px] text-gray-500 uppercase font-semibold">Hỗ trợ tận tâm</span>
                </div>
            </div>
            <a href="index.php?controller=Product&action=list" onclick="closeBrandModal()" class="inline-block w-full bg-primary text-white font-bold py-3 rounded uppercase text-sm tracking-wider hover:bg-primary-dark transition">
                Khám phá toàn bộ sản phẩm
            </a>
        </div>
    </div>

    <!-- WISHLIST & MODAL JAVASCRIPT LOGIC -->
    <script>
        // Modal helpers
        function openSearchModal() {
            const m = document.getElementById('searchModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            setTimeout(() => document.getElementById('searchKeywordInput').focus(), 100);
        }
        function closeSearchModal() {
            const m = document.getElementById('searchModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openBrandModal() {
            const m = document.getElementById('brandModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeBrandModal() {
            const m = document.getElementById('brandModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openWishlistModal() {
            renderWishlistModal();
            const m = document.getElementById('wishlistModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeWishlistModal() {
            const m = document.getElementById('wishlistModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearchModal();
                closeBrandModal();
                closeWishlistModal();
            }
        });

        // Wishlist LocalStorage Logic
        function getWishlist() {
            try {
                return JSON.parse(localStorage.getItem('hquie_wishlist')) || [];
            } catch(e) {
                return [];
            }
        }
        function saveWishlist(items) {
            localStorage.setItem('hquie_wishlist', JSON.stringify(items));
            updateWishlistCount();
        }

        function updateWishlistCount() {
            const items = getWishlist();
            const countEl = document.getElementById('wishlist-count');
            const modalCountEl = document.getElementById('wishlistModalCount');
            if (countEl) countEl.innerText = items.length;
            if (modalCountEl) modalCountEl.innerText = items.length;
        }

        function toggleWishlist(id, name, image, price) {
            let items = getWishlist();
            const existingIndex = items.findIndex(item => item.id == id);
            
            if (existingIndex > -1) {
                items.splice(existingIndex, 1);
                showToast(`Đã xóa "${name}" khỏi mục Yêu thích!`);
            } else {
                items.push({ id, name, image, price });
                showToast(`❤️ Đã thêm "${name}" vào mục Yêu thích!`);
            }
            saveWishlist(items);
            renderWishlistModal();
        }

        function removeFromWishlist(id) {
            let items = getWishlist();
            items = items.filter(item => item.id != id);
            saveWishlist(items);
            renderWishlistModal();
        }

        function clearWishlist() {
            if (confirm('Bạn có chắc muốn xóa toàn bộ danh sách yêu thích?')) {
                saveWishlist([]);
                renderWishlistModal();
            }
        }

        function renderWishlistModal() {
            const container = document.getElementById('wishlistItemsContainer');
            if (!container) return;
            const items = getWishlist();
            updateWishlistCount();

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <p class="text-gray-500 text-sm">Chưa có sản phẩm nào trong danh sách yêu thích.</p>
                        <a href="index.php?controller=Product&action=list" onclick="closeWishlistModal()" class="inline-block mt-3 text-primary text-xs font-bold underline uppercase">Khám phá ngay</a>
                    </div>
                `;
                return;
            }

            let html = '';
            items.forEach(item => {
                const formattedPrice = new Intl.NumberFormat('vi-VN').format(item.price) + 'đ';
                html += `
                    <div class="flex items-center justify-between pt-3 pb-3">
                        <div class="flex items-center space-x-3">
                            <img src="${item.image || 'https://via.placeholder.com/80'}" class="w-14 h-18 object-cover rounded bg-gray-100">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 truncate max-w-[200px]">${item.name}</h4>
                                <p class="text-xs text-primary font-black mt-0.5">${formattedPrice}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="index.php?controller=Product&action=detail&id=${item.id}" class="bg-primary hover:bg-primary-dark text-white px-3 py-1.5 rounded text-xs font-bold uppercase transition">
                                Mua ngay
                            </a>
                            <button onclick="removeFromWishlist(${item.id})" class="text-gray-400 hover:text-red-500 p-1" title="Xóa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // Đơn giản Toast Notification
        function showToast(msg) {
            let toast = document.getElementById('globalToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'globalToast';
                toast.className = 'fixed bottom-5 right-5 z-50 bg-gray-900 text-white px-5 py-3 rounded-lg shadow-2xl text-sm font-medium transition-opacity duration-300 pointer-events-none opacity-0';
                document.body.appendChild(toast);
            }
            toast.innerText = msg;
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 3000);
        }

        // Chạy khi tải trang
        document.addEventListener('DOMContentLoaded', updateWishlistCount);
    </script>
