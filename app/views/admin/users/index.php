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
                                <button type="button"
                                        onclick="openEditModal(<?php echo json_encode($user); ?>)"
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


<script>
// RESET VALUES KHI ĐÓNG FORM THÊM SẢN PHẨM 
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('modalAddUser').addEventListener('hidden.bs.modal', function () {
        // Reset toàn bộ form
        var form = document.querySelector('#modalAddUser form');
        form.reset();
        form.classList.remove('was-validated');
        var preview = document.querySelector('.preview');
        if (preview.querySelector('img')) 
        {
            preview.querySelector('img').remove();
        }

        // Xóa trạng thái validation lỗi (nếu có)
        var invalidFields = document.querySelectorAll('.needs-validation .is-invalid');
        invalidFields.forEach(function (field) {
            field.classList.remove('is-invalid');
        });
    });
});


// VALIDATE FORMS
var forms = document.querySelectorAll('.needs-validation');
Array.prototype.slice.call(forms)
    .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
  
          form.classList.add('was-validated')
        }, false)
    });



// GÁN GIÁ TRỊ VÀO MODAL SỬA SẢN PHẨM    
document.addEventListener("DOMContentLoaded", function () {
    let editButtons = document.querySelectorAll(".edit-product-btn");
    
    editButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let maGiay = btn.getAttribute("data-maGiay");
            let tenGiay = btn.getAttribute("data-tenGiay");
            let maLoaiGiay = btn.getAttribute("data-maLoaiGiay");
            let size = btn.getAttribute("data-size");
            let giaBan = btn.getAttribute("data-giaBan");
            let tonKho = btn.getAttribute("data-tonKho");
            let trangThai = btn.getAttribute("data-trangThai");


            // Gán giá trị vào input trong modal
            document.getElementById("tenGiay" + maGiay).value = tenGiay;
            document.getElementById("maLoaiGiay" + maGiay).value = maLoaiGiay;
            document.getElementById("size" + maGiay).value = size;
            document.getElementById("giaBan" + maGiay).value = giaBan;
            document.getElementById("tonKho" + maGiay).value = tonKho;
            document.getElementById("trangThai" + maGiay).value = trangThai;
            

        }); 
    });
});

// Thêm xử lý submit form bằng AJAX
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
</script>