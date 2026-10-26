<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Customer;
use App\Models\Faktur;
use App\Models\Perusahaan;
use App\Models\Produk;
use Exception;

/**
 * Class FakturController
 * 
 * Mengelola Transaksi Faktur Penjualan (Header & Multi-Item Detail).
 * Sesuai Menu Administrator: Tampil Daftar, Tambah Penjualan, Ubah Penjualan, Hapus Penjualan, Cetak Faktur.
 */
class FakturController extends Controller
{
    private Faktur $fakturModel;
    private Perusahaan $perusahaanModel;
    private Customer $customerModel;
    private Produk $produkModel;

    public function __construct()
    {
        parent::__construct();
        $this->fakturModel = new Faktur();
        $this->perusahaanModel = new Perusahaan();
        $this->customerModel = new Customer();
        $this->produkModel = new Produk();
    }

    /**
     * Menampilkan daftar transaksi Faktur Penjualan.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword = $request->query('q');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $page = (int)($request->query('page') ?? 1);

        $fakturList = $this->fakturModel->getFilteredPaginated($keyword, $startDate, $endDate, $page, 10);

        $this->view('faktur/index', [
            'pageTitle'  => 'Kelola Data Penjualan (Faktur)',
            'fakturList' => $fakturList,
            'keyword'    => $keyword,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
        ]);
    }

    /**
     * Menampilkan formulir pembuatan Faktur Penjualan baru.
     *
     * @return void
     */
    public function create(): void
    {
        $perusahaanList = $this->perusahaanModel->getAllOrdered();
        $customerList = $this->customerModel->getAllOrdered();
        $produkList = $this->produkModel->all('nama_produk', 'ASC');
        $autoNoFaktur = $this->fakturModel->generateNoFaktur();

        $this->view('faktur/create', [
            'pageTitle'      => 'Tambah Faktur Penjualan',
            'perusahaanList' => $perusahaanList,
            'customerList'   => $customerList,
            'produkList'     => $produkList,
            'autoNoFaktur'   => $autoNoFaktur,
        ]);
    }

    /**
     * Menyimpan faktur dan detail produk secara transaksi atomik (ACID).
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'no_faktur'     => 'required|min:3',
            'tgl_faktur'    => 'required',
            'due_date'      => 'required',
            'metode_bayar'  => 'required',
            'id_customer'   => 'required|numeric',
            'id_perusahaan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/faktur/create', $validator->firstError());
        }

        $produkIds = $request->input('id_produk') ?? [];
        $qtys = $request->input('qty') ?? [];
        $prices = $request->input('price') ?? [];

        if (empty($produkIds) || !is_array($produkIds)) {
            $this->redirectWithError('/faktur/create', 'Minimal harus memilih satu produk dalam faktur!');
        }

        $items = [];
        $subtotal = 0;

        for ($i = 0; $i < count($produkIds); $i++) {
            $idProduk = (int)($produkIds[$i] ?? 0);
            $qty = (int)($qtys[$i] ?? 0);
            $price = (float)($prices[$i] ?? 0);

            if ($idProduk > 0 && $qty > 0) {
                $items[] = [
                    'id_produk' => $idProduk,
                    'qty'       => $qty,
                    'price'     => $price,
                ];
                $subtotal += ($qty * $price);
            }
        }

        if (empty($items)) {
            $this->redirectWithError('/faktur/create', 'Rincian produk tidak valid atau kuantitas 0.');
        }

        // Kalkulasi PPN, DP, dan Grand Total
        $ppnInput = (float)($request->input('ppn') ?? 0);
        $dpInput = (float)($request->input('dp') ?? 0);
        $grandTotal = max(0, $subtotal + $ppnInput - $dpInput);

        $currentUser = auth('nama_lengkap') ?? auth('username') ?? 'Admin';

        $fakturData = [
            'no_faktur'     => trim($request->input('no_faktur')),
            'tgl_faktur'    => $request->input('tgl_faktur'),
            'due_date'      => $request->input('due_date'),
            'metode_bayar'  => trim($request->input('metode_bayar')),
            'ppn'           => $ppnInput,
            'dp'            => $dpInput,
            'grand_total'   => $grandTotal,
            'user'          => $currentUser,
            'id_customer'   => (int)$request->input('id_customer'),
            'id_perusahaan' => (int)$request->input('id_perusahaan'),
        ];

        try {
            $noFaktur = $this->fakturModel->createWithDetails($fakturData, $items);
            $this->redirectWithSuccess("/faktur/show/{$noFaktur}", "Faktur Penjualan [{$noFaktur}] berhasil diterbitkan!");
        } catch (Exception $e) {
            $this->redirectWithError('/faktur/create', 'Gagal memproses faktur: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail faktur penjualan.
     *
     * @param Request $request
     * @param string $noFaktur
     * @return void
     */
    public function show(Request $request, string $noFaktur): void
    {
        $faktur = $this->fakturModel->findWithDetails($noFaktur);
        if (!$faktur) {
            $this->redirectWithError('/faktur', 'Data Faktur tidak ditemukan.');
        }

        $this->view('faktur/show', [
            'pageTitle' => "Detail Faktur #{$noFaktur}",
            'faktur'    => $faktur,
        ]);
    }

