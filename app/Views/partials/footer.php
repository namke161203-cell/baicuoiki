    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <a href="index.php" class="text-2xl font-black tracking-tighter text-white uppercase mb-6 block">
                    hquie<span class="text-primary">.store</span>
                </a>
                <p class="text-sm leading-relaxed mb-6">Định hình phong cách thời trang đương đại với những bộ sưu tập tối giản, trẻ trung và tôn vinh cá tính người mặc.</p>
                <div class="flex space-x-4">
                    <button onclick="window.open('https://facebook.com', '_blank')" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition cursor-pointer text-white text-xs font-bold" title="Facebook">FB</button>
                    <button onclick="window.open('https://instagram.com', '_blank')" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition cursor-pointer text-white text-xs font-bold" title="Instagram">IG</button>
                    <button onclick="window.open('https://tiktok.com', '_blank')" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition cursor-pointer text-white text-xs font-bold" title="TikTok">TT</button>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Mua Sắm</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="index.php?controller=Product&action=list" class="hover:text-primary transition">Tất cả sản phẩm</a></li>
                    <li><a href="index.php?controller=Product&action=search&keyword=Áo Khoác" class="hover:text-primary transition">Áo khoác & Áo dạ</a></li>
                    <li><a href="index.php?controller=Product&action=search&keyword=Áo Len" class="hover:text-primary transition">Áo len mùa đông</a></li>
                    <li><a href="index.php?controller=Product&action=search&keyword=Quần Jeans" class="hover:text-primary transition">Quần Jeans & Khaki</a></li>
                    <li><a href="index.php?controller=Product&action=search&keyword=Phụ kiện" class="hover:text-primary transition">Phụ kiện giữ ấm</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Hỗ Trợ Khách Hàng</h4>
                <ul class="space-y-3 text-sm">
                    <li><button type="button" onclick="openPolicyModal('shipping')" class="hover:text-primary transition text-left">Chính sách vận chuyển</button></li>
                    <li><button type="button" onclick="openPolicyModal('return')" class="hover:text-primary transition text-left">Chính sách đổi trả 7 ngày</button></li>
                    <li><button type="button" onclick="openPolicyModal('size')" class="hover:text-primary transition text-left">Hướng dẫn chọn size</button></li>
                    <li><button type="button" onclick="openPolicyModal('contact')" class="hover:text-primary transition text-left">Liên hệ & Khiếu nại</button></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Đăng ký nhận ưu đãi</h4>
                <p class="text-sm mb-4">Nhận ngay mã giảm giá 10% (mã: <b class="text-primary">VIBE10</b>) cho đơn hàng đầu tiên.</p>
                <form class="flex" onsubmit="handleNewsletterSubmit(event)">
                    <input type="email" id="newsletterEmail" required placeholder="Nhập email của bạn" class="px-4 py-3 w-full text-gray-900 bg-white rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    <button type="submit" class="bg-primary text-white px-5 py-3 rounded-r-md hover:bg-primary-dark transition font-semibold text-sm">Gửi</button>
                </form>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm flex flex-col md:flex-row justify-between items-center">
            <p>&copy; <?= date('Y') ?> hquie Store. Nền tảng xây dựng bằng PHP MVC & TailwindCSS.</p>
            <div class="mt-4 md:mt-0 flex space-x-4 items-center">
                <span class="text-gray-500 text-xs uppercase font-bold">Thanh toán an toàn: COD &bull; VNPAY &bull; Momo &bull; Chuyển khoản</span>
            </div>
        </div>
    </footer>

    <!-- 📜 POLICY / CUSTOMER SUPPORT MODAL -->
    <div id="policyModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-xl shadow-2xl p-6 relative max-h-[85vh] overflow-y-auto animate-fade-down text-gray-800">
            <button onclick="closePolicyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 id="policyModalTitle" class="text-xl font-bold uppercase tracking-tight text-primary mb-4 pb-2 border-b border-gray-200">
                Chính sách
            </h3>
            <div id="policyModalContent" class="text-sm text-gray-600 leading-relaxed space-y-3">
                <!-- Nội dung được render qua JS -->
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 text-right">
                <button onclick="closePolicyModal()" class="bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded font-bold text-xs uppercase tracking-wider transition">
                    Đóng cửa sổ
                </button>
            </div>
        </div>
    </div>

    <script>
        const policyData = {
            shipping: {
                title: "🚚 Chính Sách Vận Chuyển Toàn Quốc",
                content: `
                    <p><b>1. Phạm vi áp dụng:</b> Giao hàng trên toàn bộ 63 tỉnh thành Việt Nam.</p>
                    <p><b>2. Thời gian giao nhận:</b></p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Khu vực nội thành Hà Nội & TP.HCM: Giao nhanh từ 24 - 48 giờ. Hỗ trợ hỏa tốc trong 2-4 giờ theo yêu cầu.</li>
                        <li>Các tỉnh thành khác: 2 - 4 ngày làm việc.</li>
                    </ul>
                    <p><b>3. Phí vận chuyển:</b></p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><b>MIỄN PHÍ VẬN CHUYỂN</b> cho toàn bộ đơn hàng từ 500.000đ trở lên.</li>
                        <li>Đơn hàng dưới 500.000đ: Đồng giá phí ship 30.000đ trên toàn quốc.</li>
                    </ul>
                    <p><b>4. Kiểm hàng trước khi thanh toán:</b> Khách hàng được quyền đồng kiểm (kiểm tra ngoại quan, mẫu mã, size) trước khi thanh toán cho shipper.</p>
                `
            },
            return: {
                title: "🔄 Chính Sách Đổi Trả Miễn Phí Trong 7 Ngày",
                content: `
                    <p><b>1. Điều kiện đổi trả:</b></p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Sản phẩm còn nguyên tem mác, hộp đựng và chưa qua giặt ủi, sử dụng.</li>
                        <li>Áp dụng đổi hàng trong vòng <b>07 ngày</b> kể từ khi nhận được sản phẩm.</li>
                    </ul>
                    <p><b>2. Các trường hợp được đổi trả:</b></p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Không vừa size (hỗ trợ đổi sang size lớn/nhỏ hơn miễn phí).</li>
                        <li>Sản phẩm bị lỗi sản xuất, rách chỉ, sai mẫu mã so với hình ảnh mô tả.</li>
                    </ul>
                    <p><b>3. Quy trình đổi hàng tận nhà:</b> Shipper sẽ đem sản phẩm mới tới giao và đồng thời thu hồi lại sản phẩm cũ, bạn không cần phải ra bưu cục gửi hàng!</p>
                `
            },
            size: {
                title: "📏 Hướng Dẫn Chọn Size & Đo Kích Thước",
                content: `
                    <p><b>Cách xác định kích cỡ chuẩn:</b></p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><b>Size S:</b> Chiều cao 155 - 165cm | Cân nặng 48 - 56kg.</li>
                        <li><b>Size M:</b> Chiều cao 165 - 172cm | Cân nặng 57 - 65kg.</li>
                        <li><b>Size L:</b> Chiều cao 170 - 178cm | Cân nặng 66 - 74kg.</li>
                        <li><b>Size XL:</b> Chiều cao 175 - 183cm | Cân nặng 75 - 83kg.</li>
                        <li><b>Size XXL:</b> Chiều cao 180 - 190cm | Cân nặng 84 - 95kg.</li>
                    </ul>
                    <p><i>💡 Nếu số đo của bạn nằm ở ranh giới giữa 2 size, nên ưu tiên chọn size lớn hơn để mặc thoải mái vào mùa đông.</i></p>
                `
            },
            contact: {
                title: "📞 Liên Hệ & Hỗ Trợ Khiếu Nại",
                content: `
                    <p>hquie luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn 24/7:</p>
                    <p><b>&bull; Hotline CSKH / Khiếu nại:</b> <a href="tel:19008888" class="text-primary font-bold">1900 8888</a> (Nhánh 1)</p>
                    <p><b>&bull; Hotline Đặt hàng nhanh:</b> <a href="tel:0988888888" class="text-primary font-bold">0988 888 888</a></p>
                    <p><b>&bull; Email tiếp nhận:</b> <a href="mailto:cskh@hquie.vn" class="text-primary font-bold">cskh@hquie.vn</a></p>
                    <p><b>&bull; Địa chỉ Showroom chính:</b> Tầng 2, TTTM Vincom Center, Hà Nội & Quận 1, TP. Hồ Chí Minh.</p>
                `
            }
        };

        function openPolicyModal(type) {
            const data = policyData[type];
            if (!data) return;
            document.getElementById('policyModalTitle').innerHTML = data.title;
            document.getElementById('policyModalContent').innerHTML = data.content;
            const m = document.getElementById('policyModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closePolicyModal() {
            const m = document.getElementById('policyModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function handleNewsletterSubmit(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletterEmail');
            const email = emailInput.value.trim();
            if (email) {
                if (typeof showToast === 'function') {
                    showToast(`🎉 Cảm ơn bạn! Đã nhận mã giảm giá VIBE10 (10% OFF) cho email "${email}".`);
                } else {
                    alert(`🎉 Cảm ơn bạn! Đã nhận mã giảm giá VIBE10 (10% OFF) cho email "${email}".`);
                }
                emailInput.value = '';
            }
        }
    </script>
</body>
</html>
