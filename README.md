# Website RW 7 Kelurahan Temas

Website resmi RW 7 Kelurahan Temas - Komunitas Bersih, Berbudaya, dan Berdaya di Kota Batu, Jawa Timur.

## 🌟 Fitur Utama

- **Homepage Dinamis**: Menampilkan informasi terbaru tentang RW 7
- **Profil Komunitas**: Sejarah, visi-misi, dan keunggulan RW 7
- **UMKM Directory**: Katalog usaha mikro dan produk warga
- **Sistem Berita**: Platform informasi dan artikel komunitas
- **Program Kegiatan**: Dokumentasi program pemberdayaan masyarakat
- **Form Kontak**: Sistem komunikasi dengan pengurus
- **Admin Panel**: Dashboard untuk mengelola konten
- **Responsive Design**: Optimal di semua perangkat

## 🛠️ Teknologi

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Framework CSS**: Custom responsive framework
- **Icons**: Emoji dan Unicode symbols
- **Fonts**: Google Fonts (Poppins)

## 📁 Struktur Folder

```
rw7-website/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── admin.css
│   ├── js/
│   │   └── script.js
│   └── images/
│       ├── news/
│       ├── umkm/
│       ├── programs/
│       └── gallery/
├── config/
│   └── database.php
├── templates/
│   ├── header.php
│   └── footer.php
├── admin/
│   ├── index.php
│   ├── models/
│   └── views/
├── process/
│   └── contact.php
├── index.php
├── tentang.php
├── umkm.php
├── berita.php
├── berita-detail.php
├── program.php
├── kontak.php
├── database.sql
├── .htaccess
└── README.md
```

## 🚀 Instalasi

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx web server
- mod_rewrite (Apache) atau URL rewriting (Nginx)

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   git clone https://github.com/username/rw7-website.git
   cd rw7-website
   ```

2. **Setup Database**
   ```bash
   # Buat database baru
   CREATE DATABASE rw7_temas;
   
   # Import struktur database
   mysql -u root -p rw7_temas < database.sql
   ```

3. **Konfigurasi Database**
   Edit file `config/database.php`:
   ```php
   private $host = 'localhost';
   private $db_name = 'rw7_temas';
   private $username = 'your_username';
   private $password = 'your_password';
   ```

4. **Setup Permissions**
   ```bash
   # Set permissions untuk folder upload
   chmod 755 assets/images/
   chmod 755 assets/images/news/
   chmod 755 assets/images/umkm/
   chmod 755 assets/images/programs/
   ```

5. **Konfigurasi Web Server**
   - Pastikan mod_rewrite aktif (Apache)
   - Upload file .htaccess ke root directory
   - Set document root ke folder project

6. **Testing**
   - Akses http://your-domain.com
   - Test semua halaman dan fitur
   - Akses admin panel di http://your-domain.com/admin

## 🔐 Admin Panel

### Login Admin
- URL: `/admin/login.php`
- Default credentials akan dibuat saat instalasi

### Fitur Admin
- **Dashboard**: Overview statistik dan aktivitas
- **UMKM Management**: Tambah, edit, hapus data UMKM
- **Berita Management**: Kelola artikel dan berita
- **Program Management**: Dokumentasi program kegiatan
- **Pesan**: Kelola pesan dari form kontak
- **Galeri**: Upload dan organize gambar

## 📝 Penggunaan

### Menambah UMKM Baru
1. Login ke admin panel
2. Pilih menu "UMKM"
3. Klik "Tambah UMKM"
4. Isi form dengan data lengkap
5. Upload gambar produk
6. Simpan data

### Menulis Berita
1. Login ke admin panel
2. Pilih menu "Berita"
3. Klik "Tulis Berita"
4. Masukkan judul, konten, dan kategori
5. Upload gambar featured (opsional)
6. Set status publish/draft
7. Simpan artikel

### Mengelola Kontak
1. Menu "Pesan" untuk melihat semua pesan masuk
2. Tandai sebagai "Dibaca" atau "Ditanggapi"
3. Export data untuk follow-up

## 🎨 Customization

### Mengubah Tema Warna
Edit file `assets/css/style.css`:
```css
:root {
  --primary-color: #1e3c72;    /* Biru utama */
  --secondary-color: #FFD700;   /* Emas */
  --accent-color: #2a5298;     /* Biru aksen */
}
```

### Menambah Halaman Baru
1. Buat file PHP baru (misal: `layanan.php`)
2. Include header dan footer template
3. Tambahkan link di navigation menu
4. Update .htaccess jika perlu clean URL

### Custom Styling
- Gunakan CSS custom di `assets/css/style.css`
- Ikuti konvensi naming yang ada
- Test responsivitas di berbagai device

## 🔧 API Endpoints

### UMKM API
- `GET /api/umkm.php` - List semua UMKM
- `GET /api/umkm.php?kategori=kuliner` - Filter by kategori
- `GET /api/umkm.php?search=keyword` - Search UMKM

### Berita API
- `GET /api/berita.php` - List berita published
- `GET /api/berita.php?slug=judul-berita` - Single artikel

## 🔐 Keamanan

### Best Practices
- Input sanitization untuk semua form
- Prepared statements untuk query database  
- CSRF protection untuk form admin
- File upload validation
- Rate limiting untuk form submission

### File Permissions
```bash
# Folders
chmod 755 assets/
chmod 755 config/
chmod 700 admin/

