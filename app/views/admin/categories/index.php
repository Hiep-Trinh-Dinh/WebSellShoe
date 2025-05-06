<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách loại giày</h2>
        <!--- Begin Modal Add Category -->
        <button 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-bs-toggle="modal" 
            data-bs-target="#modalAddCategory" 
            data-bs-whatever="@mdo"
        >
            <i class="fas fa-plus"></i> 
            Thêm loại giày
        </button>
        <div class="modal fade" id="modalAddCategory" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm loại giày</h5>
                    </div>
                    <div class="modal-body">
                        <form action="categories/add" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="tenLoaiGiay" class="col-form-label">Tên giày:</label>
                                <input type="text" class="form-control" id="tenLoaiGiay" name="tenLoaiGiay" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mt-3" style="float: right;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Send message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Modal Add Category -->
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="table-categories w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã loại</th>
                        <th class="pb-4">Tên loại giày</th>
                        <th class="pb-4">Trạng thái</th>
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
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $category['trangThai'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $category['trangThai'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <!--- Begin Modal Edit Category -->
                                <?php if($category['trangThai'] != 0): ?>
                                    <button 
                                        class="edit-category-btn text-blue-500 hover:text-blue-700 mr-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditCategory<?php echo $category['maLoaiGiay'] ?>" 
                                        data-maLoaiGiay="<?php echo $category['maLoaiGiay']; ?>"
                                        data-tenLoaiGiay="<?php echo $category['tenLoaiGiay']; ?>"
                                        data-trangThai="<?php echo $category['trangThai']; ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="modal fade" id="modalEditCategory<?php echo $category['maLoaiGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit loại giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="categories/edit" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maLoaiGiay" name="maLoaiGiay"  value="<?php echo $category['maLoaiGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <label for="tenLoaiGiay" class="col-form-label">Tên giày:</label>
                                                            <input type="text" class="form-control" id="tenLoaiGiay<?php echo $category['maLoaiGiay'] ?>" name="tenLoaiGiay" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <select class="form-select" id="trangThai<?php echo $category['maLoaiGiay'] ?>" name="trangThai" aria-label="Default select example">
                                                                <option value="1">Hoạt động</option>
                                                                <option value="0">Khóa</option>
                                                            </select>
    
                                                        </div>
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Send message</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endif; ?>
                                <!--- End Modal Edit Category -->

                                <!-- Begin Modal Unlock Category -->
                                <?php if($category['trangThai'] != 1): ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalUnlockCategory<?php echo $category['maLoaiGiay'] ?>" 
                                        class="text-yellow-500 hover:text-yellow-700 mr-2"
                                    >
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <div class="modal fade" id="modalUnlockCategory<?php echo $category['maLoaiGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Unlock loại giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="categories/unlock" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maLoaiGiay" name="maLoaiGiay"  value="<?php echo $category['maLoaiGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn mở khóa loại giày <?php echo $category['tenLoaiGiay'] ?> ?</h3>
                                                        </div>
                                                        
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Send message</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- End Modal Unlock Category -->

                                <!-- Begin Modal Delete Category -->
                                <?php if($category['trangThai'] != 0): ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDeleteCategory<?php echo $category['maLoaiGiay'] ?>" 
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="modal fade" id="modalDeleteCategory<?php echo $category['maLoaiGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete loại giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="categories/delete" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maLoaiGiay" name="maLoaiGiay"  value="<?php echo $category['maLoaiGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn khóa loại giày <?php echo $category['tenLoaiGiay'] ?> ?</h3>
                                                        </div>
                                                        
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Send message</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endif; ?>
                                <!-- End Modal Delete Category -->
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

        <!-- Phân trang -->
        <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="flex justify-center mt-6">
            <nav class="flex items-center space-x-2">
                <?php if ($pagination['currentPage'] > 1): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/categories?page=<?php echo $pagination['currentPage'] - 1; ?>" 
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
                    <a href="<?php echo BASE_URL; ?>/admin/categories?page=<?php echo $i; ?>" 
                       class="px-3 py-1 rounded border <?php echo $i == $pagination['currentPage'] ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/categories?page=<?php echo $pagination['currentPage'] + 1; ?>" 
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
// GÁN GIÁ TRỊ VÀO MODAL SỬA SẢN PHẨM    
document.addEventListener("DOMContentLoaded", function () {
    let editButtons = document.querySelectorAll(".edit-category-btn");
    
    editButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let maLoaiGiay = btn.getAttribute("data-maLoaiGiay");
            let tenLoaiGiay = btn.getAttribute("data-tenLoaiGiay");
            let trangThai = btn.getAttribute("data-trangThai");

            // Gán giá trị vào input trong modal
            document.getElementById("tenLoaiGiay" + maLoaiGiay).value = tenLoaiGiay;
            document.getElementById("trangThai" + maLoaiGiay).value = trangThai;
        });
    });
});


function deleteCategory(id) {
    if (confirm('Bạn có chắc chắn muốn khóa loại giày này?')) {
        window.location.href = `${BASE_URL}/admin/categories/delete/${id}`;
    }
}

</script> 