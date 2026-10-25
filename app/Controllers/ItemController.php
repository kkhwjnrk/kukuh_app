<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Category;
use App\Models\Item;

/**
 * Class ItemController
 * 
 * Mengelola siklus CRUD master data utama (Barang/Buku/Mobil/Layanan).
 */
class ItemController extends Controller
{
    private Item $itemModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->itemModel = new Item();
        $this->categoryModel = new Category();
    }

    /**
     * Menampilkan daftar item dengan filter pencarian dan paginasi.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        $keyword = $request->input('q');
        $categoryId = $request->input('category_id') ? (int)$request->input('category_id') : null;
        $page = (int)($request->input('page', 1));

        $result = $this->itemModel->getFilteredPaginated($keyword, $categoryId, $page, 8);
        $categories = $this->categoryModel->all('nama_kategori', 'ASC');

        $this->view('items/index', [
            'pageTitle'   => $this->terms['item']['plural'],
            'items'       => $result['data'],
            'pagination'  => $result,
            'categories'  => $categories,
            'keyword'     => $keyword,
            'categoryId'  => $categoryId,
        ]);
    }

    /**
     * Menampilkan form penambahan item baru.
     *
     * @return void
     */
    public function create(): void
    {
        $categories = $this->categoryModel->all('nama_kategori', 'ASC');
        $autoCode = $this->itemModel->generateCode('ITM');

        $this->view('items/create', [
            'pageTitle'  => 'Tambah ' . $this->terms['item']['singular'],
            'categories' => $categories,
            'autoCode'   => $autoCode,
        ]);
    }

    /**
     * Menyimpan data item baru ke basis data.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'kode_item'   => 'required|unique:items,kode_item',
            'nama_item'   => 'required|min:3',
            'category_id' => 'required|numeric',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/items/create', $validator->first('kode_item') ?? $validator->first('nama_item') ?? $validator->first('harga') ?? 'Data tidak valid.');
        }

        // Proses unggah gambar jika ada
        $imageName = $this->handleUpload($request->file('gambar'));

        $this->itemModel->create([
            'kode_item'   => $request->input('kode_item'),
            'nama_item'   => $request->input('nama_item'),
            'category_id' => (int)$request->input('category_id'),
            'harga'       => (float)$request->input('harga'),
            'stok'        => (int)$request->input('stok'),
            'gambar'      => $imageName,
            'deskripsi'   => $request->input('deskripsi'),
        ]);

        $this->redirectWithSuccess('/items', $this->terms['item']['singular'] . ' baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form ubah (edit) item.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            $this->redirectWithError('/items', 'Data tidak ditemukan.');
        }

        $categories = $this->categoryModel->all('nama_kategori', 'ASC');

        $this->view('items/edit', [
            'pageTitle'  => 'Ubah ' . $this->terms['item']['singular'],
            'item'       => $item,
            'categories' => $categories,
        ]);
    }

    /**
     * Menyimpan perubahan data item ke basis data.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            $this->redirectWithError('/items', 'Data tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'kode_item'   => "required|unique:items,kode_item,{$id}",
            'nama_item'   => 'required|min:3',
            'category_id' => 'required|numeric',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/items/edit/{$id}", $validator->first('kode_item') ?? $validator->first('nama_item') ?? 'Data tidak valid.');
        }

        $updateData = [
            'kode_item'   => $request->input('kode_item'),
            'nama_item'   => $request->input('nama_item'),
            'category_id' => (int)$request->input('category_id'),
            'harga'       => (float)$request->input('harga'),
            'stok'        => (int)$request->input('stok'),
            'deskripsi'   => $request->input('deskripsi'),
        ];

        // Cek unggahan gambar baru
        $uploaded = $this->handleUpload($request->file('gambar'));
        if ($uploaded !== null) {
            $updateData['gambar'] = $uploaded;
            // Hapus gambar lama jika ada
            if (!empty($item['gambar'])) {
                $oldPath = __DIR__ . '/../../public/uploads/' . $item['gambar'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $this->itemModel->update($id, $updateData);

        $this->redirectWithSuccess('/items', 'Perubahan data ' . $this->terms['item']['singular'] . ' berhasil disimpan!');
    }

    /**
     * Menghapus item dari sistem.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            $this->redirectWithError('/items', 'Data tidak ditemukan.');
        }

        // Hapus berkas gambar jika ada
        if (!empty($item['gambar'])) {
            $path = __DIR__ . '/../../public/uploads/' . $item['gambar'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->itemModel->delete($id);
        $this->redirectWithSuccess('/items', 'Data berhasil dihapus dari sistem.');
    }

    /**
     * API JSON untuk live search dan penambahan ke keranjang transaksi.
     *
     * @param Request $request
     * @return void
     */
    public function searchJson(Request $request): void
    {
        $kw = $request->input('q', '');
        $items = $this->itemModel->query(
            "SELECT id, kode_item, nama_item, harga, stok FROM items WHERE (nama_item LIKE :q OR kode_item LIKE :q) AND stok > 0 LIMIT 10",
            [':q' => "%{$kw}%"]
        );
        $this->json(['status' => 'success', 'data' => $items]);
    }

    /**
     * Memproses penyimpanan berkas gambar yang diunggah.
     *
     * @param array|null $file
     * @return string|null
     */
    private function handleUpload(?array $file): ?string
    {
        if (!$file || empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (!in_array($file['type'], $allowedMimes)) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'item_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return $fileName;
        }

        return null;
    }
}
