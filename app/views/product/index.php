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
                <input type="hidden" name="keyword" value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                
                <div class="sticky top-24 rounded-lg border p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-medium">Bộ lọc</h2>
                    </div>

                    <!-- Category Filter -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Danh mục</h3>
                        <div class="space-y-1">
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" 
                                           name="category[]" 
                                           value="<?php echo $category['maLoaiGiay']; ?>"
                                           <?php 
                                           if (isset($filters['categories']) && 
                                               in_array($category['maLoaiGiay'], $filters['categories'])) {
                                               echo 'checked';
                                           }
                                           ?>
                                           class="rounded border-gray-300">
                                    <span><?php echo htmlspecialchars($category['tenLoaiGiay']); ?></span>
                                </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Giá</h3>
                        <div class="space-y-1">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="price_range" value="0-500000" 
                                       <?php echo (isset($filters['price_range']) && $filters['price_range'] == '0-500000') ? 'checked' : ''; ?>>
                                <span>Dưới 500.000đ</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="price_range" value="500000-1000000"
                                       <?php echo (isset($filters['price_range']) && $filters['price_range'] == '500000-1000000') ? 'checked' : ''; ?>>
                                <span>500.000đ - 1.000.000đ</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="price_range" value="1000000-2000000"
                                       <?php echo (isset($filters['price_range']) && $filters['price_range'] == '1000000-2000000') ? 'checked' : ''; ?>>
                                <span>1.000.000đ - 2.000.000đ</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="price_range" value="2000000+"
                                       <?php echo (isset($filters['price_range']) && $filters['price_range'] == '2000000+') ? 'checked' : ''; ?>>
                                <span>Trên 2.000.000đ</span>
                            </label>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="border-t py-4">
                        <h3 class="font-medium mb-2">Sắp xếp</h3>
                        <select name="sort" class="w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                            <option value="">Mặc định</option>
                            <option value="price_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_asc') ? 'selected' : ''; ?>>
                                Giá: Thấp đến cao
                            </option>
                            <option value="price_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_desc') ? 'selected' : ''; ?>>
                                Giá: Cao đến thấp
                            </option>
                            <option value="name_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'name_asc') ? 'selected' : ''; ?>>
                                Tên: A-Z
                            </option>
                            <option value="name_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'name_desc') ? 'selected' : ''; ?>>
                                Tên: Z-A
                            </option>
                            <option value="newest" <?php echo (isset($filters['sort']) && $filters['sort'] == 'newest') ? 'selected' : ''; ?>>
                                Mới nhất
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="mt-4 w-full bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-4 py-2 font-medium">
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
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product->getHinhAnh()); ?>"
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

            <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center gap-1">
                    <!-- Previous Button -->
                    <a href="?page=<?php echo max(1, $currentPage - 1); ?>&keyword=<?php echo urlencode($keyword); ?>" 
                       class="rounded-md border px-3 py-2 text-sm <?php echo $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'; ?>"
                       <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>>
                        &lt;
                    </a>
                    
                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&keyword=<?php echo urlencode($keyword); ?>" 
                           class="rounded-md <?php echo $i === $currentPage ? 'bg-yellow-500 text-white' : 'border hover:bg-gray-100'; ?> px-3 py-2 text-sm">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <!-- Next Button -->
                    <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&keyword=<?php echo urlencode($keyword); ?>" 
                       class="rounded-md border px-3 py-2 text-sm <?php echo $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'; ?>"
                       <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>>
                        &gt;
                    </a>
                </nav>
            </div>
            <?php endif; ?>
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