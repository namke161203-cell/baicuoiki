<?php 
/**
 * @var array $cart
 * @var int $total
 * @var string $title
 */
require_once __DIR__ . '/partials/header.php'; 
?>

<div class="bg-[#eaf4f6] min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="index.php?controller=Cart&action=checkout<?= isset($_GET['type']) ? '&type=' . htmlspecialchars($_GET['type']) : '' ?>" method="POST">
            <div class="bg-white shadow-xl p-8 lg:p-10">
                <h1 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-8">Thanh toán - Bước 1: Thông tin giao hàng</h1>

                <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center justify-between text-red-800 animate-pulse">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-semibold"><?= htmlspecialchars($error) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 border-b border-gray-200 pb-10 mb-10">
                    <!-- Step 1: Form Giao Hàng -->
                    <div class="space-y-4">
                        <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase">Thông tin giao hàng</h2>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">Họ và tên</label>
                            <input type="text" name="fullname" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="Nguyễn Văn A">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">Địa chỉ cụ thể</label>
                            <input type="text" name="address" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="Số nhà, tên đường, phường/xã, quận/huyện...">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">Số điện thoại</label>
                            <input type="text" name="phone" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="09xxxxxxx">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">Tỉnh/Thành phố</label>
                            <select name="city" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition bg-white">
                                <option value="" disabled selected>Chọn Tỉnh/Thành phố</option>
                                <option value="Hà Nội">Hà Nội</option>
                                <option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
                                <option value="Đà Nẵng">Đà Nẵng</option>
                                <option value="Hải Phòng">Hải Phòng</option>
                                <option value="Cần Thơ">Cần Thơ</option>
                                <option value="An Giang">An Giang</option>
                                <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                                <option value="Bắc Giang">Bắc Giang</option>
                                <option value="Bắc Kạn">Bắc Kạn</option>
                                <option value="Bạc Liêu">Bạc Liêu</option>
                                <option value="Bắc Ninh">Bắc Ninh</option>
                                <option value="Bến Tre">Bến Tre</option>
                                <option value="Bình Định">Bình Định</option>
                                <option value="Bình Dương">Bình Dương</option>
                                <option value="Bình Phước">Bình Phước</option>
                                <option value="Bình Thuận">Bình Thuận</option>
                                <option value="Cà Mau">Cà Mau</option>
                                <option value="Cao Bằng">Cao Bằng</option>
                                <option value="Đắk Lắk">Đắk Lắk</option>
                                <option value="Đắk Nông">Đắk Nông</option>
                                <option value="Điện Biên">Điện Biên</option>
                                <option value="Đồng Nai">Đồng Nai</option>
                                <option value="Đồng Tháp">Đồng Tháp</option>
                                <option value="Gia Lai">Gia Lai</option>
                                <option value="Hà Giang">Hà Giang</option>
                                <option value="Hà Nam">Hà Nam</option>
                                <option value="Hà Tĩnh">Hà Tĩnh</option>
                                <option value="Hải Dương">Hải Dương</option>
                                <option value="Hậu Giang">Hậu Giang</option>
                                <option value="Hòa Bình">Hòa Bình</option>
                                <option value="Hưng Yên">Hưng Yên</option>
                                <option value="Khánh Hòa">Khánh Hòa</option>
                                <option value="Kiên Giang">Kiên Giang</option>
                                <option value="Kon Tum">Kon Tum</option>
                                <option value="Lai Châu">Lai Châu</option>
                                <option value="Lâm Đồng">Lâm Đồng</option>
                                <option value="Lạng Sơn">Lạng Sơn</option>
                                <option value="Lào Cai">Lào Cai</option>
                                <option value="Long An">Long An</option>
                                <option value="Nam Định">Nam Định</option>
                                <option value="Nghệ An">Nghệ An</option>
                                <option value="Ninh Bình">Ninh Bình</option>
                                <option value="Ninh Thuận">Ninh Thuận</option>
                                <option value="Phú Thọ">Phú Thọ</option>
                                <option value="Phú Yên">Phú Yên</option>
                                <option value="Quảng Bình">Quảng Bình</option>
                                <option value="Quảng Nam">Quảng Nam</option>
                                <option value="Quảng Ngãi">Quảng Ngãi</option>
                                <option value="Quảng Ninh">Quảng Ninh</option>
                                <option value="Quảng Trị">Quảng Trị</option>
                                <option value="Sóc Trăng">Sóc Trăng</option>
                                <option value="Sơn La">Sơn La</option>
                                <option value="Tây Ninh">Tây Ninh</option>
                                <option value="Thái Bình">Thái Bình</option>
                                <option value="Thái Nguyên">Thái Nguyên</option>
                                <option value="Thanh Hóa">Thanh Hóa</option>
                                <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                                <option value="Tiền Giang">Tiền Giang</option>
                                <option value="Trà Vinh">Trà Vinh</option>
                                <option value="Tuyên Quang">Tuyên Quang</option>
                                <option value="Vĩnh Long">Vĩnh Long</option>
                                <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                                <option value="Yên Bái">Yên Bái</option>
                            </select>
                        </div>
                        
                        <div class="mt-6">
                            <h2 class="text-sm font-bold text-gray-900 mb-3 uppercase flex items-center justify-between">
                                <span>Phương thức giao hàng</span>
                                <span class="text-xs text-primary font-normal">Chọn gói cước phù hợp</span>
                            </h2>
                            <div class="space-y-3" id="shippingOptionsContainer">
                                <?php 
                                $rates = $shippingRates ?? [
                                    'tieuchuan' => ['id' => 'tieuchuan', 'name' => 'Giao hàng Tiêu chuẩn', 'desc' => '3 - 5 ngày làm việc', 'fee' => 20000],
                                    'nhanh' => ['id' => 'nhanh', 'name' => 'Giao hàng Nhanh', 'desc' => '1 - 2 ngày làm việc', 'fee' => 40000],
                                    'sieutoc' => ['id' => 'sieutoc', 'name' => 'Giao Hỏa Tốc 2H', 'desc' => 'Nhận ngay trong 2 - 4 giờ', 'fee' => 65000]
                                ];
                                $curShipping = $selectedShipping ?? 'nhanh';
                                foreach ($rates as $rKey => $rate): 
                                    $isSelected = ($curShipping === $rKey);
                                ?>
                                <label class="shipping-option-card flex items-center justify-between cursor-pointer border p-3.5 rounded transition duration-200 <?= $isSelected ? 'border-primary bg-[#eaf4f6] shadow-sm' : 'border-gray-200 hover:border-gray-400 bg-white' ?>">
                                    <div class="flex items-center">
                                        <input type="radio" name="shipping" value="<?= $rate['id'] ?>" data-fee="<?= $rate['fee'] ?>" class="text-primary focus:ring-primary h-4 w-4" <?= $isSelected ? 'checked' : '' ?> onchange="updateShippingFee(this)">
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-gray-900"><?= $rate['name'] ?></p>
                                            <p class="text-xs text-gray-500 mt-0.5"><?= $rate['desc'] ?></p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-primary font-mono"><?= number_format($rate['fee'], 0, ',', '.') ?> đ</span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Mini Cart -->
                    <div class="mt-10 lg:mt-0 lg:pl-10 lg:border-l border-gray-200">
                        <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase">Mini Cart</h2>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                            <?php foreach ($cart as $item): ?>
                            <div class="flex items-center">
                                <img src="<?= $item['image'] ?>" class="w-16 h-20 object-cover bg-gray-100">
                                <div class="ml-4 flex-1">
                                    <h3 class="text-xs font-bold text-gray-900 uppercase truncate"><?= $item['name'] ?></h3>
                                    <p class="text-xs text-gray-500 mt-1">Size: <?= $item['size'] ?> | SL: <?= $item['quantity'] ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-900">Tổng tiền mặt hàng</span>
                                <span class="text-gray-900 font-bold"><?= number_format($total, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="flex justify-between text-sm mb-4">
                                <span class="text-gray-900">Phí vận chuyển</span>
                                <span class="text-primary font-bold shipping-fee-val"><?= number_format($shippingFee ?? 40000, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="flex justify-between text-base border-t border-gray-200 pt-4">
                                <span class="font-bold text-gray-900">Thành tiền</span>
                                <span class="font-bold text-gray-900 final-total-val"><?= number_format(($total ?? 0) + ($shippingFee ?? 40000), 0, ',', '.') ?> đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h1 class="text-xl font-bold text-gray-900 uppercase tracking-tight mb-8 mt-10">Thanh toán - Bước 2: Phương thức thanh toán</h1>
                
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
                    <!-- Step 2: Payment Methods -->
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase">Chọn phương thức thanh toán:</h2>
                        <div class="space-y-3" id="paymentMethodsContainer">
                            <label class="payment-method-card flex items-center p-4 border border-primary bg-[#eaf4f6] cursor-pointer transition">
                                <input type="radio" name="payment_method" value="cod" class="text-primary focus:ring-primary h-4 w-4" checked onchange="updatePaymentMethod(this)">
                                <span class="ml-3 text-sm font-bold text-primary flex items-center">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="payment-method-card flex items-center p-4 border border-gray-200 cursor-pointer hover:border-primary transition">
                                <input type="radio" name="payment_method" value="bank_transfer" class="text-primary focus:ring-primary h-4 w-4" onchange="updatePaymentMethod(this)">
                                <span class="ml-3 text-sm font-bold text-gray-700 flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg> Chuyển khoản ngân hàng (QR Code)</span>
                            </label>
                            <label class="payment-method-card flex items-center p-4 border border-gray-200 cursor-pointer hover:border-primary transition">
                                <input type="radio" name="payment_method" value="momo" class="text-primary focus:ring-primary h-4 w-4" onchange="updatePaymentMethod(this)">
                                <span class="ml-3 text-sm font-bold text-gray-700 flex items-center">
                                    <div class="w-5 h-5 bg-pink-500 rounded mr-2 flex items-center justify-center text-white text-[10px] font-bold">M</div>
                                    Ví điện tử MoMo
                                </span>
                            </label>
                            <label class="payment-method-card flex items-center p-4 border border-gray-200 cursor-pointer hover:border-primary transition">
                                <input type="radio" name="payment_method" value="zalopay" class="text-primary focus:ring-primary h-4 w-4" onchange="updatePaymentMethod(this)">
                                <span class="ml-3 text-sm font-bold text-gray-700 flex items-center">
                                    <div class="w-5 h-5 bg-blue-500 rounded mr-2 flex items-center justify-center text-white text-[10px] font-bold">Z</div>
                                    Ví điện tử ZaloPay
                                </span>
                            </label>
                            <label class="payment-method-card flex items-center p-4 border border-gray-200 cursor-pointer hover:border-primary transition">
                                <input type="radio" name="payment_method" value="credit_card" class="text-primary focus:ring-primary h-4 w-4" onchange="updatePaymentMethod(this)">
                                <span class="ml-3 text-sm font-bold text-gray-700 flex items-center"><svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Thẻ tín dụng/ghi nợ (Visa/Mastercard)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Step 2: Final Order Summary -->
                    <div class="mt-10 lg:mt-0 lg:pl-10 lg:border-l border-gray-200">
                        <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase">Đơn đặt hàng</h2>
                        <div class="space-y-3 mb-6 pb-6 border-b border-gray-200 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-900">Tổng tiền mặt hàng</span>
                                <span class="text-gray-900 font-bold"><?= number_format($total, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-900">Phí vận chuyển</span>
                                <span class="text-primary font-bold shipping-fee-val"><?= number_format($shippingFee ?? 40000, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="flex justify-between text-base pt-2">
                                <span class="font-bold text-gray-900">Thành tiền</span>
                                <span class="font-bold text-gray-900 final-total-val"><?= number_format(($total ?? 0) + ($shippingFee ?? 40000), 0, ',', '.') ?> đ</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-900 mb-2 uppercase">Mã giảm giá</label>
                            <div class="flex space-x-2">
                                <input type="text" class="flex-1 border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="Nhập mã">
                                <button type="button" class="bg-gray-200 text-gray-800 font-bold px-4 py-3 uppercase text-xs hover:bg-gray-300 transition">Áp dụng</button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-4 uppercase tracking-wider hover:bg-primary-dark transition text-sm shadow-md">
                            Hoàn tất đơn hàng
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    const SUB_TOTAL = <?= (float)($total ?? 0) ?>;

    function formatVND(amount) {
        return Number(amount).toLocaleString('vi-VN') + ' đ';
    }

    // Cập nhật phí vận chuyển và Thành tiền theo thời gian thực (Real-time live update)
    function updateShippingFee(radioEl) {
        const fee = parseFloat(radioEl.getAttribute('data-fee')) || 0;
        const finalTotal = SUB_TOTAL + fee;

        // Cập nhật text ở cả Mini Cart và Order Summary
        document.querySelectorAll('.shipping-fee-val').forEach(el => {
            el.innerText = formatVND(fee);
        });
        document.querySelectorAll('.final-total-val').forEach(el => {
            el.innerText = formatVND(finalTotal);
        });

        // Cập nhật highlight viền card
        document.querySelectorAll('.shipping-option-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-[#eaf4f6]', 'shadow-sm');
            card.classList.add('border-gray-200', 'bg-white');
        });
        const currentCard = radioEl.closest('.shipping-option-card');
        if (currentCard) {
            currentCard.classList.remove('border-gray-200', 'bg-white');
            currentCard.classList.add('border-primary', 'bg-[#eaf4f6]', 'shadow-sm');
        }
    }

    // Cập nhật highlight phương thức thanh toán
    function updatePaymentMethod(radioEl) {
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-[#eaf4f6]');
            card.classList.add('border-gray-200');
            const span = card.querySelector('span');
            if (span) {
                span.classList.remove('text-primary');
                span.classList.add('text-gray-700');
            }
        });
        const currentCard = radioEl.closest('.payment-method-card');
        if (currentCard) {
            currentCard.classList.remove('border-gray-200');
            currentCard.classList.add('border-primary', 'bg-[#eaf4f6]');
            const span = currentCard.querySelector('span');
            if (span) {
                span.classList.remove('text-gray-700');
                span.classList.add('text-primary');
            }
        }
    }
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
