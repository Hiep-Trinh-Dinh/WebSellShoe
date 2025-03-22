<header class="sticky top-0 z-50 w-full border-b bg-white">
    <div class="container flex h-16 items-center justify-between">
        <a href="<?php echo BASE_URL; ?>" class="flex items-center gap-2">
            <span class="text-2xl font-bold text-yellow-500">SoleStyle</span>
        </a>

        <nav class="hidden md:flex gap-6">
            <a href="<?php echo BASE_URL; ?>" class="text-sm font-medium hover:text-yellow-500 transition-colors">
                Home
            </a>
            <a href="<?php echo BASE_URL; ?>/products" class="text-sm font-medium hover:text-yellow-500 transition-colors">
                Products
            </a>
            <a href="<?php echo BASE_URL; ?>/about" class="text-sm font-medium hover:text-yellow-500 transition-colors">
                About
            </a>
            <a href="<?php echo BASE_URL; ?>/contact" class="text-sm font-medium hover:text-yellow-500 transition-colors">
                Contact
            </a>
        </nav>

        <div class="hidden md:flex items-center gap-4">
            <a href="<?php echo BASE_URL; ?>/user/cart" class="relative">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-yellow-500 text-[10px] font-medium text-white">
                    <?php echo $_SESSION['cart_count']; ?>
                </span>
                <?php endif; ?>
            </a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/user" class="hover:text-yellow-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center justify-center whitespace-nowrap rounded-md border border-yellow-500 px-4 py-2 text-sm font-medium text-yellow-500 hover:bg-yellow-500 hover:text-white transition-colors">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header> 