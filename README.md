<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<h1 align="center">🧾 SI Keuangan - UMKM Kebab Ikhwan</h1>

<p align="center">
  <strong>Sistem Informasi Keuangan Modern untuk Pengelolaan Bisnis UMKM</strong>
</p>

<p align="center">
  Solusi lengkap untuk mengelola keuangan, karyawan, dan laporan bisnis UMKM Anda dengan mudah, cepat, dan profesional.
</p>

<p align="center">
  <a href="#-fitur-unggulan">Fitur</a> •
  <a href="#-screenshot">Screenshot</a> •
  <a href="#-instalasi">Instalasi</a> •
  <a href="#-dokumentasi">Dokumentasi</a> •
  <a href="#-kontribusi">Kontribusi</a>
</p>

---

## 🎯 Mengapa SI Keuangan?

Apakah Anda pemilik UMKM yang masih kesulitan dengan:

-   ❌ Pencatatan keuangan manual yang ribet dan rawan error?
-   ❌ Perhitungan gaji karyawan yang memakan waktu?
-   ❌ Laporan keuangan yang tidak rapi dan sulit dipahami?
-   ❌ Kesulitan memantau performa bisnis secara real-time?

**SI Keuangan hadir sebagai solusi lengkap untuk semua masalah tersebut!** ✨

---

## ✨ Fitur Unggulan

### 📊 Dashboard Interaktif

-   Ringkasan keuangan real-time (pemasukan, pengeluaran, profit)
-   Grafik tren keuangan harian, mingguan, dan bulanan
-   Widget quick stats untuk monitoring cepat
-   Notifikasi aktivitas terkini

### 💰 Manajemen Keuangan Lengkap

-   **Pencatatan Pemasukan** - Catat semua sumber pendapatan dengan kategori
-   **Pencatatan Pengeluaran** - Kelola semua biaya operasional dengan rapi
-   **Multi-Cabang Support** - Kelola keuangan beberapa cabang sekaligus
-   **Approval System** - Workflow persetujuan untuk transaksi penting

### 👥 Manajemen Karyawan & Penggajian

-   Database karyawan lengkap dengan profil detail
-   **Sistem Penggajian Otomatis** - Hitung gaji berdasarkan persentase pemasukan
-   **Slip Gaji Digital** - Generate slip gaji PDF dengan tanda tangan digital
-   Rekap penggajian per periode dengan export PDF

### 📑 Laporan Profesional

-   **Laporan Harian** - Pantau aktivitas keuangan harian
-   **Laporan Mingguan** - Analisis tren mingguan bisnis Anda
-   **Laporan Bulanan** - Evaluasi performa bulanan dengan detail
-   **Export PDF** - Cetak laporan profesional dengan TTD digital

### 🔐 Keamanan & Multi-User

-   Sistem login aman dengan enkripsi password
-   **Role-based Access Control** - Admin & Karyawan dengan hak akses berbeda
-   Audit trail untuk setiap aktivitas
-   Tanda tangan digital untuk dokumen resmi

### 🏪 Manajemen Cabang

-   Kelola unlimited cabang bisnis
-   Laporan terpisah per cabang
-   Perbandingan performa antar cabang

### ⚙️ Pengaturan Fleksibel

-   Konfigurasi persentase gaji karyawan
-   Pengumuman/informasi untuk karyawan
-   Profil perusahaan yang dapat dikustomisasi

---

## 🖼️ Screenshot

<p align="center">
  <i>Coming Soon - Screenshot aplikasi akan ditambahkan</i>
</p>

---

## 🛠️ Tech Stack

| Kategori          | Teknologi                                |
| ----------------- | ---------------------------------------- |
| **Backend**       | Laravel 12, PHP 8.2+                     |
| **Frontend**      | Blade Templates, Tailwind CSS, Alpine.js |
| **Database**      | MySQL 8.0                                |
| **PDF Generator** | DomPDF                                   |
| **Security**      | Laravel Sanctum, Hashids                 |
| **Icons**         | Font Awesome 6                           |

---

## 📦 Instalasi

### Persyaratan Sistem

-   PHP >= 8.2
-   Composer >= 2.0
-   Node.js >= 18.x
-   MySQL >= 8.0
-   Git

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/tengkuzainul/finance-management.git
cd finance-management

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_DATABASE=sikeuangan
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi database
php artisan migrate

