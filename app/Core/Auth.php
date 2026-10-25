<?php

namespace App\Core;

use App\Models\User;

/**
 * Class Auth
 * 
 * Mengatur autentikasi pengguna, otorisasi berbasis peran (Role-Based Access Control),
 * dan enkripsi kata sandi yang aman.
 */
class Auth
{
    /** @var string Kunci sesi data pengguna */
    private const SESSION_USER = '_auth_user';

    /**
     * Melakukan login pengguna ke dalam sesi.
     *
     * @param array $user Data pengguna dari basis data
     * @return void
     */
    public static function login(array $user): void
    {
        // Hilangkan data password sebelum disimpan ke sesi
        unset($user['password']);
        Session::start();
        session_regenerate_id(true); // Proteksi Session Fixation
        Session::set(self::SESSION_USER, $user);
    }

    /**
     * Melakukan proses autentikasi dengan username dan password.
     *
     * @param string $username
     * @param string $password
     * @return array|false Data user jika sukses, false jika gagal
     */
    public static function attempt(string $username, string $password)
    {
        $userModel = new User();
        $user = $userModel->findBy('username', $username);

        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            return $user;
        }

        return false;
    }

    /**
     * Mengecek apakah pengguna saat ini sudah login.
     *
     * @return bool
     */
    public static function check(): bool
    {
        return Session::get(self::SESSION_USER) !== null;
    }

    /**
     * Mengambil data pengguna yang sedang login.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function user(?string $key = null)
    {
        $user = Session::get(self::SESSION_USER);
        if ($key !== null) {
            return $user[$key] ?? null;
        }
        return $user;
    }

    /**
     * Mengambil ID pengguna yang sedang login.
     *
     * @return int|null
     */
    public static function id(): ?int
    {
        return self::user('id');
    }

    /**
     * Mengambil nama peran (role) pengguna saat ini.
     *
     * @return string|null
     */
    public static function role(): ?string
    {
        return self::user('role');
    }

    /**
     * Memeriksa apakah pengguna memiliki salah satu peran yang diizinkan.
     *
     * @param string|array $roles Contoh: 'admin' atau ['admin', 'petugas']
     * @return bool
     */
    public static function hasRole($roles): bool
    {
        if (!self::check()) {
            return false;
        }

        $currentRole = self::role();
        if (is_array($roles)) {
            return in_array($currentRole, $roles, true);
        }

        return $currentRole === $roles;
    }

    /**
     * Melakukan logout dan menghapus data sesi.
     *
     * @return void
     */
    public static function logout(): void
    {
        Session::destroy();
    }

    /**
     * Meng-hash kata sandi menggunakan algoritma BCRYPT yang aman.
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
