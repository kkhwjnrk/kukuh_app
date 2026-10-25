<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Tests\TestRunner;

class ModelTest
{
    public function testCategoryCount(TestRunner $runner): void
    {
        $catModel = new Category();
        $categories = $catModel->all();
        $runner->assertTrue(is_array($categories), "Kategori harus mengembalikan array");
        $runner->assertTrue(count($categories) >= 3, "Minimal harus ada 3 kategori terpasang dari seeder");
    }

    public function testItemCodeGeneration(TestRunner $runner): void
    {
        $itemModel = new Item();
        $code = $itemModel->generateCode('ITM');
        $runner->assertTrue(strpos($code, 'ITM-') === 0, "Kode item harus diawali dengan ITM-");
    }

    public function testTransactionCodeGeneration(TestRunner $runner): void
    {
        $transModel = new Transaction();
        $code = $transModel->generateCode('TRX');
        $dateStr = date('Ymd');
        $runner->assertTrue(strpos($code, "TRX-{$dateStr}-") === 0, "Kode transaksi harus memuat tanggal hari ini");
    }
}
