<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Danh sách giày</h2>
        <a href="<?php echo BASE_URL; ?>/admin/products/add" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus"></i> Thêm giày mới
        </a>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-4">Mã giày</th>
                        <th class="pb-4">Hình ảnh</th>
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
                            <td class="py-4"><?php echo htmlspecialchars($product['maGiay'] ?? ''); ?></td>
                            <td class="py-4">
                                <?php if (!empty($product['hinhAnh'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['hinhAnh']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['tenGiay'] ?? ''); ?>"
                                         class="w-16 h-16 object-cover">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>/public/images/no-image.jpg" 
                                         alt="No Image"
                                         class="w-16 h-16 object-cover">
                                <?php endif; ?>
                            </td>
                            <td class="py-4"><?php echo htmlspecialchars($product['tenGiay'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($product['tenLoaiGiay'] ?? ''); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($product['size'] ?? ''); ?></td>
                            <td class="py-4"><?php echo number_format($product['giaBan'] ?? 0, 0, ',', '.'); ?>đ</td>
                            <td class="py-4"><?php echo htmlspecialchars($product['tonKho'] ?? 0); ?></td>
                            <td class="py-4">
                                <?php 
                                $tonKho = $product['tonKho'] ?? 0;
                                $class = $tonKho > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                $status = $tonKho > 0 ? 'Còn hàng' : 'Hết hàng';
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs <?php echo $class; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo htmlspecialchars($product['maGiay'] ?? ''); ?>" 
                                   class="text-blue-500 hover:text-blue-700 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/admin/products/delete/<?php echo htmlspecialchars($product['maGiay'] ?? ''); ?>" 
                                   class="text-red-500 hover:text-red-700"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa giày này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
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