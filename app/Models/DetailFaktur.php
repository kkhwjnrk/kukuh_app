<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class DetailFaktur
 * 
 * Model representasi entitas Rincian / Detail Faktur Penjualan.
 */
class DetailFaktur extends Model
{
    protected string $table = 'detail_faktur';
    protected string $primaryKey = 'id_detail';

    /**
     * Mengambil seluruh rincian barang untuk nomor faktur tertentu beserta data produk.
     *
     * @param string $noFaktur
     * @return array
     */
    public function getByNoFaktur(string $noFaktur): array
    {
        $sql = "SELECT df.*, p.nama_produk, p.jenis, (df.qty * df.price) as subtotal
                FROM `{$this->table}` df
                JOIN `produk` p ON df.id_produk = p.id_produk
                WHERE df.no_faktur = :no_faktur
                ORDER BY df.id_detail ASC";
        
        return $this->query($sql, [':no_faktur' => $noFaktur]);
    }
}
