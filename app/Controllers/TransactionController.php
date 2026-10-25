<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Item;
use App\Models\Transaction;
use Exception;

/**
 * Class TransactionController
 * 
 * Mengelola alur transaksi kasir / peminjaman / pemesanan multi-item,
 * pembuatan invoice, pencetakan nota, dan verifikasi stok secara real-time.
 */
class TransactionController extends Controller
{
    private Transaction $transModel;
    private Item $itemModel;

    public function __construct()
    {
        parent::__construct();
        $this->transModel = new Transaction();
        $this->itemModel = new Item();
    }

    /**
     * Menampilkan daftar seluruh transaksi yang tercatat.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword   = $request->input('q');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $page      = (int)($request->input('page', 1));

        $result = $this->transModel->getFilteredPaginated($keyword, $startDate, $endDate, $page, 10);

        $this->view('transactions/index', [
            'pageTitle'    => $this->terms['transaction']['plural'],
            'transactions' => $result['data'],
            'pagination'   => $result,
            'keyword'      => $keyword,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
        ]);
    }

    /**
     * Menampilkan antarmuka pembuatan transaksi baru (Interactive Cart).
     *
     * @return void
     */
    public function create(): void
    {
        $autoCode = $this->transModel->generateCode('TRX');
        // Ambil barang-barang yang stoknya masih tersedia
        $availableItems = $this->itemModel->query(
            "SELECT id, kode_item, nama_item, harga, stok FROM `items` WHERE `stok` > 0 ORDER BY `nama_item` ASC"
        );

        $this->view('transactions/create', [
            'pageTitle'      => 'Entri ' . $this->terms['transaction']['singular'] . ' Baru',
            'autoCode'       => $autoCode,
            'availableItems' => $availableItems,
        ]);
    }

    /**
     * Memproses penyimpanan transaksi multi-item dengan atomic database transaction.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'kode_transaksi' => 'required|unique:transactions,kode_transaksi',
            'tanggal'        => 'required',
            'nama_pelanggan' => 'required',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/transactions/create', 'Mohon lengkapi seluruh data transaksi.');
        }

        $itemIds  = $request->input('item_id', []);
        $qtys     = $request->input('qty', []);
        $prices   = $request->input('harga', []);

        if (empty($itemIds) || !is_array($itemIds) || count($itemIds) === 0) {
            $this->redirectWithError('/transactions/create', 'Minimal harus memilih 1 barang dalam transaksi.');
        }

        $cartItems = [];
        $totalBayar = 0;

        foreach ($itemIds as $index => $itemId) {
            $qty = (int)($qtys[$index] ?? 1);
            $price = (float)($prices[$index] ?? 0);

            if ($qty <= 0) {
                continue;
            }

            $cartItems[] = [
                'item_id' => (int)$itemId,
                'qty'     => $qty,
                'harga'   => $price,
            ];

            $totalBayar += ($price * $qty);
        }

        if (empty($cartItems)) {
            $this->redirectWithError('/transactions/create', 'Daftar barang transaksi tidak boleh kosong.');
        }

        $transData = [
            'kode_transaksi' => $request->input('kode_transaksi'),
            'tanggal'        => $request->input('tanggal'),
            'user_id'        => Auth::id() ?? 1,
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'total_bayar'    => $totalBayar,
            'status'         => 'selesai',
            'catatan'        => $request->input('catatan'),
        ];

        try {
            $newTransId = $this->transModel->createWithDetails($transData, $cartItems);
            $this->redirectWithSuccess("/transactions/show/{$newTransId}", 'Transaksi berhasil diproses dan disimpan!');
        } catch (Exception $e) {
            $this->redirectWithError('/transactions/create', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan faktur / rincian lengkap transaksi.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function show(Request $request, $id): void
    {
        $transaction = $this->transModel->findWithDetails($id);
        if (!$transaction) {
            $this->redirectWithError('/transactions', 'Data transaksi tidak ditemukan.');
        }

        $this->view('transactions/show', [
            'pageTitle'   => 'Faktur ' . $transaction['kode_transaksi'],
            'transaction' => $transaction,
        ]);
    }

    /**
     * Menampilkan tampilan cetak struk / faktur resmi tanpa header/sidebar.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function printView(Request $request, $id): void
    {
        $transaction = $this->transModel->findWithDetails($id);
        if (!$transaction) {
            die('Data transaksi tidak ditemukan.');
        }

        $this->view('transactions/print', [
            'pageTitle'   => 'Cetak Nota - ' . $transaction['kode_transaksi'],
            'transaction' => $transaction,
        ], null);
    }
}
