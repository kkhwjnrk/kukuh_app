# SIM-PRO: Sistem Informasi Manajemen Terpadu
> **Universal Modular Boilerplate untuk Ujikom / Sertifikasi BNSP LSP Informatika**  
> Dibangun dengan standar industri: **Pure PHP Native MVC**, **Clean URL**, **PDO Prepared Statements**, **PSR-12 Guidelines**, dan **Modern Clean Light UI** tanpa ketergantungan NPM / Node.js.

---

## 📌 Pemetaan 9 Unit Kompetensi SKKNI / BNSP

| Kode Unit | Judul Unit Kompetensi | Bukti Implementasi pada Proyek |
| :--- | :--- | :--- |
| **J.620100.009.01** | Menggunakan Spesifikasi Program | Dokumen SRS, Use Case, Activity Diagram, & Data Dictionary di `docs/SPESIFIKASI.md`. |
| **J.620100.016.01** | Menulis Kode Sesuai Guidelines & Best Practices | Arsitektur MVC bersih, standar PSR-12, sanitasi XSS, CSRF token, hashing BCRYPT. |
| **J.620100.017.02** | Mengimplementasikan Pemrograman Terstruktur | Modul helper prosedural terstruktur di `app/Helpers/utility.php`. |
| **J.620100.018.02** | Mengimplementasikan Pemrograman Berorientasi Objek | Pewarisan (*Inheritance*) `BaseModel`, `BaseController`, enkapsulasi, dan abstraksi. |
| **J.620100.019.02** | Menggunakan Library / Komponen Pre-Existing | Generator CSV/Excel native dengan UTF-8 BOM dan layout media cetak PDF. |
| **J.620100.021.02** | Menerapkan Akses Basis Data | PDO Singleton, Prepared Statements kebal SQL Injection, dan ACID Database Transactions. |
| **J.620100.023.02** | Membuat Dokumen Kode Program | Blok komentar standar PHPDoc pada seluruh kelas/metode, diagram ERD, dan README. |
| **J.620100.025.02** | Melakukan Debugging | Global Exception Handler, Error Logger di `storage/logs/app.log`, dan Debug mode toggle. |
| **J.620100.033.02** | Melaksanakan Pengujian Unit Program | Micro Test Runner bawaan (`php tests/run.php`) dengan 11 test case otomatis. |

---

## 🚀 Panduan Instalasi Cepat

### 1. Persyaratan Sistem
- Web Server: Apache (Laragon atau XAMPP) dengan modul `mod_rewrite` aktif.
- PHP: Versi 7.4 ke atas (Direkomendasikan PHP 8.1 / 8.2).
- Basis Data: MySQL / MariaDB.

### 2. Langkah Pengoperasian
1. Letakkan folder proyek di dalam direktori server lokal:
   - Laragon: `C:\laragon\www\kukuh_app`
   - XAMPP: `C:\xampp\htdocs\kukuh_app`
2. Jalankan migrasi dan seeder otomatis melalui terminal:
   ```bash
   php database/migrate.php
   ```
3. Buka peramban (browser) dan akses:
   ```text
   http://localhost/kukuh_app/login
   ```

---

## 🔑 Kredensial Akun Pengujian (Demo Asesor)

| Username | Password | Peran (Role) | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **admin** | `admin123` | Administrator | Akses penuh (Dashboard, Master Data, Transaksi, Laporan, Kelola Pengguna). |
| **petugas** | `petugas123` | Petugas / Kasir | Operasional harian (Entri transaksi, lihat master data, cetak nota). |
| **manajer** | `manajer123` | Pimpinan / Manajer | Monitoring eksekutif (Melihat dashboard performa dan rekapitulasi laporan). |

---

## 🧪 Menjalankan Pengujian Unit Otomatis (Unit Testing)

Sistem telah dilengkapi dengan test runner internal yang dapat dijalankan tanpa Composer:
```bash
php tests/run.php
```

