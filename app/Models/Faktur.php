<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use App\Core\Model;
use Exception;
use PDO;

/**
 * Class Faktur
 * 
 * Model representasi entitas Faktur Penjualan (Header & Transaksi ACID)
 * Sesuai Diagram LRS LSP Programmer (FR.IA.04A) dan SKKNI J.620100.021.02.
 */
class Faktur extends Model
{
    protected string $table = 'faktur';
    protected string $primaryKey = 'no_faktur';

    /**
     * Menyimpan faktur dan detail transaksi secara atomik (ACID).
     * Memotong stok produk otomatis dan melakukan Rollback bila stok tidak cukup.
     *
     * @param array $fakturData Header faktur
     * @param array $items Array produk: [['id_produk' => 1, 'price' => 50000, 'qty' => 2], ...]
     * @return string Nomor faktur yang berhasil dibuat
     * @throws Exception
     */
    public function createWithDetails(array $fakturData, array $items): string
    {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Simpan Header Faktur
            $sqlFaktur = "INSERT INTO `faktur` 
                (`no_faktur`, `tgl_faktur`, `due_date`, `metode_bayar`, `ppn`, `dp`, `grand_total`, `user`, `id_customer`, `id_perusahaan`) 
                VALUES (:no_faktur, :tgl_faktur, :due_date, :metode_bayar, :ppn, :dp, :grand_total, :user, :id_customer, :id_perusahaan)";
            
            $stmtFaktur = $pdo->prepare($sqlFaktur);
            $stmtFaktur->bindValue(':no_faktur', $fakturData['no_faktur']);
            $stmtFaktur->bindValue(':tgl_faktur', $fakturData['tgl_faktur']);
            $stmtFaktur->bindValue(':due_date', $fakturData['due_date']);
            $stmtFaktur->bindValue(':metode_bayar', $fakturData['metode_bayar']);
            $stmtFaktur->bindValue(':ppn', $fakturData['ppn']);
            $stmtFaktur->bindValue(':dp', $fakturData['dp']);
            $stmtFaktur->bindValue(':grand_total', $fakturData['grand_total']);
            $stmtFaktur->bindValue(':user', $fakturData['user']);
            $stmtFaktur->bindValue(':id_customer', $fakturData['id_customer']);
            $stmtFaktur->bindValue(':id_perusahaan', $fakturData['id_perusahaan']);
            $stmtFaktur->execute();

            // 2. Simpan Detail Item Faktur & Potong Stok Produk
            $sqlDetail = "INSERT INTO `detail_faktur` (`no_faktur`, `id_produk`, `qty`, `price`) 
                          VALUES (:no_faktur, :id_produk, :qty, :price)";
            $stmtDetail = $pdo->prepare($sqlDetail);

            $sqlStock = "UPDATE `produk` SET `stock` = `stock` - :qty WHERE `id_produk` = :id AND `stock` >= :min_stock";
            $stmtStock = $pdo->prepare($sqlStock);

            foreach ($items as $item) {
                // Insert detail faktur
                $stmtDetail->bindValue(':no_faktur', $fakturData['no_faktur']);
                $stmtDetail->bindValue(':id_produk', $item['id_produk']);
                $stmtDetail->bindValue(':qty', $item['qty']);
                $stmtDetail->bindValue(':price', $item['price']);
                $stmtDetail->execute();

                // Kurangi stok produk
                $stmtStock->bindValue(':qty', $item['qty']);
                $stmtStock->bindValue(':min_stock', $item['qty']);
                $stmtStock->bindValue(':id', $item['id_produk']);
                $stmtStock->execute();

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("Stok untuk produk ID [{$item['id_produk']}] tidak mencukupi atau telah habis!");
                }
            }

