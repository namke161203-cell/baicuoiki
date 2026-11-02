<?php
/**
 * COMPONENT: Empty State (Molecule - Atomic Design)
 * @param string $title Tiêu đề trạng thái rỗng
 * @param string $description Mô tả chi tiết
 * @param string $btnText Chữ hiển thị trên nút bấm CTA
 * @param string $btnUrl Đường dẫn khi bấm nút CTA
 */
$title = $title ?? 'Chưa có dữ liệu';
$description = $description ?? 'Hiện tại chưa có mục nào trong danh sách.';
$btnText = $btnText ?? 'Tiếp tục mua sắm';
$btnUrl = $btnUrl ?? 'index.php?controller=Product&action=list';
?>
<div class="w-full py-16 px-6 text-center bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center justify-center my-6">
    <div class="w-20 h-20 rounded-full bg-sky-50 flex items-center justify-center text-primary mb-4 shadow-inner">
        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
    </div>
    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight mb-2"><?= htmlspecialchars($title) ?></h3>
    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6"><?= htmlspecialchars($description) ?></p>
    <a href="<?= htmlspecialchars($btnUrl) ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary hover:bg-primary-dark text-white font-bold text-xs uppercase tracking-wider transition-all duration-300 shadow-md hover:shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <?= htmlspecialchars($btnText) ?>
    </a>
</div>
