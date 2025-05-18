<?php
// Format giá tiền
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . 'đ';
}

// Format trạng thái đơn hàng
function formatStatus($status) {
    switch ($status) {
        case 1:
            return '<span class="badge bg-warning text-dark">Đang xử lý</span>';
        case 2:
            return '<span class="badge bg-primary">Đang giao hàng</span>';
        case 3:
            return '<span class="badge bg-success">Đã giao hàng</span>';
        case 4:
            return '<span class="badge bg-danger">Đã hủy</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}
?>

<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold mb-4">Danh sách đơn hàng</h2>
        
        <!-- Search form -->
        <form action="<?php echo BASE_URL; ?>/admin/orders" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-6 flex">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Tìm theo SĐT hoặc địa chỉ..."
                    value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>"
                    class="border rounded-l-md px-4 py-2 flex-grow focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 flex-shrink-0"
                >
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="md:col-span-3">
                <select 
                    name="status" 
                    class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                    onchange="this.form.submit()"
                >
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : ''; ?>>Đang giao hàng</option>
                    <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : ''; ?>>Đã giao hàng</option>
                    <option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : ''; ?>>Đã hủy</option>
                </select>
            </div>
            
            <?php if (!empty($searchTerm) || !empty($status)): ?>
            <div class="md:col-span-3">
                <a href="<?php echo BASE_URL; ?>/admin/orders" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                    <i class="fas fa-times mr-1"></i> Xóa bộ lọc
                </a>
            </div>
            <?php endif; ?>
        </form>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mt-3 p-2 bg-red-50 text-red-700 border border-red-200 rounded-md">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="p-6">
        <?php if (!empty($searchTerm) || $status !== null): ?>
        <div class="mb-4 p-3 bg-blue-50 rounded-md border border-blue-100">
            <p>
                <?php if ($status !== null): ?>
                <strong>Trạng thái: </strong>
                <?php 
                    switch($status) {
                        case 1: echo 'Đang xử lý'; break;
                        case 2: echo 'Đang giao hàng'; break;
                        case 3: echo 'Đã giao hàng'; break;
                        case 4: echo 'Đã hủy'; break;
                        default: echo 'Tất cả';
                    }
                ?>
                <?php endif; ?>
                
                <span class="ml-2">(<?php echo isset($pagination['total']) ? $pagination['total'] : $total ?? 0; ?> đơn hàng)</span>
            </p>
        </div>
        <?php endif; ?>
        
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
                        <th class="pb-4">Số điện thoại</th>
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
                            <td class="py-4"><?php echo $order['soDienThoai'] ?? 'Không có'; ?></td>
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
                            <td colspan="9" class="py-4 text-center">Không có đơn hàng nào</td>
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
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $pagination['currentPage'] - 1; ?><?php echo !empty($searchTerm) ? '&search='.urlencode($searchTerm) : ''; ?><?php echo !empty($status) ? '&status='.$status : ''; ?>" 
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
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $i; ?><?php echo !empty($searchTerm) ? '&search='.urlencode($searchTerm) : ''; ?><?php echo !empty($status) ? '&status='.$status : ''; ?>" 
                       class="px-3 py-1 rounded border <?php echo $i == $pagination['currentPage'] ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/orders?page=<?php echo $pagination['currentPage'] + 1; ?><?php echo !empty($searchTerm) ? '&search='.urlencode($searchTerm) : ''; ?><?php echo !empty($status) ? '&status='.$status : ''; ?>" 
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
const BASE_URL = window.location.origin + "/Web2";

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