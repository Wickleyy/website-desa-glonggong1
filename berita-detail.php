<?php
require_once 'config/database.php';

// Get slug from URL
$slug = isset($_GET['slug']) ? sanitize_input($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: berita.php');
    exit;
}

// Database connection
$database = new Database();
$conn = $database->connect();

// Get news article
try {
    $stmt = $conn->prepare("SELECT * FROM berita WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$article) {
        header('Location: berita.php');
        exit;
    }
} catch(PDOException $e) {
    header('Location: berita.php');
    exit;
}

// Get related articles
try {
    $related_stmt = $conn->prepare("SELECT * FROM berita WHERE kategori = ? AND slug != ? AND status = 'published' ORDER BY created_at DESC LIMIT 3");
    $related_stmt->execute([$article['kategori'], $slug]);
    $related_articles = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $related_articles = [];
}

// Page metadata
$page_title = $article['judul'];
$page_description = $article['excerpt'] ?: get_excerpt(strip_tags($article['konten']), 160);
$page_image = !empty($article['gambar']) ? 'assets/images/news/' . $article['gambar'] : '';

include 'templates/header.php';
?>

    <!-- Article Header -->
    <article class="article-detail">
        <header class="article-header">
            <div class="container">
                <nav class="breadcrumb">
                    <a href="index.php">Beranda</a>
                    <span class="breadcrumb-separator">›</span>
                    <a href="berita.php">Berita</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($article['judul']); ?></span>
                </nav>
                
                <div class="article-meta">
                    <span class="article-category"><?php echo ucfirst($article['kategori']); ?></span>
                    <span class="article-date">📅 <?php echo format_date($article['created_at'], 'd F Y'); ?></span>
                    <span class="article-author">👤 <?php echo htmlspecialchars($article['penulis']); ?></span>
                </div>
                
                <h1 class="article-title"><?php echo htmlspecialchars($article['judul']); ?></h1>
                
                <?php if (!empty($article['gambar'])): ?>
                <div class="article-featured-image">
                    <img src="assets/images/news/<?php echo $article['gambar']; ?>" 
                         alt="<?php echo htmlspecialchars($article['judul']); ?>">
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Article Content -->
        <div class="article-content">
            <div class="container">
                <div class="content-wrapper">
                    <div class="article-body">
                        <?php echo nl2br(htmlspecialchars($article['konten'])); ?>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="article-share">
                        <h4>Bagikan Artikel:</h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="share-btn facebook">
                               📘 Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['judul']); ?>" 
                               target="_blank" class="share-btn twitter">
                               🐦 Twitter
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($article['judul'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="share-btn whatsapp">
                               💬 WhatsApp
                            </a>
                            <button onclick="copyToClipboard()" class="share-btn copy">
                                📋 Salin Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    <?php if (!empty($related_articles)): ?>
    <section class="related-articles">
        <div class="container">
            <h2 class="section-title">Berita Terkait</h2>
            <div class="related-grid">
                <?php foreach ($related_articles as $related): ?>
                <article class="related-card">
                    <div class="related-image">
                        <a href="berita-detail.php?slug=<?php echo $related['slug']; ?>">
                            <img src="assets/images/news/<?php echo !empty($related['gambar']) ? $related['gambar'] : 'default-news.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($related['judul']); ?>">
                        </a>
                    </div>
                    <div class="related-content">
                        <div class="related-meta">
                            <span class="related-category"><?php echo ucfirst($related['kategori']); ?></span>
                            <span class="related-date"><?php echo format_date($related['created_at']); ?></span>
                        </div>
                        <h3>
                            <a href="berita-detail.php?slug=<?php echo $related['slug']; ?>">
                                <?php echo htmlspecialchars($related['judul']); ?>
                            </a>
                        </h3>
                        <p><?php echo get_excerpt($related['konten'], 100); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Navigation -->
    <section class="article-navigation">
        <div class="container">
            <div class="nav-buttons">
                <a href="berita.php" class="btn-secondary">
                    ← Kembali ke Berita
                </a>
                <a href="kontak.php" class="btn-primary">
                    Hubungi Kami →
                </a>
            </div>
        </div>
    </section>

    <style>
    .article-detail {
        padding-top: 80px;
    }

    .article-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem 0 3rem;
    }

    .breadcrumb {
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .breadcrumb a {
        color: #1e3c72;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb-separator {
        margin: 0 0.5rem;
        color: #666;
    }

    .breadcrumb-current {
        color: #666;
    }

    .article-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .article-category {
        background: #FFD700;
        color: #1e3c72;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .article-date,
    .article-author {
        color: #666;
        font-size: 0.9rem;
    }

    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e3c72;
        line-height: 1.2;
        margin-bottom: 2rem;
    }

    .article-featured-image {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .article-featured-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .article-content {
        padding: 3rem 0;
    }

    .content-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .article-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 3rem;
    }

    .article-body p {
        margin-bottom: 1.5rem;
    }

    .article-share {
        border-top: 2px solid #e9ecef;
        padding-top: 2rem;
    }

    .article-share h4 {
        color: #1e3c72;
        margin-bottom: 1rem;
    }

    .share-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .share-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .share-btn.facebook {
        background: #1877f2;
        color: white;
    }

    .share-btn.twitter {
        background: #1da1f2;
        color: white;
    }

    .share-btn.whatsapp {
        background: #25d366;
        color: white;
    }

    .share-btn.copy {
        background: #6c757d;
        color: white;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .related-articles {
        padding: 3rem 0;
        background: #f8f9fa;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .related-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .related-card:hover {
        transform: translateY(-5px);
    }

    .related-image {
        height: 180px;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-card:hover .related-image img {
        transform: scale(1.1);
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }

    .related-category {
        background: #FFD700;
        color: #1e3c72;
        padding: 0.2rem 0.6rem;
        border-radius: 10px;
        font-weight: 500;
    }

    .related-date {
        color: #666;
    }

    .related-content h3 {
        margin-bottom: 0.75rem;
    }

    .related-content h3 a {
        color: #1e3c72;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .related-content h3 a:hover {
        color: #FFD700;
    }

    .related-content p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .article-navigation {
        padding: 2rem 0;
        background: white;
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .article-title {
            font-size: 2rem;
        }

        .article-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .article-featured-image img {
            height: 250px;
        }

        .share-buttons {
            justify-content: center;
        }

        .nav-buttons {
            flex-direction: column;
            text-align: center;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    function copyToClipboard() {
        const url = window.location.href;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link berhasil disalin!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Link berhasil disalin!');
        }
    }

    // Add structured data for SEO
    const structuredData = {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": <?php echo json_encode($article['judul']); ?>,
        "datePublished": "<?php echo date('c', strtotime($article['created_at'])); ?>",
        "dateModified": "<?php echo date('c', strtotime($article['updated_at'])); ?>",
        "author": {
            "@type": "Person",
            "name": <?php echo json_encode($article['penulis']); ?>
        },
        "publisher": {
            "@type": "Organization",
            "name": "RW 7 Kelurahan Temas",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/assets/images/logo-rw7.jpg"
            }
        },
        "description": <?php echo json_encode($page_description); ?>,
        <?php if (!empty($article['gambar'])): ?>
        "image": "<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/assets/images/news/<?php echo $article['gambar']; ?>",
        <?php endif; ?>
        "url": "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
    };

    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(structuredData);
    document.head.appendChild(script);
    </script>

<?php include 'templates/footer.php'; ?>