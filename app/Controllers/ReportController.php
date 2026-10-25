<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Transaction;

/**
 * Class ReportController
 * 
 * Mengelola rekapitulasi data, laporan periodik, ekspor file Excel/CSV,
 * dan format dokumen cetak resmi (Print/PDF).
 */
class ReportController extends Controller
{
    private Transaction $transModel;

    public function __construct()
    {
        parent::__construct();
        $this->transModel = new Transaction();
    }

    /**
     * Menampilkan antarmuka filter dan tabel laporan transaksi.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $sql = "SELECT t.*, u.nama_lengkap as nama_petugas 
                FROM `transactions` t
                LEFT JOIN `users` u ON t.user_id = u.id
                WHERE t.tanggal BETWEEN :sdate AND :edate
                ORDER BY t.tanggal DESC, t.id DESC";

        $transactions = $this->transModel->query($sql, [
            ':sdate' => $startDate,
            ':edate' => $endDate,
        ]);

        $totalOmset = 0;
        foreach ($transactions as $t) {
            if ($t['status'] === 'selesai') {
                $totalOmset += (float)$t['total_bayar'];
            }
        }

        $this->view('reports/index', [
            'pageTitle'    => $this->terms['report']['title'],
            'transactions' => $transactions,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'totalOmset'   => $totalOmset,
            'totalCount'   => count($transactions),
        ]);
    }

    /**
     * Mengekspor data laporan ke format CSV / Excel secara native tanpa ketergantungan library.
     *
     * @param Request $request
     * @return void
     */
    public function exportExcel(Request $request): void
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $sql = "SELECT t.kode_transaksi, t.tanggal, t.nama_pelanggan, u.nama_lengkap as petugas, t.total_bayar, t.status 
                FROM `transactions` t
                LEFT JOIN `users` u ON t.user_id = u.id
                WHERE t.tanggal BETWEEN :sdate AND :edate
                ORDER BY t.tanggal ASC";

        $rows = $this->transModel->query($sql, [
            ':sdate' => $startDate,
            ':edate' => $endDate,
        ]);

        $filename = 'Laporan_Transaksi_' . $startDate . '_sd_' . $endDate . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Tulis UTF-8 BOM agar terbaca sempurna di Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");

        // Header Kolom
        fputcsv($output, ['No. Faktur', 'Tanggal', 'Nama Pelanggan / Peminjam', 'Petugas', 'Total Nilai (Rp)', 'Status']);

        $total = 0;
        foreach ($rows as $row) {
            $total += (float)$row['total_bayar'];
            fputcsv($output, [
                $row['kode_transaksi'],
                $row['tanggal'],
                $row['nama_pelanggan'],
                $row['petugas'],
                $row['total_bayar'],
                ucfirst($row['status']),
            ]);
        }

        // Baris Total
        fputcsv($output, ['', '', '', 'TOTAL KESELURUHAN', $total, '']);

        fclose($output);
        exit;
    }

    /**
     * Menampilkan halaman cetak laporan yang siap dicetak ke printer atau disimpan sebagai PDF.
     *
     * @param Request $request
     * @return void
     */
    public function printView(Request $request): void
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $sql = "SELECT t.*, u.nama_lengkap as nama_petugas 
                FROM `transactions` t
                LEFT JOIN `users` u ON t.user_id = u.id
                WHERE t.tanggal BETWEEN :sdate AND :edate
                ORDER BY t.tanggal ASC";

        $transactions = $this->transModel->query($sql, [
            ':sdate' => $startDate,
            ':edate' => $endDate,
        ]);

        $totalOmset = 0;
        foreach ($transactions as $t) {
            if ($t['status'] === 'selesai') {
                $totalOmset += (float)$t['total_bayar'];
            }
        }

        $this->view('reports/print', [
            'pageTitle'    => 'Cetak ' . $this->terms['report']['title'],
            'transactions' => $transactions,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'totalOmset'   => $totalOmset,
        ], null);
    }
}
