<div class="container flex h-screen flex-col items-center justify-center md:grid lg:max-w-none lg:grid-cols-2 lg:px-0">
    <div class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r">
        <div class="absolute inset-0 bg-yellow-500"></div>
        <div class="relative z-20 flex items-center text-lg font-medium">
            <a href="<?php echo BASE_URL; ?>" class="flex items-center gap-2">
                <span class="text-2xl font-bold text-white">SoleStyle</span>
            </a>
        </div>
        <div class="relative z-20 mt-auto">
            <blockquote class="space-y-2">
                <p class="text-lg">
                    "Tham gia cùng hàng nghìn khách hàng hài lòng đã tìm thấy đôi giày hoàn hảo với SoleStyle."
                </p>
                <footer class="text-sm">The SoleStyle Team</footer>
            </blockquote>
        </div>
    </div>
    <div class="lg:p-8">
        <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
            <div class="flex flex-col space-y-2 text-center">
                <h1 class="text-2xl font-semibold tracking-tight">Tạo tài khoản</h1>
                <p class="text-sm text-muted-foreground">Nhập thông tin của bạn để tạo tài khoản</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="grid gap-6">
                <form action="<?php echo BASE_URL; ?>/register/process" method="POST">
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <label for="username" class="text-sm font-medium">Tên đăng nhập</label>
                            <input id="username" name="username" type="text" required
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        </div>
                        <div class="grid gap-2">
                            <label for="password" class="text-sm font-medium">Mật khẩu</label>
                            <input id="password" name="password" type="password" required
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        </div>
                        <div class="grid gap-2">
                            <label for="confirm-password" class="text-sm font-medium">Xác nhận mật khẩu</label>
                            <input id="confirm-password" name="confirm_password" type="password" required
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms" class="text-sm">
                                Tôi đồng ý với 
                                <a href="<?php echo BASE_URL; ?>/terms" class="text-yellow-500 hover:underline">điều khoản</a>
                                và
                                <a href="<?php echo BASE_URL; ?>/privacy" class="text-yellow-500 hover:underline">chính sách bảo mật</a>
                            </label>
                        </div>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white h-10 px-4 py-2 rounded-md">
                            Đăng ký
                        </button>
                    </div>
                </form>
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t"></span>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-background px-2 text-muted-foreground">Hoặc đăng ký với</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm">
                        <svg class="h-4 w-4" viewBox="0 0 24 24">
                            <!-- Google SVG path -->
                        </svg>
                        Google
                    </button>
                    <button class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <!-- Facebook SVG path -->
                        </svg>
                        Facebook
                    </button>
                </div>
            </div>
            <p class="px-8 text-center text-sm text-muted-foreground">
                Đã có tài khoản?
                <a href="<?php echo BASE_URL; ?>/login" 
                   class="underline text-yellow-500 hover:text-yellow-600">
                    Đăng nhập
                </a>
            </p>
        </div>
    </div>
</div> 