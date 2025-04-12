<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - Admin' : 'Admin Dashboard'; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/public/css/admin.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/css/AdminProducts.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/css/toasts.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-4">
                <a href="<?php echo BASE_URL; ?>/admin" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'dashboard' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/products" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'products' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-box mr-2"></i> Sản phẩm
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/categories" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'categories' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-list mr-2"></i> Danh mục
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/orders" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'orders' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-shopping-cart mr-2"></i> Đơn hàng
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'users' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-users mr-2"></i> Người dùng
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/suppliers" class="block px-4 py-2 hover:bg-gray-700 <?php echo $currentPage === 'suppliers' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-truck mr-2"></i> Nhà cung cấp
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold"><?php echo $title ?? 'Dashboard'; ?></h2>
                    <div class="flex items-center">
                        <span class="mr-4"><?php echo $_SESSION['username']; ?></span>
                        <a href="<?php echo BASE_URL; ?>/logout" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <?php 
                    if (isset($_SESSION['success'])) {
                        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">';
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        echo '</div>';
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">';
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        echo '</div>';
                    }
                    ?>
                    <?php 
                        require_once $content; 
                    ?>
                    <?php require_once "app/views/admin/toasts/index.php" ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/admin.js"></script>
</body>
</html> 