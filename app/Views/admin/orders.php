<?php
/**
 * @var array $orders
 * @var string $title
 */
require_once __DIR__ . '/header.php';
?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Quản lý Đơn hàng</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <?php if(empty($orders)): ?>
        <p class="text-gray-500 py-4">Chưa có đơn hàng nào trong hệ thống.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-500 font-semibold border-b border-gray-100">
                        <th class="pb-3 px-2">Mã Đơn</th>
                        <th class="pb-3 px-2">Ngày Đặt</th>
                        <th class="pb-3 px-2">Khách Hàng</th>
                        <th class="pb-3 px-2">Sản Phẩm</th>
                        <th class="pb-3 px-2 text-right">Tổng Tiền</th>
                        <th class="pb-3 px-2">Trạng Thái</th>
                        <th class="pb-3 px-2 text-center">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-4 px-2 text-gray-900 font-bold">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                            <td class="py-4 px-2 text-gray-500 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-4 px-2">
                                <p class="font-bold text-gray-800"><?= htmlspecialchars($order['customer_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($order['customer_phone']) ?></p>
                            </td>
                            <td class="py-4 px-2">
                                <div class="max-w-xs space-y-1">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="text-xs">
                                            <span class="font-medium text-gray-800"><?= htmlspecialchars($item['product_name']) ?></span>
                                            <span class="text-gray-500">(<?= $item['size'] ?>/<?= $item['color'] ?>) x <?= $item['quantity'] ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="py-4 px-2 text-right font-bold text-gray-900">đ<?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                            <td class="py-4 px-2">
                                <?php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipped' => 'bg-cyan-100 text-cyan-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700'
                                ];
                                $statusClass = $statusClasses[$order['status']] ?? 'bg-gray-100 text-gray-700';
                                ?>
                                <span class="<?= $statusClass ?> px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                            <td class="py-4 px-2">
                                <form action="index.php?controller=AdminOrder&action=updateStatus" method="POST" class="flex items-center justify-center space-x-2">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" class="text-xs border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-primary">
                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="bg-primary text-white text-xs px-3 py-1.5 rounded-md hover:bg-primary-dark transition font-semibold">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
