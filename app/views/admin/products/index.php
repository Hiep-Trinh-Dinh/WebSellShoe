<div class="bg-white shadow rounded-lg">
    <div class="template-collection p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách giày</h2>
        <!--- Begin Modal Add Product -->
        <button 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-bs-toggle="modal" 
            data-bs-target="#modalAddProduct" 
            data-bs-whatever="@mdo"
        >
            <i class="fas fa-plus"></i> 
            Thêm loại giày
        </button>
        <div class="modal fade" id="modalAddProduct" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm giày</h5>
                    </div>
                    <div class="modal-body">
                        <form action="products/add" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Hình ảnh:</label>
                                <label class="preview" for="hinhAnh">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Upload Image Preview</span>
                                </label>
                                <input type="file" hidden id="hinhAnh" name="hinhAnh" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tenGiay" class="col-form-label">Tên giày:</label>
                                <input type="text" class="form-control" id="tenGiay" name="tenGiay" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="maLoaiGiay" class="col-form-label">Loại giày:</label>
                                <select class="form-select" id="maLoaiGiay" name="maLoaiGiay" aria-label="Default select example" required>
                                    <option value=""></option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if ($category['trangThai'] != 0): ?>
                                                <option value="<?php echo $category['maLoaiGiay'] ?>"><?php echo $category['tenLoaiGiay'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="size" class="col-form-label">Size:</label>
                                <select class="form-select" id="size" name="size" aria-label="Default select example" required>
                                    <option value=""></option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                </select>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="giaBan" class="col-form-label">Giá bán:</label>
                                <input type="text" class="form-control" id="giaBan" name="giaBan" required>
                                <div class="invalid-feedback">
                                    Vui lòng không để trống trường này
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tonKho" class="col-form-label">Tồn kho:</label>
                                <input type="text" class="form-control" id="tonKho" name="tonKho" required>
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
        <!--- End Modal Add Product -->
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã giày</th>
                        <th class="pb-4">Tên giày</th>
                        <th class="pb-4">Loại giày</th>
                        <th class="pb-4">Size</th>
                        <th class="pb-4">Giá bán</th>
                        <th class="pb-4">Tồn kho</th>
                        <th class="pb-4">Trạng thái</th>
                        <th class="pb-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                        <tr class="border-t">
                            <td class="py-4"><?php echo $product['maGiay'] ?? ''; ?></td>
                            <td class="py-4"><?php echo $product['tenGiay'] ?? ''; ?></td>
                            <td class="py-4"><?php echo $product['tenLoaiGiay'] ?? ''; ?></td>
                            <td class="py-4"><?php echo $product['size'] ?? ''; ?></td>
                            <td class="py-4"><?php echo number_format($product['giaBan'] ?? 0, 0, ',', '.'); ?>đ</td>
                            <td class="py-4"><?php echo $product['tonKho'] ?? 0; ?></td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php echo $product['trangThai'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $product['trangThai'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <!--- Begin Modal Edit Product -->
                                <?php if($product['trangThai'] != 0): ?>
                                    <button 
                                        class="edit-product-btn text-blue-500 hover:text-blue-700 mr-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditProduct<?php echo $product['maGiay'] ?>" 
                                        data-maGiay="<?php echo $product['maGiay']; ?>"
                                        data-tenGiay="<?php echo $product['tenGiay']; ?>"
                                        data-maLoaiGiay="<?php echo $product['maLoaiGiay']; ?>"
                                        data-size="<?php echo $product['size']; ?>"
                                        data-giaBan="<?php echo $product['giaBan']; ?>"
                                        data-tonKho="<?php echo $product['tonKho']; ?>"
                                        data-hinhAnh="<?php echo base64_decode($product['hinhAnh']); ?>"
                                        data-trangThai="<?php echo $product['trangThai']; ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="modal fade" id="modalEditProduct<?php echo $product['maGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="products/edit" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maGiay" name="maGiay"  value="<?php echo $product['maGiay'] ?>" >
                                                        <div class="mb-3" style="display: flex; gap: 20px;">
                                                            <div>
                                                                <label for="" class="col-form-label">Hình ảnh:</label>
                                                                <label class="preview" id="preview<?php echo $product['maGiay'] ?>" for="hinhAnh<?php echo $product['maGiay'] ?>">
                                                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                                                    <span>Upload Image Preview</span>
                                                                    <input type="hidden" name="hinhAnhCu" value="<?php echo base64_decode($product['hinhAnh']) ?>">
                                                                    <img src="<?php echo BASE_URL ?>/public/img/<?php echo base64_decode($product['hinhAnh']) ?>">
                                                                </label>
                                                                <input type="file" hidden id="hinhAnh<?php echo $product['maGiay'] ?>" name="hinhAnhMoi">
                                                                <div class="invalid-feedback">
                                                                    Vui lòng không để trống trường này
                                                                </div>
                                                            </div>
                                                            <div style="margin-top: 40px;">
                                                                <button type="button" class="controll-btn" id="removeImg<?php echo $product['maGiay'] ?>">Bỏ hình</button>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tenGiay" class="col-form-label">Tên giày:</label>
                                                            <input type="text" class="form-control" id="tenGiay<?php echo $product['maGiay'] ?>" name="tenGiay" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="maLoaiGiay" class="col-form-label">Loại giày:</label>
                                                            <select class="form-select" id="maLoaiGiay<?php echo $product['maGiay'] ?>" name="maLoaiGiay" aria-label="Default select example" required>
                                                                <option value=""></option>
                                                                <?php if (!empty($categories)): ?>
                                                                    <?php foreach ($categories as $category): ?>
                                                                        <?php if ($category['trangThai'] != 0): ?>
                                                                            <option value="<?php echo $category['maLoaiGiay'] ?>"><?php echo $category['tenLoaiGiay'] ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="size" class="col-form-label">Size:</label>
                                                            <select class="form-select" id="size<?php echo $product['maGiay'] ?>" name="size" aria-label="Default select example" required>
                                                                <option value=""></option>
                                                                <option value="38">38</option>
                                                                <option value="39">39</option>
                                                                <option value="40">40</option>
                                                                <option value="41">41</option>
                                                                <option value="42">42</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="giaBan" class="col-form-label">Giá bán:</label>
                                                            <input type="text" class="form-control" id="giaBan<?php echo $product['maGiay'] ?>" name="giaBan" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tonKho" class="col-form-label">Tồn kho:</label>
                                                            <input type="text" class="form-control" id="tonKho<?php echo $product['maGiay'] ?>" name="tonKho" required>
                                                            <div class="invalid-feedback">
                                                                Vui lòng không để trống trường này
                                                            </div>
                                                        </div>
                                                        <!-- <div class="mb-3">
                                                            <label for="trangThai" class="col-form-label">Trạng thái:</label>
                                                            <select class="form-select" id="trangThai<?php echo $product['maGiay'] ?>" name="trangThai" aria-label="Default select example" required>
                                                                <option value="1">Hoạt động</option>
                                                                <option value="0">Khóa</option>
                                                            </select>
    
                                                        </div> -->
                                                        <div class="mt-3" style="float: right;">
                                                            <button type="button" class="btn btn-secondary" id="edit-close-btn<?php echo $product["maGiay"] ?>" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Send message</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!--- End Modal Edit Product -->

                                <!-- Begin Modal Lock/Unlock Product -->
                                <?php if($product['trangThai'] != 1): ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalUnlockProduct<?php echo $product['maGiay'] ?>" 
                                        class="text-yellow-500 hover:text-yellow-700 mr-2"
                                    >
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <div class="modal fade" id="modalUnlockProduct<?php echo $product['maGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Unlock giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="products/unlock" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maGiay" name="maGiay"  value="<?php echo $product['maGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn mở khóa  giày <?php echo $product['tenGiay'] ?> ?</h3>
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
                                <?php else: ?>
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalLockProduct<?php echo $product['maGiay'] ?>" 
                                        class="text-yellow-500 hover:text-yellow-700 mr-2"
                                    >
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <div class="modal fade" id="modalLockProduct<?php echo $product['maGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Lock giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="products/lock" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maGiay" name="maGiay"  value="<?php echo $product['maGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn khóa  giày <?php echo $product['tenGiay'] ?> ?</h3>
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
                                <!-- Begin Modal Lock/Unlock Product -->

                                <!-- Begin Modal Delete Product -->
                                <!-- <php if($product['trangThai'] != 0): > -->
                                    <button 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDeleteProduct<?php echo $product['maGiay'] ?>" 
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="modal fade" id="modalDeleteProduct<?php echo $product['maGiay'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete giày</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="products/delete" method="POST" class="needs-validation" novalidate>
                                                        <input type="hidden"  id="maGiay" name="maGiay"  value="<?php echo $product['maGiay'] ?>" >
                                                        <div class="mb-3">
                                                            <h3>Bạn có chắc muốn khóa  giày <?php echo $product['tenGiay'] ?> ?</h3>
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
                                <!-- <php endif; > -->
                                <!-- Begin Modal Delete Product -->
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="py-4 text-center text-gray-500">Không có giày nào</td>
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
    document.getElementById('modalAddProduct').addEventListener('hidden.bs.modal', function () {
        // Reset toàn bộ form
        var form = document.querySelector('#modalAddProduct form');
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

// UPLOAD IMAGE
var upload = document.querySelector('#hinhAnh');
upload.addEventListener('change', function(e) {
    var preview = document.querySelector('.preview');
    var file = upload.files[0];
    if(!file) {
        return
    }
    if(file.size / (1024*1024) > 5) {
        alert('Chi duoc upload anh < 5MB');
        return;
    }
    var img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    preview.appendChild(img);
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
            

            // Thay đổi ảnh
            var preview = document.querySelector("#preview" + maGiay);
            var oldImg = preview.querySelector("img");
            var newImg = document.createElement('img');
            var upload = document.querySelector('#hinhAnh' + maGiay);
            upload.addEventListener('change', function(e) {
                upload.required = true;
                var file = upload.files[0];
                if(!file) {
                    return;
                }
                if(file.size / (1024*1024) > 5) {
                    alert('Chi duoc upload anh < 5MB');
                    return;
                }
                if(oldImg)
                {
                    oldImg.style.display = "none";
                }
                newImg.setAttribute("name", "hinhAnh");
                newImg.src = URL.createObjectURL(file);
                preview.appendChild(newImg);
            });

            //Button bỏ ảnh
            var removeImgBtn = document.querySelector("#removeImg" + maGiay);
            removeImgBtn.addEventListener("click", function() {
                if(newImg)
                {
                    upload.required = true;
                    newImg.remove();
                    upload.value = "";
                }
                if(oldImg)
                {
                    upload.required = true;
                    oldImg.style.display = "none";
                    upload.value = "";
                }
            });

            // Tắt model edit product mà không bấm lưu thì ảnh sẽ về như cũ 
            document.getElementById('modalEditProduct' + maGiay)
            .addEventListener('hidden.bs.modal', function () {
                if(newImg && newImg !== oldImg) {
                    newImg.remove();
                }
                if (oldImg) {
                    oldImg.style.display = "block";
                    upload.required = false;
                }
            });
        });
    });
});
</script>