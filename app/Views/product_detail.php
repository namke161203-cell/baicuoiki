<?php require_once 'partials/header.php'; ?>

<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb navigation -->
        <nav class="flex text-sm text-gray-500 mb-10" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-indigo-600 font-medium transition">Trang chủ</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="#" class="hover:text-indigo-600 font-medium transition">Cửa hàng</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-900 font-bold truncate max-w-[200px]"><?= $product['name'] ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
            <!-- Thư viện Ảnh (Gallery) - 7 cột -->
            <div class="lg:col-span-7 flex flex-col-reverse lg:flex-row gap-4 mb-10 lg:mb-0">
                <!-- Thumbnail thu nhỏ -->
                <div class="flex lg:flex-col gap-3 w-full lg:w-24 overflow-x-auto lg:overflow-y-auto hidden-scrollbar py-1">
                    <?php foreach ($product['images'] as $index => $img): ?>
                    <button class="thumbnail-btn w-20 h-24 flex-shrink-0 rounded-xl overflow-hidden border-2 <?= $index === 0 ? 'border-indigo-600 ring-2 ring-indigo-200' : 'border-transparent hover:border-gray-300' ?> transition-all" onclick="changeImage('<?= $img ?>', this)">
                        <img src="<?= $img ?>" class="w-full h-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <!-- Ảnh chính -->
                <div class="flex-1 rounded-2xl overflow-hidden bg-gray-50 shadow-sm relative group">
                    <img id="mainImage" src="<?= $product['images'][0] ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover object-center aspect-[4/5] transition duration-500 group-hover:scale-105">
                </div>
            </div>

            <!-- Thông tin Sản phẩm (Info & Form mua hàng) - 5 cột -->
            <div class="lg:col-span-5 flex flex-col">
                <span class="text-indigo-600 font-semibold tracking-wide text-sm uppercase mb-2">VibeStore Exclusive</span>
                <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4 leading-tight"><?= $product['name'] ?></h1>
                
                <!-- Đánh giá ảo -->
                <div class="flex items-center mb-6">
                    <div class="flex text-yellow-400 mr-2">
                        <?php for($i=0; $i<5; $i++): ?>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <?php endfor; ?>
                    </div>
                    <span class="text-sm text-gray-500 font-medium">(128 Đánh giá)</span>
                </div>

                <div class="text-4xl text-gray-900 font-black mb-6 flex items-baseline">
                    <?= number_format($product['price'], 0, ',', '.') ?> ₫
                    <span class="text-lg text-gray-400 line-through font-medium ml-4">950.000 ₫</span>
                </div>
                
                <p class="text-gray-600 mb-8 leading-relaxed text-base"><?= $product['description'] ?></p>

                <form action="index.php?controller=Cart&action=add" method="POST" class="mt-auto border-t border-gray-100 pt-8" id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <!-- Lựa chọn Màu sắc -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm text-gray-900 font-bold uppercase tracking-wider">Màu sắc: <span id="selected-color-name" class="font-normal text-gray-600 capitalize">Đen</span></h3>
                        </div>
                        <div class="flex items-center space-x-3">
                            <?php 
                            $colors = array_unique(array_column($product['variants'], 'color'));
                            foreach ($colors as $index => $color): 
                                $bgClass = 'bg-gray-200';
                                if(strtolower($color) == 'đen') $bgClass = 'bg-black';
                                if(strtolower($color) == 'trắng') $bgClass = 'bg-white border border-gray-300';
                                if(strtolower($color) == 'xanh rêu') $bgClass = 'bg-green-800';
                            ?>
                            <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-1 focus:outline-none ring-gray-400">
                                <input type="radio" name="color" value="<?= $color ?>" class="sr-only peer" <?= $index === 0 ? 'checked' : '' ?> onchange="document.getElementById('selected-color-name').innerText = this.value;">
                                <span aria-hidden="true" class="h-10 w-10 rounded-full border border-black border-opacity-10 shadow-sm <?= $bgClass ?> transform transition hover:scale-110"></span>
                                <span class="pointer-events-none absolute -inset-px rounded-full border-2 border-transparent peer-checked:border-indigo-600 transition-colors" aria-hidden="true"></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Lựa chọn Kích thước -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm text-gray-900 font-bold uppercase tracking-wider">Kích thước</h3>
                            <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium underline transition">Hướng dẫn size</button>
                        </div>
                        <div class="grid grid-cols-4 gap-3">
                            <?php 
                            $sizes = array_unique(array_column($product['variants'], 'size'));
                            foreach ($sizes as $index => $size): 
                            ?>
                            <label class="group relative flex items-center justify-center rounded-xl border border-gray-200 py-3.5 px-4 text-sm font-bold uppercase hover:bg-gray-50 focus:outline-none sm:flex-1 cursor-pointer bg-white text-gray-900 shadow-sm transition-all">
                                <input type="radio" name="size" value="<?= $size ?>" class="sr-only peer" <?= $index === 0 ? 'checked' : '' ?>>
                                <span><?= $size ?></span>
                                <!-- Vòng viền khi checked -->
                                <span class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-indigo-600" aria-hidden="true"></span>
                                <!-- Background nhạt khi checked -->
                                <div class="absolute inset-0 rounded-xl bg-indigo-50 opacity-0 peer-checked:opacity-100 -z-10 transition"></div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Nút Thêm vào giỏ & Số lượng -->
                    <div class="flex space-x-4 mb-8">
                        <div class="w-24 flex-shrink-0">
                            <select id="quantity" name="quantity" class="w-full h-full rounded-xl border border-gray-300 py-3 px-4 text-lg font-semibold text-gray-700 text-center shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white cursor-pointer appearance-none">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <button type="submit" class="flex-1 bg-indigo-600 border border-transparent rounded-xl py-4 px-8 flex items-center justify-center text-lg font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] transform hover:-translate-y-1">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Thêm Vào Giỏ Hàng
                        </button>
                    </div>
                </form>

                <!-- Cam kết (Trust Badges) -->
                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-600 font-medium">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Hàng chính hãng 100%
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-medium">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        Hỗ trợ 24/7
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Ẩn scrollbar ngang của thumbnail */
.hidden-scrollbar::-webkit-scrollbar {
    display: none;
}
.hidden-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
/* Tuỳ chỉnh Select icon mũi tên xuống */
select#quantity {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
</style>

<script>
    // Script đơn giản đổi ảnh khi click thumbnail
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        
        // Cập nhật viền cho thumbnail đang chọn
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-200');
            btn.classList.add('border-transparent');
        });
        element.classList.remove('border-transparent');
        element.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-200');
    }

    // Hiệu ứng giỏ hàng đơn giản
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        
        // Hiệu ứng loading
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang thêm...`;
        
        setTimeout(() => {
            btn.innerHTML = `<svg class="w-6 h-6 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Đã thêm vào giỏ!`;
            btn.classList.replace('bg-indigo-600', 'bg-green-500');
            btn.classList.replace('hover:bg-indigo-700', 'hover:bg-green-600');
            
            // Cập nhật số trên icon giỏ hàng header
            let countEl = document.getElementById('cart-count');
            countEl.innerText = parseInt(countEl.innerText) + parseInt(document.getElementById('quantity').value);
            
            // Trả lại trạng thái sau 2 giây (Trong thực tế sẽ submit form qua Ajax hoặc chuyển trang)
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.replace('bg-green-500', 'bg-indigo-600');
                btn.classList.replace('hover:bg-green-600', 'hover:bg-indigo-700');
            }, 2000);
            
        }, 800);
    });
</script>

<?php require_once 'partials/footer.php'; ?>
