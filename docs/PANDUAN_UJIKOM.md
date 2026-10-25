# PANDUAN KILAT ADAPTASI UJIKOM (15 MENIT)
**Strategi Praktis Menghadapi Ujian Kompetensi Kejuruan / BNSP**

Dokumen ini adalah panduan rahasia Anda saat hari-H ujian untuk melakukan penyesuaian instan (*quick customization*) terhadap soal studi kasus yang diberikan oleh tim penguji / asesor.

---

## ⚡ Langkah 1: Instalasi Cepat di Komputer Ujian (2 Menit)

1. Buka folder Laragon (`C:\laragon\www\`) atau XAMPP (`C:\xampp\htdocs\`).
2. *Clone* atau *extract* repositori ini ke dalam folder tersebut (misal: `C:\laragon\www\kukuh_app`).
3. Pastikan server Apache dan MySQL sudah dinyalakan pada panel Laragon/XAMPP.
4. Buka terminal (CMD / PowerShell) di dalam direktori proyek, lalu jalankan:
   ```bash
   php database/migrate.php
   ```
   *Perintah ini akan otomatis membuat basis data `kukuh_app_db`, menyusun 5 tabel relasional, serta mengisikan data awal (Admin, Petugas, Manajer, Kategori, Barang, dan Transaksi).*
5. Buka peramban (browser) dan akses:
   ```text
   http://localhost/kukuh_app/login
   ```
   - Login Admin: `admin` / `admin123`
   - Login Kasir: `petugas` / `petugas123`
   - Login Pimpinan: `manajer` / `manajer123`

---

## 🎯 Langkah 2: Menyesuaikan dengan Soal Ujian (5 Menit)

Buka berkas `config/terms.php`. Anda **TIDAK PERLU** merombak puluhan baris kode tampilan HTML. Cukup sesuaikan kamus istilah berikut dengan skenario soal:

### Skenario A: Jika Soal adalah "Sistem Perpustakaan"
```php
return [
    'system_title' => 'Sistem Informasi Perpustakaan Digital',
    'app_name'     => 'SIPERPUS',
    'organization' => 'Perpustakaan SMK / Kampus',
    'item' => [
        'singular'      => 'Buku',
        'plural'        => 'Katalog Buku',
        'code_label'    => 'Nomor ISBN / Rak',
        'name_label'    => 'Judul Buku',
        'category_label'=> 'Klasifikasi / Genre',
        'price_label'   => 'Denda / Tarif Sewa',
        'stock_label'   => 'Jumlah Eksemplar',
        'unit'          => 'Buku',
    ],
    'category' => [
        'singular' => 'Kategori Buku',
        'plural'   => 'Daftar Kategori Buku',
    ],
    'transaction' => [
        'singular'     => 'Peminjaman',
        'plural'       => 'Data Peminjaman',
        'action_verb'  => 'Proses Peminjaman',
        'invoice_title'=> 'BUKTI PEMINJAMAN BUKU',
    ],
];
```

### Skenario B: Jika Soal adalah "Sistem Rental Mobil"
```php
return [
    'app_name'     => 'SI-RENTAL',
    'item' => [
        'singular'      => 'Mobil',
        'plural'        => 'Armada Kendaraan',
        'code_label'    => 'Nomor Polisi (Plat)',
        'name_label'    => 'Nama & Tipe Mobil',
        'price_label'   => 'Tarif Sewa / Hari',
        'stock_label'   => 'Unit Tersedia',
        'unit'          => 'Unit',
    ],
    'category' => [
        'singular' => 'Tipe Kendaraan',
        'plural'   => 'Klasifikasi Mobil',
    ],
    'transaction' => [
        'singular'     => 'Sewa Kendaraan',
        'plural'       => 'Transaksi Sewa',
        'action_verb'  => 'Sewa & Checkout',
        'invoice_title'=> 'KONTRAK SEWA KENDARAAN',
    ],
];
```

### Skenario C: Jika Soal adalah "Sistem Kasir / POS / Penjualan"
*Gunakan konfigurasi default tanpa perubahan apa pun karena sistem sudah dirancang siap pakai untuk studi kasus kasir/penjualan.*

---

## 💎 Langkah 3: Menghadapi Wawancara Asesor (Poin Kunci Jawaban)

Saat asesor menguji ke-9 unit kompetensi, tunjukkan bukti kode berikut:

| Unit Kompetensi | Cara Menjawab & Membuktikan ke Asesor |
| :--- | :--- |
| **009.01 (Spesifikasi)** | Tunjukkan berkas `docs/SPESIFIKASI.md` yang berisi Functional Requirements, Use Case Diagram, Activity Diagram, dan Data Dictionary. |
| **016.01 (Guidelines & Best Practices)** | Jelaskan: *"Kode saya menerapkan standar industri PSR-12, struktur terpisah MVC yang bersih, prinsip DRY, sanitasi input XSS, dan enkripsi password BCRYPT."* |
| **017.02 (Pemrograman Terstruktur)** | Buka berkas `app/Helpers/utility.php`. Jelaskan fungsi-fungsi prosedural untuk format Rupiah, format tanggal Indonesia, sanitasi entitas, dan token CSRF. |
| **018.02 (OOP)** | Buka `app/Core/Model.php` dan `app/Models/Item.php`. Tunjukkan konsep *Inheritance* (`extends Model`), *Encapsulation* (properti `protected`), dan *Polymorphism*. |
| **019.02 (Library / Komponen)** | Tunjukkan implementasi ekspor Excel/CSV native dengan UTF-8 BOM dan layout media cetak PDF tanpa dependensi luar. |
| **021.02 (Akses Basis Data)** | Buka `app/Core/Database.php` dan `app/Models/Transaction.php`. Tunjukkan penggunaan **PDO Singleton**, **Prepared Statements**, dan **Database Transaction** (`beginTransaction`, `commit`, `rollBack`). |
| **023.02 (Dokumen Kode Program)** | Tunjukkan blok **PHPDoc** lengkap (`@param`, `@return`, `@throws`) di atas setiap fungsi dan dokumentasi terpadu di `README.md`. |
| **025.02 (Debugging)** | Buka `app/Core/Logger.php` dan file log di `storage/logs/app.log`. Buka juga `app/Views/errors/500_debug.php` yang menampilkan stack trace saat debug mode aktif. |
| **023.02 / 033.02 (Unit Testing)** | Buka terminal dan ketik: `php tests/run.php`. Asesor akan melihat 11 test case hijau lolos `[PASS]` dalam waktu 0.2 detik! Ini bukti paling konkrit dari unit testing. |
