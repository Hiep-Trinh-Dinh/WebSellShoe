<div class="container py-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-[240px_1fr]">
        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="rounded-lg border p-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($user->getTenTK(), 0, 1)); ?>
                    </div>
                    <div>
                        <p class="font-medium"><?php echo htmlspecialchars($user->getTenTK()); ?></p>
                        <p class="text-sm text-gray-500">
                            <?php echo $user->getMaQuyen() == 1 ? 'Quản trị viên' : 'Thành viên'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <nav class="space-y-1">
                <a href="<?php echo BASE_URL; ?>/user" 
                   class="flex items-center gap-2 rounded-lg bg-yellow-50 px-4 py-2 text-yellow-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Thông tin tài khoản
                </a>
                <a href="<?php echo BASE_URL; ?>/user/orders" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Đơn hàng của tôi
                </a>
                <a href="<?php echo BASE_URL; ?>/user/wishlist" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Sản phẩm yêu thích
                </a>
                <a href="<?php echo BASE_URL; ?>/logout" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-red-600 hover:bg-red-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Đăng xuất
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="rounded-lg border">
                <div class="border-b p-4">
                    <h2 class="font-medium">Thông tin tài khoản</h2>
                </div>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="m-4 p-4 bg-green-50 text-green-600 rounded">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/user/updateProfile" method="POST" class="p-4">
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Tên đăng nhập</label>
                            <input type="text" value="<?php echo htmlspecialchars($user->getTenTK()); ?>"
                                   class="w-full rounded-md border px-3 py-2" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" id="soDienThoai" name="soDienThoai" value="<?php echo htmlspecialchars($user->getSoDienThoai() ?? ''); ?>"
                                   class="w-full rounded-md border px-3 py-2" required
                                   pattern="[0-9]{10}"
                                   title="Số điện thoại phải có đúng 10 chữ số"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10); checkEmpty('soDienThoai', 'msgSDT');">
                            <p id="msgSDT" class="text-xs text-red-500 mt-1 hidden">Bắt buộc nhập số điện thoại (10 chữ số) để mua hàng</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                            <textarea id="diaChi" name="diaChi" class="w-full rounded-md border px-3 py-2" rows="3" required oninput="checkEmpty('diaChi', 'msgDiaChi');"><?php echo htmlspecialchars($user->getDiaChi() ?? ''); ?></textarea>
                            <p id="msgDiaChi" class="text-xs text-red-500 mt-1 hidden">Bắt buộc nhập địa chỉ để mua hàng</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Mật khẩu</label>
                            <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/user/changePassword'"
                                    class="text-yellow-500 hover:text-yellow-600">
                                Đổi mật khẩu
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Trạng thái tài khoản</label>
                            <span class="<?php echo $user->getTrangThai() == 1 ? 'text-green-500' : 'text-red-500'; ?>">
                                <?php echo $user->getTrangThai() == 1 ? 'Đang hoạt động' : 'Đã khóa'; ?>
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-4 py-2">
                                Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Recent Orders -->
            <div class="rounded-lg border">
                <div class="border-b p-4">
                    <h2 class="font-medium">Đơn hàng gần đây</h2>
                </div>
                <div class="divide-y">
                    <?php if (!empty($orders)): ?>
                        <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium">Đơn hàng #<?php echo $order->getMaHD(); ?></p>
                                        <p class="text-sm text-gray-500">
                                            <?php echo date('d/m/Y', strtotime($order->getNgayTao())); ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">
                                            <?php echo number_format($order->getTongTien(), 0, ',', '.'); ?>đ
                                        </p>
                                        <p class="text-sm <?php 
                                            switch($order->getTrangThai()) {
                                                case 1:
                                                    echo 'text-yellow-500">Chờ xử lý';
                                                    break;
                                                case 2:
                                                    echo 'text-blue-500">Đang giao';
                                                    break;
                                                case 3:
                                                    echo 'text-green-500">Đã giao';
                                                    break;
                                                case 4:
                                                    echo 'text-red-500">Đã hủy';
                                                    break;
                                                default:
                                                    echo 'text-gray-500">Không xác định';
                                            }
                                        ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-gray-500">
                            Bạn chưa có đơn hàng nào
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($orders)): ?>
                    <div class="border-t p-4">
                        <a href="<?php echo BASE_URL; ?>/user/orders" 
                           class="text-yellow-500 hover:text-yellow-600">
                            Xem tất cả đơn hàng
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 

<script>
function checkEmpty(inputId, msgId) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(msgId);
    if (input.value.trim() === "") {
        message.classList.remove("hidden");
    } else {
        message.classList.add("hidden");
    }
}
</script>