# Apotek LSP - Sistem Informasi Manajemen Penjualan & Faktur Apotek

Aplikasi berbasis web untuk tata kelola operasional apotek yang mencakup penerbitan faktur penjualan multi-item, katalog produk dan obat-obatan, data rekanan/customer, profil instansi perusahaan, serta modul rekapitulasi laporan penjualan dan persediaan.

Dibangun dengan arsitektur **Pure PHP Native MVC**, **Clean URL**, **PDO Prepared Statements**, dan **Modern Responsive UI** tanpa ketergantungan library eksternal yang rumit sehingga ringan dan mudah dipelihara (*maintainable*).

---

## ✨ Fitur Utama Sistem

1. **Dashboard Ringkasan Operasional**
   - Ringkasan KPI: Total Omset Penjualan, Total Faktur Diterbitkan, Total Produk, dan Total Pelanggan.
   - Peringatan dini (*Early Warning System*) stok obat yang menipis (<= 15 unit).
   - Tabel riwayat transaksi faktur terkini.

2. **Manajemen Faktur & Transaksi Penjualan (ACID)**
   - Formulir pembuatan faktur interaktif multi-item dengan kalkulasi otomatis Subtotal, PPN (11%), Uang Muka (DP), dan Grand Total.
   - Perhitungan otomatis tanggal jatuh tempo berdasarkan metode pembayaran tempo yang dipilih.
   - Tombol simpan otomatis terproteksi jika tidak ada produk yang dipilih.
   - Pengurangan stok produk otomatis dengan integritas transaksi database (`beginTransaction`, `commit`, `rollBack`).
   - Format cetak faktur penjualan resmi lengkap dengan kop apotek, nomor izin, rincian barang, dan kolom tanda tangan ganda.

3. **Katalog Produk & Obat-obatan**
   - Pencatatan master produk (nama obat/barang, jenis/sediaan, harga satuan, dan kuantitas stok).
   - Indikator visual ketersediaan stok produk.

4. **Data Customer & Rekanan**
   - Pendataan pelanggan umum dan instansi rekanan (apotek, klinik, rumah sakit).
   - Fitur cetak rekapitulasi data pelanggan siap print.

5. **Data Perusahaan Penerbit**
   - Pengaturan informasi profil instansi apotek/perusahaan penerbit faktur (nama, alamat, no. telepon, nomor izin apotek, dan fax).

6. **Modul Laporan Terpadu**
   - Laporan Penjualan: Filter rentang tanggal, filter metode bayar (Tunai, Transfer, Tempo), total omset, cetak laporan resmi, dan ekspor data ke format CSV/Excel.
   - Laporan Produk & Stok: Filter jenis obat, filter status stok (Habis, Kritis, Aman), valuasi total aset produk, cetak laporan resmi, dan ekspor CSV.

7. **Manajemen Pengguna & Hak Akses Berjenjang (RBAC)**
   - Pengelolaan akun dengan pembagian peran:
     - **Administrator:** Hak akses penuh ke seluruh modul sistem.
     - **Petugas Kasir:** Akses operasional transaksi faktur, katalog obat, dan data customer.
     - **Manajer:** Akses pemantauan dashboard eksekutif dan rekapitulasi laporan.

8. **Antarmuka Modern & Ramah Pengguna**
   - Desain responsif dengan tipografi modern, kartu statistik KPI, dan modal dialog konfirmasi interaktif pengganti dialog bawaan browser.
   - Footer bar terstruktur di setiap halaman aplikasi.

---

## 🚀 Panduan Instalasi & Pengoperasian

### Persyaratan Sistem
- **Web Server:** Apache (Laragon atau XAMPP) dengan ekstensi `mod_rewrite` aktif.
- **PHP:** Versi 7.4 atau lebih baru (PHP 8.0, 8.1, 8.2 didukung penuh).
- **Basis Data:** MySQL atau MariaDB.

### Langkah Instalasi
1. Pindahkan folder proyek ke root web server:
   - Laragon: `C:\laragon\www\kukuh_app`
   - XAMPP: `C:\xampp\htdocs\kukuh_app`

