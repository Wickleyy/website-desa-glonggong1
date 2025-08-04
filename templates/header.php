<?php
// templates/header.php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Desa Glonggong</title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'RW 7 Kelurahan Temas adalah komunitas unggul dalam kebersihan dan gotong royong di Kota Batu, Jawa Timur.'; ?>">
    <meta name="keywords" content="RW 7 Temas, Kelurahan Temas, Kota Batu, UMKM, Komunitas, Gotong Royong">
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'RW 7 Temas'; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Komunitas Bersih, Berbudaya, dan Berdaya'; ?>">
    <meta property="og:image" content="<?php echo isset($page_image) ? $page_image : 'assets/images/rw7-hero.jpg'; ?>">
    <meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="nav-brand">
                <div class="logo-container">
                    <img src="assets/images/logo-rw7.jpg" alt="Logo RW 7" class="logo">
                    <span class="brand-text">Desa Glonggong</span>
                </div>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Beranda</a></li>
                <li><a href="tentang.php" class="nav-link <?php echo ($current_page == 'tentang') ? 'active' : ''; ?>">Tentang Glonggong</a></li>
                <li><a href="umkm.php" class="nav-link <?php echo ($current_page == 'umkm') ? 'active' : ''; ?>">Potensi UMKM</a></li>
                <li><a href="berita.php" class="nav-link <?php echo ($current_page == 'berita') ? 'active' : ''; ?>">Berita</a></li>
                <li><a href="kontak.php" class="nav-link <?php echo ($current_page == 'kontak') ? 'active' : ''; ?>">Kontak</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>