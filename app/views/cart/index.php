<div class="container py-8">
    <h1 class="text-3xl font-bold mb-8">Giỏ hàng</h1>

    <?php if (!empty($cartItems)): ?>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-[1fr_350px]">
        <!-- Cart Items -->
        <div class="rounded-lg border">
            <div class="p-4 border-b">
                <h2 class="font-bold">Sản phẩm (<?php echo count($cartItems); ?>)</h2>
            </div>

            <div class="divide-y">
                <?php foreach ($cartItems as $item): ?>
                <div class="p-4">
                    <div class="flex flex-wrap items-start gap-4">
                        <div class="relative h-24 w-24 overflow-hidden rounded-md">
                            <?php if ($item['hinhAnh']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['hinhAnh']); ?>"
                                     alt="<?php echo htmlspecialchars($item['tenGiay']); ?>"
                                     class="object-cover">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg"
                                     alt="No Image"
                                     class="object-cover">
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <a href="<?php echo BASE_URL; ?>/products/detail/<?php echo $item['maGiay']; ?>" 
                               class="font-medium hover:text-yellow-500">
                                <?php echo htmlspecialchars($item['tenGiay']); ?>
                            </a>
                            <div class="mt-1 text-sm text-gray-500">
                                <p>Size: <?php echo $item['size']; ?></p>
                            </div>
                            <div class="mt-2 flex items-center">
                                <button class="flex h-8 w-8 items-center justify-center rounded-l-md border"
                                        onclick="updateQuantity(<?php echo $item['maGiay']; ?>, 'decrease')">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <div class="flex h-8 w-10 items-center justify-center border-t border-b">
                                    <?php echo $item['soLuong']; ?>
                                </div>
                                <button class="flex h-8 w-8 items-center justify-center rounded-r-md border"
                                        onclick="updateQuantity(<?php echo $item['maGiay']; ?>, 'increase')">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="font-bold"><?php echo number_format($item['giaBan'] * $item['soLuong'], 0, ',', '.'); ?>đ</p>
                            <button class="text-gray-500 hover:text-red-500"
                                    onclick="removeItem(<?php echo $item['maGiay']; ?>)">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="p-4 border-t flex justify-between items-center">
                <a href="<?php echo BASE_URL; ?>/products" 
                   class="text-sm border rounded-md px-4 py-2 hover:bg-gray-50">
                    Tiếp tục mua sắm
                </a>
                <button onclick="clearCart()" 
                        class="text-sm text-red-500 border border-red-500 rounded-md px-4 py-2 hover:bg-red-50">
                    Xóa giỏ hàng
                </button>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="rounded-lg border h-fit">
            <div class="p-4 border-b">
                <h2 class="font-bold">Tổng đơn hàng</h2>
            </div>

            <div class="p-4 space-y-4">
                <div class="flex justify-between">
                    <span>Tạm tính</span>
                    <span><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
                </div>
                <div class="flex justify-between">
                    <span>Phí vận chuyển</span>
                    <span><?php echo $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ'; ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Thuế</span>
                    <span><?php echo number_format($tax, 0, ',', '.'); ?>đ</span>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between font-bold">
                        <span>Tổng cộng</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-4 py-2 font-medium">
                    Tiến hành thanh toán
                </button>

                <div class="pt-4">
                    <h3 class="font-medium mb-2">Mã giảm giá</h3>
                    <div class="flex">
                        <input type="text" placeholder="Nhập mã" 
                               class="flex-1 rounded-l-md border border-r-0 px-3 py-2 text-sm">
                        <button class="rounded-r-md bg-gray-200 px-4 py-2 text-sm font-medium hover:bg-gray-300">
                            Áp dụng
                        </button>
                    </div>
                </div>

                <div class="pt-4 text-sm text-gray-500">
                    <p>Chúng tôi chấp nhận:</p>
                    <div class="mt-2 flex gap-2">
                        <div class="h-8 w-12 rounded border bg-gray-50"></div>
                        <div class="h-8 w-12 rounded border bg-gray-50"></div>
                        <div class="h-8 w-12 rounded border bg-gray-50"></div>
                        <div class="h-8 w-12 rounded border bg-gray-50"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <h2 class="text-2xl font-bold mb-4">Giỏ hàng trống</h2>
        <p class="text-gray-500 mb-8">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
        <a href="<?php echo BASE_URL; ?>/products" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-6 py-2 font-medium inline-flex items-center">
            Tiếp tục mua sắm
            <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(productId, action) {
    // Implement quantity update logic
}

function removeItem(productId) {
    // Implement remove item logic
}

function clearCart() {
    // Implement clear cart logic
}
</script> 