-- ==============================================================
-- STRUKTUR BASIS DATA SISTEM FAKTUR PENJUALAN
-- Standar Uji Kompetensi LSP Programmer (FR.IA.04A)
-- Sesuai Diagram Logical Record Structure (LRS)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `apotek_lsp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `apotek_lsp`;

-- Hapus tabel lama jika ada (urutan child ke parent)
DROP TABLE IF EXISTS `detail_faktur`;
DROP TABLE IF EXISTS `faktur`;
DROP TABLE IF EXISTS `produk`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `perusahaan`;
DROP TABLE IF EXISTS `transaction_details`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

-- 1. TABEL PENGGUNA (UNTUK AUTENTIKASI LOGIN ADMIN/PETUGAS)
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'petugas', 'manajer') NOT NULL DEFAULT 'admin',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL PERUSAHAAN (SESUAI LRS)
CREATE TABLE `perusahaan` (
    `id_perusahaan` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_perusahaan` VARCHAR(100) NOT NULL,
    `alamat` TEXT NOT NULL,
    `no_telp` VARCHAR(25) NOT NULL,
    `fax` VARCHAR(25) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL CUSTOMER (SESUAI LRS)
CREATE TABLE `customer` (
    `id_customer` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_customer` VARCHAR(100) NOT NULL,
    `perusahaan_cust` VARCHAR(100) NULL,
    `alamat` TEXT NOT NULL,
    `no_telp` VARCHAR(25) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL PRODUK (SESUAI LRS)
CREATE TABLE `produk` (
    `id_produk` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_produk` VARCHAR(150) NOT NULL,
    `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `jenis` VARCHAR(50) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL FAKTUR (SESUAI LRS)
CREATE TABLE `faktur` (
    `no_faktur` VARCHAR(50) PRIMARY KEY,
    `tgl_faktur` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `metode_bayar` VARCHAR(30) NOT NULL DEFAULT 'TUNAI',
    `ppn` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `dp` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `grand_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `user` VARCHAR(50) NOT NULL,
    `id_customer` INT UNSIGNED NOT NULL,
    `id_perusahaan` INT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_customer`) REFERENCES `customer`(`id_customer`) ON UPDATE CASCADE,
    FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan`(`id_perusahaan`) ON UPDATE CASCADE,
    INDEX `idx_faktur_tgl` (`tgl_faktur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL DETAIL FAKTUR (SESUAI LRS)
CREATE TABLE `detail_faktur` (
    `id_detail` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `no_faktur` VARCHAR(50) NOT NULL,
    `id_produk` INT UNSIGNED NOT NULL,
    `qty` INT NOT NULL DEFAULT 1,
    `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`no_faktur`) REFERENCES `faktur`(`no_faktur`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_produk`) REFERENCES `produk`(`id_produk`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
