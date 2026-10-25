<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;

/**
 * Class DashboardController
 * 
 * Mengelola tampilan dasbor utama, kalkulasi KPI operasional,
 * dan visualisasi ringkasan statistik.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dasbor analitik.
     *
     * @return void
     */
    public function index(): void
    {
        $itemModel = new Item();
        $categoryModel = new Category();
        $transactionModel = new Transaction();
        $userModel = new User();

        // 1. Kartu Ringkasan Statistik
        $totalItems = $itemModel->count();
        $totalCategories = $categoryModel->count();
        $totalUsers = $userModel->count();
        $summary = $transactionModel->getDashboardSummary();

        // 2. Notifikasi Stok Menipis (Early Warning)
        $lowStockItems = $itemModel->getLowStock(15);

        // 3. Riwayat 5 Transaksi Terkini
        $recentTransactions = $transactionModel->getFilteredPaginated(null, null, null, 1, 5)['data'];

        $this->view('dashboard/index', [
            'pageTitle'          => 'Dasbor Utama - ' . $this->terms['app_name'],
            'totalItems'         => $totalItems,
            'totalCategories'    => $totalCategories,
            'totalUsers'         => $totalUsers,
            'totalRevenue'       => $summary['total_revenue'],
            'todayCount'         => $summary['today_count'],
            'todayRevenue'       => $summary['today_revenue'],
            'lowStockItems'      => $lowStockItems,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
