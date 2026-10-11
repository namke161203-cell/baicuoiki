<?php
/**
 * @var array $stats
 * @var array $latestOrders
 * @var array $bestSellers
 * @var string $title
 */
require_once __DIR__ . '/header.php';
?>

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">TỔNG QUAN HỆ THỐNG hquie</h2>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-center relative">
        <p class="text-sm font-semibold text-gray-600 mb-1">Doanh Thu Hôm Nay</p>
        <div class="flex items-end space-x-2">
            <p class="text-2xl font-black text-gray-900 tracking-tight">đ<?= number_format($stats['today_revenue'], 0, ',', '.') ?></p>
            <span class="text-green-500 font-bold mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
            </span>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Đơn Hàng Mới</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['new_orders'] ?></p>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Khách Hàng Mới</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['new_customers'] ?></p>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-red-100 text-red-500 flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Sản Phẩm Hết Hàng</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['out_of_stock'] ?></p>
        </div>
    </div>
</div>

<!-- Main Layout: 2 Columns -->
<div class="flex flex-col lg:flex-row gap-6">
    
    <!-- Left Column (Chart + Table) -->
    <div class="flex-1 space-y-6">
        
        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-6">BIỂU ĐỒ DOANH THU THEO THÁNG</h3>
            <!-- Placeholder for chart -->
            <div class="h-[250px] w-full flex items-center justify-center bg-gradient-to-b from-blue-50 to-white rounded-lg border border-blue-100 relative overflow-hidden">
                <!-- SVG Mock Chart -->
                <svg viewBox="0 0 800 250" class="absolute inset-0 w-full h-full text-primary" preserveAspectRatio="none">
                    <!-- Grid Lines -->
                    <line x1="0" y1="50" x2="800" y2="50" stroke="#f3f4f6" stroke-dasharray="4"/>
                    <line x1="0" y1="100" x2="800" y2="100" stroke="#f3f4f6" stroke-dasharray="4"/>
                    <line x1="0" y1="150" x2="800" y2="150" stroke="#f3f4f6" stroke-dasharray="4"/>
                    <line x1="0" y1="200" x2="800" y2="200" stroke="#f3f4f6" stroke-dasharray="4"/>
                    
                    <!-- Line 1 -->
                    <path d="M0,150 C100,200 200,80 300,100 C400,120 500,40 600,60 C700,80 800,150 800,150" fill="none" stroke="currentColor" stroke-width="2" class="opacity-70"/>
                    <path d="M0,150 C100,200 200,80 300,100 C400,120 500,40 600,60 C700,80 800,150 800,150 L800,250 L0,250 Z" fill="currentColor" class="opacity-10"/>
                    
                    <!-- Line 2 -->
                    <path d="M0,200 C150,150 250,220 400,120 C550,20 650,200 800,80" fill="none" stroke="#2563eb" stroke-width="2" class="opacity-80"/>
                    <path d="M0,200 C150,150 250,220 400,120 C550,20 650,200 800,80 L800,250 L0,250 Z" fill="#2563eb" class="opacity-10"/>

                    <!-- Line 3 -->
                    <path d="M0,100 C100,120 200,180 350,150 C500,120 600,220 800,130" fill="none" stroke="#0ea5e9" stroke-width="2"/>
                    <path d="M0,100 C100,120 200,180 350,150 C500,120 600,220 800,130 L800,250 L0,250 Z" fill="#0ea5e9" class="opacity-10"/>

                    <!-- Points -->
                    <circle cx="200" cy="80" r="4" fill="currentColor"/>
                    <circle cx="300" cy="100" r="4" fill="currentColor"/>
                    <circle cx="500" cy="40" r="4" fill="currentColor"/>
                    <circle cx="400" cy="120" r="4" fill="#2563eb"/>
                    <circle cx="800" cy="80" r="4" fill="#2563eb"/>
                </svg>
                
                <!-- Y Axis labels mock -->
                <div class="absolute left-2 top-0 h-full py-4 flex flex-col justify-between text-[10px] text-gray-400 font-medium">
                    <span>đ2.250.000</span>
                    <span>đ2.000.000</span>
                    <span>đ1.500.000</span>
                    <span>đ1.000.000</span>
                    <span>đ500.000</span>
                    <span>đ0</span>
                </div>
                <!-- X Axis labels mock -->
                <div class="absolute bottom-2 w-full px-12 flex justify-between text-[10px] text-gray-500 font-medium">
                    <span>January</span>
                    <span>February</span>
                    <span>March</span>
                    <span>April</span>
                    <span>May</span>
                    <span>6 tháng</span>
                </div>
            </div>
        </div>

        <!-- Orders Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-6">ĐƠN HÀNG MỚI NHẤT</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-500 font-semibold border-b border-gray-100">
                            <th class="pb-3 px-2">Mã Đơn</th>
                            <th class="pb-3 px-2">Khách Hàng</th>
                            <th class="pb-3 px-2">Ngày Đặt</th>
                            <th class="pb-3 px-2">Trạng Thái</th>
                            <th class="pb-3 px-2 text-right">Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if (empty($latestOrders)): ?>
                            <tr>
                                <td colspan="5" class="py-4 px-2 text-center text-gray-500">Chưa có đơn hàng nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($latestOrders as $order): ?>
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                                    <td class="py-4 px-2 text-gray-900">M<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td class="py-4 px-2"><?= htmlspecialchars($order['customer_name']) ?></td>
                                    <td class="py-4 px-2 text-gray-500"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                    <td class="py-4 px-2">
                                        <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'shipped' => 'bg-cyan-100 text-cyan-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700'
                                        ];
                                        $color = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-700';
                                        ?>
                                        <span class="<?= $color ?> px-3 py-1 rounded-full text-xs font-semibold"><?= $order['status'] ?></span>
                                    </td>
                                    <td class="py-4 px-2 text-right font-bold text-gray-900">đ<?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column (Best Sellers) -->
    <div class="w-full lg:w-[360px]">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">SẢN PHẨM BÁN CHẠY</h3>
                <span class="text-xs text-primary font-medium">Số lượng</span>
            </div>
            
            <div class="space-y-4">
                <?php if (empty($bestSellers)): ?>
                    <p class="text-sm text-gray-500 text-center py-4">Chưa có dữ liệu sản phẩm bán chạy.</p>
                <?php else: ?>
                    <?php foreach ($bestSellers as $product): ?>
                        <div class="flex items-center justify-between pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div class="flex items-center">
                                <?php if ($product['image']): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" class="w-10 h-12 object-cover bg-gray-100 rounded mr-3">
                                <?php else: ?>
                                    <div class="w-10 h-12 bg-gray-100 rounded mr-3 flex items-center justify-center text-xs text-gray-400">N/A</div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm text-gray-800 leading-tight mb-1 truncate max-w-[150px]"><?= htmlspecialchars($product['name']) ?></p>
                                    <p class="text-sm font-bold text-gray-900">đ<?= number_format($product['price'], 0, ',', '.') ?></p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 font-medium"><?= $product['total_sold'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>