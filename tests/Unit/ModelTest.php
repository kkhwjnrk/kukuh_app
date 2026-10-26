<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Faktur;
use App\Models\Perusahaan;
use App\Models\Produk;
use Tests\TestRunner;

class ModelTest
{
    public function testPerusahaanData(TestRunner $runner): void
    {
        $perusahaanModel = new Perusahaan();
        $count = $perusahaanModel->count();
        $runner->assertTrue($count >= 5, "Tabel perusahaan harus memiliki minimal 5 data awal (Ditemukan: {$count})");
    }

    public function testCustomerData(TestRunner $runner): void
    {
        $customerModel = new Customer();
        $count = $customerModel->count();
        $runner->assertTrue($count >= 5, "Tabel customer harus memiliki minimal 5 data awal (Ditemukan: {$count})");
    }

    public function testProdukData(TestRunner $runner): void
    {
        $produkModel = new Produk();
        $count = $produkModel->count();
        $runner->assertTrue($count >= 5, "Tabel produk harus memiliki minimal 5 data awal (Ditemukan: {$count})");

        $lowStock = $produkModel->getLowStock(100);
        $runner->assertTrue(is_array($lowStock), "Fungsi getLowStock harus mengembalikan array");
    }

    public function testFakturGenerationAndRelations(TestRunner $runner): void
    {
        $fakturModel = new Faktur();
        $code = $fakturModel->generateNoFaktur('FK');
        $dateStr = date('Ymd');
        $runner->assertTrue(strpos($code, "FK-{$dateStr}-") === 0, "Nomor Faktur harus berformat FK-YYYYMMDD-XXXX (Ditemukan: {$code})");

        $fakturList = $fakturModel->all();
        $runner->assertTrue(count($fakturList) >= 2, "Tabel faktur harus memiliki minimal 2 data transaksi");

        if (!empty($fakturList)) {
            $firstNo = $fakturList[0]['no_faktur'];
            $detail = $fakturModel->findWithDetails($firstNo);
            $runner->assertTrue($detail !== false, "Faktur findWithDetails harus menemukan data faktur {$firstNo}");
            $runner->assertTrue(!empty($detail['nama_customer']), "Faktur harus berhasil di-join dengan tabel customer");
            $runner->assertTrue(!empty($detail['nama_perusahaan']), "Faktur harus berhasil di-join dengan tabel perusahaan");
            $runner->assertTrue(is_array($detail['items']) && count($detail['items']) > 0, "Faktur harus memuat rincian produk dari tabel detail_faktur");
        }
    }
}
