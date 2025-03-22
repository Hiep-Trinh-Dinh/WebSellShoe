<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'SoleStyle - Modern Shoe Store'; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/public/css/globals.css" rel="stylesheet">
    
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

    <?php require_once 'app/components/footer.php'; ?>
</body>
</html> 