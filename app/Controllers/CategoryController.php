<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Category;

/**
 * Class CategoryController
 * 
 * Mengelola data klasifikasi / kelompok item.
 */
class CategoryController extends Controller
{
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new Category();
    }

    /**
     * Menampilkan daftar seluruh kategori.
     *
     * @return void
     */
    public function index(): void
    {
        $categories = $this->categoryModel->getWithItemCount();

        $this->view('categories/index', [
            'pageTitle'  => $this->terms['category']['plural'],
            'categories' => $categories,
        ]);
    }

    /**
     * Menyimpan kategori baru.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/categories', $validator->first('nama_kategori'));
        }

        $this->categoryModel->create([
            'nama_kategori' => $request->input('nama_kategori'),
            'deskripsi'     => $request->input('deskripsi'),
        ]);

        $this->redirectWithSuccess('/categories', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui kategori.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/categories', $validator->first('nama_kategori'));
        }

        $this->categoryModel->update($id, [
            'nama_kategori' => $request->input('nama_kategori'),
            'deskripsi'     => $request->input('deskripsi'),
        ]);

        $this->redirectWithSuccess('/categories', 'Data kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->redirectWithError('/categories', 'Kategori tidak ditemukan.');
        }

        try {
            $this->categoryModel->delete($id);
            $this->redirectWithSuccess('/categories', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            $this->redirectWithError('/categories', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa item.');
        }
    }
}
