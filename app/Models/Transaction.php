<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use App\Core\Model;
use Exception;
use PDO;

/**
 * Class Transaction
 * 
 * Model transaksi utama dengan mekanisme Atomic Transactions (ACID)
 * menggunakan PDO Transaction.
 */
class Transaction extends Model
{
    protected string $table = 'transactions';

    /**
     * Menyimpan transaksi multi-item sekaligus dalam satu kesatuan transaksi database.
     * Mengurangi stok setiap barang dan membatalkan transaksi jika stok tidak mencukupi (Rollback).
     *
     * @param array $transData
     * @param array $items Array berformat: [['item_id' => 1, 'harga' => 50000, 'qty' => 2], ...]
     * @return int ID transaksi yang berhasil dibuat
     * @throws Exception
     */
    public function createWithDetails(array $transData, array $items): int
    {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Simpan Transaksi Utama
            $sqlTrans = "INSERT INTO `transactions` 
                (`kode_transaksi`, `tanggal`, `user_id`, `nama_pelanggan`, `total_bayar`, `status`, `catatan`) 
                VALUES (:kode, :tgl, :uid, :pelanggan, :total, :status, :catatan)";
            
            $stmtTrans = $pdo->prepare($sqlTrans);
            $stmtTrans->bindValue(':kode', $transData['kode_transaksi']);
            $stmtTrans->bindValue(':tgl', $transData['tanggal']);
            $stmtTrans->bindValue(':uid', $transData['user_id']);
            $stmtTrans->bindValue(':pelanggan', $transData['nama_pelanggan']);
            $stmtTrans->bindValue(':total', $transData['total_bayar']);
            $stmtTrans->bindValue(':status', $transData['status'] ?? 'selesai');
            $stmtTrans->bindValue(':catatan', $transData['catatan'] ?? null);
            $stmtTrans->execute();

            $transactionId = (int)$pdo->lastInsertId();

            // 2. Simpan Rincian Item & Potong Stok
            $sqlDetail = "INSERT INTO `transaction_details` 
                (`transaction_id`, `item_id`, `harga_satuan`, `qty`, `subtotal`) 
                VALUES (:tid, :iid, :harga, :qty, :subtotal)";
            $stmtDetail = $pdo->prepare($sqlDetail);

            $sqlStock = "UPDATE `items` SET `stok` = `stok` - :qty WHERE `id` = :id AND `stok` >= :qty";
            $stmtStock = $pdo->prepare($sqlStock);

            foreach ($items as $item) {
                $subtotal = $item['harga'] * $item['qty'];

                // Insert detail
                $stmtDetail->bindValue(':tid', $transactionId);
                $stmtDetail->bindValue(':iid', $item['item_id']);
                $stmtDetail->bindValue(':harga', $item['harga']);
                $stmtDetail->bindValue(':qty', $item['qty']);
                $stmtDetail->bindValue(':subtotal', $subtotal);
                $stmtDetail->execute();

                // Kurangi stok
                $stmtStock->bindValue(':qty', $item['qty']);
                $stmtStock->bindValue(':id', $item['item_id']);
                $stmtStock->execute();

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("Stok untuk item ID [{$item['item_id']}] tidak mencukupi atau telah habis.");
                }
            }

