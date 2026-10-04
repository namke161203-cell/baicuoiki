<?php

/**
 * @var array $stats
 * @var string $title
 */
require_once __DIR__ . '/header.php';
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Doanh thu</p>
            <p class="text-2xl font-black text-gray-900"><?= number_format($stats['revenue'], 0, ',', '.') ?>đ</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Đơn hàng mới</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['total_orders'] ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Sản phẩm</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['total_products'] ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Khách hàng</p>
            <p class="text-2xl font-black text-gray-900"><?= $stats['new_customers'] ?></p>
        </div>
    </div>
</div>

<!-- Đơn hàng gần đây (Mock UI) -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="text-lg font-black text-gray-800 tracking-tight">Đơn hàng mới nhất</h3>
        <button class="text-sm text-indigo-600 font-bold hover:text-indigo-800 transition">Xem tất cả &rarr;</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-400 text-xs uppercase tracking-widest border-b border-gray-100">
                    <th class="px-8 py-5 font-bold">Mã Đơn</th>
                    <th class="px-8 py-5 font-bold">Khách Hàng</th>
                    <th class="px-8 py-5 font-bold">Tổng Tiền</th>
                    <th class="px-8 py-5 font-bold">Trạng Thái</th>
                    <th class="px-8 py-5 font-bold text-right">Hành Động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                <tr class="hover:bg-gray-50/80 transition duration-150">
                    <td class="px-8 py-5 font-black text-gray-900">#00124</td>
                    <td class="px-8 py-5 font-medium text-gray-700">Nguyễn Văn A</td>
                    <td class="px-8 py-5 font-bold text-indigo-600">1.250.000đ</td>
                    <td class="px-8 py-5"><span class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-yellow-200">Chờ duyệt</span></td>
                    <td class="px-8 py-5 text-right"><button class="text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition">Chi tiết</button></td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition duration-150">
                    <td class="px-8 py-5 font-black text-gray-900">#00123</td>
                    <td class="px-8 py-5 font-medium text-gray-700">Trần Thị B</td>
                    <td class="px-8 py-5 font-bold text-indigo-600">650.000đ</td>
                    <td class="px-8 py-5"><span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-200">Đang giao</span></td>
                    <td class="px-8 py-5 text-right"><button class="text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition">Chi tiết</button></td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition duration-150">
                    <td class="px-8 py-5 font-black text-gray-900">#00122</td>
                    <td class="px-8 py-5 font-medium text-gray-700">Lê Hoàng C</td>
                    <td class="px-8 py-5 font-bold text-indigo-600">3.400.000đ</td>
                    <td class="px-8 py-5"><span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-emerald-200">Hoàn thành</span></td>
                    <td class="px-8 py-5 text-right"><button class="text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition">Chi tiết</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>