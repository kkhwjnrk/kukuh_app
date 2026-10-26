<?php
/**
 * Script Migrasi & Seeder Database Otomatis
 * Sistem Informasi Apotek LSP
 * 
 * - Membuat dan menginisialisasi tabel relasional
 * - Mengisi data awal master dan transaksi
 * 
 * Jalankan via terminal: php database/migrate.php
 */

echo "==============================================================\n";
echo "   APOTEK LSP - DATABASE MIGRATION & SEEDER                   \n";
echo "==============================================================\n\n";

$dbConfig = require __DIR__ . '/../config/database.php';

try {
    // 1. Hubungkan ke server MySQL
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=%s', $dbConfig['host'], $dbConfig['port'], $dbConfig['charset']),
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "[1/4] Membuat basis data '{$dbConfig['database']}'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbConfig['database']}`");
    echo "      -> Database siap.\n\n";

    // 2. Eksekusi Schema
    echo "[2/4] Mengeksekusi tabel dari database/schema.sql...\n";
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schemaSql);
    echo "      -> 6 Tabel (users, perusahaan, customer, produk, faktur, detail_faktur) berhasil dibuat.\n\n";

    // 3. Seeding Akun Pengguna Sistem
    echo "[3/4] Melakukan seeding akun pengguna sistem...\n";
    $stmtUser = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES (:u, :p, :n, :r)");
    $users = [
        ['u' => 'admin',   'p' => password_hash('password', PASSWORD_BCRYPT),   'n' => 'Administrator Toko',   'r' => 'admin'],
        ['u' => 'petugas', 'p' => password_hash('password', PASSWORD_BCRYPT),   'n' => 'Petugas Kasir 1',      'r' => 'petugas'],
        ['u' => 'kasir',   'p' => password_hash('password', PASSWORD_BCRYPT),   'n' => 'Petugas Kasir 2',      'r' => 'petugas'],
        ['u' => 'manajer', 'p' => password_hash('password', PASSWORD_BCRYPT),   'n' => 'Manajer Operasional',  'r' => 'manajer'],
    ];
    foreach ($users as $u) {
        $stmtUser->execute($u);
    }
    echo "      -> Akun Pengguna siap (admin, petugas, kasir, manajer | password: password).\n\n";

    // 4. Seeding Data Master & Transaksi Awal
    echo "[4/4] Melakukan seeding data master dan transaksi awal...\n";

    // A. Data Master Perusahaan
    $pdo->exec("INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `alamat`, `no_telp`, `fax`) VALUES
        (1, 'APOTEK DEMO FARMA', 'Jl. Merdeka No. 123, Tangerang', '081234567840', '021-5551234'),
        (2, 'PT SUMBER MEDIKA UTAMA', 'Kawasan Industri Cikupa Mas Blok B-5, Tangerang', '021-5901234', '021-5901235'),
        (3, 'CV SEJAHTERA ABADI', 'Jl. Sudirman No. 45, Jakarta Selatan', '021-7208899', NULL),
        (4, 'PT BINTANG PHARMA INDONESIA', 'Jl. Raya Serpong KM 8, Tangerang Selatan', '021-5374411', '021-5374412'),
        (5, 'KLINIK & APOTEK SEHAT BERSAMA', 'Jl. Daan Mogot No. 88, Jakarta Barat', '021-5667788', NULL)
    ");
    echo "      - [OK] Data Perusahaan terisi.\n";

    // B. Data Master Customer
    $pdo->exec("INSERT INTO `customer` (`id_customer`, `nama_customer`, `perusahaan_cust`, `alamat`, `no_telp`) VALUES
        (1, 'Firman Syah', 'Pribadi / Perorangan', 'Jl. Tambak Medokan Ayu 3c No 23, Surabaya', '0821213445570'),
        (2, 'Hendra Kusuma', 'PT Graha Pratama Mandiri', 'Jl. Boulevard Gading Serpong Blok M-10', '081388991122'),
        (3, 'Ibu Siti Rahmawati', 'Klinik Pratama Rahma', 'Jl. Cempaka Putih Tengah No. 15, Jakarta Pusat', '085712348899'),
        (4, 'Bambang Sudiro', 'CV Berkah Karya Sejati', 'Jl. Pahlawan Seribu No. 9, BSD City', '081900112233'),
        (5, 'dr. Maya Indrawati', 'Praktek Dokter Bersama', 'Jl. Bintaro Utama Sektor 3A, Tangsel', '081122334455')
    ");
    echo "      - [OK] Data Customer terisi.\n";

    // C. Data Master Produk
    $pdo->exec("INSERT INTO `produk` (`id_produk`, `nama_produk`, `price`, `jenis`, `stock`) VALUES
        (1, 'Bodrex Flu & Batuk PE', 3000.00, 'Tablet / Ampul', 150),
        (2, 'VITAMIN C 50 mg IPI 10 Tablet', 2500.00, 'Strip / Botol', 200),
        (3, 'Paracetamol Ekstra 20mg Caplet', 12000.00, 'Box / Pcs', 80),
        (4, 'Amoxicillin Trihydrate 500mg', 8500.00, 'Strip', 120),
        (5, 'Betadine Antiseptic Solution 30ml', 21500.00, 'Botol', 65)
    ");
    echo "      - [OK] Data Produk terisi.\n";

    // D. Transaksi Faktur Awal
    $pdo->exec("INSERT INTO `faktur` 
        (`no_faktur`, `tgl_faktur`, `due_date`, `metode_bayar`, `ppn`, `dp`, `grand_total`, `user`, `id_customer`, `id_perusahaan`) 
        VALUES 
        ('PJ2110070001', '2026-10-07', '2026-10-07', 'TUNAI', 6950.00, 0.00, 76450.00, 'Kasir Utama', 1, 1),
        ('PJ2110070002', '2026-10-15', '2026-10-22', 'TRANSFER', 5170.00, 10000.00, 42170.00, 'Kasir Utama', 2, 1)
    ");

    // Detail Faktur 1
    $pdo->exec("INSERT INTO `detail_faktur` (`no_faktur`, `id_produk`, `qty`, `price`) VALUES
        ('PJ2110070001', 1, 2, 1000.00),
        ('PJ2110070001', 2, 3, 2500.00),
        ('PJ2110070001', 3, 5, 12000.00)
    ");

    // Detail Faktur 2
    $pdo->exec("INSERT INTO `detail_faktur` (`no_faktur`, `id_produk`, `qty`, `price`) VALUES
        ('PJ2110070002', 4, 3, 8500.00),
        ('PJ2110070002', 5, 1, 21500.00)
    ");
    echo "      - [OK] Transaksi Faktur & Detail Faktur terisi.\n\n";

    echo "==============================================================\n";
    echo "   MIGRASI & SEEDING SELESAI DENGAN SUKSES!                   \n";
    echo "==============================================================\n";

} catch (PDOException $e) {
    echo "\n[ERROR] Migrasi Gagal: " . $e->getMessage() . "\n";
    exit(1);
}