            // Jika semua langkah sukses, lakukan commit
            $pdo->commit();
            return $transactionId;

        } catch (Exception $e) {
            $pdo->rollBack();
            Logger::error("Gagal mengeksekusi transaksi [Rollback]: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengambil daftar transaksi dengan relasi user dan filter tanggal/kata kunci.
     *
     * @param string|null $keyword
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getFilteredPaginated(
        ?string $keyword = null,
        ?string $startDate = null,
        ?string $endDate = null,
        int $page = 1,
        int $perPage = 10
    ): array {
        $whereClauses = [];
        $params = [];

        if (!empty($keyword)) {
            $whereClauses[] = "(t.kode_transaksi LIKE :kw OR t.nama_pelanggan LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($startDate)) {
            $whereClauses[] = "t.tanggal >= :sdate";
            $params[':sdate'] = $startDate;
        }

        if (!empty($endDate)) {
            $whereClauses[] = "t.tanggal <= :edate";
            $params[':edate'] = $endDate;
        }

        $whereSql = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1=1';

        // Hitung total data
        $countSql = "SELECT COUNT(*) FROM `{$this->table}` t WHERE {$whereSql}";
        $stmtCount = $this->db->prepare($countSql);
        foreach ($params as $k => $v) {
            $stmtCount->bindValue($k, $v);
        }
        $stmtCount->execute();
        $total = (int)$stmtCount->fetchColumn();

        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));
        $offset = ($page - 1) * $perPage;

        // Ambil data
        $sql = "SELECT t.*, u.nama_lengkap as nama_petugas 
                FROM `{$this->table}` t
                LEFT JOIN `users` u ON t.user_id = u.id
                WHERE {$whereSql}
                ORDER BY t.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();

        return [
            'data'        => $stmt->fetchAll(),
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'last_page'   => $lastPage,
        ];
    }

    /**
     * Mengambil satu transaksi lengkap dengan data rincian item (Details).
     *
     * @param int|string $id
     * @return array|false
     */
    public function findWithDetails($id)
    {
        $sqlTrans = "SELECT t.*, u.nama_lengkap as nama_petugas, u.username as username_petugas 
                     FROM `{$this->table}` t
                     LEFT JOIN `users` u ON t.user_id = u.id
                     WHERE t.id = :id
                     LIMIT 1";
        $stmt = $this->db->prepare($sqlTrans);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $transaction = $stmt->fetch();

        if (!$transaction) {
            return false;
        }

        $sqlDetails = "SELECT td.*, i.nama_item, i.kode_item, c.nama_kategori 
                       FROM `transaction_details` td
                       JOIN `items` i ON td.item_id = i.id
                       LEFT JOIN `categories` c ON i.category_id = c.id
                       WHERE td.transaction_id = :tid";
        $stmtDetails = $this->db->prepare($sqlDetails);
        $stmtDetails->bindValue(':tid', $id);
        $stmtDetails->execute();

        $transaction['items'] = $stmtDetails->fetchAll();
        return $transaction;
    }

    /**
     * Menghasilkan kode transaksi otomatis berdasarkan tanggal hari ini.
     *
     * @param string $prefix
     * @return string Contoh: TRX-20261025-0001
     */
    public function generateCode(string $prefix = 'TRX'): string
    {
        $dateStr = date('Ymd');
        $pattern = "{$prefix}-{$dateStr}-%";

        $sql = "SELECT kode_transaksi FROM `{$this->table}` WHERE kode_transaksi LIKE :pattern ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pattern', $pattern);
        $stmt->execute();
        $latest = $stmt->fetchColumn();

        if ($latest && preg_match('/-(\d+)$/', $latest, $m)) {
            $nextNumber = (int)$m[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $dateStr, $nextNumber);
    }

    /**
     * Mengambil statistik ringkas dashboard (Total transaksi, pendapatan, dll).
     *
     * @return array
     */
    public function getDashboardSummary(): array
    {
        $today = date('Y-m-d');

        // Total pendapatan keseluruhan
        $totalRevenue = (float)$this->db->query("SELECT SUM(total_bayar) FROM `{$this->table}` WHERE status = 'selesai'")->fetchColumn();

        // Total transaksi hari ini
        $stmtToday = $this->db->prepare("SELECT COUNT(*), COALESCE(SUM(total_bayar), 0) FROM `{$this->table}` WHERE tanggal = :today AND status = 'selesai'");
        $stmtToday->bindValue(':today', $today);
        $stmtToday->execute();
        [$todayCount, $todayRevenue] = $stmtToday->fetch(PDO::FETCH_NUM);

        return [
            'total_revenue' => $totalRevenue,
            'today_count'   => (int)$todayCount,
            'today_revenue' => (float)$todayRevenue,
        ];
    }
}
