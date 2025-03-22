<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Chi tiết đơn hàng #<?php echo $order['maHD']; ?></h2>
            <a href="<?php echo BASE_URL; ?>/admin/orders" 
               class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Thông tin đơn hàng -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Thông tin đơn hàng</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Mã đơn hàng:</p>
                    <p class="font-medium">#<?php echo $order['maHD']; ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Ngày tạo:</p>
                    <p class="font-medium"><?php echo date('d/m/Y H:i', strtotime($order['ngayTao'])); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Trạng thái:</p>
                    <p class="font-medium">
                        <?php
                        switch($order['trangThai']) {
                            case 0:
                                echo '<span class="text-yellow-600">Chờ xử lý</span>';
                                break;
                            case 1:
                                echo '<span class="text-blue-600">Đang giao</span>';
                                break;
                            case 2:
                                echo '<span class="text-green-600">Đã giao</span>';
                                break;
                            case 3:
                                echo '<span class="text-red-600">Đã hủy</span>';
                                break;
                        }
                        ?>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Tổng tiền:</p>
                    <p class="font-medium"><?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</p>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Thông tin khách hàng</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Tên khách hàng:</p>
                    <p class="font-medium"><?php echo htmlspecialchars($order['tenTK'] ?? 'Khách vãng lai'); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Email:</p>
                    <p class="font-medium"><?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Số điện thoại:</p>
                    <p class="font-medium"><?php echo htmlspecialchars($order['soDT'] ?? 'N/A'); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Địa chỉ:</p>
                    <p class="font-medium"><?php echo htmlspecialchars($order['diaChi'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <!-- Chi tiết đơn hàng -->
        <div>
            <h3 class="text-lg font-medium mb-4">Chi tiết đơn hàng</h3>
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Sản phẩm</th>
                        <th class="pb-4">Size</th>
                        <th class="pb-4">Đơn giá</th>
                        <th class="pb-4">Số lượng</th>
                        <th class="pb-4">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails as $item): ?>
                    <tr class="border-t">
                        <td class="py-4"><?php echo htmlspecialchars($item['tenGiay']); ?></td>
                        <td class="py-4"><?php echo $item['size']; ?></td>
                        <td class="py-4"><?php echo number_format($item['giaBan'], 0, ',', '.'); ?>đ</td>
                        <td class="py-4"><?php echo $item['soLuong']; ?></td>
                        <td class="py-4"><?php echo number_format($item['thanhTien'], 0, ',', '.'); ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="border-t">
                        <td colspan="4" class="py-4 text-right font-medium">Tổng cộng:</td>
                        <td class="py-4 font-medium"><?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div> 