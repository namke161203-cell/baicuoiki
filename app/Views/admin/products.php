<?php
/**
 * @var array $products
 * @var string $title
 */
require_once __DIR__ . '/header.php';
?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Quản lý Sản Phẩm</h2>
    <a href="index.php?controller=AdminProduct&action=create" class="bg-primary text-white font-bold py-2 px-6 rounded-lg shadow-sm hover:bg-primary-dark transition text-sm">
        + Thêm Sản Phẩm Mới
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <?php if(empty($products)): ?>
        <div class="text-center py-10">
            <p class="text-gray-500 mb-4">Chưa có sản phẩm nào trong kho.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-500 font-semibold border-b border-gray-100">
                        <th class="pb-3 px-2 w-16">ID</th>
                        <th class="pb-3 px-2 w-20">Ảnh</th>
                        <th class="pb-3 px-2">Tên Sản Phẩm</th>
                        <th class="pb-3 px-2">Danh Mục</th>
                        <th class="pb-3 px-2">Giá Bán</th>
                        <th class="pb-3 px-2 text-center">Nổi bật</th>
                        <th class="pb-3 px-2 text-right">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($products as $product): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-4 px-2 text-gray-500 font-medium">#<?= $product['id'] ?></td>
                            <td class="py-4 px-2">
                                <?php if ($product['image']): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="" class="w-12 h-12 object-cover rounded bg-gray-100">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-100 flex items-center justify-center text-xs text-gray-400 rounded">N/A</div>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-bold text-gray-800"><?= htmlspecialchars($product['name']) ?></p>
                                <p class="text-xs text-gray-500 mt-1">Tồn kho: <?= htmlspecialchars($product['variants_info'] ?? 'Chưa phân loại') ?></p>
                            </td>
                            <td class="py-4 px-2 text-gray-600"><?= htmlspecialchars($product['category_name'] ?? 'Không rõ') ?></td>
                            <td class="py-4 px-2 font-bold text-primary">đ<?= number_format($product['price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-2 text-center">
                                <?php if ($product['is_featured']): ?>
                                    <span class="text-yellow-500"><svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></span>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-2 text-right">
                                <a href="index.php?controller=AdminProduct&action=delete&id=<?= $product['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');" class="text-red-500 hover:text-red-700 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition text-xs ml-2">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
