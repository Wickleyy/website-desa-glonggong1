<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Potensi UMKM';
$page_description = 'Jelajahi produk-produk unggulan dan usaha kreatif warga RW 7 yang penuh inovasi dan kualitas.';

// Database connection
$database = new Database();
$conn = $database->connect();

// Get filter parameters
$kategori_filter = isset($_GET['kategori']) ? sanitize_input($_GET['kategori']) : 'all';
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Build query
$where_conditions = ["status = 'aktif'"];
$params = [];

if ($kategori_filter !== 'all') {
    $where_conditions[] = "kategori = :kategori";
    $params[':kategori'] = $kategori_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(nama_usaha LIKE :search OR deskripsi LIKE :search OR pemilik LIKE :search)";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where_conditions);

// Get UMKM data
try {
    $stmt = $conn->prepare("SELECT * FROM umkm WHERE $where_clause ORDER BY nama_usaha ASC");
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $umkm_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $umkm_list = [];
}

// Get categories for filter
try {
    $stmt = $conn->prepare("SELECT kategori, COUNT(*) as total FROM umkm WHERE status = 'aktif' GROUP BY kategori ORDER BY kategori");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categories = [];
}

include 'templates/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Jelajahi Produk Unggulan Warga RW 7</h1>
            <p>Temukan berbagai produk berkualitas dan usaha kreatif dari masyarakat Kelurahan Temas</p>
        </div>
    </section>

    <!-- UMKM Introduction -->
    <section class="umkm-intro">
        <div class="container">
            <div class="intro-content">
                <p>
                    RW 7 Kelurahan Temas memiliki beragam usaha mikro, kecil, dan menengah (UMKM) 
                    yang dikembangkan oleh warga dengan memanfaatkan potensi lokal dan kearifan 
                    tradisional. Setiap produk dibuat dengan penuh perhatian terhadap kualitas 
                    dan cita rasa yang autentik.
                </p>
            </div>
        </div>
    </section>

    <!-- Search and Filter -->
    <section class="search-filter">
        <div class="container">
            <div class="filter-controls">
                <!-- Search Form -->
                <form method="GET" class="search-form">
                    <input type="hidden" name="kategori" value="<?php echo $kategori_filter; ?>">
                    <div class="search-input-group">
                        <input type="text" name="search" placeholder="Cari UMKM..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit">🔍</button>
                    </div>
                </form>
                
                <!-- Category Filter -->
                <div class="category-filter">
                    <div class="filter-buttons">
                        <a href="?search=<?php echo urlencode($search); ?>" 
                           class="filter-btn <?php echo ($kategori_filter == 'all') ? 'active' : ''; ?>">
                           Semua Kategori (<?php echo count($umkm_list); ?>)
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="?kategori=<?php echo $cat['kategori']; ?>&search=<?php echo urlencode($search); ?>" 
                           class="filter-btn <?php echo ($kategori_filter == $cat['kategori']) ? 'active' : ''; ?>">
                           <?php 
                           $icons = [
                               'kuliner' => '🍽️',
                               'kerajinan' => '🎨', 
                               'hiburan' => '🎮',
                               'jasa' => '🛠️'
                           ];
                           echo ($icons[$cat['kategori']] ?? '📦') . ' ' . ucfirst($cat['kategori']) . ' (' . $cat['total'] . ')';
                           ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UMKM Directory -->
    <section class="umkm-directory">
        <div class="container">
            <?php if (!empty($umkm_list)): ?>
                <?php
                // Group by category for better display
                $grouped_umkm = [];
                foreach ($umkm_list as $umkm) {
                    $grouped_umkm[$umkm['kategori']][] = $umkm;
                }

                $category_titles = [
                    'kuliner' => '🍽️ Kuliner Tradisional',
                    'kerajinan' => '🎨 Kerajinan & Kreatif',
                    'hiburan' => '🎮 Hiburan',
                    'jasa' => '🛠️ Jasa & Layanan'
                ];

                foreach ($grouped_umkm as $kategori => $umkm_items):
                ?>
                <div class="category-section">
                    <h2 class="category-title"><?php echo $category_titles[$kategori] ?? ucfirst($kategori); ?></h2>
                    <div class="umkm-grid">
                        <?php foreach ($umkm_items as $umkm): ?>
                        <div class="umkm-card">
                            <div class="umkm-image">
                                <img src="assets/images/umkm/<?php echo !empty($umkm['gambar']) ? $umkm['gambar'] : 'default-umkm.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($umkm['nama_usaha']); ?>">
                            </div>
                            <div class="umkm-content">
                                <div class="umkm-category"><?php echo ucfirst($umkm['kategori']); ?></div>
                                <h3><?php echo htmlspecialchars($umkm['nama_usaha']); ?></h3>
                                <p class="umkm-description"><?php echo htmlspecialchars($umkm['deskripsi']); ?></p>
                                <div class="umkm-owner">
                                    <strong><?php echo htmlspecialchars($umkm['pemilik']); ?></strong>
                                </div>
                                <?php if (!empty($umkm['alamat'])): ?>
                                <div class="umkm-address">
                                    📍 <?php echo htmlspecialchars($umkm['alamat']); ?>
                                </div>
                                <?php endif; ?>
                                <div class="umkm-actions">
                                    <?php if (!empty($umkm['kontak'])): ?>
                                    <a href="https://wa.me/62<?php echo ltrim($umkm['kontak'], '0'); ?>" 
                                       target="_blank" class="btn-contact">📱 WhatsApp</a>
                                    <?php endif; ?>
                                    <?php if (!empty($umkm['email'])): ?>
                                    <a href="mailto:<?php echo $umkm['email']; ?>" class="btn-email">📧 Email</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-content">
                        <div class="no-results-icon">🔍</div>
                        <h3>Tidak ada UMKM ditemukan</h3>
                        <p>Maaf, tidak ada UMKM yang sesuai dengan pencarian Anda.</p>
                        <a href="umkm.php" class="btn-primary">Lihat Semua UMKM</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Support Section -->
    <section class="support">
        <div class="container">
            <h2 class="section-title">Dukung UMKM Lokal</h2>
            <div class="support-content">
                <p>
                    Dengan membeli produk UMKM RW 7, Anda tidak hanya mendapatkan produk berkualitas, 
                    tetapi juga turut mendukung perekonomian masyarakat lokal dan pemberdayaan komunitas. 
                    Mari bersama membangun ekonomi yang berkelanjutan dan berdaya saing.
                </p>
                <div class="support-benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">✨</div>
                        <h4>Produk Berkualitas</h4>
                        <p>Setiap produk dibuat dengan standar kualitas tinggi dan cita rasa autentik</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🤝</div>
                        <h4>Mendukung Lokal</h4>
                        <p>Pembelian Anda langsung mendukung perekonomian warga RW 7</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🌱</div>
                        <h4>Ramah Lingkungan</h4>
                        <p>Banyak produk menggunakan bahan organik dan proses ramah lingkungan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ingin Bergabung dengan UMKM RW 7?</h2>
                <p>Daftarkan usaha Anda dan jadilah bagian dari komunitas UMKM RW 7 yang solid dan berkembang.</p>
                <a href="kontak.php?subject=Daftar%20UMKM" class="btn-primary">Daftar UMKM Anda</a>
            </div>
        </div>
    </section>

<?php include 'templates/footer.php'; ?>