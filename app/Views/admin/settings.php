<?php require_once __DIR__ . '/header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Cài Đặt Hệ Thống</h2>
</div>

<form action="index.php?controller=AdminSetting&action=update" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-4xl">
    
    <?php if(!empty($success)): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-md mb-6 border border-green-200">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tên Cửa Hàng</label>
            <input type="text" name="store_name" value="hquie - Thời trang cao cấp" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition text-gray-700">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Liên Hệ</label>
            <input type="email" name="store_email" value="contact@hquie.com" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition text-gray-700">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Số Điện Thoại Hotline</label>
            <input type="text" name="store_phone" value="1900 1234" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:outline-none focus:border-primary transition text-gray-700">
        </div>

        <div class="pt-4 border-t border-gray-100">
            <button type="submit" class="bg-primary text-white font-bold py-2.5 px-8 rounded-md shadow-md hover:bg-primary-dark transition text-sm">
                Lưu Cấu Hình
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
