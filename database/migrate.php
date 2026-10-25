<?php
/**
 * Script Migrasi & Seeder Database Otomatis
 * 
 * Jalankan via terminal: php database/migrate.php
 * Script ini akan membuat database `kukuh_app_db`, menyusun tabel,
 * dan mengisikan data awal (Admin, Petugas, Manajer, Kategori, Barang, dan Transaksi).
 */

echo "====================================================\n";
echo "   SIM-PRO DATABASE MIGRATION & SEEDER RUNNER      \n";
echo "====================================================\n\n";

$dbConfig = require __DIR__ . '/../config/database.php';

try {
    // 1. Hubungkan ke server MySQL tanpa memilih database terlebih dahulu
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=%s', $dbConfig['host'], $dbConfig['port'], $dbConfig['charset']),
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "[1/4] Membuat basis data '{$dbConfig['database']}' jika belum ada...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbConfig['database']}`");
    echo "      -> Database siap.\n\n";

    // 2. Eksekusi Schema
    echo "[2/4] Mengeksekusi tabel dari database/schema.sql...\n";
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schemaSql);
    echo "      -> Seluruh tabel berhasil dibuat.\n\n";

    // 3. Seeding Data Pengguna (Default Admin, Petugas, Manajer)
    echo "[3/4] Melakukan seeding data akun pengguna...\n";
    $stmtUser = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES (:u, :p, :n, :r)");

    $users = [
        [
            'u' => 'admin',
            'p' => password_hash('admin123', PASSWORD_BCRYPT),
            'n' => 'Kukuh Administrator',
            'r' => 'admin'
        ],
        [
            'u' => 'petugas',
            'p' => password_hash('petugas123', PASSWORD_BCRYPT),
            'n' => 'Ahmad Petugas',
            'r' => 'petugas'
        ],
        [
            'u' => 'manajer',
            'p' => password_hash('manajer123', PASSWORD_BCRYPT),
            'n' => 'Budi Manajer',
            'r' => 'manajer'
        ],
    ];

    foreach ($users as $u) {
        $stmtUser->execute($u);
    }
    echo "      -> 3 Akun Pengguna berhasil dibuat:\n";
    echo "         - admin   (pass: admin123)\n";
    echo "         - petugas (pass: petugas123)\n";
    echo "         - manajer (pass: manajer123)\n\n";

    // 4. Seeding Data Master (Kategori, Item, dan Transaksi Sampel)
    echo "[4/4] Melakukan seeding data Kategori, Barang, dan Transaksi...\n";

    // Kategori
    $pdo->exec("INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`) VALUES
        (1, 'Elektronik & Gadget', 'Perangkat keras dan aksesoris penunjang'),
        (2, 'Alat Tulis & Kantor', 'Perlengkapan inventaris dan administrasi'),
        (3, 'Perawatan & Komponen', 'Suku cadang dan perlengkapan servis')
    ");

    // Item / Barang
    $pdo->exec("INSERT INTO `items` (`id`, `kode_item`, `nama_item`, `category_id`, `harga`, `stok`, `gambar`, `deskripsi`) VALUES
        (1, 'ITM-001', 'Keyboard Mechanical RGB', 1, 450000.00, 25, NULL, 'Keyboard gaming tactile switch responsif'),
        (2, 'ITM-002', 'Mouse Wireless Ergonomic', 1, 185000.00, 40, NULL, 'Mouse wireless hemat daya 2.4GHz'),
        (3, 'ITM-003', 'Kertas HVS A4 80gr 1 Rim', 2, 58000.00, 100, NULL, 'Kertas putih bersih kualitas premium'),
        (4, 'ITM-004', 'Thermal Paste Kinerja Tinggi', 3, 45000.00, 50, NULL, 'Pasta pendingin CPU konduktivitas tinggi'),
        (5, 'ITM-005', 'Flashdisk 64GB USB 3.0', 1, 95000.00, 30, NULL, 'Penyimpanan portabel cepat dan handal')
    ");

    // Transaksi Awal & Rincian
    $pdo->exec("INSERT INTO `transactions` (`id`, `kode_transaksi`, `tanggal`, `user_id`, `nama_pelanggan`, `total_bayar`, `status`, `catatan`) VALUES
        (1, 'TRX-20261001-0001', CURDATE(), 2, 'PT Maju Bersama', 730000.00, 'selesai', 'Transaksi pengadaan kantor'),
        (2, 'TRX-20261001-0002', CURDATE(), 2, 'Bapak Hendra', 185000.00, 'selesai', 'Pembelian perorangan')
    ");

    $pdo->exec("INSERT INTO `transaction_details` (`transaction_id`, `item_id`, `harga_satuan`, `qty`, `subtotal`) VALUES
        (1, 1, 450000.00, 1, 450000.00),
        (1, 2, 185000.00, 1, 185000.00),
        (1, 4, 45000.00, 2, 90000.00),
        (2, 2, 185000.00, 1, 185000.00)
    ");

    echo "      -> Data Kategori, Barang, dan Transaksi sampel berhasil diisi.\n\n";

    echo "====================================================\n";
    echo "   MIGRASI BERHASIL! APLIKASI SIAP DIGUNAKAN.      \n";
    echo "====================================================\n";

} catch (PDOException $e) {
    echo "\n[ERROR] Migrasi Gagal: " . $e->getMessage() . "\n";
    exit(1);
}
