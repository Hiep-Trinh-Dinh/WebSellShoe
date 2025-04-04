<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách nhà cung cấp</h2>

        <!--- Begin Modal Add Supplier -->
        <button 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-bs-toggle="modal" 
            data-bs-target="#modalAddSupplier" 
            data-bs-whatever="@mdo"
        >
            <i class="fas fa-plus"></i> 
            Thêm nhà cung cấp
        </button>
        <div class="modal fade" id="modalAddSupplier" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm nhà cung cấp</h5>
                    </div>
                    <div class="modal-body">
                        <form action="suppliers/add" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="tenNCC" class="col-form-label">Tên nhà cung cấp:</label>
                                <input type="text" class="form-control" id="tenNCC" name="tenNCC" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="diaChi" class="col-form-label">Địa chỉ:</label>
                                <input type="text" class="form-control" id="diaChi" name="diaChi" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mt-3" style="float: right;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                                <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Modal Add Supplier -->
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
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $supplier['trangThai'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $supplier['trangThai'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <!--- Begin Modal Edit Supplier -->
                                <?php if($supplier['trangThai'] != 0): ?>
                                    <button 
                                        class="edit-supplier-btn text-blue-500 hover:text-blue-700 mr-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditSupplier<?php echo $supplier['maNCC'] ?>" 
                                        data-maNCC="<?php echo $supplier['maNCC']; ?>"
                                        data-tenNCC="<?php echo $supplier['tenNCC']; ?>"
                                        data-email="<?php echo $supplier['email']; ?>"
                                        data-diaChi="<?php echo $supplier['diaChi']; ?>"
                                        data-trangThai="<?php echo $supplier['trangThai']; ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="modal fade" id="modalEditSupplier<?php echo $supplier['maNCC'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit nhà cung cấp</h5>
                                                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>    -->
                                                </div>
                                                <div class="modal-body">
                                                    <form action="suppliers/edit" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maNCC" name="maNCC"  value="<?php echo $supplier['maNCC'] ?>" >

                                                        <div class="mb-3">
                                                            <label for="tenNCC" class="col-form-label">Tên nhà cung cấp:</label>
                                                            <input type="text" class="form-control" id="tenNCC<?php echo $supplier['maNCC'] ?>" name="tenNCC" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="col-form-label">Email:</label>
                                                            <input type="text" class="form-control" id="email<?php echo $supplier['maNCC'] ?>" name="email" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="diaChi" class="col-form-label">Địa chỉ:</label>
                                                            <input type="text" class="form-control" id="diaChi<?php echo $supplier['maNCC'] ?>" name="diaChi" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="trangThai" class="col-form-label">Trạng thái:</label>
                                                            <select class="form-select" id="trangThai<?php echo $supplier['maNCC'] ?>" name="trangThai" aria-label="Default select example" required>
                                                                <option value="1">Hoạt động</option>
                                                                <option value="0">Khóa</option>
                                                            </select>
    
                                                        </div>
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" id="edit-close-btn<?php echo $supplier["maNCC"] ?>" data-bs-dismiss="modal">Huỷ</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Lưu</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!--- End Modal Edit Supplier -->

                                <!-- Begin Modal Unlock Supplier -->
                                <?php if($supplier['trangThai'] != 1): ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalUnlockSuppliers<?php echo $supplier['maNCC'] ?>" 
                                        class="text-yellow-500 hover:text-yellow-700 mr-2"
                                    >
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <div class="modal fade" id="modalUnlockSuppliers<?php echo $supplier['maNCC'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Unlock nhà cung cấp</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="suppliers/unlock" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maNCC" name="maNCC"  value="<?php echo $supplier['maNCC'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn mở khóa nhà cung cấp <?php echo $supplier['tenNCC'] ?> ?</h3>
                                                        </div>
                                                        
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Có</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Begin Modal Unlock Supplier -->

                                <!-- Begin Modal Delete Supplier -->
                                <?php if($supplier['trangThai'] != 0): ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDeleteSupplier<?php echo $supplier['maNCC'] ?>" 
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="modal fade" id="modalDeleteSupplier<?php echo $supplier['maNCC'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete nhà cung cấp</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="suppliers/delete" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maNCC" name="maNCC"  value="<?php echo $supplier['maNCC'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn xoá nhà cung cấp <?php echo $supplier['tenNCC'] ?> ?</h3>
                                                        </div>
                                                        
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Có</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Begin Modal Delete Supplier -->
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


<script>
// RESET VALUES KHI ĐÓNG FORM THÊM SẢN PHẨM 
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('modalAddSupplier').addEventListener('hidden.bs.modal', function () {
        // Reset toàn bộ form
        var form = document.querySelector('#modalAddSupplier form');
        form.reset();
        form.classList.remove('was-validated');

        // Xóa trạng thái validation lỗi (nếu có)
        var invalidFields = document.querySelectorAll('.needs-validation .is-invalid');
        invalidFields.forEach(function (field) {
            field.classList.remove('is-invalid');
        });
    });
});


// VALIDATE FORMS
var forms = document.querySelectorAll('.needs-validation');
Array.prototype.slice.call(forms) // Chuyển đổi NodeList thành mảng:
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
    let editButtons = document.querySelectorAll(".edit-supplier-btn");
    
    editButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let maNCC = btn.getAttribute("data-maNCC");
            let tenNCC = btn.getAttribute("data-tenNCC");
            let email = btn.getAttribute("data-email");
            let diaChi = btn.getAttribute("data-diaChi");
            let trangThai = btn.getAttribute("data-trangThai");

            // Gán giá trị vào input trong modal
            document.getElementById("tenNCC" + maNCC).value = tenNCC;
            document.getElementById("email" + maNCC).value = email;
            document.getElementById("diaChi" + maNCC).value = diaChi;
            document.getElementById("trangThai" + maNCC).value = trangThai;
        });
    });
});
</script>

