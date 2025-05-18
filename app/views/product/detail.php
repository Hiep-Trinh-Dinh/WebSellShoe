<div class="container py-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="aspect-square overflow-hidden rounded-lg border">
                <?php if ($product->getHinhAnh()): ?>
                    <img 
                        src="<?php echo BASE_URL ?>/public/img/<?php echo base64_decode($product->getHinhAnh()) ?>"
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
                        <img 
                            src="<?php echo BASE_URL ?>/public/img/<?php echo base64_decode($product->getHinhAnh()) ?>"
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
                <h1 class="text-3xl font-bold">
                    <?php echo htmlspecialchars($product->getTenGiay()); ?>
                </h1>
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
                        <label class="flex cursor-pointer items-center justify-center rounded-md border py-2 border-yellow-500 bg-yellow-50">
                            <input 
                                type="radio" 
                                name="size" 
                                value="<?php echo $product->getSize() ?>" 
                                class="sr-only" 
                            >
                            <span class="text-sm"><?php echo $product->getSize() ?></span>
                        </label>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium">Tồn kho</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <input 
                            readonly
                            type="number" id="tonKho" 
                            value="<?php echo $product->getTonKho() ? $product->getTonKho() : 0  ?>"
                            class="h-10 w-20 rounded-md border text-center"
                        >
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium">Số lượng</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <button 
                            class="flex h-10 w-10 items-center justify-center rounded-md border"
                            onclick="updateQuantity('decrease')">
                            -
                        </button>
                        <input 
                            type="number" id="quantity" value="1" min="1" 
                            max="<?php echo $product->getTonKho(); ?>"
                            class="h-10 w-20 rounded-md border text-center"
                        >
                        <button class="flex h-10 w-10 items-center justify-center rounded-md border"
                                onclick="updateQuantity('increase')">
                            +
                        </button>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="addToCart(<?php echo $product->getMaGiay(); ?>)"
                            class="flex-1 bg-yellow-500 text-white rounded-md py-3 font-medium hover:bg-yellow-600">
                        Thêm vào giỏ hàng
                    </button>
                </div>

                <div class="border-t pt-6">
                <h3 class="font-medium">Mô tả sản phẩm</h3>
                <div class="prose prose-sm mt-4">
                    <p><?php echo $product->getMoTa() ?></p>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = window.location.origin + "/Web2";

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

    const maTK = localStorage.getItem('maTK');
    if(!maTK)
    {
        alert('Vui lòng đăng nhập trước khi thêm vào giỏ hàng');
        return;
    }
    
    fetch(BASE_URL + "/cart/add", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `productId=${productId}&quantity=${quantity}`
    })
    .then(response => {
        
        return response.json();
    })
    .then(data => {
        if (data.success) 
        {
            localStorage.setItem('showToast', 'success');
            localStorage.setItem('toastMessage', 'Đã thêm sản phẩm vào giỏ hàng');
            localStorage.setItem('cartItem', JSON.stringify(data.cartItem));
            let productTemp = JSON.parse(data.cartItem.product);
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; 
            let needToPush = true;
            if(cartItems.length > 0)
            {
                cartItems.forEach((item, index) => {
                    const product = JSON.parse(item.product);
                    if(product.maGiay == productTemp.maGiay)
                    {
                        item.quantity = parseInt(item.quantity) + parseInt(data.cartItem.quantity);
                        needToPush = false;
                    }
                });
            }
            if(needToPush)
            {
                cartItems.push({
                    product: data.cartItem.product,
                    quantity: parseInt(data.cartItem.quantity)
                });
            }


            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            window.location.href = BASE_URL + "/products/detail/" + productId;
        } 
        else 
        {
            localStorage.setItem('showToast', 'error');
            localStorage.setItem('toastMessage', data.message);
            window.location.href = BASE_URL + "/products/detail/" + productId;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script> 