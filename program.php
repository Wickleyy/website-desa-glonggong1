<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Program Kami';
$page_description = 'Dokumentasi program kolaborasi mahasiswa UMM dengan RW 7 Kelurahan Temas dalam pemberdayaan masyarakat.';

// Database connection
$database = new Database();
$conn = $database->connect();

// Get programs data
try {
    $stmt = $conn->prepare("SELECT * FROM program ORDER BY tanggal_mulai DESC");
    $stmt->execute();
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $programs = [];
}

// Get gallery images for programs
try {
    $gallery_stmt = $conn->prepare("SELECT * FROM galeri WHERE kategori = 'program' ORDER BY created_at DESC");
    $gallery_stmt->execute();
    $gallery_images = $gallery_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $gallery_images = [];
}

include 'templates/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Album Kegiatan Pengabdian Masyarakat</h1>
            <p>Dokumentasi program kolaborasi mahasiswa UMM dengan RW 7 Kelurahan Temas</p>
        </div>
    </section>

    <!-- Programs Timeline -->
    <section class="programs">
        <div class="container">
            <div class="timeline">
                <?php if (!empty($programs)): ?>
                    <?php foreach ($programs as $index => $program): ?>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span><?php echo format_date($program['tanggal_mulai'], 'M Y'); ?></span>
                        </div>
                        <div class="timeline-content">
                            <div class="program-card">
                                <div class="program-header">
                                    <h2><?php echo htmlspecialchars($program['nama_program']); ?></h2>
                                    <div class="program-status status-<?php echo $program['status']; ?>">
                                        <?php 
                                        $status_labels = [
                                            'akan_datang' => 'Akan Datang',
                                            'berlangsung' => 'Berlangsung', 
                                            'selesai' => 'Selesai'
                                        ];
                                        echo $status_labels[$program['status']] ?? ucfirst($program['status']);
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="program-meta">
                                    <span class="program-date">
                                        📅 <?php echo format_date($program['tanggal_mulai']); ?>
                                        <?php if ($program['tanggal_selesai'] && $program['tanggal_selesai'] != $program['tanggal_mulai']): ?>
                                            - <?php echo format_date($program['tanggal_selesai']); ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <p class="program-description">
                                    <?php echo nl2br(htmlspecialchars($program['deskripsi'])); ?>
                                </p>
                                
                                <?php if (!empty($program['gambar'])): ?>
                                <div class="program-featured-image">
                                    <img src="assets/images/programs/<?php echo $program['gambar']; ?>" 
                                         alt="<?php echo htmlspecialchars($program['nama_program']); ?>">
                                </div>
                                <?php endif; ?>
                                
                                <!-- Sample gallery for each program -->
                                <div class="program-gallery">
                                    <?php
                                    // Sample images based on program type
                                    $sample_images = [
                                        'Program Kemandirian Pangan (KRPL)' => [
                                            'kemandirian-pangan-1.jpg',
                                            'kemandirian-pangan-2.jpg', 
                                            'kemandirian-pangan-3.jpg',
                                            'kemandirian-pangan-4.jpg'
                                        ],
                                        'Program Digitalisasi Komunitas' => [
                                            'digitalisasi-1.jpg',
                                            'digitalisasi-2.jpg',
                                            'digitalisasi-3.jpg',
                                            'digitalisasi-4.jpg'
                                        ],
                                        'Program Pengelolaan Lingkungan Terpadu' => [
                                            'lingkungan-1.jpg',
                                            'lingkungan-2.jpg',
                                            'lingkungan-3.jpg', 
                                            'lingkungan-4.jpg'
                                        ]
                                    ];
                                    
                                    $program_images = $sample_images[$program['nama_program']] ?? [
                                        'default-program-1.jpg',
                                        'default-program-2.jpg',
                                        'default-program-3.jpg',
                                        'default-program-4.jpg'
                                    ];
                                    ?>
                                    
                                    <?php foreach ($program_images as $image): ?>
                                        <img src="assets/images/programs/gallery/<?php echo $image; ?>" 
                                             alt="<?php echo htmlspecialchars($program['nama_program']); ?>"
                                             class="gallery-thumb">
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="program-impact">
                                    <h4>Dampak Program:</h4>
                                    <ul>
                                        <?php
                                        // Sample impacts based on program
                                        $impacts = [
                                            'Program Kemandirian Pangan (KRPL)' => [
                                                '150 polybag tanaman sayuran telah dibagikan',
                                                'Peningkatan konsumsi sayuran segar keluarga',
                                                'Penghematan belanja sayuran hingga 30%',
                                                'Pemanfaatan lahan pekarangan yang optimal'
                                            ],
                                            'Program Digitalisasi Komunitas' => [
                                                'Website profil komunitas yang informatif',
                                                'Peningkatan literasi digital warga 60%',
                                                'Promosi potensi UMKM secara online',
                                                'Networking dengan komunitas lain'
                                            ],
                                            'Program Pengelolaan Lingkungan Terpadu' => [
                                                'Pengurangan sampah ke TPA sebesar 40%',
                                                'Produksi kompos organik untuk komunitas',
                                                'Peningkatan kesadaran lingkungan warga',
                                                'Terciptanya lingkungan yang lebih bersih'
                                            ]
                                        ];
                                        
                                        $program_impacts = $impacts[$program['nama_program']] ?? [
                                            'Peningkatan partisipasi warga dalam program',
                                            'Pemberdayaan ekonomi masyarakat',
                                            'Penguatan kapasitas komunitas',
                                            'Peningkatan kualitas hidup warga'
                                        ];
                                        
                                        foreach ($program_impacts as $impact):
                                        ?>
                                            <li><?php echo $impact; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <?php if ($program['status'] == 'berlangsung'): ?>
                                <div class="program-progress">
                                    <h4>Progress Saat Ini:</h4>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 65%"></div>
                                    </div>
                                    <p class="progress-text">65% selesai</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-programs">
                        <p>Belum ada program yang tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Partnership Section -->
    <section class="partnership">
        <div class="container">
            <h2 class="section-title">Kemitraan Program</h2>
            <div class="partnership-content">
                <p>
                    Program-program PMM ini merupakan hasil kolaborasi yang solid antara 
                    mahasiswa Universitas Muhammadiyah Malang dengan masyarakat RW 7 Kelurahan Temas. 
                    Setiap program dirancang berdasarkan analisis kebutuhan komunitas dan 
                    potensi yang dimiliki.
                </p>
                <div class="partnership-stats">
                    <div class="stat-item">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Mahasiswa Terlibat</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($programs); ?></div>
                        <div class="stat-label">Program Utama</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Warga Terdampak</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Bulan Pelaksanaan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <?php if (!empty($gallery_images)): ?>
    <section class="program-gallery-section">
        <div class="container">
            <h2 class="section-title">Dokumentasi Kegiatan</h2>
            <div class="gallery-grid">
                <?php foreach ($gallery_images as $image): ?>
                <div class="gallery-item" onclick="openLightbox('<?php echo $image['gambar']; ?>', '<?php echo htmlspecialchars($image['judul']); ?>')">
                    <img src="assets/images/gallery/<?php echo $image['gambar']; ?>" 
                         alt="<?php echo htmlspecialchars($image['judul']); ?>">
                    <div class="gallery-overlay">
                        <p><?php echo htmlspecialchars($image['judul']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ingin Berkolaborasi dengan RW 7?</h2>
                <p>Kami terbuka untuk kolaborasi dalam berbagai program pemberdayaan masyarakat dan pengembangan komunitas.</p>
                <a href="kontak.php?subject=Kerjasama" class="btn-primary">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <div class="lightbox-content" onclick="event.stopPropagation()">
            <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
            <img id="lightbox-image" src="" alt="">
            <div id="lightbox-caption" class="lightbox-caption"></div>
        </div>
    </div>

    <style>
    .program-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .program-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
    }

    .status-berlangsung {
        background: #fff3cd;
        color: #856404;
    }

    .status-akan_datang {
        background: #d1ecf1;
        color: #0c5460;
    }

    .program-meta {
        margin-bottom: 1.5rem;
        color: #666;
    }

    .program-featured-image {
        margin: 1.5rem 0;
        border-radius: 10px;
        overflow: hidden;
    }

    .program-featured-image img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .gallery-thumb {
        cursor: pointer;
        transition: transform 0.3s;
    }

    .gallery-thumb:hover {
        transform: scale(1.05);
    }

    .program-progress {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin: 0.5rem 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #FFD700, #FFA500);
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        text-align: center;
    }

    .lightbox-content img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 10px;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-caption {
        color: white;
        margin-top: 1rem;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .program-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .program-gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>

    <script>
    function openLightbox(imageSrc, caption) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxCaption = document.getElementById('lightbox-caption');
        
        lightboxImage.src = 'assets/images/gallery/' + imageSrc;
        lightboxCaption.textContent = caption;
        lightbox.classList.add('active');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }

    // Close lightbox with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
    </script>

<?php include 'templates/footer.php'; ?>