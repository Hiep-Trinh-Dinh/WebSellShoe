<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách loại giày</h2>
        <button type="button" 
                onclick="openAddModal()"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus"></i> Thêm loại giày
        </button>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã loại</th>
                        <th class="pb-4">Tên loại giày</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                        <tr class="border-t">
                            <td class="py-4"><?php echo htmlspecialchars($category['maLoaiGiay'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($category['tenLoaiGiay'] ?? ''); ?></td>
                            <td class="py-4">
                                <button type="button"
                                        onclick="openEditModal(<?php echo $category['maLoaiGiay']; ?>, '<?php echo htmlspecialchars($category['tenLoaiGiay']); ?>')"
                                        class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                        onclick="deleteCategory(<?php echo $category['maLoaiGiay']; ?>)"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Không có loại giày nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Thêm loại giày mới</h3>
            <form id="categoryForm" class="mt-4">
                <input type="hidden" id="categoryId" name="maLoaiGiay" value="">
                <div class="mt-2">
                    <label for="tenLoaiGiay" class="block text-sm font-medium text-gray-700">Tên loại giày</label>
                    <input type="text" 
                           id="tenLoaiGiay" 
                           name="tenLoaiGiay" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
    document.getElementById('modalTitle').textContent = 'Thêm loại giày mới';
    document.getElementById('categoryId').value = '';
    document.getElementById('tenLoaiGiay').value = '';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function openEditModal(id, name) {
    document.getElementById('modalTitle').textContent = 'Sửa loại giày';
    document.getElementById('categoryId').value = id;
    document.getElementById('tenLoaiGiay').value = name;
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function deleteCategory(id) {
    if (confirm('Bạn có chắc chắn muốn xóa loại giày này?')) {
        window.location.href = `${BASE_URL}/admin/categories/delete/${id}`;
    }
}

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('categoryId').value;
    const url = id ? `${BASE_URL}/admin/categories/edit/${id}` : `${BASE_URL}/admin/categories/add`;
    
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