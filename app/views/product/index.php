<div class="container py-8">
    <h1 class="text-3xl font-bold mb-8">Tất cả sản phẩm</h1>

    <!-- Search Bar -->
    <form action="<?php echo BASE_URL; ?>/products/search" method="GET" class="mb-8 max-w-2xl mx-auto">
        <div class="flex gap-4">
            <input type="text" 
                   name="keyword" 
                   placeholder="Tìm kiếm sản phẩm..." 
                   value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>"
                   class="flex-1 rounded-md border border-gray-300 px-4 py-2">
            <button type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-6 py-2">
                Tìm kiếm
            </button>
        </div>
    </form>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <form action="<?php echo BASE_URL; ?>/products/search" method="GET" id="filterForm">
                <!-- Giữ lại keyword nếu có -->
                <input type="hidden" name="keyword" value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                <!-- Giữ lại trang hiện tại -->
                <input type="hidden" name="page" value="<?php echo isset($currentPage) ? $currentPage : 1; ?>">
                
                <div class="sticky top-24 rounded-lg border p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-medium">Bộ lọc</h2>
                        <button type="reset" class="text-sm text-gray-500 hover:text-gray-700">Đặt lại</button>
                    </div>

                    <!-- Category Filter -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Danh mục</h3>
                        <?php foreach ($categories as $category): ?>
                        <label class="flex items-center gap-2 py-1">
                            <input type="checkbox" 
                                   name="category[]" 
                                   value="<?php echo $category['maLoaiGiay']; ?>"
                                   <?php echo (isset($filters['categories']) && in_array($category['maLoaiGiay'], $filters['categories'])) ? 'checked' : ''; ?>>
                            <span><?php echo htmlspecialchars($category['tenLoaiGiay']); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Khoảng giá</h3>
                        <select name="price_range" class="w-full rounded-md border p-2">
                            <option value="">Tất cả giá</option>
                            <option value="0-500000" <?php echo (isset($filters['price_range']) && $filters['price_range'] == '0-500000') ? 'selected' : ''; ?>>
                                Dưới 500.000đ
                            </option>
                            <option value="500000-1000000" <?php echo (isset($filters['price_range']) && $filters['price_range'] == '500000-1000000') ? 'selected' : ''; ?>>
                                500.000đ - 1.000.000đ
                            </option>
                            <option value="1000000-2000000" <?php echo (isset($filters['price_range']) && $filters['price_range'] == '1000000-2000000') ? 'selected' : ''; ?>>
                                1.000.000đ - 2.000.000đ
                            </option>
                            <option value="2000000-up" <?php echo (isset($filters['price_range']) && $filters['price_range'] == '2000000-up') ? 'selected' : ''; ?>>
                                Trên 2.000.000đ
                            </option>
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Sắp xếp</h3>
                        <select name="sort" class="w-full rounded-md border p-2">
                            <option value="">Mặc định</option>
                            <option value="price_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_asc') ? 'selected' : ''; ?>>
                                Giá tăng dần
                            </option>
                            <option value="price_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_desc') ? 'selected' : ''; ?>>
                                Giá giảm dần
                            </option>
                            <option value="name_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'name_asc') ? 'selected' : ''; ?>>
                                Tên A-Z
                            </option>
                            <option value="name_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'name_desc') ? 'selected' : ''; ?>>
                                Tên Z-A
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-yellow-500 text-white rounded-md py-2 hover:bg-yellow-600">
                        Áp dụng
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    <?php if (empty($products)): ?>
                        Không tìm thấy sản phẩm nào
                    <?php else: ?>
                        Hiển thị <?php echo count($products); ?> sản phẩm
                    <?php endif; ?>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
                    <div class="group relative">
                        <a href="<?php echo BASE_URL . '/products/detail/' . $product->getMaGiay(); ?>" 
                        class="block overflow-hidden rounded-lg bg-white shadow-md hover:shadow-lg">
                            <div class="aspect-square overflow-hidden">
                                <?php if ($product->getHinhAnh()): ?>
                                    <img 
                                        src="<?php echo BASE_URL ?>/public/img/<?php echo base64_decode($product->getHinhAnh()) ?>"
                                        alt="<?php echo htmlspecialchars($product->getTenGiay()); ?>"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg"
                                        alt="No Image"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php echo htmlspecialchars($product->getTenGiay()); ?>
                                    (size: <?php echo htmlspecialchars($product->getSize()); ?>)
                                </h3>
                                <p class="mt-1 text-gray-500">
                                    <?php echo htmlspecialchars($product->getTenLoaiGiay()); ?>
                                </p>
                                <div class="mt-3 flex items-center justify-between">
                                    <p class="text-lg font-medium text-yellow-500">
                                        <?php echo number_format($product->getGiaBan(), 0, ',', '.'); ?>đ
                                    </p>
                                    <button onclick="event.stopPropagation(); addToCart(<?php echo $product->getMaGiay(); ?>)" 
                                            class="rounded-md bg-yellow-500 px-3 py-2 text-sm text-white hover:bg-yellow-600">
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php 
            $currentPage = $currentPage ?? 1;
            $totalPages = $totalPages ?? 0;
            $keyword = $keyword ?? '';
            ?>

            <div class="mt-8 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?php 
                        $queryParams = $_GET;
                        $queryParams['page'] = $i;
                        echo BASE_URL . '/products/search?' . http_build_query($queryParams);
                    ?>" 
                       class="px-4 py-2 rounded-md <?php echo $i == $currentPage ? 'bg-yellow-500 text-white' : 'border hover:bg-gray-50'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = window.location.origin + "/Web2";

function addToCart(productId, event) {
    const quantity = 1;
    
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
            window.location.reload();
        } 
        else 
        {
            localStorage.setItem('showToast', 'error');
            localStorage.setItem('toastMessage', data.message);
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sắp xếp
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // Hiển thị loading state nếu muốn
            document.getElementById('filterForm').submit();
        });
    }
});
</script> 