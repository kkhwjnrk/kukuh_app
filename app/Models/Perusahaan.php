<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Perusahaan
 * 
 * Model representasi entitas Perusahaan (Penerbit Faktur Penjualan).
 */
class Perusahaan extends Model
{
    protected string $table = 'perusahaan';
    protected string $primaryKey = 'id_perusahaan';

    /**
     * Mengambil daftar perusahaan berurutan berdasarkan nama.
     *
     * @return array
     */
    public function getAllOrdered(): array
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `id_perusahaan` ASC";
        return $this->query($sql);
    }

    /**
     * Mengambil daftar perusahaan terfilter dan terpaginasi.
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
            $whereSql = "(`nama_perusahaan` LIKE :kw OR `alamat` LIKE :kw OR `no_telp` LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        return $this->paginate($page, $perPage, $whereSql, $params, 'id_perusahaan', 'ASC');
    }
}
