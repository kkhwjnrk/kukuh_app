<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Perusahaan;
use Exception;

/**
 * Class PerusahaanController
 * 
 * Mengelola Master Data Perusahaan (Penerbit Faktur Penjualan).
 * Sesuai Menu Administrator: Tampil Daftar, Tambah Data, Ubah Data, Hapus Data.
 */
class PerusahaanController extends Controller
{
    private Perusahaan $perusahaanModel;

    public function __construct()
    {
        parent::__construct();
        $this->perusahaanModel = new Perusahaan();
    }

    /**
     * Menampilkan daftar master data Perusahaan.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword = $request->query('q');
        $page = (int)($request->query('page') ?? 1);

        $perusahaan = $this->perusahaanModel->getFilteredPaginated($keyword, $page, 10);

        $this->view('perusahaan/index', [
            'pageTitle'  => 'Kelola Data Perusahaan',
            'perusahaan' => $perusahaan,
            'keyword'    => $keyword,
        ]);
    }

    /**
     * Menampilkan form tambah perusahaan baru.
     *
     * @return void
     */
    public function create(): void
    {
        $this->view('perusahaan/create', [
            'pageTitle' => 'Tambah Data Perusahaan',
        ]);
    }

    /**
     * Menyimpan data perusahaan baru.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|min:3',
            'alamat'          => 'required|min:5',
            'no_telp'         => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/perusahaan/create', $validator->firstError());
        }

        $this->perusahaanModel->create([
            'nama_perusahaan' => trim($request->input('nama_perusahaan')),
            'alamat'          => trim($request->input('alamat')),
            'no_telp'         => trim($request->input('no_telp')),
            'fax'             => trim($request->input('fax') ?? ''),
        ]);

        $this->redirectWithSuccess('/perusahaan', 'Data Perusahaan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form ubah data perusahaan.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        $data = $this->perusahaanModel->find($id);
        if (!$data) {
            $this->redirectWithError('/perusahaan', 'Data Perusahaan tidak ditemukan.');
        }

        $this->view('perusahaan/edit', [
            'pageTitle'  => 'Ubah Data Perusahaan',
            'perusahaan' => $data,
        ]);
    }

    /**
     * Menyimpan perubahan data perusahaan.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $data = $this->perusahaanModel->find($id);
        if (!$data) {
            $this->redirectWithError('/perusahaan', 'Data Perusahaan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|min:3',
            'alamat'          => 'required|min:5',
            'no_telp'         => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/perusahaan/edit/{$id}", $validator->firstError());
        }

        $this->perusahaanModel->update($id, [
            'nama_perusahaan' => trim($request->input('nama_perusahaan')),
            'alamat'          => trim($request->input('alamat')),
            'no_telp'         => trim($request->input('no_telp')),
            'fax'             => trim($request->input('fax') ?? ''),
        ]);

        $this->redirectWithSuccess('/perusahaan', 'Data Perusahaan berhasil diperbarui!');
    }

    /**
     * Menghapus data perusahaan.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        $data = $this->perusahaanModel->find($id);
        if (!$data) {
            $this->redirectWithError('/perusahaan', 'Data Perusahaan tidak ditemukan.');
        }

        try {
            $this->perusahaanModel->delete($id);
            $this->redirectWithSuccess('/perusahaan', 'Data Perusahaan berhasil dihapus.');
        } catch (Exception $e) {
            $this->redirectWithError('/perusahaan', 'Perusahaan tidak dapat dihapus karena masih digunakan pada data Faktur Penjualan.');
        }
    }
}
