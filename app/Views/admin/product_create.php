<?php
/**
 * @var array $categories
 * @var string $title
 */
require_once __DIR__ . '/header.php';
?>

<div class="mb-6 flex items-center">
    <a href="index.php?controller=AdminProduct&action=index" class="mr-4 text-gray-500 hover:text-primary transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Thêm Sản Phẩm Mới</h2>
</div>

<form action="index.php?controller=AdminProduct&action=store" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-4xl">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Thông tin cơ bản -->
        <div class="space-y-5">
            <h3 class="text-sm font-bold text-gray-800 uppercase border-b border-gray-100 pb-2">Thông tin cơ bản</h3>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tên Sản Phẩm *</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Danh mục</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition bg-white">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Giá Bán (VNĐ) *</label>
                <input type="number" name="price" required min="0" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sản phẩm nổi bật?</label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-700">Có</span>
                </label>
            </div>
        </div>

        <!-- Hình ảnh & Mô tả -->
        <div class="space-y-5">
            <h3 class="text-sm font-bold text-gray-800 uppercase border-b border-gray-100 pb-2">Chi tiết</h3>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Hình ảnh sản phẩm</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-md p-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark transition cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">Có thể chọn nhiều ảnh. Ảnh đầu tiên sẽ làm ảnh bìa.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mô tả sản phẩm</label>
                <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition"></textarea>
            </div>
        </div>
    </div>

    <!-- Biến thể sản phẩm (Size, Màu) -->
    <div class="mb-8">
        <div class="flex justify-between items-center border-b border-gray-100 pb-2 mb-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase">Phân loại hàng (Màu sắc, Size & Tồn kho)</h3>
            <button type="button" id="add-variant" class="text-xs font-bold text-primary hover:text-primary-dark transition border border-primary px-3 py-1.5 rounded">+ Thêm phân loại</button>
        </div>
        
        <div id="variants-container" class="space-y-3">
            <!-- Một dòng trống mặc định để nhập -->
            <div class="flex items-center space-x-4 bg-gray-50 p-3 rounded-md border border-gray-100 variant-row">
                <div class="w-24">
                    <input type="text" name="sizes[]" placeholder="Nhập Size..." class="w-full border border-gray-300 rounded-md p-2 text-sm font-bold text-gray-700 focus:outline-none focus:border-primary">
                </div>
                <div class="flex-1">
                    <input type="text" name="colors[]" placeholder="Màu sắc (vd: Đen, Trắng...)" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="flex-1">
                    <input type="number" name="stocks[]" placeholder="Tồn kho (còn bao nhiêu chiếc)" min="0" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <button type="button" class="remove-variant text-red-400 hover:text-red-600 transition" title="Xóa dòng này">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Nút Lưu -->
    <div class="flex justify-end pt-4 border-t border-gray-100">
        <a href="index.php?controller=AdminProduct&action=index" class="bg-gray-100 text-gray-600 font-bold py-2.5 px-6 rounded-md hover:bg-gray-200 transition text-sm mr-3">Hủy</a>
        <button type="submit" class="bg-primary text-white font-bold py-2.5 px-8 rounded-md shadow-md hover:bg-primary-dark transition text-sm">
            Lưu Sản Phẩm
        </button>
    </div>
</form>

<script>
    document.getElementById('add-variant').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const html = `
            <div class="flex items-center space-x-4 bg-gray-50 p-3 rounded-md border border-gray-100 mt-3">
                <div class="w-24">
                    <input type="text" name="sizes[]" placeholder="Nhập Size..." class="w-full border border-gray-300 rounded-md p-2 text-sm font-bold text-gray-700 focus:outline-none focus:border-primary">
                </div>
                <div class="flex-1">
                    <input type="text" name="colors[]" placeholder="Màu sắc (vd: Đen, Trắng...)" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="flex-1">
                    <input type="number" name="stocks[]" placeholder="Tồn kho (còn bao nhiêu chiếc)" min="0" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <button type="button" class="remove-variant text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    document.getElementById('variants-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            e.target.closest('.flex').remove();
        }
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