Output:
```text
======================================================
   SIM-PRO AUTOMATED UNIT TEST SUITE (SKKNI 033.02)  
======================================================
=== Menjalankan Test Suite: Tests\Unit\DatabaseTest ===
  [PASS] testDatabaseConnection
  [PASS] testDatabaseQueryExecution
=== Menjalankan Test Suite: Tests\Unit\AuthTest ===
  [PASS] testPasswordHashingAndVerification
  [PASS] testRoleCheckingLogic
=== Menjalankan Test Suite: Tests\Unit\ValidationTest ===
  [PASS] testRequiredValidationPasses
  [PASS] testRequiredValidationFails
  [PASS] testNumericValidation
  [PASS] testEmailValidation
=== Menjalankan Test Suite: Tests\Unit\ModelTest ===
  [PASS] testCategoryCount
  [PASS] testItemCodeGeneration
  [PASS] testTransactionCodeGeneration
======================================================
Total Test Dijalankan : 11
Sukses (Passed)       : 11
Gagal  (Failed)       : 0
======================================================
[SEMUA UNIT TEST BERHASIL DIUJI DENGAN SEMPURNA!]
```

---

## 📁 Struktur Direktori Proyek

```text
kukuh_app/
├── app/
│   ├── Controllers/        # Controller: Auth, Dashboard, Item, Kategori, Transaksi, Laporan, User
│   ├── Core/               # Engine: Router, Controller, Model, Database, Request, Session, Validator, Logger
│   ├── Helpers/            # Helper prosedural terstruktur (Rupiah, tanggal, format)
│   ├── Models/             # Active Record PDO Model: User, Category, Item, Transaction, Detail
│   ├── Views/              # Antarmuka Modern Clean Light Dashboard
│   └── routes.php          # Definisi Clean URL dan Middleware RBAC
├── config/
│   ├── app.php             # Konfigurasi aplikasi & mode debug
│   ├── database.php        # Konfigurasi koneksi MySQL PDO
│   └── terms.php           # KAMUS ISTILAH DINAMIS (Kunci adaptasi kilat 15 menit)
├── database/
│   ├── schema.sql          # DDL skema relasional tabel MySQL
│   └── migrate.php         # Script migrasi & seeder 1-klik
├── docs/
│   ├── SPESIFIKASI.md      # Bukti SKKNI Unit 1 & 7 (SRS, Use Case, Activity, ERD)
│   └── PANDUAN_UJIKOM.md   # Cheat Sheet cara ubah ke Toko/Perpus/Rental saat ujian
├── public/
│   ├── assets/             # CSS modern, JavaScript vanilla, dan ikon vektor SVG
│   ├── uploads/            # Berkas foto produk yang diunggah
│   ├── .htaccess           # Aturan Pretty URL Apache
│   └── index.php           # Front Controller
├── storage/
│   └── logs/app.log        # Bukti SKKNI Unit 8 (Log Debugging)
├── tests/                  # Bukti SKKNI Unit 9 (Unit Testing Suite)
│   ├── Unit/               # Test Case komponen
│   └── run.php             # Runner CLI pengujian
├── .gitignore
└── README.md
```

---

## 🛡️ Fitur Keamanan Sistem
1. **SQL Injection Prevention:** 100% menggunakan PDO Prepared Statements dengan binding parameter terpisah.
2. **Cross-Site Scripting (XSS) Prevention:** Sanitasi global seluruh input formulir via `htmlspecialchars` dan pembersihan entitas HTML.
3. **Cross-Site Request Forgery (CSRF) Prevention:** Token kriptografi acak `_csrf_token` yang diverifikasi secara otomatis pada seluruh request HTTP POST.
4. **Session Fixation Prevention:** Regenerasi ID sesi secara berkala saat pengguna berhasil melakukan login.
5. **Secure Password Storage:** Seluruh kata sandi di-hash menggunakan algoritma standar industri BCRYPT.
