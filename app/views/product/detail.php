<div class="container py-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="aspect-square overflow-hidden rounded-lg border">
                <?php if ($product->getHinhAnh()): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product->getHinhAnh()); ?>"
                         alt="<?php echo htmlspecialchars($product->getTenGiay()); ?>"
                         class="h-full w-full object-cover">
                <?php else: ?>
                    <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg"
                         alt="No Image"
                         class="h-full w-full object-cover">
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="aspect-square cursor-pointer overflow-hidden rounded-lg border hover:border-yellow-500">
                    <?php if ($product->getHinhAnh()): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product->getHinhAnh()); ?>"
                             alt="<?php echo htmlspecialchars($product->getTenGiay()); ?>"
                             class="h-full w-full object-cover">
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg"
                             alt="No Image"
                             class="h-full w-full object-cover">
                    <?php endif; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($product->getTenGiay()); ?></h1>
                <p class="text-lg text-gray-500"><?php echo htmlspecialchars($product->getTenLoaiGiay()); ?></p>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg class="h-5 w-5 <?php echo $i < 4 ? 'text-yellow-500 fill-yellow-500' : 'text-gray-200 fill-gray-200'; ?>"
                             viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    <?php endfor; ?>
                </div>
                <span class="text-sm text-gray-500">(<?php echo rand(10, 100); ?> đánh giá)</span>
            </div>

            <div>
                <p class="text-3xl font-bold text-yellow-500">
                    <?php echo number_format($product->getGiaBan(), 0, ',', '.'); ?>đ
                </p>
                <p class="text-sm text-gray-500">
                    Đã bao gồm thuế
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium">Size</h3>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        <?php
                        $sizes = [36, 37, 38, 39, 40, 41, 42, 43];
                        foreach ($sizes as $size):
                        ?>
                        <label class="flex cursor-pointer items-center justify-center rounded-md border py-2 <?php echo $size == $product->getSize() ? 'border-yellow-500 bg-yellow-50' : 'hover:border-yellow-500'; ?>">
                            <input type="radio" name="size" value="<?php echo $size; ?>" class="sr-only" 
                                   <?php echo $size == $product->getSize() ? 'checked' : ''; ?>>
                            <span class="text-sm"><?php echo $size; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium">Số lượng</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <button class="flex h-10 w-10 items-center justify-center rounded-md border"
                                onclick="updateQuantity('decrease')">-</button>
                        <input type="number" id="quantity" value="1" min="1" 
                               max="<?php echo $product->getTonKho(); ?>"
                               class="h-10 w-20 rounded-md border text-center">
                        <button class="flex h-10 w-10 items-center justify-center rounded-md border"
                                onclick="updateQuantity('increase')">+</button>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="addToCart(<?php echo $product->getMaGiay(); ?>)"
                            class="flex-1 bg-yellow-500 text-white rounded-md py-3 font-medium hover:bg-yellow-600">
                        Thêm vào giỏ hàng
                    </button>
                    <button class="flex-1 border border-yellow-500 text-yellow-500 rounded-md py-3 font-medium hover:bg-yellow-50">
                        Mua ngay
                    </button>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="font-medium">Mô tả sản phẩm</h3>
                <div class="prose prose-sm mt-4">
                    <p>Thông tin chi tiết về sản phẩm...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(action) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxStock = <?php echo $product->getTonKho(); ?>;
    
    if (action === 'increase' && currentValue < maxStock) {
        input.value = currentValue + 1;
    } else if (action === 'decrease' && currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    const size = document.querySelector('input[name="size"]:checked').value;
    
    fetch(`${BASE_URL}/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `productId=${productId}&quantity=${quantity}&size=${size}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã thêm sản phẩm vào giỏ hàng');
            if (data.cartCount) {
                document.getElementById('cartCount').textContent = data.cartCount;
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script> 