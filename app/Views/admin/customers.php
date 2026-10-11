<?php require_once __DIR__ . '/header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Quản lý Khách Hàng</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <?php if(empty($customers)): ?>
        <p class="text-gray-500 py-4">Chưa có khách hàng nào đăng ký.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-500 font-semibold border-b border-gray-100">
                        <th class="pb-3 px-2">ID</th>
                        <th class="pb-3 px-2">Họ Tên</th>
                        <th class="pb-3 px-2">Email</th>
                        <th class="pb-3 px-2">Số Điện Thoại</th>
                        <th class="pb-3 px-2">Địa Chỉ</th>
                        <th class="pb-3 px-2">Ngày Đăng Ký</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($customers as $customer): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-4 px-2 text-gray-900 font-bold">#<?= $customer['id'] ?></td>
                            <td class="py-4 px-2 font-semibold text-primary"><?= htmlspecialchars($customer['name']) ?></td>
                            <td class="py-4 px-2 text-gray-600"><?= htmlspecialchars($customer['email']) ?></td>
                            <td class="py-4 px-2 text-gray-600"><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                            <td class="py-4 px-2 text-gray-600 truncate max-w-xs"><?= htmlspecialchars($customer['address'] ?? 'N/A') ?></td>
                            <td class="py-4 px-2 text-gray-500"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
