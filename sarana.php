<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Sarana dan Prasarana';
$page_description = 'Fasilitas umum, pendidikan, kesehatan, dan infrastruktur yang tersedia di RW 7 Kelurahan Temas.';

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
$where_conditions = ["status = 'aktif'"];
$params = [];

if (!empty($kategori_filter)) {
    $where_conditions[] = "kategori = :kategori";
    $params[':kategori'] = $kategori_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(nama LIKE :search OR deskripsi LIKE :search)";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
try {
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM fasilitas WHERE $where_clause");
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

// Get fasilitas data
try {
    $stmt = $conn->prepare("SELECT * FROM fasilitas WHERE $where_clause ORDER BY kategori, nama LIMIT :limit OFFSET :offset");
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $fasilitas_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $fasilitas_list = [];
}

// Get categories
try {
    $cat_stmt = $conn->prepare("SELECT kategori, COUNT(*) as total FROM fasilitas WHERE status = 'aktif' GROUP BY kategori ORDER BY kategori");
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
        <h1>Sarana dan Prasarana RW 7</h1>
        <p>Berikut adalah daftar fasilitas yang tersedia untuk mendukung kegiatan warga</p>
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
                    <input type="text" name="search" placeholder="Cari fasilitas..." value="<?php echo htmlspecialchars($search); ?>">
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

<!-- Fasilitas List -->
<section class="fasilitas-list">
    <div class="container">
        <?php if (!empty($fasilitas_list)): ?>
            <div class="fasilitas-grid">
                <?php foreach ($fasilitas_list as $item): ?>
                <article class="fasilitas-card">
                    <div class="fasilitas-content">
                        <h3><?php echo htmlspecialchars($item['nama']); ?></h3>
                        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($item['kategori']); ?></p>
                        <p><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="pagination">
                <div class="pagination-info">
                    Menampilkan <?php echo (($page - 1) * $items_per_page + 1); ?> - <?php echo min($page * $items_per_page, $total_items); ?> 
                    dari <?php echo $total_items; ?> fasilitas
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
                    <div class="no-results-icon">🏢</div>
                    <h3>Tidak ada fasilitas ditemukan</h3>
                    <p>Maaf, fasilitas tidak ditemukan untuk pencarian atau filter yang diterapkan.</p>
                    <a href="sarana.php" class="btn-primary">Lihat Semua Fasilitas</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    /* =================================================================
   FASILITAS PAGE STYLES
   ================================================================= */

.fasilitas-list {
    padding: 5rem 0;
    background: #f8f9fa;
}

.fasilitas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.fasilitas-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
}

.fasilitas-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

.fasilitas-image {
    height: 200px;
    overflow: hidden;
}

.fasilitas-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.fasilitas-card:hover .fasilitas-image img {
    transform: scale(1.08);
}

.fasilitas-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.fasilitas-meta {
    margin-bottom: 1rem;
}

.fasilitas-kategori {
    background: #1e3c72;
    color: #FFD700;
    padding: 0.3rem 0.8rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.fasilitas-content h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #1e3c72;
    margin-bottom: 0.75rem;
}

.fasilitas-content p {
    color: #555;
    line-height: 1.6;
    flex-grow: 1;
}

/* =================================================================
   SEARCH AND FILTER SECTION
   ================================================================= */

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

/* --- Search Form --- */
.search-form {
    flex-grow: 1;
    max-width: 450px; /* Lebar maksimal form pencarian */
}

.search-input-group {
    display: flex;
    width: 100%;
    box-shadow: 0 5px 20px rgba(0,0,0,0.07);
    border-radius: 50px; /* Membuat sudut menjadi sangat bulat */
}

.search-input-group input[type="text"] {
    flex-grow: 1;
    padding: 0.8rem 1.5rem;
    border: 1px solid #ddd;
    border-radius: 50px 0 0 50px;
    font-size: 1rem;
    color: #333;
    outline: none;
    border-right: none; /* Menghilangkan border kanan agar menyatu dengan tombol */
    transition: border-color 0.3s ease;
}

.search-input-group input[type="text"]:focus {
    border-color: #1e3c72;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
}

.search-input-group button {
    padding: 0.8rem 1.5rem;
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


/* --- Category Filter Buttons --- */
.filter-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    background: white;
    border: 2px solid #ddd;
    color: #333;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    text-decoration: none;
}

.filter-btn:hover {
    border-color: #1e3c72;
    color: #1e3c72;
    transform: translateY(-2px);
}

.filter-btn.active {
    background: #1e3c72;
    color: white;
    border-color: #1e3c72;
    box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
    transform: translateY(-2px);
}

</style>
<?php include 'templates/footer.php'; ?>