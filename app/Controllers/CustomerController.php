<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Customer;
use Exception;

/**
 * Class CustomerController
 * 
 * Mengelola Master Data Customer (Pelanggan).
 * Sesuai Menu Administrator: Tampil Daftar, Tambah Data, Ubah Data, Hapus Data, Preview/Cetak Data Customer.
 */
class CustomerController extends Controller
{
    private Customer $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new Customer();
    }

    /**
     * Menampilkan daftar master data Customer.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword = $request->query('q');
        $page = (int)($request->query('page') ?? 1);

        $customer = $this->customerModel->getFilteredPaginated($keyword, $page, 10);

        $this->view('customer/index', [
            'pageTitle' => 'Kelola Data Customer',
            'customer'  => $customer,
            'keyword'   => $keyword,
        ]);
    }

    /**
     * Menampilkan form tambah customer baru.
     *
     * @return void
     */
    public function create(): void
    {
        $this->view('customer/create', [
            'pageTitle' => 'Tambah Data Customer',
        ]);
    }

    /**
     * Menyimpan data customer baru.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'nama_customer'   => 'required|min:3',
            'perusahaan_cust' => 'required|min:2',
            'alamat'          => 'required|min:5',
            'no_telp'         => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/customer/create', $validator->firstError());
        }

        $this->customerModel->create([
            'nama_customer'   => trim($request->input('nama_customer')),
            'perusahaan_cust' => trim($request->input('perusahaan_cust')),
            'alamat'          => trim($request->input('alamat')),
            'no_telp'         => trim($request->input('no_telp')),
        ]);

        $this->redirectWithSuccess('/customer', 'Data Customer baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form ubah data customer.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        $data = $this->customerModel->find($id);
        if (!$data) {
            $this->redirectWithError('/customer', 'Data Customer tidak ditemukan.');
        }

        $this->view('customer/edit', [
            'pageTitle' => 'Ubah Data Customer',
            'customer'  => $data,
        ]);
    }

    /**
     * Menyimpan pembaruan data customer.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $data = $this->customerModel->find($id);
        if (!$data) {
            $this->redirectWithError('/customer', 'Data Customer tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_customer'   => 'required|min:3',
            'perusahaan_cust' => 'required|min:2',
            'alamat'          => 'required|min:5',
            'no_telp'         => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/customer/edit/{$id}", $validator->firstError());
        }

        $this->customerModel->update($id, [
            'nama_customer'   => trim($request->input('nama_customer')),
            'perusahaan_cust' => trim($request->input('perusahaan_cust')),
            'alamat'          => trim($request->input('alamat')),
            'no_telp'         => trim($request->input('no_telp')),
        ]);

        $this->redirectWithSuccess('/customer', 'Data Customer berhasil diperbarui!');
    }

    /**
     * Menghapus data customer.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        $data = $this->customerModel->find($id);
        if (!$data) {
            $this->redirectWithError('/customer', 'Data Customer tidak ditemukan.');
        }

        try {
            $this->customerModel->delete($id);
            $this->redirectWithSuccess('/customer', 'Data Customer berhasil dihapus.');
        } catch (Exception $e) {
            $this->redirectWithError('/customer', 'Customer tidak dapat dihapus karena masih terhubung dengan data Faktur Penjualan.');
        }
    }

    /**
     * Menampilkan halaman Preview / Cetak Data Customer.
     * Fitur khusus yang tercantum pada Menu Administrator (Photo 3).
     *
     * @param Request $request
     * @return void
     */
    public function printPreview(Request $request): void
    {
        $keyword = $request->query('q');
        $customers = $this->customerModel->getFiltered($keyword);

        // Render standalone printable view without sidebar
        $this->view('customer/print', [
            'pageTitle' => 'Laporan Rekap Data Customer',
            'customers' => $customers,
            'keyword'   => $keyword,
        ], null);
    }
}
