<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Customer;
use App\Models\Faktur;
use App\Models\Produk;

/**
 * Class ReportController
 * 
 * Mengelola Modul Laporan Sistem Apotek LSP:
 * 1. Laporan Penjualan (Faktur Penjualan, Omset, PPN, Piutang)
 * 2. Laporan Produk (Stok, Nilai Aset/Persediaan, Status Stok)
 * Mendukung filter interaktif, cetak dokumen PDF/Print resmi, dan ekspor Excel/CSV.
 */
class ReportController extends Controller
{
    private Faktur $fakturModel;
    private Produk $produkModel;
    private Customer $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->fakturModel = new Faktur();
        $this->produkModel = new Produk();
        $this->customerModel = new Customer();
    }

    // =========================================================================
    // 1. MODUL LAPORAN PENJUALAN (FAKTUR)
    // =========================================================================

    /**
     * Menampilkan antarmuka dan rekapitulasi data Laporan Penjualan.
     *
     * @param Request $request
     * @return void
     */
    public function penjualan(Request $request): void
    {
        $startDate   = $request->input('start_date', date('Y-m-01'));
        $endDate     = $request->input('end_date', date('Y-m-d'));
        $metodeBayar = $request->input('metode_bayar', '');
        $customerId  = $request->input('id_customer', '');

        $data = $this->getPenjualanData($startDate, $endDate, $metodeBayar, $customerId);

        $this->view('reports/penjualan', [
            'pageTitle'    => 'Laporan Penjualan',
            'fakturList'   => $data['rows'],
            'summary'      => $data['summary'],
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'metodeBayar'  => $metodeBayar,
            'customerId'   => $customerId,
            'customers'    => $this->customerModel->all('nama_customer', 'ASC'),
        ]);
    }

    /**
     * Mengekspor Laporan Penjualan ke file CSV (Kompatibel Microsoft Excel).
     *
     * @param Request $request
     * @return void
     */
    public function exportPenjualanExcel(Request $request): void
    {
        $startDate   = $request->input('start_date', date('Y-m-01'));
        $endDate     = $request->input('end_date', date('Y-m-d'));
        $metodeBayar = $request->input('metode_bayar', '');
        $customerId  = $request->input('id_customer', '');

        $data = $this->getPenjualanData($startDate, $endDate, $metodeBayar, $customerId);
        $filename = 'Laporan_Penjualan_' . $startDate . '_sd_' . $endDate . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM untuk Excel
        fputs($out, "\xEF\xBB\xBF");

        // Judul Laporan
        fputcsv($out, ['LAPORAN PENJUALAN - ' . strtoupper($this->terms['app_name'])]);
        fputcsv($out, ['Periode: ' . $startDate . ' s/d ' . $endDate]);
        fputcsv($out, []); // Baris kosong

        // Header Kolom
        fputcsv($out, [
            'No',
            'No. Faktur',
            'Tanggal',
            'Jatuh Tempo',
            'Customer',
            'Perusahaan Penerbit',
            'Metode Bayar',
            'Subtotal (Rp)',
            'PPN (Rp)',
            'DP / Bayar (Rp)',
            'Grand Total (Rp)',
            'Sisa Tagihan (Rp)',
            'Petugas'
        ]);

        $no = 1;
        foreach ($data['rows'] as $r) {
            $sisa = max(0, (float)$r['grand_total'] - (float)$r['dp']);
            fputcsv($out, [
                $no++,
                $r['no_faktur'],
                $r['tgl_faktur'],
                $r['due_date'],
                $r['nama_customer'],
                $r['nama_perusahaan'],
                $r['metode_bayar'],
                $r['subtotal'],
                $r['ppn'],
                $r['dp'],
                $r['grand_total'],
                $sisa,
                $r['user']
            ]);
        }

        // Baris Total Ringkasan
        fputcsv($out, []);
        fputcsv($out, [
            '',
            'TOTAL KESELURUHAN',
            '',
            '',
            '',
            '',
            '',
            $data['summary']['total_subtotal'],
            $data['summary']['total_ppn'],
            $data['summary']['total_dp'],
            $data['summary']['total_grand_total'],
            $data['summary']['total_sisa'],
            ''
        ]);

        fclose($out);
        exit;
    }

    /**
     * Menampilkan format cetak resmi Laporan Penjualan (Print / PDF).
     *
     * @param Request $request
     * @return void
     */
    public function printPenjualan(Request $request): void
    {
        $startDate   = $request->input('start_date', date('Y-m-01'));
        $endDate     = $request->input('end_date', date('Y-m-d'));
        $metodeBayar = $request->input('metode_bayar', '');
        $customerId  = $request->input('id_customer', '');

        $data = $this->getPenjualanData($startDate, $endDate, $metodeBayar, $customerId);

        $this->view('reports/penjualan_print', [
            'pageTitle'  => 'Cetak Laporan Penjualan',
            'fakturList' => $data['rows'],
            'summary'    => $data['summary'],
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'printDate'  => date('Y-m-d H:i'),
        ], false);
    }

    // =========================================================================
    // 2. MODUL LAPORAN PRODUK & STOK
    // =========================================================================

    /**
     * Menampilkan antarmuka dan rekapitulasi data Laporan Produk.
     *
     * @param Request $request
     * @return void
     */
    public function produk(Request $request): void
    {
        $keyword    = $request->input('q', '');
        $jenis      = $request->input('jenis', '');
        $statusStok = $request->input('status_stok', '');

        $data = $this->getProdukData($keyword, $jenis, $statusStok);

        // Ambil daftar jenis produk unik untuk opsi filter dropdown
        $jenisList = $this->produkModel->query("SELECT DISTINCT `jenis` FROM `produk` WHERE `jenis` IS NOT NULL AND `jenis` != '' ORDER BY `jenis` ASC");

        $this->view('reports/produk', [
            'pageTitle'   => 'Laporan Data Produk & Stok',
            'produkList'  => $data['rows'],
            'summary'     => $data['summary'],
            'keyword'     => $keyword,
            'jenis'       => $jenis,
            'statusStok'  => $statusStok,
            'jenisList'   => array_column($jenisList, 'jenis'),
        ]);
    }

    /**
     * Mengekspor Laporan Produk ke file CSV (Kompatibel Microsoft Excel).
     *
     * @param Request $request
     * @return void
     */
    public function exportProdukExcel(Request $request): void
    {
        $keyword    = $request->input('q', '');
        $jenis      = $request->input('jenis', '');
        $statusStok = $request->input('status_stok', '');

        $data = $this->getProdukData($keyword, $jenis, $statusStok);
        $filename = 'Laporan_Produk_Stok_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF");

        fputcsv($out, ['LAPORAN DATA PRODUK & STOK - ' . strtoupper($this->terms['app_name'])]);
        fputcsv($out, ['Tanggal Unduh: ' . date('d/m/Y H:i:s')]);
        fputcsv($out, []);

        fputcsv($out, [
            'No',
            'ID Produk',
            'Nama Produk',
            'Jenis / Satuan',
            'Harga Satuan (Rp)',
            'Stok Fisik',
            'Total Nilai Aset (Rp)',
            'Status Stok'
        ]);

        $no = 1;
        foreach ($data['rows'] as $p) {
            $status = ($p['stock'] <= 0) ? 'Habis' : (($p['stock'] <= 10) ? 'Menipis' : 'Tersedia');
            fputcsv($out, [
                $no++,
                $p['id_produk'],
                $p['nama_produk'],
                $p['jenis'],
                $p['price'],
                $p['stock'],
                $p['nilai_aset'],
                $status
            ]);
        }

        fputcsv($out, []);
        fputcsv($out, [
            '',
            'TOTAL KESELURUHAN',
            '',
            '',
            '',
            $data['summary']['total_stok'],
            $data['summary']['total_nilai_aset'],
            ''
        ]);

        fclose($out);
        exit;
    }

    /**
     * Menampilkan format cetak resmi Laporan Produk (Print / PDF).
     *
     * @param Request $request
     * @return void
     */
    public function printProduk(Request $request): void
    {
        $keyword    = $request->input('q', '');
        $jenis      = $request->input('jenis', '');
        $statusStok = $request->input('status_stok', '');

        $data = $this->getProdukData($keyword, $jenis, $statusStok);

        $this->view('reports/produk_print', [
            'pageTitle'  => 'Cetak Laporan Produk & Stok',
            'produkList' => $data['rows'],
            'summary'    => $data['summary'],
            'printDate'  => date('Y-m-d H:i'),
        ], false);
    }

    // =========================================================================
    // HELPER QUERY DATA
    // =========================================================================

    /**
     * Query data rekapitulasi penjualan dan hitung akumulasi ringkasan.
     */
    private function getPenjualanData(string $startDate, string $endDate, string $metodeBayar = '', string $customerId = ''): array
    {
        $where = ["f.tgl_faktur BETWEEN :sdate AND :edate"];
        $params = [
            ':sdate' => $startDate,
            ':edate' => $endDate,
        ];

        if (!empty($metodeBayar)) {
            $where[] = "f.metode_bayar = :mb";
            $params[':mb'] = $metodeBayar;
        }

        if (!empty($customerId)) {
            $where[] = "f.id_customer = :cid";
            $params[':cid'] = $customerId;
        }

        $whereSql = implode(' AND ', $where);

        $sql = "SELECT f.*, 
                       c.nama_customer, c.perusahaan_cust, 
                       p.nama_perusahaan,
                       COALESCE((SELECT SUM(df.qty * df.price) FROM detail_faktur df WHERE df.no_faktur = f.no_faktur), 0) AS subtotal,
                       COALESCE((SELECT COUNT(*) FROM detail_faktur df WHERE df.no_faktur = f.no_faktur), 0) AS total_item_terjual
                FROM `faktur` f
                JOIN `customer` c ON f.id_customer = c.id_customer
                JOIN `perusahaan` p ON f.id_perusahaan = p.id_perusahaan
                WHERE {$whereSql}
                ORDER BY f.tgl_faktur DESC, f.no_faktur DESC";

        $rows = $this->fakturModel->query($sql, $params);

        $totalSubtotal   = 0;
        $totalPpn        = 0;
        $totalGrandTotal = 0;
        $totalDp         = 0;
        $totalSisa       = 0;

        foreach ($rows as $r) {
            $totalSubtotal   += (float)$r['subtotal'];
            $totalPpn        += (float)$r['ppn'];
            $totalGrandTotal += (float)$r['grand_total'];
            $totalDp         += (float)$r['dp'];
            $sisa             = max(0, (float)$r['grand_total'] - (float)$r['dp']);
            $totalSisa       += $sisa;
        }

        return [
            'rows' => $rows,
            'summary' => [
                'total_faktur'      => count($rows),
                'total_subtotal'    => $totalSubtotal,
                'total_ppn'         => $totalPpn,
                'total_grand_total' => $totalGrandTotal,
                'total_dp'          => $totalDp,
                'total_sisa'        => $totalSisa,
            ]
        ];
    }

    /**
     * Query data rekapitulasi produk dan hitung akumulasi ringkasan.
     */
    private function getProdukData(string $keyword = '', string $jenis = '', string $statusStok = ''): array
    {
        $where = ["1=1"];
        $params = [];

        if (!empty($keyword)) {
            $where[] = "(p.nama_produk LIKE :kw OR p.jenis LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($jenis)) {
            $where[] = "p.jenis = :jenis";
            $params[':jenis'] = $jenis;
        }

        if ($statusStok === 'tersedia') {
            $where[] = "p.stock > 10";
        } elseif ($statusStok === 'menipis') {
            $where[] = "p.stock > 0 AND p.stock <= 10";
        } elseif ($statusStok === 'habis') {
            $where[] = "p.stock <= 0";
        }

        $whereSql = implode(' AND ', $where);

        $sql = "SELECT p.*,
                       (p.stock * p.price) AS nilai_aset,
                       COALESCE((SELECT SUM(df.qty) FROM detail_faktur df WHERE df.id_produk = p.id_produk), 0) AS total_terjual
                FROM `produk` p
                WHERE {$whereSql}
                ORDER BY p.id_produk ASC";

        $rows = $this->produkModel->query($sql, $params);

        $totalStok      = 0;
        $totalNilaiAset = 0;
        $countMenipis   = 0;
        $countHabis     = 0;

        foreach ($rows as $p) {
            $stok = (int)$p['stock'];
            $totalStok      += $stok;
            $totalNilaiAset += (float)$p['nilai_aset'];
            if ($stok <= 0) {
                $countHabis++;
            } elseif ($stok <= 10) {
                $countMenipis++;
            }
        }

        return [
            'rows' => $rows,
            'summary' => [
                'total_produk'     => count($rows),
                'total_stok'       => $totalStok,
                'total_nilai_aset' => $totalNilaiAset,
                'count_menipis'    => $countMenipis,
                'count_habis'      => $countHabis,
            ]
        ];
    }
}
