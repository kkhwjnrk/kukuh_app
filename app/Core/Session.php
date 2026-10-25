<?php

namespace App\Core;

/**
 * Class Session
 * 
 * Mengelola lifecycle sesi pengguna, notifikasi kilat (Flash Message),
 * dan pertahanan keamanan terhadap ancaman CSRF (Cross-Site Request Forgery).
 */
class Session
{
    /**
     * Memulai sesi jika belum aktif dengan konfigurasi keamanan ketat.
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Pengaturan cookie sesi yang aman
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }

    /**
     * Menyimpan nilai ke sesi.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Mengambil nilai dari sesi.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Menghapus kunci tertentu dari sesi.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Mengatur flash message (pesan sekali tampil).
     *
     * @param string $type success|error|warning|info
     * @param string $message
     * @return void
     */
    public static function flash(string $type, string $message): void
    {
        self::start();
        $_SESSION['_flash'][$type] = $message;
    }

    /**
     * Mengambil dan menghapus flash message yang tersimpan.
     *
     * @return array
     */
    public static function getFlash(): array
    {
        self::start();
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }

    /**
     * Membuat atau mengambil token CSRF aktif.
     *
     * @return string
     */
    public static function csrfToken(): string
    {
        self::start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Memvalidasi apakah token CSRF yang dikirim valid.
     *
     * @param string|null $token
     * @return bool
     */
    public static function validateCsrf(?string $token): bool
    {
        self::start();
        $stored = $_SESSION['_csrf_token'] ?? '';
        return !empty($stored) && !empty($token) && hash_equals($stored, $token);
    }

    /**
     * Menghancurkan seluruh sesi saat logout.
     *
     * @return void
     */
    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}
