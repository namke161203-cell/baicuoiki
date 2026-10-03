    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <a href="index.php" class="text-2xl font-black tracking-tighter text-white uppercase mb-6 block">
                    VIBE<span class="text-indigo-500">STORE</span>
                </a>
                <p class="text-sm leading-relaxed mb-6">Định hình phong cách thời trang đương đại với những bộ sưu tập tối giản, trẻ trung và tôn vinh cá tính người mặc.</p>
                <div class="flex space-x-4">
                    <!-- Fake social icons -->
                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer text-white">FB</div>
                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer text-white">IG</div>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Mua Sắm</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-indigo-400 transition">Tất cả sản phẩm</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Áo thun nam/nữ</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Quần Jeans & Khaki</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Phụ kiện</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Hỗ Trợ Khách Hàng</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-indigo-400 transition">Chính sách vận chuyển</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Chính sách đổi trả 7 ngày</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Hướng dẫn chọn size</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Liên hệ & Khiếu nại</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Đăng ký nhận ưu đãi</h4>
                <p class="text-sm mb-4">Nhận ngay mã giảm giá 10% cho đơn hàng đầu tiên.</p>
                <form class="flex" onsubmit="event.preventDefault();">
                    <input type="email" placeholder="Nhập email của bạn" class="px-4 py-3 w-full text-gray-900 bg-white rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button class="bg-indigo-600 text-white px-5 py-3 rounded-r-md hover:bg-indigo-700 transition font-semibold">Gửi</button>
                </form>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm flex flex-col md:flex-row justify-between items-center">
            <p>&copy; <?= date('Y') ?> VibeStore. Nền tảng xây dựng bằng PHP & TailwindCSS.</p>
            <div class="mt-4 md:mt-0 flex space-x-4">
                <span class="text-gray-500 text-xs uppercase font-bold">Thanh toán an toàn</span>
            </div>
        </div>
    </footer>
</body>
</html>
