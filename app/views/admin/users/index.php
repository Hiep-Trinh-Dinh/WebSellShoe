<script>
// Tất cả JavaScript được gói trong một hàm DOMContentLoaded duy nhất
document.addEventListener('DOMContentLoaded', function() {
    // Khóa tài khoản
    window.lockUser = function(id) {
        if (confirm('Bạn có chắc chắn muốn khóa tài khoản này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo BASE_URL; ?>/admin/users/delete';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Mở khóa tài khoản
    window.unlockUser = function(id) {
        if (confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo BASE_URL; ?>/admin/users/unlock';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Mở modal sửa thông tin
    window.openEditModal = function(user) {
        console.log('Opening edit modal with user:', user);
        
        try {
            // Đảm bảo user là một object
            if (typeof user === 'string') {
                user = JSON.parse(user);
            }
            
            // Kiểm tra dữ liệu
            if (!user || !user.maTK) {
                console.error('Invalid user data:', user);
                alert('Lỗi: Dữ liệu người dùng không hợp lệ');
                return;
            }
            
            // Cập nhật form
            document.getElementById('edit_maTK').value = user.maTK;
            document.getElementById('edit_tenTK').value = user.tenTK || '';
            document.getElementById('edit_maQuyen').value = user.maQuyen || 2;
            document.getElementById('edit_trangThai').value = user.trangThai || 1;
            
            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
            modal.show();
        } catch (e) {
            console.error('Error in openEditModal:', e);
            alert('Lỗi khi mở form sửa thông tin: ' + e.message);
        }
    }

    // RESET VALUES KHI ĐÓNG FORM THÊM SẢN PHẨM
    if (document.getElementById('modalAddUser')) {
        document.getElementById('modalAddUser').addEventListener('hidden.bs.modal', function () {
            // Reset toàn bộ form
            var form = document.querySelector('#modalAddUser form');
            form.reset();
            form.classList.remove('was-validated');
            var preview = document.querySelector('.preview');
            if (preview && preview.querySelector('img')) {
                preview.querySelector('img').remove();
            }

            // Xóa trạng thái validation lỗi (nếu có)
            var invalidFields = document.querySelectorAll('.needs-validation .is-invalid');
            invalidFields.forEach(function (field) {
                field.classList.remove('is-invalid');
            });
        });
    }

    // VALIDATE FORMS
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

    // Thêm xử lý submit form bằng AJAX
    if (document.getElementById('addUserForm')) {
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            fetch('<?php echo BASE_URL; ?>/admin/users/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAddUser'));
                    modal.hide();
                    
                    // Reset form
                    this.reset();
                    this.classList.remove('was-validated');
                    
                    // Reload trang để cập nhật danh sách
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm người dùng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm người dùng');
            });
        });
    }

    // Xử lý submit form sửa thông tin
    if (document.getElementById('editUserForm')) {
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            fetch('<?php echo BASE_URL; ?>/admin/users/edit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditUser'));
                    modal.hide();
                    
                    // Reload trang để cập nhật danh sách
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi cập nhật thông tin');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật thông tin');
            });
        });
    }

    // Xử lý submit form đổi mật khẩu
    if (document.getElementById('passwordForm')) {
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            fetch('<?php echo BASE_URL; ?>/admin/users/changePassword', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalChangePassword'));
                    modal.hide();
                    
                    // Reset form
                    this.reset();
                    
                    alert('Đổi mật khẩu thành công');
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi đổi mật khẩu');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đổi mật khẩu');
            });
        });
    }
});
</script>

<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách người dùng</h2>
        <!--- Begin Modal Add User -->
        <button 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-bs-toggle="modal" 
            data-bs-target="#modalAddUser" 
            data-bs-whatever="@mdo"
        >
            <i class="fas fa-plus"></i> 
            Thêm người dùng
        </button>
        <div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm User</h5>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="tenTK" class="col-form-label">Tên User:</label>
                                <input type="text" class="form-control" id="tenTK" name="tenTK" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="matKhau" class="col-form-label">Mật khẩu:</label>
                                <input type="text" class="form-control" id="matKhau" name="matKhau" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="maQuyen" class="col-form-label">Quyền:</label>
                                <select class="form-select" id="maQuyen" name="maQuyen" aria-label="Default select example" required>
                                    <option value=""></option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            
                            <div class="mt-3" style="float: right;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Modal Add User -->
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
                            <td class="py-4"><?php echo $user['tenTK']; ?></td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $user['maQuyen'] == 1 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                    <?php echo $user['tenQuyen']; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $user['trangThai'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $user['trangThai'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <?php if ($user['maQuyen'] != 1): ?>
                                    <?php if ($user['trangThai'] == 1): ?>
                                        <!-- Hiển thị các nút chức năng khi tài khoản đang hoạt động -->
                                        <button type="button"
                                                onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8'); ?>)"
                                                class="text-blue-500 hover:text-blue-700 mr-2"
                                                title="Sửa thông tin">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                                onclick="lockUser(<?php echo $user['maTK']; ?>)"
                                                class="text-red-500 hover:text-red-700 mr-2"
                                                title="Khóa tài khoản">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Chỉ hiển thị nút mở khóa khi tài khoản bị khóa -->
                                        <button type="button"
                                                onclick="unlockUser(<?php echo $user['maTK']; ?>)"
                                                class="text-green-500 hover:text-green-700"
                                                title="Mở khóa tài khoản">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Tài khoản admin - chỉ hiển thị nút sửa -->
                                    <button type="button"
                                            onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8'); ?>)"
                                            class="text-blue-500 hover:text-blue-700 mr-2"
                                            title="Sửa thông tin">
                                        <i class="fas fa-edit"></i>
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

<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa thông tin người dùng</h5>
            </div>
            <div class="modal-body">
                <form id="editUserForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit_maTK" name="maTK">
                    <div class="mb-3">
                        <label for="edit_tenTK" class="col-form-label">Tên đăng nhập:</label>
                        <input type="text" class="form-control" id="edit_tenTK" name="tenTK" required>
                        <div class="invalid-feedback">
                            Vui lòng không để trống trường này
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_maQuyen" class="col-form-label">Quyền:</label>
                        <select class="form-select" id="edit_maQuyen" name="maQuyen" required>
                            <option value=""></option>
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>
                        <div class="invalid-feedback">
                            Vui lòng không để trống trường này
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_trangThai" class="col-form-label">Trạng thái:</label>
                        <select class="form-select" id="edit_trangThai" name="trangThai" required>
                            <option value="1">Hoạt động</option>
                            <option value="0">Khóa</option>
                        </select>
                    </div>
                    <div class="mt-3" style="float: right;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Change Password -->
<div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
            </div>
            <div class="modal-body">
                <form id="passwordForm" class="needs-validation" novalidate>
                    <input type="hidden" id="password_maTK" name="maTK">
                    <div class="mb-3">
                        <label for="password" class="col-form-label">Mật khẩu mới:</label>
                        <input type="password" class="form-control" id="password" name="matKhau" required>
                        <div class="invalid-feedback">
                            Vui lòng không để trống trường này
                        </div>
                    </div>
                    <div class="mt-3" style="float: right;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>