<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Berita';
$page_description = 'Ikuti perkembangan terbaru kegiatan dan pencapaian RW 7 Kelurahan Temas.';

// Database connection
$database = new Database();
$conn = $database->connect();

// Pagination
$items_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get filter parameters
$kategori_filter = isset($_GET['kategori']) ? sanitize_input($_GET['kategori']) : '';
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Build query
$where_conditions = ["status = 'published'"];
$params = [];

if (!empty($kategori_filter)) {
    $where_conditions[] = "kategori = :kategori";
    $params[':kategori'] = $kategori_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(judul LIKE :search OR konten LIKE :search OR penulis LIKE :search)";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
try {
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM berita WHERE $where_clause");
    foreach ($params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_items / $items_per_page);
} catch(PDOException $e) {
    $total_items = 0;
    $total_pages = 0;
}

// Get news data
try {
    $stmt = $conn->prepare("SELECT * FROM berita WHERE $where_clause ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $news_list = [];
}

// Get categories
try {
    $cat_stmt = $conn->prepare("SELECT kategori, COUNT(*) as total FROM berita WHERE status = 'published' GROUP BY kategori ORDER BY kategori");
    $cat_stmt->execute();
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categories = [];
}

include 'templates/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Berita & Informasi RW 7</h1>
            <p>Ikuti perkembangan terbaru kegiatan dan pencapaian komunitas kami</p>
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
                        <input type="text" name="search" placeholder="Cari berita..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit">🔍</button>
                    </div>
                </form>
                
                <!-- Category Filter -->
                <div class="category-filter">
                    <div class="filter-buttons">
                        <a href="?search=<?php echo urlencode($search); ?>" 
                           class="filter-btn <?php echo empty($kategori_filter) ? 'active' : ''; ?>">
                           Semua Kategori
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="?kategori=<?php echo $cat['kategori']; ?>&search=<?php echo urlencode($search); ?>" 
                           class="filter-btn <?php echo ($kategori_filter == $cat['kategori']) ? 'active' : ''; ?>">
                           <?php echo ucfirst($cat['kategori']); ?> (<?php echo $cat['total']; ?>)
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News List -->
    <section class="news-list">
        <div class="container">
            <?php if (!empty($news_list)): ?>
                <div class="news-grid">
                    <?php foreach ($news_list as $news): ?>
                    <article class="news-card">
                        <div class="news-image">
                            <img src="assets/images/news/<?php echo !empty($news['gambar']) ? $news['gambar'] : 'default-news.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($news['judul']); ?>">
                            <div class="news-category-badge">
                                <?php echo ucfirst($news['kategori']); ?>
                            </div>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-author">👤 <?php echo htmlspecialchars($news['penulis']); ?></span>
                                <span class="news-date">📅 <?php echo format_date($news['created_at']); ?></span>
                            </div>
                            <h3><a href="berita-detail.php?slug=<?php echo $news['slug']; ?>"><?php echo htmlspecialchars($news['judul']); ?></a></h3>
                            <p class="news-excerpt"><?php echo htmlspecialchars($news['excerpt'] ?: get_excerpt($news['konten'], 150)); ?></p>
                            <a href="berita-detail.php?slug=<?php echo $news['slug']; ?>" class="read-more">Baca Selengkapnya →</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="pagination">
                    <div class="pagination-info">
                        Menampilkan <?php echo (($page - 1) * $items_per_page + 1); ?> - <?php echo min($page * $items_per_page, $total_items); ?> 
                        dari <?php echo $total_items; ?> berita
                    </div>
                    <div class="pagination-links">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo ($page - 1); ?>&kategori=<?php echo urlencode($kategori_filter); ?>&search=<?php echo urlencode($search); ?>" 
                               class="pagination-btn">← Sebelumnya</a>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="?page=<?php echo $i; ?>&kategori=<?php echo urlencode($kategori_filter); ?>&search=<?php echo urlencode($search); ?>" 
                               class="pagination-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                               <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo ($page + 1); ?>&kategori=<?php echo urlencode($kategori_filter); ?>&search=<?php echo urlencode($search); ?>" 
                               class="pagination-btn">Selanjutnya →</a>
                        <?php endif; ?>
                    </div>
                </nav>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-content">
                        <div class="no-results-icon">📰</div>
                        <h3>Tidak ada berita ditemukan</h3>
                        <p>Maaf, tidak ada berita yang sesuai dengan pencarian Anda.</p>
                        <a href="berita.php" class="btn-primary">Lihat Semua Berita</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>Dapatkan Update Terbaru</h2>
                <p>Berlangganan untuk mendapatkan informasi terbaru tentang kegiatan dan perkembangan RW 7.</p>
                <form class="newsletter-form" action="process/newsletter.php" method="POST">
                    <div class="newsletter-input-group">
                        <input type="email" name="email" placeholder="Masukkan email Anda" required>
                        <button type="submit">Berlangganan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include 'templates/footer.php'; ?>