# Files
chmod 644 *.php
chmod 600 config/database.php
```

## 📱 Mobile Optimization

- Responsive grid system
- Touch-friendly navigation
- Optimized images for mobile
- Fast loading times
- Mobile-first CSS approach

## 🎯 SEO Features

- Clean URL structure
- Meta tags optimization
- Open Graph tags
- Structured data (JSON-LD)
- Sitemap generation
- Image alt tags
- Page speed optimization

## 📊 Analytics

### Google Analytics Setup
1. Tambahkan tracking code di `templates/header.php`
2. Setup Goals untuk form submissions
3. Monitor traffic dan user behavior

### Performance Monitoring
- Page load times
- Database query optimization
- Image compression
- CDN integration (opsional)

## 🔄 Backup & Maintenance

### Database Backup
```bash
# Backup database
mysqldump -u username -p rw7_temas > backup_$(date +%Y%m%d).sql

# Restore database
mysql -u username -p rw7_temas < backup_file.sql
```

### File Backup
- Backup folder assets/ secara berkala
- Backup file konfigurasi
- Versioning untuk major updates

## 🐛 Troubleshooting

### Common Issues

**Error: "Page not found"**
- Check .htaccess file
- Verify mod_rewrite is enabled
- Check file permissions

**Database Connection Error**
- Verify database credentials
- Check MySQL service status
- Confirm database exists

**Images not loading**
- Check file permissions (755 for directories)
- Verify image paths in database
- Check upload directory structure

**Admin login issues**
- Clear browser cache
- Check session configuration
- Verify admin credentials

## 🤝 Contributing

1. Fork the project
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📞 Support

- **Email**: rw7temas@gmail.com
- **WhatsApp**: +62 813-3456-7890
- **Website**: https://rw7temas.com

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🙏 Acknowledgments

- Universitas Muhammadiyah Malang (UMM)
- Mahasiswa PMM UMM
- Warga RW 7 Kelurahan Temas
- Pemerintah Kota Batu

---
**RW 7 Kelurahan Temas - Komunitas Bersih, Berbudaya, dan Berdaya** 🏘️✨

## 📋 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Complete UMKM management system
- ✅ News/article system with rich content
- ✅ Program documentation with timeline
- ✅ Contact form with database storage
- ✅ Responsive admin panel
- ✅ SEO optimization
- ✅ Mobile-first design

### Planned Features (v1.1.0)
- 🔄 Advanced search functionality
- 🔄 User registration system
- 🔄 Online marketplace for UMKM
- 🔄 Event calendar integration
- 🔄 Multi-language support (ID/EN)
- 🔄 Push notifications
- 🔄 Advanced analytics dashboard

## 🎯 Development Roadmap

### Phase 1: Foundation (✅ Complete)
- [x] Basic website structure
- [x] Database design and implementation
- [x] Admin panel development
- [x] Content management system
- [x] Mobile responsiveness

### Phase 2: Enhancement (🔄 In Progress)
- [ ] User authentication system
- [ ] Advanced filtering and search
- [ ] Integration with social media
- [ ] Payment gateway for UMKM
- [ ] Inventory management

### Phase 3: Advanced Features (📋 Planned)
- [ ] Mobile app development
- [ ] AI-powered recommendations
- [ ] Real-time chat support
- [ ] Integration with government systems
- [ ] Advanced reporting and analytics

## 🔍 Testing

### Manual Testing Checklist
```
□ Homepage loads correctly
□ Navigation menu works on all devices
□ UMKM directory displays properly
□ Search functionality works
□ Contact form submits successfully
□ Admin login system secure
□ Image uploads work correctly
□ Database operations function properly
□ Mobile responsiveness verified
□ Cross-browser compatibility tested
```

### Browser Support
- ✅ Chrome 80+
- ✅ Firefox 75+
- ✅ Safari 13+
- ✅ Edge 80+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## 🛡️ Security Measures

### Implemented Security Features
- SQL injection prevention (prepared statements)
- XSS protection (input sanitization)
- CSRF token validation
- File upload restrictions
- Rate limiting on forms
- Secure session management
- Password hashing (if user system implemented)
- Directory traversal prevention

### Security Headers (.htaccess)
```apache
Header always append X-Frame-Options SAMEORIGIN
Header set X-XSS-Protection "1; mode=block"
Header set X-Content-Type-Options nosniff
Header set Referrer-Policy "strict-origin-when-cross-origin"
```

## 📈 Performance Optimization

### Implemented Optimizations
- Image compression and WebP support
- CSS/JS minification
- Browser caching with proper headers
- Database query optimization
- Lazy loading for images
- CDN-ready structure
- Gzip compression

### Performance Metrics Goals
- Page load time: < 3 seconds
- First Contentful Paint: < 1.5 seconds
- Largest Contentful Paint: < 2.5 seconds
- Cumulative Layout Shift: < 0.1
- Time to Interactive: < 3.5 seconds

## 🌐 Deployment

### Production Deployment
1. **Server Requirements**
   ```
   - PHP 7.4+ with extensions: mysqli, gd, curl, zip
   - MySQL 5.7+ or MariaDB 10.2+
   - Apache 2.4+ with mod_rewrite
   - SSL certificate (recommended)
   - Minimum 1GB RAM, 10GB storage
   ```

2. **Environment Setup**
   ```bash
   # Create production database
   mysql -u root -p -e "CREATE DATABASE rw7_temas_prod;"
   
   # Import production data
   mysql -u root -p rw7_temas_prod < database.sql
   
   # Set production permissions
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod 600 config/database.php
   ```

3. **Production Configuration**
   ```php
   // config/database.php (production)
   private $host = 'localhost';
   private $db_name = 'rw7_temas_prod';
   private $username = 'prod_user';
   private $password = 'secure_password';
   ```

### Staging Environment
- Create separate staging database
- Use subdomain: staging.rw7temas.com
- Test all features before production deployment
- Monitor performance and security

## 📊 Monitoring & Analytics

### Server Monitoring
- Server uptime monitoring
- Database performance tracking
- Error log monitoring
- Security incident detection
- Backup verification

### User Analytics
- Google Analytics integration
- User behavior tracking
- Form conversion rates
- Mobile vs desktop usage
- Popular content analysis

## 🔧 Maintenance Tasks

### Daily
- [ ] Check error logs
- [ ] Monitor server performance
- [ ] Review new contact messages
- [ ] Backup database

### Weekly  
- [ ] Update news/content
- [ ] Review and moderate new UMKM submissions
- [ ] Check website security
- [ ] Analyze traffic reports

### Monthly
- [ ] Full system backup
- [ ] Security audit
- [ ] Performance optimization review
- [ ] Content strategy evaluation
- [ ] Update dependencies

## 📚 API Documentation

### Authentication
Most API endpoints are public, but admin endpoints require authentication:
```php
// Example API call with authentication
$headers = [
    'Authorization: Bearer ' . $admin_token,
    'Content-Type: application/json'
];
```

### UMKM Endpoints
```
GET    /api/umkm.php              - List all active UMKM
GET    /api/umkm.php?id=123       - Get specific UMKM
GET    /api/umkm.php?kategori=kuliner - Filter by category
POST   /api/umkm.php              - Create new UMKM (admin)
PUT    /api/umkm.php?id=123       - Update UMKM (admin)
DELETE /api/umkm.php?id=123       - Delete UMKM (admin)
```

### News Endpoints
```
GET    /api/berita.php            - List published articles
GET    /api/berita.php?slug=title - Get article by slug
GET    /api/berita.php?kategori=program - Filter by category
POST   /api/berita.php            - Create new article (admin)
```

## 🎨 Design System

### Color Palette
```css
/* Primary Colors */
--primary-blue: #1e3c72;
--primary-gold: #FFD700;
--accent-blue: #2a5298;

