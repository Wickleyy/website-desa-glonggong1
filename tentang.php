<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Tentang RW 7';
$page_description = 'Mengenal lebih dekat sejarah, keunggulan, dan budaya gotong royong yang menjadi kekuatan RW 7 Kelurahan Temas.';

// Database connection
$database = new Database();
$conn = $database->connect();

// Get some statistics for the about page
try {
    $umkm_stmt = $conn->prepare("SELECT COUNT(*) as total FROM umkm WHERE status = 'aktif'");
    $umkm_stmt->execute();
    $total_umkm = $umkm_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $program_stmt = $conn->prepare("SELECT COUNT(*) as total FROM program");
    $program_stmt->execute();
    $total_program = $program_stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch(PDOException $e) {
    $total_umkm = 10;
    $total_program = 4;
}

include 'templates/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Mengenal Lebih Dekat RW 7 Kelurahan Temas</h1>
            <p>Sejarah, keunggulan, dan budaya yang membentuk identitas komunitas kami</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="content-grid">
                <div class="content-text">
                    <h2>Sejarah dan Identitas RW 7</h2>
                    <p>
                        RW 7 Kelurahan Temas merupakan salah satu rukun warga yang terletak di Desa Glonggong, 
                        Kecamatan Batu, Kota Batu, Jawa Timur. Komunitas ini telah lama dikenal sebagai contoh 
                        dalam penerapan nilai-nilai gotong royong dan kepedulian terhadap lingkungan.
                    </p>
                    <p>
                        Dengan latar belakang masyarakat yang mayoritas berprofesi sebagai petani dan pedagang, 
                        RW 7 telah berkembang menjadi komunitas yang mandiri dan progresif, selalu terbuka 
                        terhadap inovasi untuk meningkatkan kesejahteraan warga.
                    </p>
                    <p>
                        Sejak didirikan lebih dari 25 tahun yang lalu, RW 7 telah melalui berbagai transformasi 
                        dan terus beradaptasi dengan perkembangan zaman tanpa meninggalkan nilai-nilai luhur 
                        yang menjadi fondasi komunitas.
                    </p>
                </div>
                <div class="content-image">
                    <img src="assets/images/about/sejarah-rw7.jpg" alt="Sejarah RW 7">
                </div>
            </div>
        </div>
    </section>

    <!-- Excellence Section -->
    <section class="excellence">
        <div class="container">
            <h2 class="section-title">Keunggulan Kami</h2>
            <div class="excellence-grid">
                <div class="excellence-item">
                    <div class="excellence-icon">🌱</div>
                    <h3>Pengelolaan Sampah Terpadu</h3>
                    <p>
                        RW 7 telah menerapkan sistem pengelolaan sampah yang terintegrasi dengan pemilahan 
                        dari sumber, pengomposan, dan daur ulang yang melibatkan seluruh warga.
                    </p>
                    <div class="excellence-stats">
                        <span class="stat">40% pengurangan sampah ke TPA</span>
                    </div>
                </div>
                <div class="excellence-item">
                    <div class="excellence-icon">🤝</div>
                    <h3>Budaya Gotong Royong</h3>
                    <p>
                        Tradisi kerja bakti dan saling membantu antar warga masih terjaga dengan baik, 
                        menjadi kekuatan utama dalam setiap program pembangunan komunitas.
                    </p>
                    <div class="excellence-stats">
                        <span class="stat">100% partisipasi warga</span>
                    </div>
                </div>
                <div class="excellence-item">
                    <div class="excellence-icon">🏡</div>
                    <h3>Lingkungan Bersih</h3>
                    <p>
                        Komitmen menjaga kebersihan lingkungan telah menjadikan RW 7 sebagai contoh 
                        dalam penerapan pola hidup sehat dan ramah lingkungan.
                    </p>
                    <div class="excellence-stats">
                        <span class="stat">Juara kebersihan tingkat kecamatan</span>
                    </div>
                </div>
                <div class="excellence-item">
                    <div class="excellence-icon">💡</div>
                    <h3>Inovasi Berkelanjutan</h3>
                    <p>
                        Keterbukaan terhadap teknologi dan program-program baru, termasuk kolaborasi 
                        dengan mahasiswa untuk pengembangan komunitas yang berkelanjutan.
                    </p>
                    <div class="excellence-stats">
                        <span class="stat"><?php echo $total_program; ?> program inovasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tradition Section -->
    <section class="tradition">
        <div class="container">
            <div class="content-grid reverse">
                <div class="content-image">
                    <img src="assets/images/about/tradisi-selamatan.jpg" alt="Selamatan Desa">
                </div>
                <div class="content-text">
                    <h2>Tradisi dan Budaya</h2>
                    <h3>Selamatan Desa Temas</h3>
                    <p>
                        Setiap tahun, RW 7 aktif berpartisipasi dalam tradisi Selamatan Desa Temas 
                        yang merupakan wujud syukur dan doa bersama untuk keselamatan dan kemakmuran desa. 
                        Acara ini menjadi momentum penting dalam memperkuat ikatan sosial antar warga.
                    </p>
                    <p>
                        Tradisi ini tidak hanya melestarikan budaya lokal, tetapi juga menjadi sarana 
                        untuk mempererat hubungan antarwarga, berbagi informasi pembangunan, dan 
                        merencanakan program-program komunitas ke depan.
                    </p>
                    
                    <h3>Kegiatan Rutin Komunitas</h3>
                    <ul class="tradition-list">
                        <li>🗓️ Arisan bulanan RT dan RW</li>
                        <li>🧹 Kerja bakti mingguan</li>
                        <li>🎯 Senam sehat setiap minggu</li>
                        <li>📚 Pengajian dan kegiatan keagamaan</li>
                        <li>🎉 Perayaan hari besar nasional</li>
                        <li>🌾 Festival panen dan hasil bumi</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership">
        <div class="container">
            <h2 class="section-title">Struktur Pengurus</h2>
            <div class="leadership-grid">
                <div class="leader-card">
                    <div class="leader-photo">
                        <img src="assets/images/leaders/ketua-rw.jpg" alt="Ketua RW 7">
                    </div>
                    <div class="leader-info">
                        <h3>Bapak Sutrisno</h3>
                        <p class="leader-position">Ketua RW 7</p>
                        <p class="leader-description">
                            Memimpin RW 7 dengan visi membangun komunitas yang mandiri dan berkelanjutan. 
                            Aktif dalam berbagai program pemberdayaan masyarakat.
                        </p>
                        <div class="leader-contact">
                            <a href="https://wa.me/6281334567890">📱 Hubungi</a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card">
                    <div class="leader-photo">
                        <img src="assets/images/leaders/sekretaris-rw.jpg" alt="Sekretaris RW 7">
                    </div>
                    <div class="leader-info">
                        <h3>Ibu Siti Aminah</h3>
                        <p class="leader-position">Sekretaris RW 7</p>
                        <p class="leader-description">
                            Mengelola administrasi dan dokumentasi kegiatan RW 7. 
                            Koordinator program pemberdayaan perempuan dan UMKM.
                        </p>
                        <div class="leader-contact">
                            <a href="https://wa.me/6281234567891">📱 Hubungi</a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card">
                    <div class="leader-photo">
                        <img src="assets/images/leaders/bendahara-rw.jpg" alt="Bendahara RW 7">
                    </div>
                    <div class="leader-info">
                        <h3>Bapak Ahmad Supriyanto</h3>
                        <p class="leader-position">Bendahara RW 7</p>
                        <p class="leader-description">
                            Mengelola keuangan komunitas dengan transparan. 
                            Aktif dalam program koperasi dan simpan pinjam warga.
                        </p>
                        <div class="leader-contact">
                            <a href="https://wa.me/6281234567892">📱 Hubungi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Mission -->
    <section class="vision-mission">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-item">
                    <div class="vm-icon">🎯</div>
                    <h3>Visi Komunitas</h3>
                    <p>
                        Menjadi komunitas yang mandiri, bersih, dan sejahtera dengan tetap 
                        melestarikan nilai-nilai budaya dan kearifan lokal yang dimiliki, 
                        serta menjadi contoh bagi komunitas lain dalam pembangunan berkelanjutan.
                    </p>
                </div>
                <div class="vm-item">
                    <div class="vm-icon">🚀</div>
                    <h3>Misi Komunitas</h3>
                    <ul>
                        <li>Menjaga kebersihan dan kelestarian lingkungan hidup</li>
                        <li>Mengembangkan potensi ekonomi warga melalui UMKM</li>
                        <li>Melestarikan budaya gotong royong dan tradisi lokal</li>
                        <li>Meningkatkan kualitas hidup melalui inovasi dan teknologi</li>
                        <li>Membangun kemitraan strategis dengan berbagai pihak</li>
                        <li>Menciptakan generasi muda yang berkarakter dan berdaya saing</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="achievements">
        <div class="container">
            <h2 class="section-title">Pencapaian dan Penghargaan</h2>
            <div class="achievements-grid">
                <div class="achievement-item">
                    <div class="achievement-icon">🏆</div>
                    <h4>Juara 1 Kebersihan Lingkungan</h4>
                    <p>Tingkat Kecamatan Batu - 2023</p>
                </div>
                <div class="achievement-item">
                    <div class="achievement-icon">🌟</div>
                    <h4>RW Percontohan Pengelolaan Sampah</h4>
                    <p>Dinas Lingkungan Hidup Kota Batu - 2022</p>
                </div>
                <div class="achievement-item">
                    <div class="achievement-icon">🥇</div>
                    <h4>Kampung KB Teladan</h4>
                    <p>BKKBN Provinsi Jawa Timur - 2023</p>
                </div>
                <div class="achievement-item">
                    <div class="achievement-icon">🎖️</div>
                    <h4>Komunitas Digital Terbaik</h4>
                    <p>Kominfo Kota Batu - 2024</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="about-statistics">
        <div class="container">
            <h2 class="section-title">RW 7 dalam Angka</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Rumah Tangga</div>
                    <div class="stat-description">Keluarga yang tergabung dalam RW 7</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Tahun Berdiri</div>
                    <div class="stat-description">Pengalaman membangun komunitas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_umkm; ?>+</div>
                    <div class="stat-label">UMKM Aktif</div>
                    <div class="stat-description">Usaha yang diberdayakan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Partisipasi Warga</div>
                    <div class="stat-description">Keterlibatan dalam kegiatan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">40%</div>
                    <div class="stat-label">Pengurangan Sampah</div>
                    <div class="stat-description">Efisiensi pengelolaan sampah</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Program Aktif</div>
                    <div class="stat-description">Kegiatan pemberdayaan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Bergabunglah dengan Komunitas Kami</h2>
                <p>Mari bersama-sama membangun masa depan yang lebih baik untuk generasi mendatang.</p>
                <div class="cta-buttons">
                    <a href="kontak.php" class="btn-primary">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <style>
    .excellence-stats {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #FFD700;
    }

    .excellence-stats .stat {
        background: #f8f9fa;
        color: #1e3c72;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .tradition-list {
        list-style: none;
        padding: 0;
        margin-top: 1.5rem;
    }

    .tradition-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 1rem;
    }

    .tradition-list li:last-child {
        border-bottom: none;
    }

    .leadership {
        padding: 5rem 0;
        background: #f8f9fa;
    }

    .leadership-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .leader-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .leader-card:hover {
        transform: translateY(-5px);
    }

    .leader-photo {
        height: 200px;
        overflow: hidden;
    }

    .leader-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .leader-info {
        padding: 2rem;
    }

    .leader-info h3 {
        color: #1e3c72;
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
    }

    .leader-position {
        color: #FFD700;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .leader-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .leader-contact a {
        background: #25d366;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .leader-contact a:hover {
        background: #128c7e;
    }

    .vm-icon {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .achievements {
        padding: 5rem 0;
        background: white;
    }

    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .achievement-item {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .achievement-item:hover {
        transform: translateY(-5px);
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .achievement-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .achievement-item h4 {
        color: #1e3c72;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .achievement-item p {
        color: #666;
        font-size: 0.9rem;
    }

    .about-statistics {
        padding: 5rem 0;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
    }

    .about-statistics .section-title {
        color: white;
    }

    .about-statistics .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }

    .about-statistics .stat-item {
        text-align: center;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .about-statistics .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #FFD700;
        margin-bottom: 0.5rem;
    }

    .about-statistics .stat-label {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-description {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .leadership-grid,
        .achievements-grid {
            grid-template-columns: 1fr;
        }

        .about-statistics .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 480px) {
        .about-statistics .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

<?php include 'templates/footer.php'; ?>