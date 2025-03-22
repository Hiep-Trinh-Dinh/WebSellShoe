<div class="container py-8">
    <h1 class="text-3xl font-bold mb-8">Tất cả sản phẩm</h1>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <div class="sticky top-24 rounded-lg border p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-medium">Bộ lọc</h2>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>

                <!-- Category Filter -->
                <div class="border-t py-4">
                    <button class="flex w-full items-center justify-between">
                        <span class="font-medium">Danh mục</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="mt-2 space-y-1">
                        <?php if (isset($categories) && !empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="category[]" 
                                       value="<?php echo $category['maLoaiGiay']; ?>" 
                                       class="rounded border-gray-300">
                                <span><?php echo htmlspecialchars($category['tenLoaiGiay']); ?></span>
                                <span class="text-sm text-gray-500">
                                    (<?php echo isset($category['product_count']) ? $category['product_count'] : '0'; ?>)
                                </span>
                            </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Không có danh mục nào</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="border-t py-4">
                    <button class="flex w-full items-center justify-between">
                        <span class="font-medium">Giá</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="mt-2 space-y-1">
                        <?php
                        $priceRanges = [
                            'Dưới 500.000đ',
                            '500.000đ - 1.000.000đ',
                            '1.000.000đ - 2.000.000đ',
                            'Trên 2.000.000đ'
                        ];
                        foreach ($priceRanges as $range):
                        ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-gray-300">
                            <span><?php echo $range; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button class="mt-4 w-full bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-4 py-2 font-medium">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-500">Hiển thị <?php echo count($products); ?> sản phẩm</p>
                <select class="rounded-md border border-gray-300 px-3 py-1 text-sm">
                    <option>Sắp xếp: Mặc định</option>
                    <option>Giá: Thấp đến cao</option>
                    <option>Giá: Cao đến thấp</option>
                    <option>Mới nhất</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
                <a href="<?php echo BASE_URL; ?>/products/detail/<?php echo $product['maGiay']; ?>" class="group">
                    <div class="overflow-hidden rounded-lg bg-white shadow-md transition-all hover:shadow-lg">
                        <div class="relative h-64 w-full overflow-hidden">
                            <?php if ($product['hinhAnh']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['hinhAnh']); ?>"
                                     alt="<?php echo htmlspecialchars($product['tenGiay']); ?>"
                                     class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg"
                                     alt="No Image"
                                     class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                            <?php endif; ?>
                            <div class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                <?php echo htmlspecialchars($product['tenLoaiGiay']); ?>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium"><?php echo htmlspecialchars($product['tenGiay']); ?></h3>
                            <div class="mt-1 flex items-center">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="h-4 w-4 <?php echo $i < 4 ? 'text-yellow-500 fill-yellow-500' : 'text-gray-200 fill-gray-200'; ?>" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                <?php endfor; ?>
                                <span class="ml-2 text-sm text-gray-500">(<?php echo rand(10, 100); ?>)</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="font-bold"><?php echo number_format($product['giaBan'], 0, ',', '.'); ?>đ</span>
                                <button class="rounded-md bg-yellow-500 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center gap-1">
                    <button class="rounded-md border px-3 py-2 text-sm" disabled>&lt;</button>
                    <button class="rounded-md bg-yellow-500 px-3 py-2 text-sm text-white">1</button>
                    <button class="rounded-md border px-3 py-2 text-sm">2</button>
                    <button class="rounded-md border px-3 py-2 text-sm">3</button>
                    <button class="rounded-md border px-3 py-2 text-sm">&gt;</button>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
function addToCart(productId, event) {
    event.preventDefault();
    
    fetch(`${BASE_URL}/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `productId=${productId}&quantity=1`
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