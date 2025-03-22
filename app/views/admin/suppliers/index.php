<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách nhà cung cấp</h2>
        <button type="button" 
                onclick="openAddModal()"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus"></i> Thêm nhà cung cấp
        </button>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã NCC</th>
                        <th class="pb-4">Tên nhà cung cấp</th>
                        <th class="pb-4">Email</th>
                        <th class="pb-4">Địa chỉ</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $supplier): ?>
                        <tr class="border-t">
                            <td class="py-4"><?php echo htmlspecialchars($supplier['maNCC'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($supplier['tenNCC'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($supplier['email'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($supplier['diaChi'] ?? ''); ?></td>
                            <td class="py-4">
                                <button type="button"
                                        onclick="openEditModal(<?php echo htmlspecialchars(json_encode($supplier)); ?>)"
                                        class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                        onclick="deleteSupplier(<?php echo $supplier['maNCC']; ?>)"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Không có nhà cung cấp nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa -->
<div id="supplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Thêm nhà cung cấp mới</h3>
            <form id="supplierForm" class="mt-4">
                <input type="hidden" id="maNCC" name="maNCC" value="">
                <div class="mt-2">
                    <label for="tenNCC" class="block text-sm font-medium text-gray-700">Tên nhà cung cấp</label>
                    <input type="text" 
                           id="tenNCC" 
                           name="tenNCC" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-2">
                    <label for="diaChi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                    <textarea id="diaChi" 
                              name="diaChi" 
                              required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" 
                            onclick="closeModal()"
                            class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Thêm nhà cung cấp mới';
    document.getElementById('maNCC').value = '';
    document.getElementById('tenNCC').value = '';
    document.getElementById('email').value = '';
    document.getElementById('diaChi').value = '';
    document.getElementById('supplierModal').classList.remove('hidden');
}

function openEditModal(supplier) {
    document.getElementById('modalTitle').textContent = 'Sửa nhà cung cấp';
    document.getElementById('maNCC').value = supplier.maNCC;
    document.getElementById('tenNCC').value = supplier.tenNCC;
    document.getElementById('email').value = supplier.email;
    document.getElementById('diaChi').value = supplier.diaChi;
    document.getElementById('supplierModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('supplierModal').classList.add('hidden');
}

function deleteSupplier(id) {
    if (confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')) {
        window.location.href = `${BASE_URL}/admin/suppliers/delete/${id}`;
    }
}

document.getElementById('supplierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('maNCC').value;
    const url = id ? `${BASE_URL}/admin/suppliers/edit/${id}` : `${BASE_URL}/admin/suppliers/add`;
    
    fetch(url, {
        method: 'POST',
        body: new FormData(this)
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
});
</script> 