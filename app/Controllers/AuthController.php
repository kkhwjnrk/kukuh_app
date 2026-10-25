<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;

/**
 * Class AuthController
 * 
 * Menangani siklus autentikasi pengguna: Login, Logout, dan Profil Akun.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     *
     * @return void
     */
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirectWithSuccess('/dashboard', 'Anda sudah masuk ke dalam sistem.');
        }

        $this->viewAuth('auth/login', [
            'pageTitle' => 'Masuk ke Sistem - ' . $this->terms['app_name'],
        ]);
    }

    /**
     * Memproses verifikasi username dan password.
     *
     * @param Request $request
     * @return void
     */
    public function processLogin(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->first('username') ?? $validator->first('password'));
            $this->redirectWithError('/login', 'Mohon lengkapi username dan password.');
        }

        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt($username, $password)) {
            $user = Auth::user();
            $this->redirectWithSuccess('/dashboard', "Selamat datang kembali, {$user['nama_lengkap']}!");
        } else {
            $this->redirectWithError('/login', 'Username atau password yang Anda masukkan tidak sesuai.');
        }
    }

    /**
     * Melakukan proses logout pengguna.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirectWithSuccess('/login', 'Anda telah berhasil keluar dari sistem.');
    }
}
