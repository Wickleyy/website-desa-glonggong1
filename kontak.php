<?php
require_once 'config/database.php';

// Page metadata
$page_title = 'Informasi Kontak';
$page_description = 'Hubungi kami untuk informasi lebih lanjut tentang RW 7 Kelurahan Temas atau kunjungan ke komunitas kami.';

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $conn = $database->connect();
    
    $nama = sanitize_input($_POST['nama']);
    $email = sanitize_input($_POST['email']);
    $telepon = sanitize_input($_POST['telepon']);
    $subjek = sanitize_input($_POST['subjek']);
    $pesan = sanitize_input($_POST['pesan']);
    
    // Validation
    if (empty($nama) || empty($email) || empty($pesan)) {
        $error_message = 'Mohon lengkapi semua field yang wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Format email tidak valid.';
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO kontak (nama, email, telepon, subjek, pesan) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$nama, $email, $telepon, $subjek, $pesan]);
            
            if ($result) {
                $success_message = 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.';
                // Clear form data
                $_POST = [];
            } else {
                $error_message = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
            }
        } catch(PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi nanti.';
        }
    }
}

// Pre-fill subject if provided in URL
$default_subject = isset($_GET['subject']) ? sanitize_input($_GET['subject']) : '';

include 'templates/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Informasi Kontak</h1>
            <p>Hubungi kami untuk informasi lebih lanjut tentang RW 7 Kelurahan Temas</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Alamat Lengkap</h2>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <p><strong>RW 7 Kelurahan Temas</strong></p>
                            <p>Desa Glonggong/Kelurahan Temas</p>
                            <p>Kecamatan Batu, Kota Batu</p>
                            <p>Provinsi Jawa Timur, Indonesia</p>
                            <p>Kode Pos: 65311</p>
                        </div>
                    </div>
                    
                    <h2>Kontak Pengurus</h2>
                    <div class="contact-item">
                        <div class="contact-icon">👤</div>
                        <div class="contact-details">
                            <p><strong>Ketua RW 7</strong></p>
                            <p>Bapak Sutrisno</p>
                            <p>📱 <a href="https://wa.me/6281334567890">+62 813-3456-7890</a></p>
                            <p>📧 <a href="mailto:rw7temas@gmail.com">rw7temas@gmail.com</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">🏢</div>
                        <div class="contact-details">
                            <p><strong>Sekretaris RW 7</strong></p>
                            <p>Ibu Siti Aminah</p>
                            <p>📱 <a href="https://wa.me/6281234567891">+62 812-3456-7891</a></p>
                        </div>
                    </div>

                    <h2>Media Sosial</h2>
                    <div class="social-links">
                        <a href="#" class="social-link" target="_blank">📘 Facebook RW 7 Temas</a>
                        <a href="#" class="social-link" target="_blank">📷 Instagram @rw7temas</a>
                        <a href="https://wa.me/6281334567890" class="social-link" target="_blank">💬 WhatsApp Pengurus</a>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <h2>Kirim Pesan</h2>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success">
                            <div class="alert-icon">✅</div>
                            <div class="alert-message"><?php echo $success_message; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-error">
                            <div class="alert-icon">❌</div>
                            <div class="alert-message"><?php echo $error_message; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <form class="contact-form" method="POST" action="">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" 
                                   value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telepon">Nomor Telepon</label>
                            <input type="tel" id="telepon" name="telepon" 
                                   value="<?php echo isset($_POST['telepon']) ? htmlspecialchars($_POST['telepon']) : ''; ?>" 
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        
                        <div class="form-group">
                            <label for="subjek">Subjek</label>
                            <select id="subjek" name="subjek">
                                <option value="">Pilih subjek...</option>
                                <option value="Informasi Umum" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Informasi Umum') || $default_subject == 'Informasi Umum' ? 'selected' : ''; ?>>Informasi Umum</option>
                                <option value="Daftar UMKM" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Daftar UMKM') || $default_subject == 'Daftar UMKM' ? 'selected' : ''; ?>>Pendaftaran UMKM</option>
                                <option value="Kunjungan" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Kunjungan') || $default_subject == 'Kunjungan' ? 'selected' : ''; ?>>Rencana Kunjungan</option>
                                <option value="Kerjasama" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Kerjasama') || $default_subject == 'Kerjasama' ? 'selected' : ''; ?>>Kerjasama/Partnership</option>
                                <option value="Saran" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Saran') || $default_subject == 'Saran' ? 'selected' : ''; ?>>Saran & Masukan</option>
                                <option value="Lainnya" <?php echo (isset($_POST['subjek']) && $_POST['subjek'] == 'Lainnya') || $default_subject == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="pesan">Pesan *</label>
                            <textarea id="pesan" name="pesan" rows="5" required 
                                      placeholder="Tuliskan pesan Anda di sini..."><?php echo isset($_POST['pesan']) ? htmlspecialchars($_POST['pesan']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn-primary btn-submit">
                            <span class="btn-text">Kirim Pesan</span>
                            <span class="btn-icon">📤</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2 class="section-title">Lokasi RW 7</h2>
            <div class="map-container">
                <div class="map-embed">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15805.543!2d112.52!3d-7.87!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7027c6b4ff0c7%3A0x123456789abcdef!2sTemas%2C%20Batu%2C%20Kota%20Batu%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1234567890"
                        width="100%" 
                        height="400" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <p class="map-note">
                    <em>Catatan: Koordinat peta adalah perkiraan umum Kelurahan Temas. 
                    Silakan hubungi kontak di atas untuk lokasi spesifik RW 7.</em>
                </p>
            </div>
        </div>
    </section>

    <!-- Visit Info -->
    <section class="visit-info">
        <div class="container">
            <h2 class="section-title">Berkunjung ke RW 7</h2>
            <div class="visit-content">
                <p>
                    RW 7 Kelurahan Temas selalu terbuka untuk kunjungan dari berbagai pihak 
                    yang ingin belajar tentang pengelolaan komunitas, kehidupan masyarakat desa, 
                    atau sekedar melihat keindahan alam dan budaya lokal.
                </p>
                <div class="visit-tips">
                    <div class="tip-item">
                        <h4>📅 Waktu Terbaik Berkunjung</h4>
                        <p>Senin - Jumat: 08.00 - 16.00 WIB<br>
                        Sabtu - Minggu: 09.00 - 15.00 WIB<br>
                        <em>Hindari kunjungan saat hujan deras</em></p>
                    </div>
                    <div class="tip-item">
                        <h4>🚗 Transportasi</h4>
                        <p>Dapat diakses dengan kendaraan pribadi atau transportasi umum. 
                        Dari pusat Kota Batu sekitar 15 menit berkendara. 
                        Tersedia area parkir yang memadai.</p>
                    </div>
                    <div class="tip-item">
                        <h4>📞 Konfirmasi Kunjungan</h4>
                        <p>Untuk kunjungan rombongan atau keperluan khusus, 
                        mohon hubungi pengurus terlebih dahulu minimal 2 hari sebelumnya 
                        untuk koordinasi yang lebih baik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">Layanan Komunitas</h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-icon">🏠</div>
                    <h3>Administrasi Warga</h3>
                    <p>Pelayanan surat-menyurat dan administrasi kependudukan untuk warga RW 7</p>
                </div>
                <div class="service-item">
                    <div class="service-icon">🌱</div>
                    <h3>Pengelolaan Lingkungan</h3>
                    <p>Program kebersihan, pengelolaan sampah, dan pelestarian lingkungan hidup</p>
                </div>
                <div class="service-item">
                    <div class="service-icon">🤝</div>
                    <h3>Kegiatan Sosial</h3>
                    <p>Koordinasi gotong royong, arisan, dan kegiatan kemasyarakatan lainnya</p>
                </div>
                <div class="service-item">
                    <div class="service-icon">🛡️</div>
                    <h3>Keamanan Lingkungan</h3>
                    <p>Sistem keamanan lingkungan dan koordinasi dengan pihak keamanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <h2 class="section-title">Pertanyaan Umum</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Bagaimana cara mendaftar UMKM di RW 7?</h4>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Anda dapat mendaftar UMKM dengan menghubungi pengurus RW 7 melalui kontak yang tersedia atau mengisi form kontak dengan subjek "Daftar UMKM". Tim kami akan membantu proses pendaftaran dan promosi usaha Anda.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Apakah ada biaya untuk berkunjung ke RW 7?</h4>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Tidak ada biaya untuk berkunjung ke RW 7. Namun, untuk kunjungan rombongan atau study tour, mohon koordinasi terlebih dahulu dengan pengurus untuk persiapan yang lebih baik.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Bagaimana cara bergabung dalam kegiatan komunitas?</h4>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Jika Anda berdomisili di wilayah RW 7, Anda otomatis menjadi bagian dari komunitas. Untuk warga luar yang ingin berpartisipasi, silakan hubungi pengurus untuk informasi kegiatan yang terbuka untuk umum.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* Additional styles for contact page */
    .contact-form-container {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 15px;
    }
    
    .contact-form .form-group {
        margin-bottom: 1.5rem;
    }
    
    .contact-form label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #1e3c72;
    }
    
    .contact-form input,
    .contact-form select,
    .contact-form textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    
    .contact-form input:focus,
    .contact-form select:focus,
    .contact-form textarea:focus {
        outline: none;
        border-color: #FFD700;
    }
    
    .btn-submit {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .faq-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .faq-question {
        padding: 1.5rem;
        background: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
    }
    
    .faq-question:hover {
        background: #f8f9fa;
    }
    
    .faq-question h4 {
        margin: 0;
        color: #1e3c72;
    }
    
    .faq-toggle {
        font-size: 1.5rem;
        font-weight: bold;
        color: #FFD700;
    }
    
    .faq-answer {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s, padding 0.3s;
    }
    
    .faq-item.active .faq-answer {
        padding: 1.5rem;
        max-height: 200px;
    }
    
    .faq-item.active .faq-toggle {
        transform: rotate(45deg);
    }
    </style>

    <script>
    // FAQ Toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all FAQ items
                faqItems.forEach(faq => faq.classList.remove('active'));
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    });
    </script>

<?php include 'templates/footer.php'; ?>