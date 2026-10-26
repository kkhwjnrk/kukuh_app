<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\Produk;
use Exception;

/**
 * Class ProdukController
 * 
 * Mengelola Master Data Produk (Barang/Obat/Item Penjualan).
 * Sesuai Menu Administrator: Tampil Daftar, Tambah Data, Ubah Data, Hapus Data.
 */
class ProdukController extends Controller
{
    private Produk $produkModel;

    public function __construct()
    {
        parent::__construct();
        $this->produkModel = new Produk();
    }

    /**
     * Menampilkan daftar master data Produk.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword = $request->query('q');
        $jenis = $request->query('jenis');
        $page = (int)($request->query('page') ?? 1);

        $produk = $this->produkModel->getFilteredPaginated($keyword, $jenis, $page, 10);
        $jenisList = $this->produkModel->getUniqueJenis();

        $this->view('produk/index', [
            'pageTitle' => 'Kelola Data Produk',
            'produk'    => $produk,
            'jenisList' => $jenisList,
            'keyword'   => $keyword,
            'jenis'     => $jenis,
        ]);
    }

    /**
     * Menampilkan formulir penambahan produk baru.
     *
     * @return void
     */
    public function create(): void
    {
        $jenisList = $this->produkModel->getUniqueJenis();

        $this->view('produk/create', [
            'pageTitle' => 'Tambah Data Produk',
            'jenisList' => $jenisList,
        ]);
    }

    /**
     * Menyimpan produk baru.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|min:2',
            'price'       => 'required|numeric|min:0',
            'jenis'       => 'required|min:2',
            'stock'       => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/produk/create', $validator->firstError());
        }

        $this->produkModel->create([
            'nama_produk' => trim($request->input('nama_produk')),
            'price'       => (float)$request->input('price'),
            'jenis'       => trim($request->input('jenis')),
            'stock'       => (int)$request->input('stock'),
        ]);

        $this->redirectWithSuccess('/produk', 'Produk baru berhasil disimpan!');
    }

    /**
     * Menampilkan formulir ubah data produk.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        $data = $this->produkModel->find($id);
        if (!$data) {
            $this->redirectWithError('/produk', 'Produk tidak ditemukan.');
        }

        $jenisList = $this->produkModel->getUniqueJenis();

        $this->view('produk/edit', [
            'pageTitle' => 'Ubah Data Produk',
            'produk'    => $data,
            'jenisList' => $jenisList,
        ]);
    }

    /**
     * Menyimpan pembaruan data produk.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $data = $this->produkModel->find($id);
        if (!$data) {
            $this->redirectWithError('/produk', 'Produk tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|min:2',
            'price'       => 'required|numeric|min:0',
            'jenis'       => 'required|min:2',
            'stock'       => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/produk/edit/{$id}", $validator->firstError());
        }

        $this->produkModel->update($id, [
            'nama_produk' => trim($request->input('nama_produk')),
            'price'       => (float)$request->input('price'),
            'jenis'       => trim($request->input('jenis')),
            'stock'       => (int)$request->input('stock'),
        ]);

        $this->redirectWithSuccess('/produk', 'Data produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        $data = $this->produkModel->find($id);
        if (!$data) {
            $this->redirectWithError('/produk', 'Produk tidak ditemukan.');
        }

        try {
            $this->produkModel->delete($id);
            $this->redirectWithSuccess('/produk', 'Data produk berhasil dihapus.');
        } catch (Exception $e) {
            $this->redirectWithError('/produk', 'Produk tidak dapat dihapus karena sudah tercatat dalam Detail Faktur Penjualan.');
        }
    }

    /**
     * Endpoint API JSON untuk pencarian produk interaktif (autocomplete) pada pembuatan faktur.
     *
     * @param Request $request
     * @return void
     */
    public function searchJson(Request $request): void
    {
        $query = $request->query('q') ?? '';
        $items = $this->produkModel->search($query);

        Response::json([
            'status' => 'success',
            'data'   => $items,
        ]);
    }
}