# 7. (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed

# 8. Buat symbolic link untuk storage
php artisan storage:link

# 9. Build assets
npm run build

# 10. Jalankan server development
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### Login Default

| Role     | Username           | Password |
| -------- | ------------------ | -------- |
| Admin    | admin@kebab.com    | password |
| Karyawan | karyawan@kebab.com | password |

---

## 📖 Dokumentasi

### Struktur Folder Utama

```
sikeuangan-kebabikhwan/
├── app/
│   ├── Http/Controllers/    # Controller aplikasi
│   ├── Models/              # Model Eloquent
│   └── Traits/              # Traits reusable
├── database/
│   ├── migrations/          # Migrasi database
│   └── seeders/             # Data seeder
├── resources/
│   ├── views/               # Blade templates
│   │   ├── layouts/         # Layout utama
│   │   ├── components/      # Komponen reusable
│   │   └── pages/           # Halaman aplikasi
│   └── css/                 # Stylesheet
├── routes/
│   └── web.php              # Route definitions
└── public/                  # Asset publik
```

### Model & Relasi

| Model             | Deskripsi                                      |
| ----------------- | ---------------------------------------------- |
| `User`            | User authentication dengan role admin/karyawan |
| `Karyawan`        | Data karyawan yang terhubung ke user           |
| `Cabang`          | Data cabang/outlet bisnis                      |
| `LaporanKeuangan` | Transaksi pemasukan & pengeluaran              |
| `Gaji`            | Data penggajian karyawan                       |
| `Pengaturan`      | Konfigurasi sistem (key-value)                 |
| `Informasi`       | Pengumuman untuk karyawan                      |

---

## 🎨 Customization

### Mengubah Warna Tema

Edit file `tailwind.config.js` untuk mengubah color palette:

```js
theme: {
  extend: {
    colors: {
      primary: '#f97316', // Orange (default)
      // Ubah sesuai branding Anda
    }
  }
}
```

### Menambah Kategori Transaksi

Edit di bagian pengaturan atau langsung di database pada tabel yang relevan.

---

## 🤝 Kontribusi

Kontribusi sangat diapresiasi! Berikut cara berkontribusi:

1. **Fork** repository ini
2. **Clone** fork Anda: `git clone https://github.com/username/finance-management.git`
3. **Buat branch** fitur baru: `git checkout -b fitur-baru`
4. **Commit** perubahan: `git commit -m "Menambahkan fitur baru"`
5. **Push** ke branch: `git push origin fitur-baru`
6. Buat **Pull Request**

### Panduan Kontribusi

-   Ikuti coding style PSR-12 untuk PHP
-   Gunakan conventional commits untuk pesan commit
-   Pastikan semua test passed sebelum submit PR
-   Dokumentasikan fitur baru yang ditambahkan

---

## 📋 Roadmap

-   [x] Dashboard dengan statistik real-time
-   [x] Manajemen pemasukan & pengeluaran
-   [x] Sistem penggajian otomatis
-   [x] Export laporan PDF
-   [x] Tanda tangan digital
-   [x] Multi-cabang support
-   [ ] API untuk integrasi mobile app
-   [ ] Notifikasi email & WhatsApp
-   [ ] Backup otomatis ke cloud
-   [ ] Multi-bahasa support
-   [ ] Dark mode

---

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Developer

<p align="center">
  Dikembangkan dengan ❤️ oleh <strong>Tengku Zainul</strong>
</p>

<p align="center">
  <a href="https://github.com/tengkuzainul">
    <img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" alt="GitHub">
  </a>
</p>

---

## 💬 Support & Kontak

Butuh bantuan atau punya pertanyaan?

-   📧 Email: [support@example.com](mailto:support@example.com)
-   💬 Issues: [GitHub Issues](https://github.com/tengkuzainul/finance-management/issues)

---

## ⭐ Dukung Project Ini

Jika project ini bermanfaat untuk Anda, berikan ⭐ star di repository ini!

```
🌟 Star = Semangat untuk terus mengembangkan! 🌟
```

---

<p align="center">
  <strong>SI Keuangan - UMKM Kebab Ikhwan</strong><br>
  <sub>Solusi Keuangan Modern untuk UMKM Indonesia 🇮🇩</sub>
</p>
