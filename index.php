<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Beranda';
$page_description = 'RW 7 Kelurahan Temas adalah komunitas unggul dalam kebersihan dan gotong royong di Kota Batu, Jawa Timur.';

// Database connection
$database = new Database();
$conn = $database->connect();

// Get latest news
try {
    $stmt = $conn->prepare("SELECT * FROM berita WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $latest_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $latest_news = [];
}

// Get featured UMKM
try {
    $stmt = $conn->prepare("SELECT * FROM umkm WHERE status = 'aktif' ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $featured_umkm = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $featured_umkm = [];
}

// Get statistics
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as total_umkm FROM umkm WHERE status = 'aktif'");
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_umkm = $stats['total_umkm'];
} catch(PDOException $e) {
    $total_umkm = 10;
}

include 'templates/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Selamat Datang di RW 7 Temas</h1>
                <p class="hero-subtitle">Komunitas Bersih, Berbudaya, dan Berdaya</p>
                <p class="hero-description">
                    RW 7 adalah komunitas unggul dalam kebersihan dan gotong royong, 
                    yang terus berkembang menuju kemandirian pangan dan kemajuan 
                    dengan tetap melestarikan nilai-nilai budaya lokal.
                </p>
                <a href="tentang.php" class="btn-primary">Jelajahi Komunitas Kami</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/images/hero-rw7.jpg" alt="Suasana RW 7 Temas">
        </div>
    </section>

    <!-- Quick Links -->
    <section class="quick-links">
        <div class="container">
            <h2 class="section-title">Eksplorasi RW 7 Temas</h2>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/umkm-featured.jpg" alt="Potensi UMKM">
                    </div>
                    <div class="card-content">
                        <h3>Potensi UMKM</h3>
                        <p>Jelajahi produk-produk unggulan dan usaha kreatif warga RW 7 yang penuh inovasi dan kualitas.</p>
                        <a href="umkm.php" class="card-link">Lihat UMKM →</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/program-featured.jpg" alt="Program Kami">
                    </div>
                    <div class="card-content">
                        <h3>Program Kami</h3>
                        <p>Dokumentasi kegiatan dan program pemberdayaan masyarakat yang telah dan sedang berjalan.</p>
                        <a href="program.php" class="card-link">Lihat Program →</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/berita-featured.jpg" alt="Berita Terkini">
                    </div>
                    <div class="card-content">
                        <h3>Berita Terkini</h3>
                        <p>Ikuti perkembangan terbaru kegiatan dan pencapaian RW 7 Kelurahan Temas.</p>
                        <a href="berita.php" class="card-link">Baca Berita →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <?php if (!empty($latest_news)): ?>
    <section class="latest-news">
        <div class="container">
            <h2 class="section-title">Berita Terbaru</h2>
            <div class="news-grid">
                <?php foreach ($latest_news as $news): ?>
                <article class="news-card">
                    <div class="news-image">
                        <img src="assets/images/news/<?php echo !empty($news['gambar']) ? $news['gambar'] : 'default-news.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($news['judul']); ?>">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-category"><?php echo ucfirst($news['kategori']); ?></span>
                            <span class="news-date"><?php echo format_date($news['created_at']); ?></span>
                        </div>
                        <h3><a href="berita-detail.php?slug=<?php echo $news['slug']; ?>"><?php echo htmlspecialchars($news['judul']); ?></a></h3>
                        <p><?php echo htmlspecialchars($news['excerpt']); ?></p>
                        <a href="berita-detail.php?slug=<?php echo $news['slug']; ?>" class="read-more">Baca Selengkapnya →</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="berita.php" class="btn-primary">Lihat Semua Berita</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Featured UMKM -->
    <?php if (!empty($featured_umkm)): ?>
    <section class="featured-umkm">
        <div class="container">
            <h2 class="section-title">UMKM Unggulan</h2>
            <div class="umkm-grid">
                <?php foreach (array_slice($featured_umkm, 0, 3) as $umkm): ?>
                <div class="umkm-card">
                    <div class="umkm-image">
                        <img src="assets/images/umkm/<?php echo !empty($umkm['gambar']) ? $umkm['gambar'] : 'default-umkm.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($umkm['nama_usaha']); ?>">
                    </div>
                    <div class="umkm-content">
                        <div class="umkm-category"><?php echo ucfirst($umkm['kategori']); ?></div>
                        <h3><?php echo htmlspecialchars($umkm['nama_usaha']); ?></h3>
                        <p class="umkm-description"><?php echo get_excerpt($umkm['deskripsi'], 100); ?></p>
                        <div class="umkm-owner">
                            <strong><?php echo htmlspecialchars($umkm['pemilik']); ?></strong>
                        </div>
                        <?php if (!empty($umkm['kontak'])): ?>
                        <div class="umkm-contact">
                            <a href="https://wa.me/62<?php echo ltrim($umkm['kontak'], '0'); ?>" target="_blank">📱 Hubungi Penjual</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="umkm.php" class="btn-primary">Lihat Semua UMKM</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Statistics -->
    <section class="statistics">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Rumah Tangga</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Tahun Berdiri</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_umkm; ?>+</div>
                    <div class="stat-label">UMKM Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Partisipasi Warga</div>
                </div>
            </div>
        </div>
    </section>

<?php include 'templates/footer.php'; ?>