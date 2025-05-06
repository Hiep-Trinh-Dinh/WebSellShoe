<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold">Danh sách đơn hàng</h2>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã ĐH</th>
                        <th class="pb-4">Khách hàng</th>
                        <th class="pb-4">Ngày tạo</th>
                        <th class="pb-4">Số lượng</th>
                        <th class="pb-4">Tổng tiền</th>
                        <th class="pb-4">Thanh toán</th>
                        <th class="pb-4">Địa chỉ</th>
                        <th class="pb-4">Trạng thái</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr class="border-t">
                            <td class="py-4">#<?php echo $order['maHD']; ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($order['tenTK'] ?? 'Khách vãng lai'); ?></td>
                            <td class="py-4"><?php echo date('d/m/Y H:i', strtotime($order['ngayTao'])); ?></td>
                            <td class="py-4"><?php echo $order['tongSoLuong']; ?></td>
                            <td class="py-4"><?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</td>
                            <td class="py-4" ><?php echo $order['thanhToan'] == 1 ? "Tiền mặt" : "Chuyển khoản" ?></td>
                            <td class="py-4"><?php echo $order['diaChi'] ?></td>
                            <td class="py-4">
                                <select onchange="updateOrderStatus(<?php echo $order['maHD']; ?>, this.value)"
                                        class="rounded-full px-3 py-1 text-sm 
                                        <?php
                                        switch($order['trangThai']) {
                                            case 1: echo 'bg-green-100 text-green-800'; break;
                                            case 2: echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 3: echo 'bg-blue-100 text-blue-800'; break;
                                            case 4: echo 'bg-gray-100 text-gray-800'; break;
                                            default: echo 'bg-red-100 text-red-800';
                                        }
                                        ?>">
                                    <option value="1" <?php echo $order['trangThai'] == 1 ? 'selected' : ''; ?>>Đang xử lý</option>
                                    <option value="2" <?php echo $order['trangThai'] == 2 ? 'selected' : ''; ?>>Đang giao</option>
                                    <option value="3" <?php echo $order['trangThai'] == 3 ? 'selected' : ''; ?>>Đã giao</option>
                                    <option value="4" <?php echo $order['trangThai'] == 4 ? 'selected' : ''; ?>>Đã hủy</option>
                                </select>
                            </td>
                            <td class="py-4">
                                <a href="<?php echo BASE_URL; ?>/admin/orders/view/<?php echo $order['maHD']; ?>" 
                                   class="text-blue-500 hover:text-blue-700"
                                   onclick="return confirm('Bạn có muốn xem chi tiết đơn hàng này?')">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-4 text-center">Không có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Phân trang -->
        <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="flex justify-center mt-6">
            <nav class="flex items-center space-x-2">
                <?php if ($pagination['currentPage'] > 1): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $pagination['currentPage'] - 1; ?>" 
                       class="px-3 py-1 rounded border hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php
                // Hiển thị số trang
                $startPage = max(1, $pagination['currentPage'] - 2);
                $endPage = min($pagination['totalPages'], $startPage + 4);
                
                if ($endPage - $startPage < 4 && $startPage > 1) {
                    $startPage = max(1, $endPage - 4);
                }
                
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $i; ?>" 
                       class="px-3 py-1 rounded border <?php echo $i == $pagination['currentPage'] ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $pagination['currentPage'] + 1; ?>" 
                       class="px-3 py-1 rounded border hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
        fetch(`${BASE_URL}/admin/orders/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `orderId=${orderId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    }
}
</script> 