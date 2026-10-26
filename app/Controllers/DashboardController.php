<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Customer;
use App\Models\Faktur;
use App\Models\Perusahaan;
use App\Models\Produk;
use App\Models\User;

/**
 * Class DashboardController
 * 
 * Mengelola tampilan dasbor utama, kalkulasi KPI operasional Sistem Faktur Penjualan,
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
        $perusahaanModel = new Perusahaan();
        $customerModel = new Customer();
        $produkModel = new Produk();
        $fakturModel = new Faktur();
        $userModel = new User();

        // 1. Kartu Ringkasan Statistik
        $totalPerusahaan = $perusahaanModel->count();
        $totalCustomer = $customerModel->count();
        $totalProduk = $produkModel->count();
        $totalUsers = $userModel->count();
        $summary = $fakturModel->getDashboardSummary();

        // 2. Notifikasi Stok Menipis (Early Warning)
        $lowStockItems = $produkModel->getLowStock(15);

        // 3. Riwayat 5 Faktur Terkini
        $recentFaktur = $fakturModel->getFilteredPaginated(null, null, null, 1, 5)['data'];

        $this->view('dashboard/index', [
            'pageTitle'          => 'Dasbor Utama - ' . $this->terms['app_name'],
            'totalPerusahaan'    => $totalPerusahaan,
            'totalCustomer'      => $totalCustomer,
            'totalProduk'        => $totalProduk,
            'totalUsers'         => $totalUsers,
            'totalRevenue'       => $summary['total_revenue'],
            'totalFaktur'        => $summary['total_faktur'],
            'todayCount'         => $summary['today_count'],
            'todayRevenue'       => $summary['today_revenue'],
            'lowStockItems'      => $lowStockItems,
            'recentFaktur'       => $recentFaktur,
        ]);
    }
}
