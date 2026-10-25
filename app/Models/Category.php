<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Category
 * 
 * Model representasi klasifikasi atau kelompok data (Categories).
 */
class Category extends Model
{
    protected string $table = 'categories';

    /**
     * Mengambil seluruh kategori beserta jumlah item di dalamnya.
     *
     * @return array
     */
    public function getWithItemCount(): array
    {
        $sql = "SELECT c.*, COUNT(i.id) as total_items 
                FROM `{$this->table}` c
                LEFT JOIN `items` i ON c.id = i.category_id
                GROUP BY c.id
                ORDER BY c.nama_kategori ASC";
        return $this->query($sql);
    }
}
