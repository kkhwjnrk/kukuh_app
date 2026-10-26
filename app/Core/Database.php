<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Class Database
 * 
 * Mengelola koneksi basis data menggunakan PDO (PHP Data Objects)
 * dengan pola Singleton untuk efisiensi memori dan Prepared Statements
 * untuk pencegahan celah keamanan SQL Injection.
 */
class Database
{
    /** @var Database|null Instance tunggal dari kelas Database */
    private static ?Database $instance = null;

    /** @var PDO Koneksi aktif PDO */
    private PDO $connection;

    /**
     * Konstruktor privat untuk mencegah instansiasi langsung dari luar.
     */
    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            Logger::error("Gagal terhubung ke basis data: " . $e->getMessage());
            die("Koneksi ke basis data gagal. Silakan pastikan server MySQL menyala dan database sudah dibuat.");
        }
    }

    /**
     * Mengambil instance tunggal dari Database (Singleton Pattern).
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mengambil koneksi PDO aktif.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Memulai transaksi database (Atomic Transaction).
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Melakukan commit transaksi.
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Membatalkan transaksi (Rollback).
     *
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * Mencegah proses clone objek.
     */
    private function __clone() {}

    /**
     * Mencegah deserialisasi objek.
     */
    public function __wakeup()
    {
        throw new \Exception("Tidak diperkenankan melakukan unserialize instance Database.");
    }
}
