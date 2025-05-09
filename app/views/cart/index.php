<div class="container py-8">
    <h1 class="text-3xl font-bold mb-8">Giỏ hàng</h1>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-[1fr_350px]" id="wrap-cart-list" style="display: none">
            <div class="rounded-lg border">
                <div class="p-4 border-b">
                    <h2 class="font-bold">Sản phẩm</h2>
                </div>

                <div class="divide-y" id="cart-list">
                    
                </div>

                <div class="p-4 border-t flex justify-between items-center">
                    <a href="<?php echo BASE_URL; ?>/products" 
                    class="text-sm border rounded-md px-4 py-2 hover:bg-gray-50">
                        Tiếp tục mua sắm
                    </a>
                    <button onclick="clearCart()" 
                            class="text-sm text-red-500 border border-red-500 rounded-md px-4 py-2 hover:bg-red-50">
                        Xóa giỏ hàng
                    </button>
                </div>

            </div>


            <div class="rounded-lg border h-fit">
                <div class="p-4 border-b">
                    <h2 class="font-bold">Tổng đơn hàng</h2>
                </div>

                <div class="p-4 space-y-4">
                    <div class="flex justify-between">
                        <span>Tạm tính</span>
                        <span id="subtotal"><!--php echo number_format($subtotal, 0, ',', '.'); -->đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Phí vận chuyển</span>
                        <span id="shipping"><!--php echo $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ'; --></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Thuế</span>
                        <span id="tax"><!--php echo number_format($tax, 0, ',', '.'); -->đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tổng số lượng</span>
                        <span id="totalQuantity"><!--php echo number_format($tax, 0, ',', '.'); --></span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between font-bold">
                            <span>Tổng cộng</span>
                            <span id="total"><!--php echo number_format($total, 0, ',', '.'); -->đ</span>
                        </div>
                    </div>

                    <!-- Begin Modal Pay -->
                    <button 
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-4 py-2 font-medium"
                        id="btnOpenModal" 
                        data-bs-target="#modalPay" 
                        data-bs-whatever="@mdo"
                    >
                        Tiến hành thanh toán
                    </button>
                    <div class="modal fade" id="modalPay" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thanh toán</h5>
                                </div>
                                <div class="modal-body">
                                    <form  method="POST" class="needs-validation" novalidate>
                                        <div class="mb-3">
                                            <label for="thanhToan" class="col-form-label">Phương thức thanh toán:</label>
                                            <br>
                                            <label>
                                                <input type="radio" id="tienMatRadio" name="thanhToan" value="1" checked>
                                                Tiền mặt
                                            </label>
                                            <br>
                                            <label>
                                                <input type="radio" id="chuyenKhoanRadio" name="thanhToan" value="2">
                                                Chuyển khoản
                                            </label>

                                        </div>
                                        <div id="chuyenKhoanFields" style="display: none;">
                                            <div class="mb-3">
                                                <label for="soThe" class="col-form-label">Số thẻ:</label>
                                                <input type="text" class="form-control" id="soThe" name="soThe">
                                                <div class="invalid-feedback">
                                                    Vui lòng không để trống trường này
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tenUser" class="col-form-label">Tên chủ tài khoản:</label>
                                                <input type="text" class="form-control" id="tenUser" name="tenUser">
                                                <div class="invalid-feedback">
                                                    Vui lòng không để trống trường này
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3" style="float: right;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button 
                                                type="button" 
                                                class="btn btn-primary" 
                                                style="margin-left: 5px;"
                                                onclick="pay()"
                                            >
                                                Send message
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Pay -->

                    <div class="pt-4">
                        <h3 class="font-medium mb-2">Địa chỉ</h3>
                        <div class="flex">
                            <input 
                                type="text" placeholder="Nhập địa chỉ" 
                                class="flex-1 rounded-l-md border border-r-0 px-3 py-2 text-sm"
                                id="diaChiInput"
                                required
                            >
                        </div>
                    </div>

                    <div class="pt-4 text-sm text-gray-500">
                        <p>Chúng tôi chấp nhận:</p>
                        <div class="mt-2 flex gap-2">
                            <div class="h-8 w-12 rounded border bg-gray-50"></div>
                            <div class="h-8 w-12 rounded border bg-gray-50"></div>
                            <div class="h-8 w-12 rounded border bg-gray-50"></div>
                            <div class="h-8 w-12 rounded border bg-gray-50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center py-16" id="is-cart-null" style="display: none">
        <h2 class="text-2xl font-bold mb-4">Giỏ hàng trống</h2>
        <p class="text-gray-500 mb-8">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
        <a href="<?php echo BASE_URL; ?>/products" 
        class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-md px-6 py-2 font-medium inline-flex items-center">
            Tiếp tục mua sắm
            <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>





