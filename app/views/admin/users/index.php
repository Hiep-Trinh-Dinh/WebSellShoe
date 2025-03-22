<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách người dùng</h2>
        <button type="button" 
                onclick="openAddModal()"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus"></i> Thêm người dùng
        </button>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã TK</th>
                        <th class="pb-4">Tên đăng nhập</th>
                        <th class="pb-4">Quyền</th>
                        <th class="pb-4">Trạng thái</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-t">
                            <td class="py-4"><?php echo $user['maTK']; ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($user['tenTK']); ?></td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $user['maQuyen'] == 1 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                    <?php echo htmlspecialchars($user['tenQuyen']); ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $user['trangThai'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $user['trangThai'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <button type="button"
                                        onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)"
                                        class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                        onclick="openPasswordModal(<?php echo $user['maTK']; ?>)"
                                        class="text-yellow-500 hover:text-yellow-700 mr-2">
                                    <i class="fas fa-key"></i>
                                </button>
                                <?php if ($user['maQuyen'] != 1): ?>
                                <button type="button"
                                        onclick="deleteUser(<?php echo $user['maTK']; ?>)"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Không có người dùng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Thêm người dùng mới</h3>
            <form id="userForm" class="mt-4">
                <input type="hidden" id="maTK" name="maTK" value="">
                <div class="mt-2">
                    <label for="tenTK" class="block text-sm font-medium text-gray-700">Tên đăng nhập</label>
                    <input type="text" 
                           id="tenTK" 
                           name="tenTK" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-2" id="passwordField">
                    <label for="matKhau" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input type="password" 
                           id="matKhau" 
                           name="matKhau" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-2">
                    <label for="maQuyen" class="block text-sm font-medium text-gray-700">Quyền</label>
                    <select id="maQuyen" 
                            name="maQuyen" 
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['maQuyen']; ?>">
                                <?php echo htmlspecialchars($role['tenQuyen']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mt-2">
                    <label for="trangThai" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select id="trangThai" 
                            name="trangThai" 
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">Hoạt động</option>
                        <option value="0">Khóa</option>
                    </select>
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

<!-- Modal Đổi mật khẩu -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Đổi mật khẩu</h3>
            <form id="passwordForm" class="mt-4">
                <input type="hidden" id="userIdPassword" name="maTK" value="">
                <div class="mt-2">
                    <label for="newPassword" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                    <input type="password" 
                           id="newPassword" 
                           name="matKhau" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" 
                            onclick="closePasswordModal()"
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
    document.getElementById('modalTitle').textContent = 'Thêm người dùng mới';
    document.getElementById('maTK').value = '';
    document.getElementById('tenTK').value = '';
    document.getElementById('matKhau').value = '';
    document.getElementById('maQuyen').value = '2';
    document.getElementById('trangThai').value = '1';
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('matKhau').required = true;
    document.getElementById('userModal').classList.remove('hidden');
}

function openEditModal(user) {
    document.getElementById('modalTitle').textContent = 'Sửa người dùng';
    document.getElementById('maTK').value = user.maTK;
    document.getElementById('tenTK').value = user.tenTK;
    document.getElementById('maQuyen').value = user.maQuyen;
    document.getElementById('trangThai').value = user.trangThai;
    document.getElementById('passwordField').style.display = 'none';
    document.getElementById('matKhau').required = false;
    document.getElementById('userModal').classList.remove('hidden');
}

function openPasswordModal(userId) {
    document.getElementById('userIdPassword').value = userId;
    document.getElementById('newPassword').value = '';
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}

function deleteUser(id) {
    if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
        window.location.href = `${BASE_URL}/admin/users/delete/${id}`;
    }
}

document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('maTK').value;
    const url = id ? `${BASE_URL}/admin/users/edit` : `${BASE_URL}/admin/users/add`;
    
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

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(`${BASE_URL}/admin/users/changePassword`, {
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