    /**
     * Menampilkan formulir ubah data faktur penjualan.
     *
     * @param Request $request
     * @param string $noFaktur
     * @return void
     */
    public function edit(Request $request, string $noFaktur): void
    {
        $faktur = $this->fakturModel->findWithDetails($noFaktur);
        if (!$faktur) {
            $this->redirectWithError('/faktur', 'Data Faktur tidak ditemukan.');
        }

        $perusahaanList = $this->perusahaanModel->getAllOrdered();
        $customerList = $this->customerModel->getAllOrdered();
        $produkList = $this->produkModel->all('nama_produk', 'ASC');

        $this->view('faktur/edit', [
            'pageTitle'      => "Ubah Faktur #{$noFaktur}",
            'faktur'         => $faktur,
            'perusahaanList' => $perusahaanList,
            'customerList'   => $customerList,
            'produkList'     => $produkList,
        ]);
    }

    /**
     * Menyimpan pembaruan faktur penjualan.
     *
     * @param Request $request
     * @param string $noFaktur
     * @return void
     */
    public function update(Request $request, string $noFaktur): void
    {
        $faktur = $this->fakturModel->findWithDetails($noFaktur);
        if (!$faktur) {
            $this->redirectWithError('/faktur', 'Data Faktur tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'tgl_faktur'    => 'required',
            'due_date'      => 'required',
            'metode_bayar'  => 'required',
            'id_customer'   => 'required|numeric',
            'id_perusahaan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/faktur/edit/{$noFaktur}", $validator->firstError());
        }

        $produkIds = $request->input('id_produk') ?? [];
        $qtys = $request->input('qty') ?? [];
        $prices = $request->input('price') ?? [];

        $items = [];
        $subtotal = 0;

        for ($i = 0; $i < count($produkIds); $i++) {
            $idProduk = (int)($produkIds[$i] ?? 0);
            $qty = (int)($qtys[$i] ?? 0);
            $price = (float)($prices[$i] ?? 0);

            if ($idProduk > 0 && $qty > 0) {
                $items[] = [
                    'id_produk' => $idProduk,
                    'qty'       => $qty,
                    'price'     => $price,
                ];
                $subtotal += ($qty * $price);
            }
        }

        if (empty($items)) {
            $this->redirectWithError("/faktur/edit/{$noFaktur}", 'Rincian produk tidak valid.');
        }

        $ppnInput = (float)($request->input('ppn') ?? 0);
        $dpInput = (float)($request->input('dp') ?? 0);
        $grandTotal = max(0, $subtotal + $ppnInput - $dpInput);

        $currentUser = auth('nama_lengkap') ?? auth('username') ?? 'Admin';

        $fakturData = [
            'tgl_faktur'    => $request->input('tgl_faktur'),
            'due_date'      => $request->input('due_date'),
            'metode_bayar'  => trim($request->input('metode_bayar')),
            'ppn'           => $ppnInput,
            'dp'            => $dpInput,
            'grand_total'   => $grandTotal,
            'user'          => $currentUser,
            'id_customer'   => (int)$request->input('id_customer'),
            'id_perusahaan' => (int)$request->input('id_perusahaan'),
        ];

        try {
            $this->fakturModel->updateWithDetails($noFaktur, $fakturData, $items);
            $this->redirectWithSuccess("/faktur/show/{$noFaktur}", "Data Faktur [{$noFaktur}] berhasil diperbarui!");
        } catch (Exception $e) {
            $this->redirectWithError("/faktur/edit/{$noFaktur}", 'Gagal memperbarui faktur: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus faktur penjualan serta mengembalikan stok produk terkait.
     *
     * @param Request $request
     * @param string $noFaktur
     * @return void
     */
    public function delete(Request $request, string $noFaktur): void
    {
        $faktur = $this->fakturModel->findWithDetails($noFaktur);
        if (!$faktur) {
            $this->redirectWithError('/faktur', 'Data Faktur tidak ditemukan.');
        }

        try {
            $this->fakturModel->deleteWithDetails($noFaktur);
            $this->redirectWithSuccess('/faktur', "Faktur [{$noFaktur}] berhasil dibatalkan/dihapus dan stok telah dikembalikan!");
        } catch (Exception $e) {
            $this->redirectWithError('/faktur', 'Gagal menghapus faktur: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan tampilan Cetak Faktur Penjualan resmi (sesuai tata letak Photo 3).
     *
     * @param Request $request
     * @param string $noFaktur
     * @return void
     */
    public function printView(Request $request, string $noFaktur): void
    {
        $faktur = $this->fakturModel->findWithDetails($noFaktur);
        if (!$faktur) {
            $this->redirectWithError('/faktur', 'Data Faktur tidak ditemukan.');
        }

        $this->view('faktur/print', [
            'pageTitle' => "Cetak Faktur Penjualan - {$noFaktur}",
            'faktur'    => $faktur,
        ], null);
    }
}
