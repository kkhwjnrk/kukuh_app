# DOKUMEN SPESIFIKASI PROGRAM & ARSITEKTUR SISTEM
**Standar Kompetensi Kerja Nasional Indonesia (SKKNI)**
- **Unit Kompetensi 1:** (J.620100.009.01) - Menggunakan Spesifikasi Program
- **Unit Kompetensi 7:** (J.620100.023.02) - Membuat Dokumen Kode Program

---

## 1. Deskripsi Umum Sistem
**SIM-PRO** (*Sistem Informasi Manajemen Terpadu*) adalah aplikasi berbasis web yang dirancang untuk mengelola inventaris master data, pemrosesan transaksi multi-item, rekapitulasi pelaporan periodik, dan tata kelola akun pengguna dengan pembagian hak akses (*Role-Based Access Control*).

Sistem ini dibangun dengan arsitektur **Clean MVC (Model-View-Controller)** menggunakan bahasa pemrograman **PHP Native** berstandar **PSR-12** dan basis data relasional **MySQL** via **PDO**.

---

## 2. Kebutuhan Sistem

### 2.1 Kebutuhan Fungsional (Functional Requirements)
1. **Modul Autentikasi & Otorisasi:**
   - Pengguna dapat masuk (*login*) menggunakan username dan password yang terenkripsi BCRYPT.
   - Sistem membedakan hak akses menjadi 3 peran: **Administrator**, **Petugas/Operator**, dan **Manajer/Pimpinan**.
   - Sistem menyediakan perlindungan *Session Fixation* dan regenerasi sesi saat login.
2. **Modul Master Data (Item & Kategori):**
   - Menampilkan daftar data dengan pencarian kata kunci dan penyaringan kategori.
   - Paginasi data dinamis untuk menjaga performa rendering memori.
   - Operasi CRUD (Create, Read, Update, Delete) lengkap dengan unggah gambar produk.
3. **Modul Transaksi & Keranjang Interaktif:**
   - Pencatatan transaksi multi-item dalam satu kesatuan nota (*Interactive Cart*).
   - Pengurangan stok barang secara otomatis dan verifikasi ketersediaan stok (*ACID Transaction*).
   - Pencetakan faktur/nota resmi dalam format cetak ramah printer atau PDF.
4. **Modul Rekapitulasi & Pelaporan:**
   - Penyaringan transaksi berdasarkan rentang tanggal (*Date Range Filter*).
   - Ekspor rekap transaksi ke format **Excel/CSV** native dengan standar UTF-8 BOM.
   - Dokumen cetak laporan formal yang dilengkapi kolom pengesahan penguji/pimpinan.
5. **Modul Manajemen Pengguna:**
   - Administrator dapat menambah, memperbarui, dan menghapus akun pengguna sistem.

### 2.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)
1. **Keamanan (Security):**
   - Bebas celah SQL Injection dengan Prepared Statements PDO 100%.
   - Proteksi XSS (*Cross-Site Scripting*) via sanitasi entitas HTML global.
   - Proteksi CSRF (*Cross-Site Request Forgery*) dengan token terenkripsi pada setiap formulir POST.
2. **Kemandirian Lingkungan (Portabilitas / Offline-First):**
   - Tidak memerlukan *build tool* (Node.js/NPM) maupun Composer saat dijalankan di komputer lab.
   - Seluruh asset CSS, ikon vektor SVG, dan skrip JavaScript tersimpan lokal (100% berfungsi tanpa koneksi internet).
3. **Fleksibilitas (Adaptability):**
   - Kamus istilah terpusat (`config/terms.php`) memungkinkan perubahan konteks studi kasus (Toko, Perpustakaan, Rental, Apotek) dalam waktu kurang dari 15 menit.

---

## 3. Diagram Alur & Pemodelan Sistem

### 3.1 Use Case Diagram
```mermaid
flowchart LR
    Admin((Administrator))
    Petugas((Petugas / Kasir))
    Manajer((Pimpinan / Manajer))

    subgraph SIM_PRO [Sistem Informasi Terpadu]
        UC_Login([Autentikasi Login])
        UC_Dashboard([Melihat Dasbor & Statistik])
        UC_Item([Kelola Master Item / Barang])
        UC_Category([Kelola Kategori])
        UC_Trans([Entri Transaksi & Cetak Nota])
        UC_Report([Rekap Laporan & Ekspor Excel/PDF])
        UC_User([Kelola Akun Pengguna])
    end

    Admin --> UC_Login
    Admin --> UC_Dashboard
    Admin --> UC_Item
    Admin --> UC_Category
    Admin --> UC_Trans
    Admin --> UC_Report
    Admin --> UC_User

    Petugas --> UC_Login
    Petugas --> UC_Dashboard
    Petugas --> UC_Item
    Petugas --> UC_Trans
    Petugas --> UC_Report

    Manajer --> UC_Login
    Manajer --> UC_Dashboard
    Manajer --> UC_Report
```

