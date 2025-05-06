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
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">Danh sách đơn hàng gần đây</h2>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="startDate" class="text-gray-600">Từ ngày:</label>
                    <input type="date" id="startDate" class="border rounded px-3 py-1">
                </div>
                <div class="flex items-center gap-2">
                    <label for="endDate" class="text-gray-600">Đến ngày:</label>
                    <input type="date" id="endDate" class="border rounded px-3 py-1">
                </div>
                <button onclick="searchOrders()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Tìm kiếm
                </button>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã đơn</th>
                        <th class="pb-4">Khách hàng</th>
                        <th class="pb-4">Ngày mua</th>
                        <th class="pb-4">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (!empty($stats['recentOrders'])): ?>
                        <?php foreach ($stats['recentOrders'] as $order): ?>
                        <tr class="border-t">
                            <td class="py-4">#<?php echo $order['maHD']; ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($order['tenTK']); ?></td>
                            <td class="py-4"><?php echo date('d/m/Y H:i', strtotime($order['ngayTao'])); ?></td>
                            <td class="py-4"><?php echo number_format($order['tongTien'], 0, ',', '.'); ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">Không có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div id="ordersPagination" class="mt-4"></div>
    </div>
</div>

<!-- Thống kê khách hàng -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">Thống kê khách hàng theo khoảng thời gian</h2>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="startDateSelect" class="text-gray-600">Từ ngày:</label>
                    <input type="date" id="startDateSelect" class="border rounded px-3 py-1" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                </div>
                <div class="flex items-center gap-2">
                    <label for="endDateSelect" class="text-gray-600">Đến ngày:</label>
                    <input type="date" id="endDateSelect" class="border rounded px-3 py-1" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <button onclick="searchCustomers()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Tìm kiếm
                </button>
            </div>
        </div>
    </div>
    <div class="p-6">
        <table class="w-full" id="customersTable">
            <thead>
                <tr class="text-left">
                    <th class="pb-4 text-center w-[10%]">TOP</th>
                    <th class="pb-4 w-[30%]">Khách hàng</th>
                    <th class="pb-4 text-center w-[20%]">Số đơn hàng</th>
                    <th class="pb-4 text-center w-[25%]">Tổng chi tiêu</th>
                    <th class="pb-4 text-center w-[15%]">Thao tác</th>
                </tr>
            </thead>
            <tbody id="customersTableBody">
                <?php if (!empty($stats['topCustomers'])): ?>
                    <?php foreach ($stats['topCustomers'] as $index => $customer): ?>
                    <tr class="border-t">
                        <td class="py-4 text-center"><?php echo $index + 1; ?></td>
                        <td class="py-4"><?php echo htmlspecialchars($customer['tenTK']); ?></td>
                        <td class="py-4 text-center"><?php echo $customer['soLuongDon']; ?> đơn</td>
                        <td class="py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <span><?php echo number_format($customer['tongChiTieu'], 0, ',', '.'); ?>đ</span>
                            </div>
                        </td>
                        <td class="py-4 text-center">
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard/customer-orders/<?php echo $customer['maTK']; ?>?startDate=<?php echo date('Y-m-d', strtotime('-7 days')); ?>&endDate=<?php echo date('Y-m-d'); ?>" 
                               class="text-blue-500 hover:text-blue-700">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal chi tiết đơn hàng -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-4xl w-full max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Chi tiết đơn hàng</h3>
            <button onclick="document.getElementById('orderModal').style.display='none'" 
                    class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="orderModalContent">
            <!-- Nội dung sẽ được thêm bằng JavaScript -->
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?php echo BASE_URL; ?>';

function searchCustomers() {
    const startDate = document.getElementById('startDateSelect').value;
    const endDate = document.getElementById('endDateSelect').value;
    
    if (!startDate || !endDate) {
        alert('Vui lòng chọn cả ngày bắt đầu và kết thúc');
        return;
    }
    
    fetch(`${BASE_URL}/admin/dashboard/top-customers?startDate=${startDate}&endDate=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('customersTableBody');
            if (!data || data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">
                            Không có dữ liệu trong khoảng thời gian từ ${startDate} đến ${endDate}
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.forEach((customer, index) => {
                html += `
                    <tr>
                        <td class="py-4 text-center">${index + 1}</td>
                        <td class="py-4">${customer.tenTK}</td>
                        <td class="py-4 text-center">${customer.soLuongDon} đơn</td>
                        <td class="py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <span>${new Intl.NumberFormat('vi-VN').format(customer.tongChiTieu)}đ</span>
                            </div>
                        </td>
                        <td class="py-4 text-center">
                            <a href="${BASE_URL}/admin/dashboard/customer-orders/${customer.maTK}?startDate=${startDate}&endDate=${endDate}" 
                               class="text-blue-500 hover:text-blue-700">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('customersTableBody').innerHTML = `
                <tr>
                    <td colspan="5" class="py-4 text-center text-red-500">
                        Đã xảy ra lỗi khi tải dữ liệu
                    </td>
                </tr>
            `;
        });
}

