<?php
// admin/index.php
session_start();
require_once '../config/database.php';

// Simple authentication (you should implement proper authentication)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$database = new Database();
$conn = $database->connect();

// Get statistics
try {
    // Total UMKM
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM umkm");
    $stmt->execute();
    $total_umkm = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Active UMKM
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM umkm WHERE status = 'aktif'");
    $stmt->execute();
    $active_umkm = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total Berita
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM berita");
    $stmt->execute();
    $total_berita = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Published Berita
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM berita WHERE status = 'published'");
    $stmt->execute();
    $published_berita = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total Messages
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM kontak");
    $stmt->execute();
    $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Unread Messages
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM kontak WHERE status = 'baru'");
    $stmt->execute();
    $unread_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Recent Messages
    $stmt = $conn->prepare("SELECT * FROM kontak ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent UMKM
    $stmt = $conn->prepare("SELECT * FROM umkm ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recent_umkm = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error_message = "Error loading dashboard data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RW 7 Temas</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>RW 7 Admin</h2>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="index.php" class="active">📊 Dashboard</a></li>
                    <li><a href="umkm.php">🏪 UMKM</a></li>
                    <li><a href="berita.php">📰 Berita</a></li>
                    <li><a href="program.php">📋 Program</a></li>
                    <li><a href="kontak.php">📬 Pesan</a></li>
                    <li><a href="galeri.php">🖼️ Galeri</a></li>
                    <li><a href="logout.php">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Selamat datang, Admin!</span>
                </div>
            </header>

            <div class="admin-content">
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🏪</div>
                        <div class="stat-info">
                            <h3><?php echo $total_umkm; ?></h3>
                            <p>Total UMKM</p>
                            <small><?php echo $active_umkm; ?> aktif</small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📰</div>
                        <div class="stat-info">
                            <h3><?php echo $total_berita; ?></h3>
                            <p>Total Berita</p>
                            <small><?php echo $published_berita; ?> published</small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📬</div>
                        <div class="stat-info">
                            <h3><?php echo $total_messages; ?></h3>
                            <p>Total Pesan</p>
                            <small><?php echo $unread_messages; ?> belum dibaca</small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-info">
                            <h3>150</h3>
                            <p>Rumah Tangga</p>
                            <small>Total warga</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h2>Aksi Cepat</h2>
                    <div class="action-buttons">
                        <a href="umkm.php?action=add" class="action-btn">
                            <span class="btn-icon">➕</span>
                            <span>Tambah UMKM</span>
                        </a>
                        <a href="berita.php?action=add" class="action-btn">
                            <span class="btn-icon">📝</span>
                            <span>Tulis Berita</span>
                        </a>
                        <a href="program.php?action=add" class="action-btn">
                            <span class="btn-icon">📋</span>
                            <span>Tambah Program</span>
                        </a>
                        <a href="kontak.php" class="action-btn">
                            <span class="btn-icon">📬</span>
                            <span>Lihat Pesan</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="dashboard-grid">
                    <!-- Recent Messages -->
                    <div class="dashboard-section">
                        <h2>Pesan Terbaru</h2>
                        <div class="recent-items">
                            <?php if (!empty($recent_messages)): ?>
                                <?php foreach ($recent_messages as $message): ?>
                                <div class="recent-item">
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($message['nama']); ?></h4>
                                        <p><?php echo get_excerpt($message['pesan'], 60); ?></p>
                                        <small><?php echo format_date($message['created_at']); ?></small>
                                    </div>
                                    <div class="item-status status-<?php echo $message['status']; ?>">
                                        <?php echo ucfirst($message['status']); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <a href="kontak.php" class="view-all">Lihat Semua Pesan →</a>
                            <?php else: ?>
                                <p class="no-data">Belum ada pesan terbaru</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent UMKM -->
                    <div class="dashboard-section">
                        <h2>UMKM Terbaru</h2>
                        <div class="recent-items">
                            <?php if (!empty($recent_umkm)): ?>
                                <?php foreach ($recent_umkm as $umkm): ?>
                                <div class="recent-item">
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($umkm['nama_usaha']); ?></h4>
                                        <p><?php echo htmlspecialchars($umkm['pemilik']); ?></p>
                                        <small><?php echo ucfirst($umkm['kategori']); ?> • <?php echo format_date($umkm['created_at']); ?></small>
                                    </div>
                                    <div class="item-status status-<?php echo $umkm['status']; ?>">
                                        <?php echo ucfirst($umkm['status']); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <a href="umkm.php" class="view-all">Lihat Semua UMKM →</a>
                            <?php else: ?>
                                <p class="no-data">Belum ada UMKM terdaftar</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="system-info">
                    <h2>Informasi Sistem</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Last Login:</strong>
                            <span><?php echo date('d M Y H:i'); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>PHP Version:</strong>
                            <span><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Database:</strong>
                            <span>MySQL</span>
                        </div>
                        <div class="info-item">
                            <strong>Server:</strong>
                            <span><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #333;
    }

    .admin-layout {
        display: flex;
        min-height: 100vh;
    }

    .admin-sidebar {
        width: 250px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .admin-logo {
        padding: 2rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .admin-logo h2 {
        color: #FFD700;
        font-size: 1.5rem;
    }

    .admin-nav ul {
        list-style: none;
        padding: 1rem 0;
    }

    .admin-nav li {
        margin-bottom: 0.5rem;
    }

    .admin-nav a {
        display: block;
        padding: 1rem 2rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }

    .admin-nav a:hover,
    .admin-nav a.active {
        background: rgba(255,215,0,0.2);
        border-right: 3px solid #FFD700;
    }

    .admin-main {
        margin-left: 250px;
        flex: 1;
        padding: 0;
    }

    .admin-header {
        background: white;
        padding: 1.5rem 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-header h1 {
        color: #1e3c72;
        font-size: 2rem;
    }

    .admin-user {
        color: #666;
    }

    .admin-content {
        padding: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.8;
    }

    .stat-info h3 {
        font-size: 2.5rem;
        color: #1e3c72;
        margin-bottom: 0.5rem;
    }

    .stat-info p {
        color: #666;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .stat-info small {
        color: #999;
        font-size: 0.85rem;
    }

    .quick-actions {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
    }

    .quick-actions h2 {
        color: #1e3c72;
        margin-bottom: 1.5rem;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .action-btn:hover {
        background: #1e3c72;
        color: white;
        border-color: #1e3c72;
        transform: translateY(-2px);
    }

    .btn-icon {
        font-size: 1.5rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .dashboard-section {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .dashboard-section h2 {
        color: #1e3c72;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }

    .recent-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .item-info h4 {
        color: #1e3c72;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .item-info p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .item-info small {
        color: #999;
        font-size: 0.8rem;
    }

    .item-status {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-baru {
        background: #fff3cd;
        color: #856404;
    }

    .status-dibaca {
        background: #d4edda;
        color: #155724;
    }

    .status-aktif {
        background: #d4edda;
        color: #155724;
    }

    .status-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }

    .view-all {
        display: block;
        text-align: center;
        padding: 1rem;
        color: #1e3c72;
        text-decoration: none;
        font-weight: 500;
        border-top: 1px solid #f0f0f0;
        margin-top: 1rem;
    }

    .view-all:hover {
        background: #f8f9fa;
    }

    .no-data {
        text-align: center;
        color: #999;
        font-style: italic;
        padding: 2rem;
    }

    .system-info {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .system-info h2 {
        color: #1e3c72;
        margin-bottom: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-item strong {
        color: #1e3c72;
    }

    @media (max-width: 768px) {
        .admin-sidebar {
            width: 200px;
        }

        .admin-main {
            margin-left: 200px;
        }

        .admin-header {
            padding: 1rem;
        }

        .admin-content {
            padding: 1rem;
        }

        .stats-grid,
        .action-buttons,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .admin-sidebar {
            width: 100%;
            position: static;
            height: auto;
        }

        .admin-main {
            margin-left: 0;
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }
    }
    </style>

    <script>
    // Simple dashboard functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh stats every 5 minutes
        setInterval(function() {
            // You can implement AJAX refresh here
            console.log('Stats refreshed');
        }, 300000);
        
        // Add some interactivity
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
            });
        });
    });
    </script>
</body>
</html>