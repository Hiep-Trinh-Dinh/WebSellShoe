<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Thống kê tổng quan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-20">
                <i class="fas fa-box text-blue-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Tổng sản phẩm</p>
                <p class="text-2xl font-semibold"><?php echo $stats['totalProducts']; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-20">
                <i class="fas fa-shopping-cart text-green-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Tổng đơn hàng</p>
                <p class="text-2xl font-semibold"><?php echo $stats['totalOrders']; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20">
                <i class="fas fa-users text-yellow-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Tổng người dùng</p>
                <p class="text-2xl font-semibold"><?php echo $stats['totalUsers']; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 bg-opacity-20">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Sắp hết hàng</p>
                <p class="text-2xl font-semibold"><?php echo count($stats['lowStockProducts']); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Đơn hàng gần đây -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="p-6 border-b">
        <h2 class="text-lg font-semibold">Đơn hàng gần đây</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã đơn</th>
                        <th class="pb-4">Ngày tạo</th>
                        <th class="pb-4">Tổng tiền</th>
                        <th class="pb-4">Trạng thái</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['recentOrders'])): ?>
                        <?php foreach ($stats['recentOrders'] as $order): ?>
                        <tr class="border-t">
                            <td class="py-4">#<?php echo $order['maHD']; ?></td>
                            <td class="py-4"><?php echo date('d/m/Y', strtotime($order['ngayTao'])); ?></td>
                            <td class="py-4"><?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php
                                    switch($order['trangThai']) {
                                        case 0:
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 1:
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        case 2:
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 3:
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                    }
                                    ?>">
                                    <?php
                                    switch($order['trangThai']) {
                                        case 0:
                                            echo 'Chờ xử lý';
                                            break;
                                        case 1:
                                            echo 'Đang giao';
                                            break;
                                        case 2:
                                            echo 'Đã giao';
                                            break;
                                        case 3:
                                            echo 'Đã hủy';
                                            break;
                                    }
                                    ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <a href="<?php echo BASE_URL; ?>/admin/orders/view/<?php echo $order['maHD']; ?>" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Không có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sản phẩm sắp hết hàng -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-lg font-semibold">Sản phẩm sắp hết hàng</h2>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="text-left">
                    <th class="pb-4">Sản phẩm</th>
                    <th class="pb-4">Tồn kho</th>
                    <th class="pb-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['lowStockProducts'] as $product): ?>
                <tr class="border-t">
                    <td class="py-4">
                        <div class="flex items-center">
                            <?php if ($product['hinhAnh']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['hinhAnh']); ?>"
                                     alt="<?php echo htmlspecialchars($product['tenGiay']); ?>"
                                     class="w-12 h-12 object-cover rounded">
                            <?php endif; ?>
                            <span class="ml-4"><?php echo htmlspecialchars($product['tenGiay']); ?></span>
                        </div>
                    </td>
                    <td class="py-4">
                        <span class="text-red-500"><?php echo $product['tonKho']; ?></span>
                    </td>
                    <td class="py-4">
                        <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $product['maGiay']; ?>" 
                           class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 