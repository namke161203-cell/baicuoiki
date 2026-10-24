<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Đăng nhập - hquie' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#34869c',
                        'primary-dark': '#236070',
                    }
                }
            }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-screen relative flex items-center justify-center bg-[#d5e7eb]">
    
    <!-- Background giả lập (Blur) để giống modal -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover filter blur-[2px]">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    </div>

    <!-- Login Modal -->
    <div class="relative z-10 w-full max-w-sm bg-white p-8 shadow-2xl rounded-sm">
        <!-- Đóng Icon -->
        <a href="index.php" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <a href="index.php?controller=Auth&action=login" class="flex-1 py-3 text-center text-xs font-bold <?= !($is_register ?? false) ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-gray-600' ?> uppercase tracking-wider transition">Đăng nhập</a>
            <a href="index.php?controller=Auth&action=register" class="flex-1 py-3 text-center text-xs font-bold <?= ($is_register ?? false) ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-gray-600' ?> uppercase tracking-wider transition">Tạo tài khoản</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-500 text-xs p-3 mb-4 border border-red-100 text-center font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= ($is_register ?? false) ? 'index.php?controller=Auth&action=register' : 'index.php?controller=Auth&action=login' ?>" method="POST">
            <?php if ($is_register ?? false): ?>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-900 mb-2">Họ và Tên</label>
                <input type="text" name="name" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="Nguyễn Văn A">
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-900 mb-2">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="Email email@gmail.com">
            </div>
            <div class="mb-2">
                <label class="block text-xs font-bold text-gray-900 mb-2">Password</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" required <?= ($is_register ?? false) ? 'pattern=".*@.*" title="Mật khẩu bắt buộc phải chứa ít nhất một ký tự @"' : '' ?> class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="••••••••">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 focus:outline-none" title="Ẩn/Hiện mật khẩu">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>
            
            <?php if (!($is_register ?? false)): ?>
            <div class="text-right mb-6 mt-2">
                <button type="button" onclick="openForgotModal()" class="text-xs font-medium text-primary hover:underline">Quên mật khẩu?</button>
            </div>
            <?php else: ?>
            <div class="mb-6 mt-2"></div>
            <?php endif; ?>

            <button type="submit" class="w-full bg-primary text-white font-bold py-3 uppercase text-sm tracking-widest hover:bg-primary-dark transition mb-6 shadow-md">
                <?= ($is_register ?? false) ? 'ĐĂNG KÝ' : 'ĐĂNG NHẬP' ?>
            </button>
            
            <div class="relative flex items-center justify-center mb-6">
                <div class="border-t border-gray-200 w-full absolute"></div>
                <span class="bg-white px-3 text-xs text-gray-500 font-medium relative z-10">Hoặc tiếp tục với</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="socialLoginDemo('Google')" class="border border-gray-300 p-2.5 flex justify-center items-center hover:bg-gray-50 transition shadow-sm rounded-sm">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 mr-2"> <span class="text-xs font-bold text-gray-700">Google</span>
                </button>
                <button type="button" onclick="socialLoginDemo('Facebook')" class="bg-[#1877F2] text-white p-2.5 flex justify-center items-center hover:bg-[#166fe5] transition shadow-sm rounded-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="text-xs font-bold">Facebook</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 🔑 FORGOT PASSWORD MODAL -->
    <div id="forgotModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-lg shadow-2xl p-6 relative">
            <button onclick="closeForgotModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-base font-bold text-gray-900 uppercase tracking-wide mb-2">Khôi phục mật khẩu</h3>
            <p class="text-xs text-gray-500 mb-4">Nhập email đăng ký của bạn. Chúng tôi sẽ gửi hướng dẫn thiết lập lại mật khẩu.</p>
            
            <form id="forgotForm" onsubmit="handleForgotSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Email của bạn</label>
                    <input type="email" id="forgotEmail" required placeholder="nhapemail@gmail.com" class="w-full border border-gray-300 p-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
                <div id="forgotAlert" class="hidden p-3 rounded text-xs"></div>
                <button type="submit" id="forgotBtn" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2.5 uppercase text-xs tracking-wider transition">
                    Gửi yêu cầu
                </button>
            </form>

            <div class="mt-4 pt-3 border-t border-gray-100 text-center text-xs text-gray-500">
                Cần hỗ trợ gấp? Gọi <a href="tel:19008888" class="text-primary font-bold">1900 8888</a>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('passwordInput');
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

        function openForgotModal() {
            document.getElementById('forgotModal').classList.remove('hidden');
            document.getElementById('forgotModal').classList.add('flex');
            setTimeout(() => document.getElementById('forgotEmail').focus(), 100);
        }

        function closeForgotModal() {
            document.getElementById('forgotModal').classList.add('hidden');
            document.getElementById('forgotModal').classList.remove('flex');
            const alert = document.getElementById('forgotAlert');
            alert.classList.add('hidden');
        }

        function handleForgotSubmit(e) {
            e.preventDefault();
            const email = document.getElementById('forgotEmail').value;
            const btn = document.getElementById('forgotBtn');
            const alert = document.getElementById('forgotAlert');
            
            btn.innerText = 'ĐANG GỬI...';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerText = 'Gửi yêu cầu';
                btn.disabled = false;
                alert.className = 'block p-3 rounded text-xs bg-green-50 text-green-700 border border-green-200';
                alert.innerText = `✅ Đã gửi email khôi phục mật khẩu tới "${email}". Vui lòng kiểm tra hộp thư đến hoặc mục Spam!`;
            }, 700);
        }

        function socialLoginDemo(provider) {
            alert(`Tính năng đăng nhập qua ${provider} đang kết nối Sandbox OAuth2.`);
        }
    </script>
</body>
</html>