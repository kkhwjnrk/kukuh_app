<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Customer
 * 
 * Model representasi entitas Customer (Pelanggan).
 */
class Customer extends Model
{
    protected string $table = 'customer';
    protected string $primaryKey = 'id_customer';

    /**
     * Mengambil daftar seluruh customer terurut dengan pencarian kata kunci.
     *
     * @param string|null $keyword
     * @return array
     */
    public function getFiltered(?string $keyword = null): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " WHERE `nama_customer` LIKE :kw OR `perusahaan_cust` LIKE :kw OR `alamat` LIKE :kw OR `no_telp` LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY `id_customer` ASC";
        return $this->query($sql, $params);
    }

    /**
     * Mengambil daftar customer dengan paginasi.
     *
     * @param string|null $keyword
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getFilteredPaginated(?string $keyword = null, int $page = 1, int $perPage = 10): array
    {
        $whereSql = null;
        $params = [];

        if (!empty($keyword)) {
            $whereSql = "(`nama_customer` LIKE :kw OR `perusahaan_cust` LIKE :kw OR `alamat` LIKE :kw OR `no_telp` LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        return $this->paginate($page, $perPage, $whereSql, $params, 'id_customer', 'ASC');
    }

    /**
     * Mengambil seluruh data customer untuk cetak / dropdown.
     *
     * @return array
     */
    public function getAllOrdered(): array
    {
        return $this->query("SELECT * FROM `{$this->table}` ORDER BY `id_customer` ASC");
    }
}
