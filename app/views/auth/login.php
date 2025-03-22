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
                    "SoleStyle đã hoàn toàn thay đổi trải nghiệm mua sắm giày của tôi. Chất lượng và sự thoải mái của giày thật tuyệt vời!"
                </p>
                <footer class="text-sm">Sofia Davis</footer>
            </blockquote>
        </div>
    </div>
    <div class="lg:p-8">
        <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
            <div class="flex flex-col space-y-2 text-center">
                <h1 class="text-2xl font-semibold tracking-tight">Đăng nhập</h1>
                <p class="text-sm text-muted-foreground">Nhập thông tin đăng nhập của bạn</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 text-green-600 p-3 rounded">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/login/process" method="POST">
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <label for="username" class="text-sm font-medium">Tên đăng nhập</label>
                        <input id="username" name="username" type="text" required
                               class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-medium">Mật khẩu</label>
                            <a href="<?php echo BASE_URL; ?>/forgot-password" 
                               class="text-sm text-yellow-500 hover:underline">
                                Quên mật khẩu?
                            </a>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" class="text-sm">Ghi nhớ đăng nhập</label>
                    </div>
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white h-10 px-4 py-2 rounded-md">
                        Đăng nhập
                    </button>
                </div>
            </form>
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t"></span>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-background px-2 text-muted-foreground">Hoặc tiếp tục với</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <svg class="h-4 w-4" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </button>
                <button class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                    </svg>
                    Facebook
                </button>
            </div>
            <p class="px-8 text-center text-sm text-muted-foreground">
                Chưa có tài khoản?
                <a href="<?php echo BASE_URL; ?>/register" 
                   class="underline text-yellow-500 hover:text-yellow-600">
                    Đăng ký
                </a>
            </p>
        </div>
    </div>
</div> 