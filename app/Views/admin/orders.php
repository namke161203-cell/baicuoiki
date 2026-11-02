<?php
/**
 * @var array $orders
 * @var array $orderStats
 * @var string $title
 */
require_once __DIR__ . '/header.php';

$orderStats = $orderStats ?? [
    'total_orders' => count($orders),
    'total_revenue' => array_sum(array_map(fn($o) => $o['status'] !== 'cancelled' ? $o['total_amount'] : 0, $orders)),
    'count_pending' => count(array_filter($orders, fn($o) => $o['status'] === 'pending')),
    'count_processing' => count(array_filter($orders, fn($o) => $o['status'] === 'processing')),
    'count_shipped' => count(array_filter($orders, fn($o) => $o['status'] === 'shipped')),
    'count_completed' => count(array_filter($orders, fn($o) => $o['status'] === 'completed')),
    'count_cancelled' => count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'))
];
?>

<div class="space-y-6">
    <!-- Top Bar: Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Quản Lý Đơn Hàng & Vận Chuyển</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-orange-100 text-orange-800"><?= count($orders) ?> đơn hàng</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Theo dõi hành trình đơn, xác nhận bưu tá, đối soát doanh thu COD và in phiếu xuất kho chuẩn hóa đơn.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="index.php?controller=AdminOrder&action=exportCSV" class="px-4 py-2.5 rounded-full border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold transition flex items-center shadow-sm">
                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Xuất File Đơn (CSV)
            </a>
        </div>
    </div>

    <!-- 5 Pipeline Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Tổng Doanh Thu</span>
            <span class="text-lg font-black text-gray-900"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?>đ</span>
            <span class="text-[10px] text-gray-400 block mt-0.5 font-semibold"><?= count($orders) ?> đơn phát sinh</span>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-amber-400 transition" onclick="filterAdminOrdersTab('pending')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-600">Chờ Duyệt</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <span class="text-xl font-black text-amber-600"><?= $orderStats['count_pending'] ?></span>
            <span class="text-[10px] text-amber-600 font-semibold block mt-0.5">Cần xử lý ngay</span>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-blue-400 transition" onclick="filterAdminOrdersTab('processing')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">Đang Đóng Gói</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <span class="text-xl font-black text-blue-600"><?= $orderStats['count_processing'] ?></span>
            <span class="text-[10px] text-blue-600 font-semibold block mt-0.5">Chuẩn bị kiện</span>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-purple-400 transition" onclick="filterAdminOrdersTab('shipped')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-purple-600">Đang Giao Hàng</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <span class="text-xl font-black text-purple-600"><?= $orderStats['count_shipped'] ?></span>
            <span class="text-[10px] text-purple-600 font-semibold block mt-0.5">Đã bàn giao ship</span>
        </div>

        <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm cursor-pointer hover:border-emerald-400 transition" onclick="filterAdminOrdersTab('completed')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Hoàn Thành</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <span class="text-xl font-black text-emerald-600"><?= $orderStats['count_completed'] ?></span>
            <span class="text-[10px] text-emerald-600 font-semibold block mt-0.5">Giao thành công</span>
        </div>
    </div>

    <!-- Status Tabs Filter & Live Search Toolbar -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center space-x-1 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
            <button type="button" onclick="filterAdminOrdersTab('all', this)" class="admin-order-status-tab active px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-gray-900 text-white shadow-sm cursor-pointer">
                Tất cả (<?= count($orders) ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('pending', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Chờ duyệt (<?= $orderStats['count_pending'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('processing', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đang đóng gói (<?= $orderStats['count_processing'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('shipped', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đang giao (<?= $orderStats['count_shipped'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('completed', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Hoàn thành (<?= $orderStats['count_completed'] ?>)
            </button>
            <button type="button" onclick="filterAdminOrdersTab('cancelled', this)" class="admin-order-status-tab px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap text-gray-600 hover:bg-gray-100 cursor-pointer">
                Đã hủy (<?= $orderStats['count_cancelled'] ?>)
            </button>
        </div>

        <div class="relative w-full lg:w-80">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="adminOrderSearch" oninput="filterAdminOrdersList()" placeholder="Tìm theo mã đơn #000..., tên, SĐT..." class="w-full pl-10 pr-4 py-2 bg-gray-50 rounded-full border border-gray-200 text-xs font-semibold focus:outline-none focus:bg-white focus:border-gray-900 transition">
        </div>
    </div>

    <!-- Orders Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="adminOrdersTable">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="pb-4">Mã Đơn</th>
                        <th class="pb-4">Khách Hàng</th>
                        <th class="pb-4 min-w-[200px]">Chi Tiết Sản Phẩm</th>
                        <th class="pb-4">Thời Gian Đặt</th>
                        <th class="pb-4 text-right">Tổng Tiền</th>
                        <th class="pb-4 text-center">Trạng Thái</th>
                        <th class="pb-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="adminOrdersTbody">
                    <?php foreach ($orders as $ord): 
                        $status = strtolower($ord['status']);
                        $items = $ord['items'] ?? [];
                        $firstItem = $items[0] ?? null;
                        $otherCount = count($items) - 1;
                    ?>
                    <tr class="admin-order-row hover:bg-gray-50/70 transition"
                        data-id="#<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?>"
                        data-customer="<?= strtolower(htmlspecialchars($ord['customer_name'])) ?>"
                        data-phone="<?= htmlspecialchars($ord['customer_phone']) ?>"
                        data-status="<?= $status ?>">
                        
                        <td class="py-4 font-black text-gray-900 whitespace-nowrap">
                            <button type="button" onclick="openOrderDetailById(<?= $ord['id'] ?>)" class="hover:text-teal-600 transition underline underline-offset-4 decoration-gray-300">
                                #<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?>
                            </button>
                        </td>

                        <td class="py-4">
                            <p class="font-bold text-gray-900 text-xs"><?= htmlspecialchars($ord['customer_name']) ?></p>
                            <p class="text-[11px] text-gray-500 font-semibold"><?= htmlspecialchars($ord['customer_phone']) ?></p>
                            <p class="text-[10px] text-gray-400 truncate max-w-[180px]"><?= htmlspecialchars($ord['shipping_address']) ?></p>
                        </td>

                        <td class="py-4">
                            <?php if ($firstItem): ?>
                            <div class="flex items-center space-x-2.5">
                                <img src="<?= htmlspecialchars($firstItem['product_image'] ?? 'https://via.placeholder.com/60') ?>" class="w-9 h-11 object-cover rounded-lg bg-gray-100 border border-gray-200 flex-shrink-0">
                                <div class="text-xs">
                                    <p class="font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($firstItem['product_name']) ?></p>
                                    <span class="text-[11px] text-gray-500">
                                        <?= htmlspecialchars($firstItem['size']) ?> / <?= htmlspecialchars($firstItem['color']) ?> &times; <?= $firstItem['quantity'] ?>
                                    </span>
                                    <?php if ($otherCount > 0): ?>
                                    <span class="text-[10px] font-bold text-teal-600 block">+<?= $otherCount ?> sản phẩm khác</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">Không có dữ liệu món</span>
                            <?php endif; ?>
                        </td>

                        <td class="py-4 text-xs text-gray-500 whitespace-nowrap font-medium">
                            <?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?>
                        </td>

                        <td class="py-4 text-right font-black text-gray-900 whitespace-nowrap">
                            <?= number_format($ord['total_amount'], 0, ',', '.') ?>đ
                        </td>

                        <td class="py-4 text-center whitespace-nowrap">
                            <?php
                            $badgeMap = [
                                'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Chờ duyệt'],
                                'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Đang đóng gói'],
                                'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Đang giao'],
                                'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'label' => 'Hoàn thành'],
                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Đã hủy']
                            ];
                            $currBadge = $badgeMap[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status)];
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider <?= $currBadge['bg'] ?> <?= $currBadge['text'] ?>">
                                <?= $currBadge['label'] ?>
                            </span>
                        </td>

                        <td class="py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1.5">
                                <button type="button" onclick="printOrderInvoiceById(<?= $ord['id'] ?>)" class="p-1.5 text-gray-500 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition cursor-pointer" title="In Hóa Đơn / Phiếu Giao Hàng">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>

                                <form action="index.php?controller=AdminOrder&action=updateStatus" method="POST" class="inline-block">
                                    <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold border border-gray-200 rounded-xl px-2 py-1 bg-gray-50 text-gray-800 focus:outline-none focus:border-gray-900 cursor-pointer">
                                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                                        <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>Đang gói</option>
                                        <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Đang giao</option>
                                        <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                        <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                    </select>
                                </form>

                                <button type="button" onclick="openOrderDetailById(<?= $ord['id'] ?>)" class="px-2.5 py-1 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition cursor-pointer">
                                    Chi tiết &rarr;
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="adminOrderEmptyState" class="hidden text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-sm font-bold text-gray-800">Không tìm thấy đơn hàng phù hợp</p>
            <p class="text-xs text-gray-400 mt-1">Hãy thử tìm theo từ khóa hoặc mã đơn khác.</p>
        </div>
    </div>
</div>

<!-- Order Detail Drawer Modal -->
<div id="orderDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-3xl rounded-3xl p-6 sm:p-8 shadow-2xl border border-gray-100 transform transition-all max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                    <h3 class="font-black text-xl text-gray-900 tracking-tight" id="odModalId">Đơn Hàng #000000</h3>
                    <span id="odModalStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase">Pending</span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5" id="odModalDate">Ngày đặt: --/--/----</p>
            </div>
            <button type="button" onclick="closeOrderDetail()" class="text-gray-400 hover:text-gray-900 text-2xl font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <div class="py-6 border-b border-gray-100">
            <div class="relative">
                <div class="h-1 bg-gray-100 rounded-full w-full absolute top-3.5 left-0 z-0"></div>
                <div id="odStepperProgress" class="h-1 bg-teal-500 rounded-full absolute top-3.5 left-0 z-0 transition-all duration-500" style="width: 25%;"></div>
                
                <div class="relative z-10 flex justify-between">
                    <div class="flex flex-col items-center">
                        <div id="step-dot-pending" class="w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md">1</div>
                        <span class="text-[11px] font-bold text-gray-800 mt-1.5">Đặt Hàng</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="step-dot-processing" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">2</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Đóng Gói</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="step-dot-shipped" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">3</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Đang Giao</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="step-dot-completed" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center">4</div>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5">Hoàn Thành</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-b border-gray-100">
            <div class="space-y-3 bg-gray-50/70 p-4 rounded-2xl">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Thông Tin Khách Hàng
                </h4>
                <div class="text-xs space-y-1.5 text-gray-600">
                    <p><span class="font-bold text-gray-800">Họ và tên:</span> <span id="odCustomerName" class="font-semibold text-gray-900"></span></p>
                    <p><span class="font-bold text-gray-800">Số điện thoại:</span> <a id="odCustomerPhone" href="#" class="text-teal-600 font-bold hover:underline"></a></p>
                    <p><span class="font-bold text-gray-800">Email:</span> <span id="odCustomerEmail"></span></p>
                    <p><span class="font-bold text-gray-800">Địa chỉ nhận hàng:</span> <span id="odCustomerAddress" class="text-gray-900 font-medium"></span></p>
                    <p><span class="font-bold text-gray-800">Phương thức:</span> <span id="odPaymentMethod" class="px-2 py-0.5 rounded bg-gray-200 font-bold text-[10px] text-gray-800 uppercase">COD</span></p>
                </div>
            </div>

            <div class="space-y-3 bg-gray-50/70 p-4 rounded-2xl flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                        Tổng Kết Đơn Hàng
                    </h4>
                    <div class="text-xs space-y-2 text-gray-600 mt-2">
                        <div class="flex justify-between">
                            <span>Tạm tính hàng hóa:</span>
                            <span id="odSubtotal" class="font-bold text-gray-900">0đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span class="font-bold text-emerald-600">Miễn phí (Free)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ưu đãi voucher:</span>
                            <span class="font-bold text-orange-600">-0đ</span>
                        </div>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between items-baseline">
                    <span class="text-xs font-black uppercase tracking-wider text-gray-900">Tổng Thanh Toán:</span>
                    <span id="odTotalAmount" class="text-xl font-black text-gray-950">0đ</span>
                </div>
            </div>
        </div>

        <div class="py-6">
            <h4 class="text-xs font-black uppercase tracking-wider text-gray-700 mb-3">Danh Sách Món Hàng (<span id="odItemsCount">0</span>)</h4>
            <div class="border border-gray-100 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-400 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="py-2.5 px-3">Sản phẩm</th>
                            <th class="py-2.5 px-3 text-center">Biến thể</th>
                            <th class="py-2.5 px-3 text-right">Đơn giá</th>
                            <th class="py-2.5 px-3 text-center">Số lượng</th>
                            <th class="py-2.5 px-3 text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="odItemsTbody" class="divide-y divide-gray-100 text-gray-700">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <form action="index.php?controller=AdminOrder&action=updateStatus" method="POST" class="flex items-center space-x-2">
                <input type="hidden" name="order_id" id="odFormOrderId">
                <label class="text-xs font-bold text-gray-700 whitespace-nowrap">Đổi trạng thái:</label>
                <select name="status" id="odFormStatusSelect" class="text-xs font-bold border border-gray-300 rounded-xl px-3 py-2 bg-white text-gray-900 focus:outline-none focus:border-teal-500">
                    <option value="pending">Chờ duyệt (Pending)</option>
                    <option value="processing">Đang đóng gói (Processing)</option>
                    <option value="shipped">Đang giao hàng (Shipped)</option>
                    <option value="completed">Hoàn thành (Completed)</option>
                    <option value="cancelled">Đã hủy (Cancelled)</option>
                </select>
                <button type="submit" class="px-3.5 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition cursor-pointer">
                    Cập nhật
                </button>
            </form>

            <div class="flex items-center space-x-2.5">
                <button type="button" id="odPrintInvoiceBtn" onclick="triggerPrintCurrentOrder()" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-full text-xs font-bold transition flex items-center shadow-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    In Hóa Đơn
                </button>
                <button type="button" onclick="closeOrderDetail()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs font-bold transition cursor-pointer">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Invoice Print Modal -->
<div id="orderInvoicePrintModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl rounded-3xl p-8 shadow-2xl relative my-auto">
        <div class="flex items-center justify-between pb-6 border-b border-gray-200 print:hidden">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Xem Trước Bản In Hóa Đơn Khách Hàng</span>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="px-5 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-full transition flex items-center shadow-md cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    In Ngay (Print)
                </button>
                <button type="button" onclick="closePrintInvoice()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-full transition cursor-pointer">
                    Đóng
                </button>
            </div>
        </div>

        <div id="printableInvoiceArea" class="py-6 text-gray-900 font-sans">
            <div class="flex justify-between items-start pb-6 border-b-2 border-gray-900">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900">hquie Atelier</h2>
                    <p class="text-xs text-gray-500 font-medium">Thời Trang Cao Cấp &bull; Winter Studio 2026</p>
                    <p class="text-[11px] text-gray-500 mt-1">Hotline: 1900 8888 &bull; Website: hquie.studio</p>
                    <p class="text-[11px] text-gray-500">Địa chỉ: 124 Phố Huế, Hoàn Kiếm, Hà Nội</p>
                </div>
                <div class="text-right">
                    <h3 class="text-lg font-black uppercase text-gray-900 tracking-wider">Hóa Đơn Bán Lẻ</h3>
                    <p class="text-xs font-bold text-gray-700 mt-1" id="invOrderId">Mã Đơn: #000000</p>
                    <p class="text-[11px] text-gray-500" id="invOrderDate">Ngày: --/--/----</p>
                    <div class="mt-2 inline-flex flex-col items-end">
                        <div class="h-6 w-32 bg-gradient-to-r from-gray-900 via-gray-400 to-gray-900 flex items-center justify-around px-1">
                            <div class="w-1 h-full bg-white"></div>
                            <div class="w-0.5 h-full bg-white"></div>
                            <div class="w-1.5 h-full bg-white"></div>
                            <div class="w-0.5 h-full bg-white"></div>
                        </div>
                        <span class="text-[9px] font-mono text-gray-400 tracking-widest" id="invBarcode">HQ-2026-0000</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 py-4 text-xs border-b border-gray-200">
                <div>
                    <span class="font-bold text-gray-500 uppercase text-[10px] block mb-1">Khách Hàng:</span>
                    <p class="font-extrabold text-sm text-gray-900" id="invCustomerName">---</p>
                    <p class="text-gray-600 font-semibold" id="invCustomerPhone">---</p>
                </div>
                <div>
                    <span class="font-bold text-gray-500 uppercase text-[10px] block mb-1">Địa Chỉ Nhận Hàng:</span>
                    <p class="text-gray-800 font-medium" id="invCustomerAddress">---</p>
                    <p class="text-[11px] text-gray-500 mt-0.5"><span class="font-bold">Phương thức:</span> Thanh toán khi nhận hàng (COD)</p>
                </div>
            </div>

            <div class="py-4">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-300 font-black text-gray-700 uppercase">
                            <th class="py-2">Sản Phẩm</th>
                            <th class="py-2 text-center">Phân Loại</th>
                            <th class="py-2 text-right">Đơn Giá</th>
                            <th class="py-2 text-center">SL</th>
                            <th class="py-2 text-right">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody id="invItemsTbody" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>

            <div class="py-3 border-t-2 border-gray-900 text-xs flex justify-end">
                <div class="w-64 space-y-1.5">
                    <div class="flex justify-between text-gray-600">
                        <span>Tiền hàng:</span>
                        <span id="invSubtotal" class="font-bold text-gray-900">0đ</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Phí vận chuyển:</span>
                        <span class="font-bold text-emerald-600">0đ (Miễn phí)</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-gray-950 pt-2 border-t border-gray-300">
                        <span>Tổng Thanh Toán:</span>
                        <span id="invTotalAmount">0đ</span>
                    </div>
                </div>
            </div>

            <div class="pt-8 grid grid-cols-2 text-center text-xs text-gray-600">
                <div>
                    <p class="font-bold text-gray-900">Người Lập Phiếu</p>
                    <p class="text-[10px] text-gray-400 italic mt-1">(Ký và ghi rõ họ tên)</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-gray-800">Bộ phận kho hquie</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Người Nhận Hàng</p>
                    <p class="text-[10px] text-gray-400 italic mt-1">(Kiểm tra hàng trước khi ký)</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-gray-800" id="invSignCustomerName"></p>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-dashed border-gray-300 text-center text-[11px] text-gray-500 italic">
                Cảm ơn Quý khách đã tin tưởng lựa chọn hquie Atelier! Hỗ trợ đổi trả trong vòng 30 ngày.
            </div>
        </div>
    </div>
</div>

<script>
    const adminOrdersData = <?= json_encode($orders ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    function openOrderDetailById(id) {
        const ord = adminOrdersData.find(item => item.id == id);
        if (ord) openOrderDetail(ord);
    }

    function printOrderInvoiceById(id) {
        const ord = adminOrdersData.find(item => item.id == id);
        if (ord) printOrderInvoice(ord);
    }

    let currentActiveOrderStatus = 'all';
    window.currentViewingOrder = null;

    function filterAdminOrdersTab(status, btnElement) {
        currentActiveOrderStatus = status;
        document.querySelectorAll('.admin-order-status-tab').forEach(btn => {
            btn.classList.remove('bg-gray-900', 'text-white', 'shadow-sm', 'active');
            btn.classList.add('text-gray-600');
        });
        if (btnElement) {
            btnElement.classList.add('bg-gray-900', 'text-white', 'shadow-sm', 'active');
            btnElement.classList.remove('text-gray-600');
        }
        filterAdminOrdersList();
    }

    function filterAdminOrdersList() {
        const query = (document.getElementById('adminOrderSearch')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.admin-order-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const id = (row.getAttribute('data-id') || '').toLowerCase();
            const customer = row.getAttribute('data-customer') || '';
            const phone = row.getAttribute('data-phone') || '';
            const status = row.getAttribute('data-status') || '';

            let matchQuery = !query || id.includes(query) || customer.includes(query) || phone.includes(query);
            let matchStatus = currentActiveOrderStatus === 'all' || status === currentActiveOrderStatus;

            if (matchQuery && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('adminOrderEmptyState');
        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function openOrderDetail(order) {
        if (!order) return;
        window.currentViewingOrder = order;

        const orderIdPadded = '#' + String(order.id).padStart(6, '0');
        document.getElementById('odModalId').innerText = `Đơn Hàng ${orderIdPadded}`;
        document.getElementById('odModalDate').innerText = `Ngày đặt: ${order.created_at || ''}`;
        
        const status = (order.status || 'pending').toLowerCase();
        const badgeEl = document.getElementById('odModalStatusBadge');
        badgeEl.innerText = status.toUpperCase();
        
        const statusColors = {
            pending: 'bg-amber-100 text-amber-800',
            processing: 'bg-blue-100 text-blue-800',
            shipped: 'bg-purple-100 text-purple-800',
            completed: 'bg-emerald-100 text-emerald-800',
            cancelled: 'bg-red-100 text-red-800'
        };
        badgeEl.className = `px-2.5 py-0.5 rounded-full text-xs font-black uppercase ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;

        const progressEl = document.getElementById('odStepperProgress');
        const dots = {
            pending: document.getElementById('step-dot-pending'),
            processing: document.getElementById('step-dot-processing'),
            shipped: document.getElementById('step-dot-shipped'),
            completed: document.getElementById('step-dot-completed')
        };
        
        Object.values(dots).forEach(d => {
            if (d) d.className = 'w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center';
        });

        if (status === 'pending') {
            if (progressEl) progressEl.style.width = '15%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'processing') {
            if (progressEl) progressEl.style.width = '48%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.processing) dots.processing.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'shipped') {
            if (progressEl) progressEl.style.width = '78%';
            if (dots.pending) dots.pending.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.processing) dots.processing.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            if (dots.shipped) dots.shipped.className = 'w-7 h-7 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
        } else if (status === 'completed') {
            if (progressEl) progressEl.style.width = '100%';
            Object.values(dots).forEach(d => {
                if (d) d.className = 'w-7 h-7 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center shadow-md';
            });
        } else if (status === 'cancelled') {
            if (progressEl) progressEl.style.width = '0%';
        }

        document.getElementById('odCustomerName').innerText = order.customer_name || 'Khách vãng lai';
        const phoneEl = document.getElementById('odCustomerPhone');
        phoneEl.innerText = order.customer_phone || '---';
        phoneEl.href = `tel:${order.customer_phone || ''}`;
        document.getElementById('odCustomerEmail').innerText = order.customer_email || 'Chưa cung cấp';
        document.getElementById('odCustomerAddress').innerText = order.shipping_address || 'Nhận tại cửa hàng';
        document.getElementById('odPaymentMethod').innerText = order.payment_method || 'COD';

        const total = Number(order.total_amount || 0);
        document.getElementById('odSubtotal').innerText = total.toLocaleString('vi-VN') + 'đ';
        document.getElementById('odTotalAmount').innerText = total.toLocaleString('vi-VN') + 'đ';

        const items = order.items || [];
        document.getElementById('odItemsCount').innerText = items.length;
        const tbody = document.getElementById('odItemsTbody');
        tbody.innerHTML = '';

        items.forEach(it => {
            const lineTotal = Number(it.price * it.quantity);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2.5 px-3">
                    <div class="flex items-center space-x-2">
                        <img src="${it.product_image || 'https://via.placeholder.com/60'}" class="w-8 h-10 object-cover rounded bg-gray-100 border border-gray-200">
                        <span class="font-bold text-gray-900">${it.product_name || 'Sản phẩm'}</span>
                    </div>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <span class="px-2 py-0.5 rounded bg-stone-100 font-bold text-[10px] text-gray-700">
                        ${it.size || 'M'} / ${it.color || 'Đen'}
                    </span>
                </td>
                <td class="py-2.5 px-3 text-right font-medium">${Number(it.price).toLocaleString('vi-VN')}đ</td>
                <td class="py-2.5 px-3 text-center font-bold">${it.quantity}</td>
                <td class="py-2.5 px-3 text-right font-black text-gray-900">${lineTotal.toLocaleString('vi-VN')}đ</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('odFormOrderId').value = order.id;
        document.getElementById('odFormStatusSelect').value = status;

        const m = document.getElementById('orderDetailModal');
        if (m) m.classList.remove('hidden');
    }

    function closeOrderDetail() {
        const m = document.getElementById('orderDetailModal');
        if (m) m.classList.add('hidden');
    }

    function triggerPrintCurrentOrder() {
        if (window.currentViewingOrder) {
            printOrderInvoice(window.currentViewingOrder);
        }
    }

    function printOrderInvoice(order) {
        if (!order) return;
        const orderIdPadded = '#' + String(order.id).padStart(6, '0');
        document.getElementById('invOrderId').innerText = `Mã Đơn: ${orderIdPadded}`;
        document.getElementById('invOrderDate').innerText = `Ngày: ${order.created_at || ''}`;
        document.getElementById('invBarcode').innerText = `HQ-2026-${String(order.id).padStart(4, '0')}`;
        
        document.getElementById('invCustomerName').innerText = order.customer_name || 'Khách hàng';
        document.getElementById('invCustomerPhone').innerText = order.customer_phone || '';
        document.getElementById('invCustomerAddress').innerText = order.shipping_address || 'Địa chỉ giao hàng';
        document.getElementById('invSignCustomerName').innerText = order.customer_name || '';

        const total = Number(order.total_amount || 0);
        document.getElementById('invSubtotal').innerText = total.toLocaleString('vi-VN') + 'đ';
        document.getElementById('invTotalAmount').innerText = total.toLocaleString('vi-VN') + 'đ';

        const tbody = document.getElementById('invItemsTbody');
        tbody.innerHTML = '';
        (order.items || []).forEach(it => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2 font-bold">${it.product_name || 'Sản phẩm'}</td>
                <td class="py-2 text-center">${it.size || 'M'} / ${it.color || 'Tiêu chuẩn'}</td>
                <td class="py-2 text-right">${Number(it.price).toLocaleString('vi-VN')}đ</td>
                <td class="py-2 text-center font-bold">${it.quantity}</td>
                <td class="py-2 text-right font-bold">${Number(it.price * it.quantity).toLocaleString('vi-VN')}đ</td>
            `;
            tbody.appendChild(tr);
        });

        const m = document.getElementById('orderInvoicePrintModal');
        if (m) m.classList.remove('hidden');
    }

    function closePrintInvoice() {
        const m = document.getElementById('orderInvoicePrintModal');
        if (m) m.classList.add('hidden');
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
