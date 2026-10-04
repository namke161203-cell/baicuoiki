<?php 
/**
 * @var array $featuredProducts
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<!-- Hero Banner -->
<div class="relative bg-gray-100 h-[600px] flex items-center">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1515347619362-6584617da9d8?q=80&w=2000&auto=format&fit=crop" alt="Hero" class="w-full h-full object-cover object-right">
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-lg">
            <h1 class="text-4xl sm:text-5xl font-black text-gray-900 leading-tight mb-6 uppercase">Tìm phong cách biển của bạn.<br>Mua bộ sưu tập Wave Rider</h1>
            <a href="#shop" class="inline-block bg-primary text-white font-bold px-8 py-3 uppercase tracking-wider hover:bg-primary-dark transition shadow-lg">
                Shop Now
            </a>
        </div>
    </div>
</div>

<!-- Shop By Category -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-xl font-bold text-gray-900 uppercase tracking-widest mb-8">Shop By Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Category Card -->
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1499939667766-4afceb292d05?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-primary bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Sống ven<br>biển</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-primary bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Váy mùa<br>Dresses</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1550614000-4b95d466989b?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-primary bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Cơ bản<br>nâng cấp</h3>
            </div>
        </a>
        <a href="#" class="relative h-64 overflow-hidden group border border-gray-200">
            <img src="https://images.unsplash.com/photo-1564859228273-274232fdb916?w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-primary bg-opacity-40 flex items-center justify-center transition group-hover:bg-opacity-50">
                <h3 class="text-white text-xl font-bold uppercase text-center drop-shadow-md">Đồ bơi</h3>
            </div>
        </a>
    </div>
</div>

<!-- Featured Products -->
<div id="shop" class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900 uppercase tracking-widest mb-8">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featuredProducts as $item): ?>
            <div class="group bg-white p-4 border border-gray-100 shadow-sm hover:shadow-lg transition">
                <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-gray-100">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 flex items-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="w-full bg-primary text-white text-center py-3 font-bold uppercase hover:bg-primary-dark transition text-sm tracking-wider">
                            Mua Ngay
                        </a>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 uppercase truncate">
                    <a href="index.php?controller=Product&action=detail&id=<?= $item['id'] ?>" class="hover:text-primary"><?= $item['name'] ?></a>
                </h3>
                <p class="text-sm font-bold text-gray-600 mt-1"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