/* Secondary Colors */
--light-gray: #f8f9fa;
--medium-gray: #6c757d;
--dark-gray: #343a40;

/* Status Colors */
--success-green: #28a745;
--warning-yellow: #ffc107;
--danger-red: #dc3545;
--info-blue: #17a2b8;
```

### Typography
```css
/* Font Stack */
font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

/* Font Sizes */
--font-xs: 0.75rem;    /* 12px */
--font-sm: 0.875rem;   /* 14px */
--font-base: 1rem;     /* 16px */
--font-lg: 1.125rem;   /* 18px */
--font-xl: 1.25rem;    /* 20px */
--font-2xl: 1.5rem;    /* 24px */
--font-3xl: 2rem;      /* 32px */
```

### Spacing System
```css
/* Spacing Scale */
--space-xs: 0.25rem;   /* 4px */
--space-sm: 0.5rem;    /* 8px */
--space-base: 1rem;    /* 16px */
--space-lg: 1.5rem;    /* 24px */
--space-xl: 2rem;      /* 32px */
--space-2xl: 3rem;     /* 48px */
```

## 🌍 Internationalization (i18n)

### Language Support Structure
```php
// config/languages.php
$languages = [
    'id' => [
        'name' => 'Bahasa Indonesia',
        'flag' => '🇮🇩',
        'default' => true
    ],
    'en' => [
        'name' => 'English',
        'flag' => '🇺🇸',
        'default' => false
    ]
];
```

### Translation Files
```
/lang/
├── id/
│   ├── common.php
│   ├── pages.php
│   └── admin.php
└── en/
    ├── common.php
    ├── pages.php
    └── admin.php
