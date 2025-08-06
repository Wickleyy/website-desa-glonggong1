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

    <style>
        /* BERITA PAGE STYLES*/

/* Search and Filter Section */
.search-filter {
    padding: 3rem 0;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}

.search-form {
    flex-grow: 1;
    max-width: 400px;
}

.search-input-group {
    display: flex;
    width: 100%;
}

.search-input-group input[type="text"] {
    flex-grow: 1;
    padding: 0.75rem 1.25rem;
    border: 1px solid #ddd;
    border-radius: 50px 0 0 50px;
    font-size: 1rem;
    outline: none;
    transition: box-shadow 0.3s ease;
}

.search-input-group input[type="text"]:focus {
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.2);
    border-color: #1e3c72;
}

.search-input-group button {
    padding: 0.75rem 1.25rem;
    border: 1px solid #1e3c72;
    background: #1e3c72;
    color: white;
    cursor: pointer;
    border-radius: 0 50px 50px 0;
    font-size: 1.2rem;
    line-height: 1;
    transition: background-color 0.3s ease;
}

.search-input-group button:hover {
    background: #2a5298;
}

/* Category filter buttons use existing styles from the reference CSS */
/* .category-filter, .filter-buttons, .filter-btn are already styled */


/* News List Section */
.news-list {
    padding: 5rem 0;
    background: white;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2.5rem;
}

.news-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

.news-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.news-card:hover .news-image img {
    transform: scale(1.08);
}

.news-category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(255, 215, 0, 0.9); /* #FFD700 */
    color: #1e3c72;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    backdrop-filter: blur(5px);
}

.news-content {
    padding: 1.5rem 2rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.news-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem 1.5rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #666;
}

.news-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.news-content h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1e3c72;
    margin-bottom: 0.75rem;
}

.news-content h3 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-content h3 a:hover {
    color: #FFD700;
}

.news-excerpt {
    color: #555;
    line-height: 1.7;
    flex-grow: 1;
    margin-bottom: 1.5rem;
}

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


/* Pagination */
.pagination {
    margin-top: 4rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.pagination-info {
    color: #666;
    font-size: 0.95rem;
}

.pagination-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    text-decoration: none;
    border: 1px solid #ddd;
    color: #333;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.pagination-btn:hover {
    background-color: #f0f0f0;
    border-color: #ccc;
}

.pagination-btn.active {
    background-color: #1e3c72;
    color: white;
    border-color: #1e3c72;
    cursor: default;
}


/* No Results Found */
.no-results {
    text-align: center;
    padding: 5rem 2rem;
    background-color: #f8f9fa;
    border-radius: 15px;
}
.no-results-content {
    max-width: 600px;
    margin: 0 auto;
}
.no-results-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    line-height: 1;
    color: #FFD700;
}
.no-results h3 {
    font-size: 2rem;
    color: #1e3c72;
    margin-bottom: 1rem;
}
.no-results p {
    color: #666;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}
/* .btn-primary is already styled in the reference */


/* Newsletter Signup Section */
.newsletter {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.newsletter-content {
    max-width: 700px;
    margin: 0 auto;
}

.newsletter-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.newsletter-content p {
    opacity: 0.9;
    font-size: 1.1rem;
    margin-bottom: 2.5rem;
}

.newsletter-form {
    max-width: 500px;
    margin: 0 auto;
}

.newsletter-input-group {
    display: flex;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.newsletter-input-group input[type="email"] {
    flex-grow: 1;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    outline: none;
}

.newsletter-input-group button {
    border: none;
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #1e3c72;
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter-input-group button:hover {
    filter: brightness(1.1);
    transform: scale(1.05);
}


/* Responsive Adjustments */
@media (max-width: 768px) {
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .search-form {
        max-width: 100%;
    }

    .news-grid {
        grid-template-columns: 1fr;
    }

    .pagination {
        flex-direction: column;
    }
}
    </style>

<?php include 'templates/footer.php'; ?>