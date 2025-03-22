<div class="flex flex-col">
    <!-- Hero Section -->
    <section class="relative h-[600px] w-full overflow-hidden">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <img src="<?php echo BASE_URL; ?>/public/images/hero-banner.jpg" alt="Hero Image" class="absolute inset-0 object-cover w-full h-full">
        <div class="container relative z-20 flex h-full flex-col items-center justify-center text-center text-white">
            <h1 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                Step Into <span class="text-yellow-400">Style</span>
            </h1>
            <p class="mt-4 max-w-lg text-lg">
                Khám phá bộ sưu tập giày cao cấp được thiết kế cho sự thoải mái và phong cách
            </p>
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <a href="<?php echo BASE_URL; ?>/products" class="inline-flex items-center justify-center rounded-md bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                    Mua sắm ngay
                </a>
                <a href="<?php echo BASE_URL; ?>/products?category=new" class="inline-flex items-center justify-center rounded-md border border-white px-4 py-2 text-sm font-medium text-white hover:bg-white hover:text-black">
                    Sản phẩm mới
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container">
            <h2 class="text-3xl font-bold text-center mb-12">Danh mục sản phẩm</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $categories = ['Nam', 'Nữ', 'Trẻ em'];
                foreach ($categories as $category):
                ?>
                <a href="<?php echo BASE_URL; ?>/products?category=<?php echo strtolower($category); ?>" 
                   class="group relative h-80 overflow-hidden rounded-lg">
                    <img src="<?php echo BASE_URL; ?>/public/images/category-<?php echo strtolower($category); ?>.jpg" 
                         alt="<?php echo $category; ?>"
                         class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white"><?php echo $category; ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="container">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold">Sản phẩm nổi bật</h2>
                <a href="<?php echo BASE_URL; ?>/products" class="flex items-center text-yellow-500 hover:text-yellow-600">
                    Xem tất cả
                    <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($featuredProducts as $product): ?>
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
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="container">
            <h2 class="text-3xl font-bold text-center mb-12">Khách hàng nói gì về chúng tôi</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $testimonials = [
                    [
                        'name' => 'Nguyễn Văn A',
                        'text' => 'Giày rất thoải mái, tôi có thể đi cả ngày mà không thấy mệt mỏi.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Trần Thị B',
                        'text' => 'Chất lượng sản phẩm tuyệt vời, dịch vụ khách hàng rất tốt!',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Lê Văn C',
                        'text' => 'Giao hàng nhanh, đóng gói cẩn thận, sẽ ủng hộ shop dài dài.',
                        'rating' => 4
                    ]
                ];
                foreach ($testimonials as $testimonial):
                ?>
                <div class="rounded-lg bg-gray-50 p-6 shadow">
                    <div class="flex items-center mb-4">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-5 w-5 <?php echo $i < $testimonial['rating'] ? 'text-yellow-500 fill-yellow-500' : 'text-gray-200 fill-gray-200'; ?>" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-4 italic">"<?php echo $testimonial['text']; ?>"</p>
                    <p class="font-medium"><?php echo $testimonial['name']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-16 bg-yellow-500 text-white">
        <div class="container text-center">
            <h2 class="text-3xl font-bold mb-4">Đăng ký nhận tin</h2>
            <p class="mb-8 max-w-lg mx-auto">
                Đăng ký để nhận những ưu đãi đặc biệt và thông tin về sản phẩm mới.
            </p>
            <form class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto">
                <input type="email" placeholder="Nhập email của bạn" 
                       class="flex-1 rounded-md px-4 py-2 text-black" required>
                <button type="submit" 
                        class="bg-black hover:bg-gray-800 text-white rounded-md px-4 py-2 font-medium">
                    Đăng ký
                </button>
            </form>
        </div>
    </section>
</div> 