```

## 📱 Progressive Web App (PWA)

### PWA Features (Planned)
- Offline functionality
- Add to home screen
- Push notifications
- Background sync
- Service worker implementation

### Manifest.json
```json
{
  "name": "RW 7 Kelurahan Temas",
  "short_name": "RW7 Temas",
  "description": "Komunitas Bersih, Berbudaya, dan Berdaya",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#1e3c72",
  "theme_color": "#FFD700",
  "icons": [
    {
      "src": "assets/images/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    }
  ]
}
```

## 🤖 Automation

### Automated Tasks
- Daily database backups
- Weekly security scans
- Monthly performance reports
- Automated testing (planned)
- Content moderation (planned)

### Cron Jobs Examples
```bash
# Daily backup at 2 AM
0 2 * * * /usr/local/bin/backup-database.sh

# Weekly cleanup at Sunday 3 AM  
0 3 * * 0 /usr/local/bin/cleanup-logs.sh

# Monthly reports at 1st day 6 AM
0 6 1 * * /usr/local/bin/generate-reports.sh
```

---

**Dibuat dengan ❤️ untuk Komunitas RW 7 Kelurahan Temas**

*Website ini dikembangkan sebagai bagian dari program Pengabdian Masyarakat Mahasiswa (PMM) Universitas Muhammadiyah Malang dalam upaya digitalisasi dan pemberdayaan komunitas.*