            $pdo->commit();
            return $fakturData['no_faktur'];

        } catch (Exception $e) {
            $pdo->rollBack();
            Logger::error("Gagal menyimpan Faktur [Rollback]: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui faktur beserta detail item (kembalikan stok lama, pasang rincian baru, potong stok baru).
     *
     * @param string $noFaktur
     * @param array $fakturData
     * @param array $items
     * @return bool
     * @throws Exception
     */
    public function updateWithDetails(string $noFaktur, array $fakturData, array $items): bool
    {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Ambil detail lama untuk mengembalikan stok
            $stmtOld = $pdo->prepare("SELECT id_produk, qty FROM `detail_faktur` WHERE `no_faktur` = :no");
            $stmtOld->bindValue(':no', $noFaktur);
            $stmtOld->execute();
            $oldItems = $stmtOld->fetchAll();

            $sqlRestore = "UPDATE `produk` SET `stock` = `stock` + :qty WHERE `id_produk` = :id";
            $stmtRestore = $pdo->prepare($sqlRestore);
            foreach ($oldItems as $old) {
                $stmtRestore->bindValue(':qty', $old['qty']);
                $stmtRestore->bindValue(':id', $old['id_produk']);
                $stmtRestore->execute();
            }

            // 2. Hapus detail lama
            $stmtDel = $pdo->prepare("DELETE FROM `detail_faktur` WHERE `no_faktur` = :no");
            $stmtDel->bindValue(':no', $noFaktur);
            $stmtDel->execute();

            // 3. Update data header faktur
            $sqlHeader = "UPDATE `faktur` SET 
                `tgl_faktur` = :tgl_faktur,
                `due_date` = :due_date,
                `metode_bayar` = :metode_bayar,
                `ppn` = :ppn,
                `dp` = :dp,
                `grand_total` = :grand_total,
                `user` = :user,
                `id_customer` = :id_customer,
                `id_perusahaan` = :id_perusahaan
                WHERE `no_faktur` = :no_faktur";
            
            $stmtHeader = $pdo->prepare($sqlHeader);
            $stmtHeader->bindValue(':tgl_faktur', $fakturData['tgl_faktur']);
            $stmtHeader->bindValue(':due_date', $fakturData['due_date']);
            $stmtHeader->bindValue(':metode_bayar', $fakturData['metode_bayar']);
            $stmtHeader->bindValue(':ppn', $fakturData['ppn']);
            $stmtHeader->bindValue(':dp', $fakturData['dp']);
            $stmtHeader->bindValue(':grand_total', $fakturData['grand_total']);
            $stmtHeader->bindValue(':user', $fakturData['user']);
            $stmtHeader->bindValue(':id_customer', $fakturData['id_customer']);
            $stmtHeader->bindValue(':id_perusahaan', $fakturData['id_perusahaan']);
            $stmtHeader->bindValue(':no_faktur', $noFaktur);
            $stmtHeader->execute();

            // 4. Masukkan detail baru dan potong stok baru
            $sqlDetail = "INSERT INTO `detail_faktur` (`no_faktur`, `id_produk`, `qty`, `price`) 
                          VALUES (:no_faktur, :id_produk, :qty, :price)";
            $stmtDetail = $pdo->prepare($sqlDetail);

            $sqlStock = "UPDATE `produk` SET `stock` = `stock` - :qty WHERE `id_produk` = :id AND `stock` >= :min_stock";
            $stmtStock = $pdo->prepare($sqlStock);

            foreach ($items as $item) {
                $stmtDetail->bindValue(':no_faktur', $noFaktur);
                $stmtDetail->bindValue(':id_produk', $item['id_produk']);
                $stmtDetail->bindValue(':qty', $item['qty']);
                $stmtDetail->bindValue(':price', $item['price']);
                $stmtDetail->execute();

                $stmtStock->bindValue(':qty', $item['qty']);
                $stmtStock->bindValue(':min_stock', $item['qty']);
                $stmtStock->bindValue(':id', $item['id_produk']);
                $stmtStock->execute();

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("Stok untuk produk ID [{$item['id_produk']}] tidak mencukupi!");
                }
            }

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            Logger::error("Gagal update Faktur [Rollback]: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus faktur serta mengembalikan stok produk yang tertera pada detail.
     *
     * @param string $noFaktur
     * @return bool
     * @throws Exception
     */
    public function deleteWithDetails(string $noFaktur): bool
    {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Ambil detail untuk kembalikan stok
            $stmt = $pdo->prepare("SELECT id_produk, qty FROM `detail_faktur` WHERE `no_faktur` = :no");
            $stmt->bindValue(':no', $noFaktur);
            $stmt->execute();
            $items = $stmt->fetchAll();

            $sqlRestore = "UPDATE `produk` SET `stock` = `stock` + :qty WHERE `id_produk` = :id";
            $stmtRestore = $pdo->prepare($sqlRestore);
            foreach ($items as $item) {
                $stmtRestore->bindValue(':qty', $item['qty']);
                $stmtRestore->bindValue(':id', $item['id_produk']);
                $stmtRestore->execute();
            }

            // 2. Hapus detail
            $stmtDel = $pdo->prepare("DELETE FROM `detail_faktur` WHERE `no_faktur` = :no");
            $stmtDel->bindValue(':no', $noFaktur);
            $stmtDel->execute();

            // 3. Hapus header faktur
            $stmtFaktur = $pdo->prepare("DELETE FROM `faktur` WHERE `no_faktur` = :no");
            $stmtFaktur->bindValue(':no', $noFaktur);
            $stmtFaktur->execute();

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            Logger::error("Gagal menghapus Faktur: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengambil satu faktur lengkap dengan relasi Customer, Perusahaan, dan rincian Detail Produk.
     *
     * @param string $noFaktur
     * @return array|false
     */
    public function findWithDetails(string $noFaktur)
    {
        $sql = "SELECT f.*, 
                       c.nama_customer, c.perusahaan_cust, c.alamat AS alamat_customer, c.no_telp AS telp_customer,
                       p.nama_perusahaan, p.alamat AS alamat_perusahaan, p.no_telp AS telp_perusahaan, p.fax AS fax_perusahaan
                FROM `{$this->table}` f
                JOIN `customer` c ON f.id_customer = c.id_customer
                JOIN `perusahaan` p ON f.id_perusahaan = p.id_perusahaan
                WHERE f.no_faktur = :no
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':no', $noFaktur);
        $stmt->execute();
        $faktur = $stmt->fetch();

        if (!$faktur) {
            return false;
        }

        $detailModel = new DetailFaktur();
        $faktur['items'] = $detailModel->getByNoFaktur($noFaktur);

        // Hitung total nilai kotor sebelum diskon / pajak
        $subtotal = 0;
        foreach ($faktur['items'] as $item) {
            $subtotal += ($item['qty'] * $item['price']);
        }
        $faktur['subtotal_items'] = $subtotal;

        return $faktur;
    }

    /**
     * Mengambil daftar faktur terpaginasi dengan pencarian kata kunci dan filter rentang tanggal.
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
            $whereClauses[] = "(f.no_faktur LIKE :kw OR c.nama_customer LIKE :kw OR c.perusahaan_cust LIKE :kw OR p.nama_perusahaan LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($startDate)) {
            $whereClauses[] = "f.tgl_faktur >= :sdate";
            $params[':sdate'] = $startDate;
        }

        if (!empty($endDate)) {
            $whereClauses[] = "f.tgl_faktur <= :edate";
            $params[':edate'] = $endDate;
        }

        $whereSql = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1=1';

        // Hitung total baris
        $countSql = "SELECT COUNT(*) 
                     FROM `{$this->table}` f 
                     JOIN `customer` c ON f.id_customer = c.id_customer
                     JOIN `perusahaan` p ON f.id_perusahaan = p.id_perusahaan
                     WHERE {$whereSql}";
        
        $stmtCount = $this->db->prepare($countSql);
        foreach ($params as $k => $v) {
            $stmtCount->bindValue($k, $v);
        }
        $stmtCount->execute();
        $total = (int)$stmtCount->fetchColumn();

        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));
        $offset = ($page - 1) * $perPage;

        // Ambil data faktur
        $sql = "SELECT f.*, c.nama_customer, c.perusahaan_cust, p.nama_perusahaan
                FROM `{$this->table}` f
                JOIN `customer` c ON f.id_customer = c.id_customer
                JOIN `perusahaan` p ON f.id_perusahaan = p.id_perusahaan
                WHERE {$whereSql}
                ORDER BY f.tgl_faktur DESC, f.no_faktur DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();

        return [
            'data'      => $stmt->fetchAll(),
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => $lastPage,
        ];
    }

    /**
     * Menghasilkan nomor faktur otomatis berformat FK-YYYYMMDD-XXXX.
     *
     * @param string $prefix
     * @return string
     */
    public function generateNoFaktur(string $prefix = 'FK'): string
    {
        $dateStr = date('Ymd');
        $pattern = "{$prefix}-{$dateStr}-%";

        $sql = "SELECT no_faktur FROM `{$this->table}` WHERE no_faktur LIKE :pattern ORDER BY no_faktur DESC LIMIT 1";
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
     * Mengambil ringkasan statistik Faktur untuk dasbor utama.
     *
     * @return array
     */
    public function getDashboardSummary(): array
    {
        $today = date('Y-m-d');

        // Total omset penjualan keseluruhan
        $totalRevenue = (float)$this->db->query("SELECT COALESCE(SUM(grand_total), 0) FROM `{$this->table}`")->fetchColumn();
        $totalFaktur = (int)$this->db->query("SELECT COUNT(*) FROM `{$this->table}`")->fetchColumn();

        // Hari ini
        $stmtToday = $this->db->prepare("SELECT COUNT(*), COALESCE(SUM(grand_total), 0) FROM `{$this->table}` WHERE tgl_faktur = :today");
        $stmtToday->bindValue(':today', $today);
        $stmtToday->execute();
        [$todayCount, $todayRevenue] = $stmtToday->fetch(PDO::FETCH_NUM);

        return [
            'total_revenue' => $totalRevenue,
            'total_faktur'  => $totalFaktur,
            'today_count'   => (int)$todayCount,
            'today_revenue' => (float)$todayRevenue,
        ];
    }
}
