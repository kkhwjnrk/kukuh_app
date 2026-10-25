-- ==============================================================
-- STRUKTUR BASIS DATA SIM-PRO (STANDAR SKKNI J.620100.021.02)
-- Relasi Antar-Tabel, Foreign Key Constraints, dan Indexing Optimal
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `kukuh_app_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kukuh_app_db`;

-- 1. TABEL PENGGUNA (USERS)
DROP TABLE IF EXISTS `transaction_details`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'petugas', 'manajer') NOT NULL DEFAULT 'petugas',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL KATEGORI (CATEGORIES)
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_categories_nama` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL ITEM / PRODUK / MASTER DATA (ITEMS)
CREATE TABLE `items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_item` VARCHAR(50) NOT NULL UNIQUE,
    `nama_item` VARCHAR(150) NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `harga` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `stok` INT NOT NULL DEFAULT 0,
    `gambar` VARCHAR(255) NULL,
    `deskripsi` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_items_kode` (`kode_item`),
    INDEX `idx_items_nama` (`nama_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL TRANSAKSI UTAMA (TRANSACTIONS)
CREATE TABLE `transactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_transaksi` VARCHAR(50) NOT NULL UNIQUE,
    `tanggal` DATE NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `nama_pelanggan` VARCHAR(100) NOT NULL DEFAULT 'Umum',
    `total_bayar` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('selesai', 'pending', 'batal') NOT NULL DEFAULT 'selesai',
    `catatan` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE,
    INDEX `idx_transactions_kode` (`kode_transaksi`),
    INDEX `idx_transactions_tanggal` (`tanggal`),
    INDEX `idx_transactions_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL RINCIAN TRANSAKSI (TRANSACTION_DETAILS)
CREATE TABLE `transaction_details` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` INT UNSIGNED NOT NULL,
    `item_id` INT UNSIGNED NOT NULL,
    `harga_satuan` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `qty` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `items`(`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
