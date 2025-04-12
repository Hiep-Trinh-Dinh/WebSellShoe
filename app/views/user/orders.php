<div class="container py-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-[240px_1fr]">
        <!-- Sidebar (giống như trong profile.php) -->
        <div class="space-y-4">
            <!-- ... (copy phần sidebar từ profile.php) ... -->
        </div>

        <!-- Orders List -->
        <div class="rounded-lg border">
            <div class="border-b p-4">
                <h2 class="font-medium">Đơn hàng của tôi</h2>
            </div>

            <?php if (!empty($orders)): ?>
                <div class="divide-y">
                    <?php foreach ($orders as $order): ?>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="font-medium">Đơn hàng #<?php echo $order->getMaHD(); ?></p>
                                <p class="text-sm text-gray-500">
                                    Ngày đặt: <?php echo date('d/m/Y', strtotime($order->getNgayTao())); ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">
                                    <?php echo number_format($order->getTongTien(), 0, ',', '.'); ?>đ
                                </p>
                                <p class="text-sm <?php 
                                    switch($order->getTrangThai()) {
                                        case 0:
                                            echo 'text-yellow-500">Chờ xử lý';
                                            break;
                                        case 1:
                                            echo 'text-blue-500">Đang giao';
                                            break;
                                        case 2:
                                            echo 'text-green-500">Đã giao';
                                            break;
                                        case 3:
                                            echo 'text-red-500">Đã hủy';
                                            break;
                                        default:
                                            echo 'text-gray-500">Không xác định';
                                    }
                                ?></p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <?php if (!empty($order->getItems())): ?>
                            <div class="space-y-3">
                                <?php foreach ($order->getItems() as $item): ?>
                                <div class="flex gap-4">
                                    <div class="h-20 w-20 flex-shrink-0">
                                        <?php if ($item['hinhAnh']): ?>
                                            <img src="<?php echo BASE_URL ?>/public/img/<?php echo base64_decode($item['hinhAnh']) ?>"
                                                 alt="<?php echo htmlspecialchars($item['tenGiay']); ?>"
                                                 class="h-full w-full object-cover rounded">
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium"><?php echo htmlspecialchars($item['tenGiay']); ?></p>
                                        <p class="text-sm text-gray-500">
                                            Số lượng: <?php echo $item['soLuong']; ?>
                                        </p>
                                        <p class="text-sm font-medium">
                                            <?php echo number_format($item['giaBan'], 0, ',', '.'); ?>đ
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="p-8 text-center">
                    <p class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào</p>
                    <a href="<?php echo BASE_URL; ?>/products" 
                       class="inline-block rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                        Mua sắm ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 