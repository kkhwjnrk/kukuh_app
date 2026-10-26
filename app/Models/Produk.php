<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Produk
 * 
 * Model representasi entitas Produk (Barang/Obat/Item Penjualan).
 */
class Produk extends Model
{
    protected string $table = 'produk';
    protected string $primaryKey = 'id_produk';

    /**
     * Mengambil daftar produk dengan filter pencarian dan paginasi.
     *
     * @param string|null $keyword
     * @param string|null $jenis
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getFilteredPaginated(?string $keyword = null, ?string $jenis = null, int $page = 1, int $perPage = 10): array
    {
        $where = [];
        $params = [];

        if (!empty($keyword)) {
            $where[] = "(`nama_produk` LIKE :kw OR `jenis` LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($jenis)) {
            $where[] = "`jenis` = :jenis";
            $params[':jenis'] = $jenis;
        }

        $whereSql = !empty($where) ? implode(' AND ', $where) : null;
        return $this->paginate($page, $perPage, $whereSql, $params, 'id_produk', 'ASC');
    }

    /**
     * Mengurangi stok produk saat terjadi transaksi faktur.
     *
     * @param int $idProduk
     * @param int $qty
     * @return bool
     */
    public function decreaseStock(int $idProduk, int $qty): bool
    {
        $sql = "UPDATE `{$this->table}` SET `stock` = `stock` - :qty WHERE `id_produk` = :id AND `stock` >= :min_stock";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':qty', $qty);
        $stmt->bindValue(':min_stock', $qty);
        $stmt->bindValue(':id', $idProduk);
        return $stmt->execute();
    }

    /**
     * Mengambil daftar produk yang stoknya menipis (dibawah threshold).
     *
     * @param int $threshold
     * @return array
     */
    public function getLowStock(int $threshold = 15): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `stock` <= :th ORDER BY `stock` ASC LIMIT 10";
        return $this->query($sql, [':th' => $threshold]);
    }

    /**
     * Mencari produk berdasarkan kata kunci untuk API autocomplete faktur.
     *
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        $sql = "SELECT * FROM `{$this->table}` 
                WHERE `nama_produk` LIKE :q OR `jenis` LIKE :q 
                ORDER BY `nama_produk` ASC LIMIT 20";
        return $this->query($sql, [':q' => '%' . $query . '%']);
    }

    /**
     * Mengambil semua jenis unik untuk filter dropdown.
     *
     * @return array
     */
    public function getUniqueJenis(): array
    {
        $sql = "SELECT DISTINCT `jenis` FROM `{$this->table}` WHERE `jenis` IS NOT NULL AND `jenis` != '' ORDER BY `jenis` ASC";
        $rows = $this->query($sql);
        return array_column($rows, 'jenis');
    }
}