---

### 3.2 Activity Diagram: Alur Transaksi Multi-Item (ACID Transaction)
```mermaid
sequenceDiagram
    autonumber
    actor Petugas as Petugas Kasir
    participant UI as Antarmuka Keranjang (View)
    participant Ctrl as TransactionController
    participant Model as Transaction (Model)
    participant DB as MySQL Database

    Petugas->>UI: Pilih barang & atur kuantitas
    UI-->>Petugas: Hitung otomatis subtotal & grand total (JS)
    Petugas->>UI: Klik "Bayar & Proses Transaksi"
    UI->>Ctrl: POST /transactions/store (Data Header + Array Items + CSRF)
    Ctrl->>Ctrl: Validasi Token CSRF & Input Formulir
    Ctrl->>Model: createWithDetails(transData, cartItems)
    Model->>DB: beginTransaction()
    Model->>DB: INSERT INTO transactions
    loop Tiap Item dalam Keranjang
        Model->>DB: INSERT INTO transaction_details
        Model->>DB: UPDATE items SET stok = stok - qty WHERE id = ? AND stok >= qty
        alt Stok Tidak Cukup
            DB-->>Model: Affected Rows = 0
            Model->>DB: rollBack()
            Model-->>Ctrl: Throw Exception("Stok tidak mencukupi")
            Ctrl-->>UI: Flash Error & Redirect Kembali
        end
    end
    Model->>DB: commit()
    Model-->>Ctrl: Return transaction_id
    Ctrl-->>UI: Redirect ke /transactions/show/{id} (Faktur Sukses)
```

---

### 3.3 Entity Relationship Diagram (ERD)
```mermaid
erDiagram
    USERS ||--o{ TRANSACTIONS : "mencatat"
    CATEGORIES ||--o{ ITEMS : "mengelompokkan"
    TRANSACTIONS ||--|{ TRANSACTION_DETAILS : "memiliki rincian"
    ITEMS ||--o{ TRANSACTION_DETAILS : "dijual / dipinjam"

    USERS {
        int id PK
        varchar username UK
        varchar password
        varchar nama_lengkap
        enum role "admin, petugas, manajer"
        datetime created_at
    }

    CATEGORIES {
        int id PK
        varchar nama_kategori
        text deskripsi
        datetime created_at
    }

    ITEMS {
        int id PK
        varchar kode_item UK
        varchar nama_item
        int category_id FK
        decimal harga
        int stok
        varchar gambar
        text deskripsi
        datetime created_at
    }

    TRANSACTIONS {
        int id PK
        varchar kode_transaksi UK
        date tanggal
        int user_id FK
        varchar nama_pelanggan
        decimal total_bayar
        enum status "selesai, pending, batal"
        text catatan
        datetime created_at
    }

    TRANSACTION_DETAILS {
        int id PK
        int transaction_id FK
        int item_id FK
        decimal harga_satuan
        int qty
        decimal subtotal
    }
```

---

## 4. Kamus Data (Data Dictionary)

### 4.1 Tabel `items` (Master Produk / Barang / Entitas Inti)
| Nama Kolom | Tipe Data | Null | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT UNSIGNED (PK) | No | Auto Increment Identifier |
| `kode_item` | VARCHAR(50) (UK) | No | Kode unik SKU / ISBN / No Polisi |
| `nama_item` | VARCHAR(150) | No | Nama lengkap item produk/layanan |
| `category_id` | INT UNSIGNED (FK) | No | Terhubung ke `categories.id` |
| `harga` | DECIMAL(15,2) | No | Tarif / Harga satuan transaksi |
| `stok` | INT | No | Kuantitas persediaan fisik yang tersedia |
| `gambar` | VARCHAR(255) | Yes | Nama berkas gambar produk |
| `deskripsi` | TEXT | Yes | Spesifikasi atau catatan lengkap |

### 4.2 Tabel `transactions` (Header Transaksi)
| Nama Kolom | Tipe Data | Null | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT UNSIGNED (PK) | No | Auto Increment Identifier |
| `kode_transaksi` | VARCHAR(50) (UK) | No | Kode unik faktur (TRX-YYYYMMDD-XXXX) |
| `tanggal` | DATE | No | Tanggal pelaksanaan transaksi |
| `user_id` | INT UNSIGNED (FK) | No | ID petugas pembuat transaksi |
| `nama_pelanggan` | VARCHAR(100) | No | Nama pembeli / peminjam / klien |
| `total_bayar` | DECIMAL(15,2) | No | Akumulasi nilai pembayaran |
| `status` | ENUM | No | `selesai`, `pending`, `batal` |