<script>
    const BASE_URL = window.location.origin + "/Web2";
    
    // LẤY DỮ LIỆU TỪ LOCAL STORAGE VÀ ĐỔ VÀO TRANG CART
    let subtotal = 0;
    let shipping = 0;
    let tax = 0;
    let totalQuantity = 0;
    let total = 0;
    let rawCart = JSON.parse(localStorage.getItem('cartItems')) || []; 
    let cartList = document.getElementById("cart-list");
    let wrapCartList = document.getElementById("wrap-cart-list");
    let isCartNull = document.getElementById("is-cart-null");

    console.log(">>> ", rawCart)

    if(rawCart.length > 0)
    {
        wrapCartList.style.display = "grid";
        isCartNull.style.display = "none";
    }
    else
    {
        isCartNull.style.display = "block";
        wrapCartList.style.display = "none";
    }

    
    // Chuyển values từ localStorage thành object
    let cartItems = rawCart.map(item => {
        return {
            ...JSON.parse(item.product),
            soLuong: item.quantity
        };
    });


    // Tính giá trị bên hóa đơn
    cartItems.reduce((total, item) => {
        subtotal += (item.giaBan * item.soLuong);
        totalQuantity += item.soLuong;
    }, 0);

    total = subtotal - tax - shipping;
    subtotal = subtotal.toLocaleString('vi-VN');
    shipping = shipping.toLocaleString('vi-VN');
    tax = tax.toLocaleString('vi-VN');
    total = total.toLocaleString('vi-VN');
    document.getElementById("subtotal").textContent = subtotal + "đ";
    document.getElementById("shipping").textContent = "-" + shipping + "đ";
    document.getElementById("tax").textContent = "-" + tax + "đ";
    document.getElementById("totalQuantity").textContent = totalQuantity;
    document.getElementById("total").textContent = total + "đ";


    cartList.innerHTML += cartItems.map((item, index) => `
        <div class="p-4">
            <div class="flex flex-wrap items-start gap-4">
                <div class="relative h-24 w-24 overflow-hidden rounded-md">
                    <img 
                        src="${item.hinhAnh ? `${BASE_URL}/public/img/${atob(item.hinhAnh)}` : `${BASE_URL}/public/images/no-image.jpg`}" 
                        alt="${item.tenGiay}" 
                        class="object-cover">
                </div>
                <div class="flex-1">
                    <a href="${BASE_URL}/products/detail/${item.maGiay}" class="font-medium hover:text-yellow-500">
                        ${item.tenGiay}
                    </a>
                    <div class="mt-1 text-sm text-gray-500">
                        <p>Size: ${item.size}</p>
                    </div>
                    <div class="mt-2 flex items-center">
                        <button class="flex h-8 w-8 items-center justify-center rounded-l-md border" onclick="updateQuantity(${index}, 'decrease')">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <div 
                            class="flex h-8 w-10 items-center justify-center border-t border-b"
                            
                        >
                            ${item.soLuong}
                        </div>
                        <button class="flex h-8 w-8 items-center justify-center rounded-r-md border" onclick="updateQuantity(${index}, 'increase')">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <p class="font-bold">${(item.giaBan * item.soLuong).toLocaleString('vi-VN')}đ</p>
                    <button class="text-gray-500 hover:text-red-500" onclick="removeItem(${index})">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');


    function updateProductToLocalStorage()
    {
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; 
        if(cartItems.length > 0)
        {
            cartItems.forEach((item, index) => {
                
            });
        }
    }


    
    function updateQuantity(index, action) 
    {
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; 
        let product = JSON.parse(cartItems[index].product);
        let maxStock = product.tonKho;

        if(cartItems.length > 0)
        {
            if(action == "increase" && cartItems[index].quantity < maxStock)
            {
                cartItems[index].quantity += 1; 
                
            }
            else if(action == "decrease" && cartItems[index].quantity > 1)
            {
                cartItems[index].quantity -= 1; 
                if(cartItems[index].quantity == 0)
                {
                    cartItems.splice(index, 1);
                    window.location.reload();
                    return;
                }
            }
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            window.location.reload();
        }
    }

    function removeItem(index) 
    {
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; 
        if(cartItems.length > 0)
        {
            cartItems.splice(index, 1);
        }
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        window.location.reload();
    }

    function clearCart() 
    {
        localStorage.removeItem('cartItems');
        window.location.reload();
    }

    // GỬI DỮ LIỆU QUA CART CONTROLLER
    function pay() {
        const BASE_URL = window.location.origin + "/Web2";
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        let maTK = localStorage.getItem('maTK');
        let tienMat = document.getElementById('tienMatRadio');
        let totalQuantity = document.getElementById('totalQuantity').innerText;
        let total = document.getElementById('total').innerText;
        let totalFormatted = parseInt(total.replace(/\./g, ''));
        let diaChi = document.getElementById('diaChiInput').value;
        let thanhToan = tienMat.checked == true ? 1 : 2;
        const now = new Date();
        const formattedDay = now.toISOString().slice(0, 19).replace('T', ' ');
        fetch(BASE_URL + "/cart/pay", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: JSON.stringify({ 
                cartItems: cartItems,
                totalQuantity: totalQuantity,
                total: totalFormatted,
                thanhToan: thanhToan,
                diaChi: diaChi,
                date: formattedDay,
                maTK: maTK

            })
        })
        // .then(async (response) => {
        //     const text = await response.text();
        //     console.log("Raw response: ", text);
        //     return JSON.parse(text); // parse thủ công để xem lỗi
        // })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.success) 
            {
                localStorage.setItem('showToast', 'success');
                localStorage.setItem('toastMessage', 'Đặt hàng thành công');
                localStorage.removeItem('cartItems');
                window.location.reload();
            }
            else 
            {
                localStorage.setItem('showToast', 'error');
                localStorage.setItem('toastMessage', 'Đặt hàng thất bại');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
        
    }

    // XỬ LÝ PHẦN THANH TOÁN
    const tienMatRadio = document.getElementById("tienMatRadio");
    const chuyenKhoanRadio = document.getElementById("chuyenKhoanRadio");
    const chuyenKhoanFields = document.getElementById("chuyenKhoanFields");
    const soThe = document.getElementById("soThe");
    const tenUser = document.getElementById("tenUser");

    document.getElementById("btnOpenModal").addEventListener("click", function (e) {
        const diaChiInput = document.getElementById("diaChiInput");
        const diaChi = diaChiInput.value.trim();

        if (diaChi === "") {
            alert("Vui lòng nhập địa chỉ trước khi thanh toán!");
            diaChiInput.focus();
        } else {
            // Mở modal bằng Bootstrap 5 JS
            const modal = new bootstrap.Modal(document.getElementById("modalPay"));
            modal.show();
        }
    });

    tienMatRadio.addEventListener("change", function () {
        if (tienMatRadio.checked) {
            chuyenKhoanFields.style.display = "none";
            soThe.required = false;
            tenUser.required = false;
        }
    });

    chuyenKhoanRadio.addEventListener("change", function () {
        if (chuyenKhoanRadio.checked) {
            chuyenKhoanFields.style.display = "block";
            soThe.required = true;
            tenUser.required = true;
        }
    });

    // RESET VALUES KHI ĐÓNG FORM THÊM SẢN PHẨM 
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('modalAddProduct').addEventListener('hidden.bs.modal', function () {
            // Reset toàn bộ form
            var form = document.querySelector('#modalAddProduct form');
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

</script> 