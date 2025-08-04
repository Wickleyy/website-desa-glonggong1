<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>RW 7 Kelurahan Temas</h3>
                    <p>Komunitas bersih, berbudaya, dan berdaya di Kota Batu, Jawa Timur.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">📘 Facebook</a>
                        <a href="#" class="social-link">📷 Instagram</a>
                        <a href="#" class="social-link">💬 WhatsApp</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="tentang.php">Tentang RW 7</a></li>
                        <li><a href="program.php">Program Kami</a></li>
                        <li><a href="umkm.php">Potensi UMKM</a></li>
                        <li><a href="berita.php">Berita</a></li>
                        <li><a href="kontak.php">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p>📍 Desa Glonggong/Kelurahan Temas<br>
                    Kecamatan Batu, Kota Batu<br>
                    Provinsi Jawa Timur</p>
                    <p>📱 +62 813-3456-7890<br>
                    📧 rw7temas@gmail.com</p>
                </div>
                <div class="footer-section">
                    <h4>Statistik</h4>
                    <p>🏠 150+ Rumah Tangga<br>
                    🏢 10+ UMKM Aktif<br>
                    🌱 25+ Tahun Berdiri<br>
                    🤝 100% Partisipasi Warga</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> RW 7 Kelurahan Temas - Komunitas Bersih, Berbudaya, dan Berdaya</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    
    <!-- Additional Scripts -->
    <?php if (isset($additional_scripts)): ?>
        <?php echo $additional_scripts; ?>
    <?php endif; ?>
</body>
</html>