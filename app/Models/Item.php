<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Item
 * 
 * Model representasi entitas master utama (Barang/Buku/Mobil/Layanan).
 */
class Item extends Model
{
    protected string $table = 'items';

    /**
     * Mengambil item dengan relasi nama kategori dan filter pencarian.
     *
     * @param string|null $keyword
     * @param int|null $categoryId
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getFilteredPaginated(?string $keyword = null, ?int $categoryId = null, int $page = 1, int $perPage = 10): array
    {
        $whereClauses = [];
        $params = [];

        if (!empty($keyword)) {
            $whereClauses[] = "(i.nama_item LIKE :kw OR i.kode_item LIKE :kw OR i.deskripsi LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($categoryId)) {
            $whereClauses[] = "i.category_id = :cat_id";
            $params[':cat_id'] = $categoryId;
        }

        $whereSql = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1=1';

        // Hitung total data
        $countSql = "SELECT COUNT(*) FROM `{$this->table}` i WHERE {$whereSql}";
        $stmtCount = $this->db->prepare($countSql);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val);
        }
        $stmtCount->execute();
        $total = (int)$stmtCount->fetchColumn();

        $lastPage = max(1, (int)ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));
        $offset = ($page - 1) * $perPage;

        // Ambil data dengan join
        $sql = "SELECT i.*, c.nama_kategori 
                FROM `{$this->table}` i
                LEFT JOIN `categories` c ON i.category_id = c.id
                WHERE {$whereSql}
                ORDER BY i.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
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
     * Mengambil item tunggal lengkap dengan nama kategorinya.
     *
     * @param int|string $id
     * @return array|false
     */
    public function findWithCategory($id)
    {
        $sql = "SELECT i.*, c.nama_kategori 
                FROM `{$this->table}` i
                LEFT JOIN `categories` c ON i.category_id = c.id
                WHERE i.id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Mengurangi stok barang saat terjadi transaksi.
     *
     * @param int $itemId
     * @param int $qty
     * @return bool
     */
    public function decreaseStock(int $itemId, int $qty): bool
    {
        $sql = "UPDATE `{$this->table}` SET `stok` = `stok` - :qty WHERE `id` = :id AND `stok` >= :qty";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':qty', $qty);
        $stmt->bindValue(':id', $itemId);
        return $stmt->execute();
    }

    /**
     * Mengambil daftar barang dengan stok menipis (dibawah batas minimum).
     *
     * @param int $threshold
     * @return array
     */
    public function getLowStock(int $threshold = 10): array
    {
        $sql = "SELECT i.*, c.nama_kategori 
                FROM `{$this->table}` i
                LEFT JOIN `categories` c ON i.category_id = c.id
                WHERE i.stok <= :th
                ORDER BY i.stok ASC
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':th', $threshold);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghasilkan kode item otomatis unik (misal: ITM-006).
     *
     * @param string $prefix
     * @return string
     */
    public function generateCode(string $prefix = 'ITM'): string
    {
        $sql = "SELECT kode_item FROM `{$this->table}` ORDER BY id DESC LIMIT 1";
        $latest = $this->db->query($sql)->fetchColumn();

        if ($latest && preg_match('/' . preg_quote($prefix, '/') . '-(\d+)/', $latest, $m)) {
            $nextNumber = (int)$m[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%03d', $prefix, $nextNumber);
    }
}
