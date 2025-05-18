<div class="order-details-container">
    <div class="order-details-card">
        <div class="order-header">
            <div class="order-title">
                <h1>Chi tiết đơn hàng #<?php echo $order['maHD']; ?></h1>
                <span class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['ngayTao'])); ?></span>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/orders" class="back-button">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="order-content">
            <!-- Order Summary Section -->
            <div class="order-summary">
                <h2 class="section-title">Thông tin đơn hàng</h2>
                
                <div class="info-group">
                    <div class="info-item">
                        <span class="info-label">Mã đơn hàng:</span>
                        <span class="info-value">#<?php echo $order['maHD']; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Khách hàng:</span>
                        <span class="info-value"><?php echo $order['tenTK']; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Tổng số lượng:</span>
                        <span class="info-value"><?php echo $order['tongSoLuong']; ?> sản phẩm</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Tổng tiền:</span>
                        <span class="info-value total-price"><?php echo number_format($order['tongTien']); ?>đ</span>
                    </div>
                    
                    <div class="info-item status-item">
                        <span class="info-label">Trạng thái:</span>
                        <div class="status-select-wrapper">
                            <select class="status-select" data-id="<?php echo $order['maHD']; ?>">
                                <option value="1" <?php echo (int)$order['trangThai'] === 1 ? 'selected' : ''; ?>>Đang xử lý</option>
                                <option value="2" <?php echo (int)$order['trangThai'] === 2 ? 'selected' : ''; ?>>Đang giao hàng</option>
                                <option value="3" <?php echo (int)$order['trangThai'] === 3 ? 'selected' : ''; ?>>Đã giao hàng</option>
                                <option value="4" <?php echo (int)$order['trangThai'] === 4 ? 'selected' : ''; ?>>Đã hủy</option>
                            </select>
                            <!-- Debug info -->
                            <p style="font-size: 10px; margin-top: 5px; color: #888;">
                                Giá trị hiện tại: <?php echo $order['trangThai']; ?> (<?php echo gettype($order['trangThai']); ?>)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="order-details">
                <h2 class="section-title">Chi tiết sản phẩm</h2>
                
                <div class="products-table-wrapper">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Size</th>
                                <th>Giá bán</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stt = 1;
                            foreach ($orderDetails as $detail): 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $stt++; ?></td>
                                    <td class="product-name"><?php echo $detail['tenGiay']; ?></td>
                                    <td class="size"><?php echo $detail['size']; ?></td>
                                    <td class="text-right"><?php echo number_format($detail['giaBan']); ?>đ</td>
                                    <td class="text-center"><?php echo $detail['soLuong']; ?></td>
                                    <td class="text-right product-total"><?php echo number_format($detail['thanhTien']); ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right">Tổng cộng:</td>
                                <td class="text-right grand-total"><?php echo number_format($order['tongTien']); ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4361ee;
    --primary-light: #eef2ff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-color: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --border-radius: 8px;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9fafb;
    color: var(--text-color);
    margin: 0;
    padding: 0;
    line-height: 1.5;
}

.order-details-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem;
}

.order-details-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    width: 100%;
    max-width: 1000px;
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
}

.order-title {
    display: flex;
    flex-direction: column;
}

.order-title h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
    margin: 0;
}

.order-date {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.back-button {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #f3f4f6;
    color: var(--text-color);
    border-radius: var(--border-radius);
    text-decoration: none;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.back-button:hover {
    background-color: #e5e7eb;
}

.order-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    padding: 2rem;
}

@media (max-width: 768px) {
    .order-content {
        grid-template-columns: 1fr;
    }
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-color);
}

