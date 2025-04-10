<?php
?>

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Danh sách nhà cung cấp</h1>
    <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Thêm nhà cung cấp
    </button>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã NCC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên NCC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $supplier['maNCC']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $supplier['tenNCC']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $supplier['email']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $supplier['diaChi']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <button onclick="openEditModal(<?php echo intval($supplier['maNCC']); ?>)" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?= BASE_URL ?>/admin/suppliers/delete" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $supplier['maNCC']; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add/Edit Supplier -->
<div id="supplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle">Thêm nhà cung cấp</h3>
            <form id="supplierForm" onsubmit="handleSubmit(event)">
                <input type="hidden" id="maNCC" name="maNCC">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tenNCC">
                        Tên nhà cung cấp
                    </label>
                    <input type="text" id="tenNCC" name="tenNCC" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input type="email" id="email" name="email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="diaChi">
                        Địa chỉ
                    </label>
                    <input type="text" id="diaChi" name="diaChi" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                        Hủy
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?php echo BASE_URL; ?>';
// Lưu trữ tất cả nhà cung cấp từ PHP
const allSuppliers = <?php echo json_encode($suppliers); ?>;

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Thêm nhà cung cấp';
    document.getElementById('supplierForm').reset();
    document.getElementById('maNCC').value = '';
    document.getElementById('supplierModal').classList.remove('hidden');
}

function openEditModal(id) {
    console.log('Opening edit modal with ID:', id);
    
    if (!id) {
        alert('ID không hợp lệ');
        return;
    }

    // Reset form trước khi mở modal
    document.getElementById('supplierForm').reset();
    
    // Tìm nhà cung cấp trong dữ liệu đã có
    const supplier = allSuppliers.find(s => parseInt(s.maNCC) === parseInt(id));
    
    if (!supplier) {
        alert('Không tìm thấy thông tin nhà cung cấp');
        return;
    }
    
    console.log('Found supplier data:', supplier);
    
    document.getElementById('modalTitle').textContent = 'Sửa nhà cung cấp';
    document.getElementById('maNCC').value = supplier.maNCC;
    document.getElementById('tenNCC').value = supplier.tenNCC || '';
    document.getElementById('email').value = supplier.email || '';
    document.getElementById('diaChi').value = supplier.diaChi || '';
    document.getElementById('supplierModal').classList.remove('hidden');
}

function handleSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const id = document.getElementById('maNCC').value;
    const isEdit = id !== '';
    
    // Log dữ liệu form
    console.log('Form data being submitted:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    console.log('ID from form:', id);
    console.log('isEdit:', isEdit);
    console.log('Request URL:', `${BASE_URL}/admin/suppliers/${isEdit ? 'update/' + id : 'add'}`);
    
    // Nếu đang sửa, đảm bảo ID được gửi đúng
    if (isEdit) {
        // Thêm ID vào URL và formData
        formData.set('id', id);
    }
    
    fetch(`${BASE_URL}/admin/suppliers/${isEdit ? 'update/' + id : 'add'}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Có lỗi xảy ra khi cập nhật thông tin');
    });
}

function closeModal() {
    document.getElementById('supplierModal').classList.add('hidden');
    document.getElementById('supplierForm').reset();
}
</script>