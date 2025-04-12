<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'SoleStyle - Modern Shoe Store'; ?></title>
    
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
    <link href="<?php echo BASE_URL; ?>/public/css/globals.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/css/toasts.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen flex-col">
    <?php require_once 'app/components/navbar.php'; ?>
    
    <main class="flex-1">
        <?php 
        if (isset($content)) {
            require_once $content;
        }
        ?>
    </main>

    <?php require_once "app/views/admin/toasts/index.php" ?>
    <?php require_once 'app/components/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>/public/js/user.js"></script>
</body>
</html> 