/* Order Summary Styles */
.order-summary {
    background-color: #f9fafb;
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-label {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.info-value {
    font-weight: 500;
}

.total-price {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1.125rem;
}

.status-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.status-select-wrapper {
    width: 100%;
    position: relative;
}

.status-select {
    width: 100%;
    padding: 0.625rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
    background-color: white;
    font-size: 0.875rem;
    appearance: none;
    cursor: pointer;
}

.status-select:focus {
    outline: 2px solid var(--primary-light);
    border-color: var(--primary-color);
}

.status-select option {
    padding: 0.5rem;
}

/* Order Details Styles */
.products-table-wrapper {
    overflow-x: auto;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th {
    background-color: #f9fafb;
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-color);
}

.products-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    font-size: 0.875rem;
}

.products-table tbody tr:last-child td {
    border-bottom: 2px solid var(--border-color);
}

.products-table tfoot td {
    padding: 1rem;
    font-weight: 600;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.product-name {
    font-weight: 500;
}

.product-total {
    font-weight: 600;
}

.grand-total {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1rem;
}

/* Status colors */
.status-select[data-id="<?php echo $order['maHD']; ?>"] {
    <?php if ((int)$order['trangThai'] === 1): ?>
    border-color: #3b82f6;
    background-color: #eff6ff;
    color: #1d4ed8;
    <?php elseif ((int)$order['trangThai'] === 2): ?>
    border-color: #f59e0b;
    background-color: #fffbeb;
    color: #b45309;
    <?php elseif ((int)$order['trangThai'] === 3): ?>
    border-color: #10b981;
    background-color: #ecfdf5;
    color: #047857;
    <?php elseif ((int)$order['trangThai'] === 4): ?>
    border-color: #ef4444;
    background-color: #fef2f2;
    color: #b91c1c;
    <?php endif; ?>
}
</style>

<script>
$(document).ready(function() {
    // Lấy trạng thái hiện tại để so sánh sau khi cập nhật
    var currentStatus = parseInt('<?php echo $order['trangThai']; ?>');
    console.log("Trạng thái hiện tại:", currentStatus, "- Kiểu dữ liệu:", typeof currentStatus);
    
    $('.status-select').change(function() {
        var orderId = $(this).data('id');
        var status = parseInt($(this).val()); // Đảm bảo giá trị status là số nguyên
        var $select = $(this);
        
        console.log("Đang cập nhật trạng thái đơn hàng #" + orderId + " từ: " + currentStatus + " sang: " + status);
        
        // Hiển thị hộp thoại xác nhận
        if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng từ ' + 
                    getStatusText(currentStatus) + ' thành ' + 
                    getStatusText(status) + '?')) {
            // Nếu người dùng không xác nhận, đặt lại giá trị select
            $(this).val(currentStatus);
            return false;
        }
        
        // Hiển thị thông báo đang xử lý
        showNotification('Đang cập nhật trạng thái...', 'info');
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>/admin/orders/update-status',
            type: 'POST',
            data: {
                orderId: orderId,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                console.log("Response from server:", response);
                if (response.success) {
                    showNotification('Cập nhật trạng thái thành công!', 'success');
                    
                    // Ghi log thông tin cập nhật
                    console.log("Cập nhật thành công - Trạng thái cũ: " + response.oldStatus + ", Trạng thái mới: " + response.newStatus);
                    
                    // Tải lại trang sau 1 giây để hiển thị trạng thái mới
                    setTimeout(function() {
                        window.location.href = '<?php echo BASE_URL; ?>/admin/orders/view/' + orderId;
                    }, 1000);
                } else {
                    showNotification('Có lỗi xảy ra khi cập nhật trạng thái: ' + (response.message || 'Không xác định'), 'error');
                    console.error("Error response:", response);
                    // Đặt lại giá trị select
                    $select.val(currentStatus);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                console.log("Status:", status);
                console.log("Response:", xhr.responseText);
                showNotification('Có lỗi xảy ra: ' + error, 'error');
                // Đặt lại giá trị select
                $select.val(currentStatus);
            }
        });
    });
    
    // Hàm hiển thị thông báo
    function showNotification(message, type) {
        var bgColor, textColor;
        
        switch(type) {
            case 'success':
                bgColor = '#d1fae5';
                textColor = '#047857';
                break;
            case 'error':
                bgColor = '#fee2e2';
                textColor = '#b91c1c';
                break;
            case 'info':
            default:
                bgColor = '#e0f2fe';
                textColor = '#0369a1';
                break;
        }
        
        // Tạo và hiển thị thông báo
        var $notification = $('<div>')
            .text(message)
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'background-color': bgColor,
                'color': textColor,
                'padding': '10px 15px',
                'border-radius': '4px',
                'box-shadow': '0 2px 5px rgba(0,0,0,0.2)',
                'z-index': '9999',
                'opacity': '0',
                'transition': 'opacity 0.3s ease'
            })
            .appendTo('body');
        
        // Hiệu ứng hiển thị
        setTimeout(function() {
            $notification.css('opacity', '1');
        }, 10);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            $notification.css('opacity', '0');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Hàm chuyển đổi mã trạng thái thành văn bản
    function getStatusText(statusCode) {
        switch(parseInt(statusCode)) {
            case 1: return 'Đang xử lý';
            case 2: return 'Đang giao hàng';
            case 3: return 'Đã giao hàng';
            case 4: return 'Đã hủy';
            default: return 'Không xác định';
        }
    }
});
</script>