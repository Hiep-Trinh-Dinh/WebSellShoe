<div class="container py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-6">Nhập hàng</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded mb-4">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 text-green-600 p-4 rounded mb-4">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/admin/import/process" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="import_file">
                        Chọn file CSV hoặc Excel
                    </label>
                    <input type="file" 
                           name="import_file" 
                           id="import_file" 
                           accept=".csv,.xlsx"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Định dạng file: CSV hoặc Excel (.xlsx)
                    </p>
                </div>

                <div class="mb-4">
                    <h3 class="font-bold mb-2">Lưu ý:</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600">
                        <li>File CSV phải có các cột theo thứ tự: mã giày, tên giày, mô tả, giá bán, số lượng, mã loại</li>
                        <li>Mã giày không được trùng lặp</li>
                        <li>Giá bán và số lượng phải là số</li>
                        <li>Mã loại phải tồn tại trong danh sách danh mục</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h3 class="font-bold mb-2">Danh sách mã loại:</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <?php foreach ($categories as $category): ?>
                            <div class="bg-gray-50 p-2 rounded">
                                <span class="font-medium"><?php echo $category->getMaLoai(); ?></span>
                                - <?php echo $category->getTenLoai(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Nhập hàng
                </button>
            </form>
        </div>
    </div>
</div> 