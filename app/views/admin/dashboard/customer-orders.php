<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng của khách hàng: <?php echo $customerData['tenTK']; ?></h1>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-3">Thông tin khách hàng</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">Tên:</span> <?php echo $customerData['tenTK']; ?></p>
                    <p><span class="font-medium">Email:</span> <?php echo $customerData['email'] ?? ''; ?></p>
                </div>
                <div>
                    <p><span class="font-medium">Số điện thoại:</span> <?php echo $customerData['soDT'] ?? ''; ?></p>
                    <p><span class="font-medium">Địa chỉ:</span> <?php echo $customerData['diaChi'] ?? ''; ?></p>
                </div>
            </div>
        </div>

        <!-- Thống kê tháng -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-3">Thống kê từ <?php echo date('d/m/Y', strtotime($startDate)); ?> đến <?php echo date('d/m/Y', strtotime($endDate)); ?></h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">Tổng số đơn hàng:</span> <?php echo $totalOrders; ?></p>
                </div>
                <div>
                    <p><span class="font-medium">Tổng tiền:</span> <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</p>
                </div>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <?php if (empty($orders)): ?>
            <div class="text-center py-4">
                <p class="text-gray-500">Không có đơn hàng nào trong khoảng thời gian này</p>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="border rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold">Đơn hàng #<?php echo $order['maHD']; ?></h3>
                            <p class="text-gray-600">Ngày tạo: <?php echo date('d/m/Y H:i', strtotime($order['ngayTao'])); ?></p>
                        </div>
                        <div>
                            <span class="font-medium">Tổng tiền: <?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</span>
                        </div>
                    </div>

                    <!-- Chi tiết sản phẩm -->
                    <div class="mt-4">
                        <h4 class="font-medium mb-2">Chi tiết sản phẩm:</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-2 text-left">Sản phẩm</th>
                                        <th class="px-4 py-2 text-center w-24">Số lượng</th>
                                        <th class="px-4 py-2 text-right w-32">Đơn giá</th>
                                        <th class="px-4 py-2 text-right w-32">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['products'] as $product): ?>
                                        <tr>
                                            <td class="px-4 py-2 text-left"><?php echo $product['tenGiay']; ?></td>
                                            <td class="px-4 py-2 text-center"><?php echo $product['soLuong']; ?></td>
                                            <td class="px-4 py-2 text-right"><?php echo number_format($product['giaBan'], 0, ',', '.'); ?>đ</td>
                                            <td class="px-4 py-2 text-right"><?php echo number_format($product['thanhTien'], 0, ',', '.'); ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>