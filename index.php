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
                <h1 class="hero-title">Selamat Datang di desa Glonggong</h1>
                <p class="hero-subtitle">Komunitas Bersih, Berbudaya, dan Berdaya</p>
                <p class="hero-description">
                    Desa Glonggong adalah komunitas unggul dalam kebersihan dan gotong royong, 
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
                        <h3>Sarana & Prasarana</h3>
                        <p>Sarana & Prasarana yang tersedia di desa glonggong ini.</p>
                        <a href="sarana.php" class="card-link">Lihat Sarana & Prasarana →</a>
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

    <script>
/* =================================================================
   HALAMAN BERANDA - BAGIAN BERITA TERBARU
   ================================================================= */

/* Pembungkus utama untuk seluruh bagian "Berita Terbaru" */
.latest-news {
    padding: 5rem 0;
    background-color: #f8f9fa; /* Memberi sedikit warna latar belakang */
}

/* Wadah yang akan menampung kartu-kartu berita dalam format grid */
.news-grid {
    display: grid;
    /* Membuat grid yang responsif: 3 kolom di layar besar, 2 di layar medium,
       dan 1 di layar kecil secara otomatis. */
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 2.5rem; /* Jarak antar kartu berita */
    margin-bottom: 3rem; /* Jarak sebelum tombol "Lihat Semua Berita" */
}

/* Desain untuk setiap kartu berita */
.news-card {
    background: #fff;
    border-radius: 15px; /* Sudut yang membulat */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); /* Efek bayangan halus */
    overflow: hidden; /* Penting agar gambar tetap di dalam sudut yang membulat */
    transition: all 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
}

/* Efek saat kursor mouse diarahkan ke kartu */
.news-card:hover {
    transform: translateY(-8px); /* Sedikit terangkat ke atas */
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12); /* Bayangan lebih jelas */
}

/* Wadah untuk gambar berita, memperbaiki masalah gambar kebesaran */
.news-image {
    height: 200px; /* Menetapkan tinggi yang sama untuk semua gambar */
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Membuat gambar mengisi area tanpa terdistorsi */
    transition: transform 0.4s ease;
}

.news-card:hover .news-image img {
    transform: scale(1.08); /* Efek zoom saat disentuh kursor */
}

/* Area untuk konten teks di bawah gambar */
.news-content {
    padding: 1.5rem 2rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* Memastikan semua kartu memiliki tinggi yang sama */
}

/* Info meta seperti kategori dan tanggal */
.news-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    color: #666;
}

.news-category {
    background-color: #1e3c72;
    color: #FFD700;
    padding: 0.3rem 0.8rem;
    border-radius: 50px;
    font-weight: 500;
}

/* Judul berita */
.news-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.news-content h3 a {
    color: #1e3c72;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-content h3 a:hover {
    color: #FFD700;
}

/* Cuplikan (excerpt) berita */
.news-content p {
    color: #555;
    line-height: 1.6;
    flex-grow: 1; /* Mendorong link "Baca Selengkapnya" ke bawah */
    margin-bottom: 1.5rem;
}

/* Link "Baca Selengkapnya" */
.read-more {
    color: #1e3c72;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
    align-self: flex-start;
}

.read-more:hover {
    color: #FFA500;
}

/* Kelas bantu untuk menengahkan tombol */
.text-center {
    text-align: center;
}
    </script>
<?php include 'templates/footer.php'; ?>