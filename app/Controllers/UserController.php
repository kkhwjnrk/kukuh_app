<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\User;

/**
 * Class UserController
 * 
 * Mengelola data pengguna sistem dan penugasan peran (Khusus peran Administrator).
 */
class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Menampilkan daftar seluruh akun pengguna.
     *
     * @return void
     */
    public function index(): void
    {
        $users = $this->userModel->getAllUsers();

        $this->view('users/index', [
            'pageTitle' => 'Manajemen Pengguna',
            'users'     => $users,
        ]);
    }

    /**
     * Menampilkan form tambah pengguna baru.
     *
     * @return void
     */
    public function create(): void
    {
        $this->view('users/create', [
            'pageTitle' => 'Tambah Pengguna Baru',
        ]);
    }

    /**
     * Menyimpan data pengguna baru.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|min:3|unique:users,username',
            'password'     => 'required|min:5',
            'nama_lengkap' => 'required',
            'role'         => 'required',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError('/users/create', $validator->first('username') ?? $validator->first('password') ?? 'Data tidak valid.');
        }

        $this->userModel->create([
            'username'     => $request->input('username'),
            'password'     => Auth::hashPassword($request->input('password')),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'role'         => $request->input('role'),
        ]);

        $this->redirectWithSuccess('/users', 'Pengguna baru berhasil didaftarkan!');
    }

    /**
     * Menampilkan form edit pengguna.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->redirectWithError('/users', 'Pengguna tidak ditemukan.');
        }

        $this->view('users/edit', [
            'pageTitle' => 'Ubah Data Pengguna',
            'user'      => $user,
        ]);
    }

    /**
     * Memperbarui data pengguna.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function update(Request $request, $id): void
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->redirectWithError('/users', 'Pengguna tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'username'     => "required|min:3|unique:users,username,{$id}",
            'nama_lengkap' => 'required',
            'role'         => 'required',
        ]);

        if ($validator->fails()) {
            $this->redirectWithError("/users/edit/{$id}", $validator->first('username') ?? 'Data tidak valid.');
        }

        $updateData = [
            'username'     => $request->input('username'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'role'         => $request->input('role'),
        ];

        // Jika password diisi, perbarui kata sandi
        $password = $request->input('password');
        if (!empty($password)) {
            $updateData['password'] = Auth::hashPassword($password);
        }

        $this->userModel->update($id, $updateData);

        $this->redirectWithSuccess('/users', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus akun pengguna.
     *
     * @param Request $request
     * @param int|string $id
     * @return void
     */
    public function delete(Request $request, $id): void
    {
        if ((int)$id === (int)Auth::id()) {
            $this->redirectWithError('/users', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $this->userModel->delete($id);
        $this->redirectWithSuccess('/users', 'Akun pengguna berhasil dihapus.');
    }
}
