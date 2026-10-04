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
                    <input type="password" name="password" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-primary transition" placeholder="••••••••">
                    <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>
            
            <?php if (!($is_register ?? false)): ?>
            <div class="text-right mb-6 mt-2">
                <a href="#" class="text-xs font-medium text-primary hover:underline">Quên mật khẩu?</a>
            </div>
            <?php else: ?>
            <div class="mb-6 mt-2"></div>
            <?php endif; ?>

            <button type="submit" class="w-full bg-primary text-white font-bold py-3 uppercase text-sm tracking-widest hover:bg-primary-dark transition mb-6 shadow-md">
                <?= ($is_register ?? false) ? 'ĐĂNG KÝ' : 'ĐĂNG NHẬP' ?>
            </button>
            
            <div class="relative flex items-center justify-center mb-6">
                <div class="border-t border-gray-200 w-full absolute"></div>
                <span class="bg-white px-3 text-xs text-gray-500 font-medium relative z-10">Quản trị khoản</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" class="border border-gray-300 p-2.5 flex justify-center items-center hover:bg-gray-50 transition shadow-sm rounded-sm">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 mr-2"> <span class="text-xs font-bold text-gray-700">Google</span>
                </button>
                <button type="button" class="bg-[#1877F2] text-white p-2.5 flex justify-center items-center hover:bg-[#166fe5] transition shadow-sm rounded-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="text-xs font-bold">Facebook</span>
                </button>
            </div>
        </form>
    </div>
</body>
</html>