// Thêm event listener cho thay đổi ngày
document.getElementById('startDateSelect').addEventListener('change', searchCustomers);
document.getElementById('endDateSelect').addEventListener('change', searchCustomers);

// Load dữ liệu khi trang được tải
document.addEventListener('DOMContentLoaded', searchCustomers);

function viewOrderDetail(orderId) {
    fetch(`${BASE_URL}/admin/orders/get-order-detail/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (!data) {
                throw new Error('Không tìm thấy thông tin đơn hàng');
            }

            const modal = document.getElementById('orderModal');
            const content = document.getElementById('orderModalContent');
            
            let statusText = '';
            switch(data.trangThai) {
                case 1: statusText = 'Đang giao'; break;
                case 2: statusText = 'Đã giao'; break;
                case 3: statusText = 'Đã hủy'; break;
                default: statusText = 'Chờ xử lý';
            }

            let html = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold">Thông tin đơn hàng</h4>
                            <p>Mã đơn: #${data.maHD}</p>
                            <p>Ngày tạo: ${new Date(data.ngayTao).toLocaleDateString('vi-VN')}</p>
                            <p>Tổng tiền: ${new Intl.NumberFormat('vi-VN').format(data.tongTien)}đ</p>
                            <p>Trạng thái: <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">${statusText}</span></p>
                        </div>
                        <div>
                            <h4 class="font-semibold">Thông tin khách hàng</h4>
                            <p>Tên: ${data.tenTK}</p>
                            <p>Email: ${data.email}</p>
                            <p>Số điện thoại: ${data.soDT}</p>
                            <p>Địa chỉ: ${data.diaChi}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-2">Chi tiết sản phẩm</h4>
                        <table class="w-full">
                            <thead>
                                <tr class="text-left">
                                    <th class="pb-2">Sản phẩm</th>
                                    <th class="pb-2">Số lượng</th>
                                    <th class="pb-2">Đơn giá</th>
                                    <th class="pb-2">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.chiTiet.map(item => `
                                    <tr class="border-t">
                                        <td class="py-2">${item.tenGiay}</td>
                                        <td class="py-2">${item.soLuong}</td>
                                        <td class="py-2">${new Intl.NumberFormat('vi-VN').format(item.giaBan)}đ</td>
                                        <td class="py-2">${new Intl.NumberFormat('vi-VN').format(item.giaBan * item.soLuong)}đ</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            content.innerHTML = html;
            modal.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin đơn hàng');
        });
}

function searchOrders(page = 1) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        alert('Vui lòng chọn cả ngày bắt đầu và kết thúc');
        return;
    }

    const tableBody = document.getElementById('ordersTableBody');
    const pagination = document.getElementById('ordersPagination');
    
    // Hiển thị loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="4" class="py-4 text-center">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                    <span class="ml-2">Đang tải...</span>
                </div>
            </td>
        </tr>
    `;

    fetch(`${BASE_URL}/admin/dashboard/search-orders?startDate=${startDate}&endDate=${endDate}&page=${page}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            if (!data.orders || data.orders.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            Không tìm thấy đơn hàng nào trong khoảng thời gian này
                        </td>
                    </tr>
                `;
                pagination.innerHTML = '';
                return;
            }

            // Hiển thị đơn hàng
            let html = '';
            data.orders.forEach(order => {
                html += `
                    <tr class="border-t">
                        <td class="py-4">#${order.maHD}</td>
                        <td class="py-4">${order.tenTK}</td>
                        <td class="py-4">${new Date(order.ngayTao).toLocaleString('vi-VN')}</td>
                        <td class="py-4">${new Intl.NumberFormat('vi-VN').format(order.tongTien)}đ</td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;

            // Hiển thị phân trang
            let paginationHtml = '<div class="flex justify-center gap-2 mt-4">';
            
            // Nút Previous
            if (data.currentPage > 1) {
                paginationHtml += `
                    <button onclick="searchOrders(${data.currentPage - 1})" 
                            class="px-3 py-1 border rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                `;
            }

            // Các nút số trang
            for (let i = 1; i <= data.totalPages; i++) {
                paginationHtml += `
                    <button onclick="searchOrders(${i})" 
                            class="px-3 py-1 border rounded ${i === data.currentPage ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'}">
                        ${i}
                    </button>
                `;
            }

            // Nút Next
            if (data.currentPage < data.totalPages) {
                paginationHtml += `
                    <button onclick="searchOrders(${data.currentPage + 1})" 
                            class="px-3 py-1 border rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                `;
            }

            paginationHtml += '</div>';
            pagination.innerHTML = paginationHtml;
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="py-4 text-center text-red-500">
                        ${error.message || 'Đã xảy ra lỗi khi tải dữ liệu. Vui lòng thử lại sau.'}
                    </td>
                </tr>
            `;
            pagination.innerHTML = '';
        });
}
</script> 