2. Pastikan service Apache dan MySQL sudah berjalan.

3. Jalankan migrasi dan seeder data awal melalui terminal:
   ```bash
   php database/migrate.php
   ```
   *Perintah ini akan membuat database `apotek_lsp`, seluruh tabel relasional, dan data awal yang diperlukan.*

4. Buka peramban (browser) dan akses alamat:
   ```text
   http://localhost/kukuh_app
   ```

---

## 🔑 Akun Pengguna Bawaan

| Username | Password | Peran (Role) | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **admin** | `password` | Administrator | Akses penuh: Master data, Transaksi, Laporan, Manajemen Pengguna |
| **petugas** | `password` | Petugas Kasir | Transaksi penjualan, input master data, dan cetak faktur |
| **kasir** | `password` | Petugas Kasir | Transaksi penjualan, input master data, dan cetak faktur |
| **manajer** | `password` | Manajer | Monitoring dashboard dan rekapitulasi seluruh laporan |

---

## 🧪 Pengujian Unit Otomatis (Unit Testing)

Aplikasi dilengkapi dengan test suite mandiri untuk memverifikasi fungsionalitas inti (koneksi database, enkripsi autentikasi, validasi data, integritas relasi model) tanpa memerlukan instalasi PHPUnit atau Composer:

```bash
php tests/run.php
```

---

## 📁 Struktur Direktori

```text
kukuh_app/
├── app/
│   ├── Controllers/        # Controller alur aplikasi (Auth, Dashboard, Faktur, Laporan, dll)
│   ├── Core/               # Engine inti MVC (Router, Controller, Model, Database, Validator, Logger)
│   ├── Helpers/            # Utilitas pembantu (format mata uang, tanggal, URL, sanitasi)
│   ├── Models/             # Model Active Record PDO (User, Perusahaan, Customer, Produk, Faktur)
│   ├── Views/              # File antarmuka tampilan (HTML/PHP terstruktur)
│   └── routes.php          # Definisi rute Clean URL dan proteksi Middleware RBAC
├── config/
│   ├── app.php             # Konfigurasi nama aplikasi, zona waktu, dan mode debug
│   ├── database.php        # Kredensial koneksi MySQL PDO
│   └── terms.php           # Konfigurasi penamaan entitas dan terminologi aplikasi
├── database/
│   ├── schema.sql          # Struktur DDL skema basis data relasional
│   └── migrate.php         # Script migrasi dan seeder otomatis
├── docs/
│   └── SPESIFIKASI.md      # Spesifikasi kebutuhan perangkat lunak (SRS) & arsitektur
├── public/
│   ├── assets/             # Berkas CSS, JavaScript, dan aset visual
│   ├── .htaccess           # Konfigurasi rewrite rule Apache
│   └── index.php           # Front Controller / Single Entry Point
├── storage/
│   └── logs/app.log        # Berkas pencatatan log error dan aktivitas sistem
├── tests/
│   ├── Unit/               # Kasus uji komponen unit (Database, Auth, Validation, Model)
│   ├── TestRunner.php      # Test runner engine mandiri
│   └── run.php             # Eksekutor pengujian unit CLI
└── README.md
```

---

## 🛡️ Standar Kualitas & Keamanan

- **SQL Injection Prevention:** 100% menggunakan PDO Prepared Statements dengan parameter binding.
- **Cross-Site Scripting (XSS) Prevention:** Sanitasi global seluruh keluaran HTML menggunakan `htmlspecialchars`.
- **Cross-Site Request Forgery (CSRF) Prevention:** Token verifikasi `_csrf_token` pada seluruh pengiriman form POST.
- **Secure Password Hashing:** Pengamanan kata sandi menggunakan algoritma standar BCRYPT.
- **Atomic Transactions (ACID):** Pengelolaan pemotongan stok dan penyimpanan transaksi faktur dengan `BEGIN TRANSACTION` / `COMMIT` / `ROLLBACK`.
