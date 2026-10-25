<?php
/**
 * Modul Fungsi Bantu Terstruktur (Structured Helper Utilities)
 * 
 * Kumpulan fungsi prosedural/terstruktur untuk format data, URL,
 * sanitasi, kamus istilah, dan token CSRF.
 * Memenuhi SKKNI J.620100.017.02 (Pemrograman Terstruktur).
 */

use App\Core\Auth;
use App\Core\Session;

/**
 * Mengambil istilah dinamis dari kamus config/terms.php.
 * Mendukung dot-notation seperti 'item.singular' atau 'transaction.action_verb'.
 *
 * @param string $key
 * @param string $default
 * @return mixed
 */
function term(string $key, string $default = '')
{
    static $terms = null;
    if ($terms === null) {
        $terms = require __DIR__ . '/../../config/terms.php';
    }

    $keys = explode('.', $key);
    $current = $terms;

    foreach ($keys as $segment) {
        if (!is_array($current) || !isset($current[$segment])) {
            return $default;
        }
        $current = $current[$segment];
    }

    return $current;
}

/**
 * Menghasilkan URL lengkap aplikasi.
 *
 * @param string $path
 * @return string
 */
function url(string $path = ''): string
{
    static $baseUrl = null;
    if ($baseUrl === null) {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $scriptDir = preg_replace('#/public$#', '', $scriptDir);
            $scriptDir = rtrim($scriptDir, '/');
            $baseUrl = $scheme . '://' . $host . $scriptDir;
        } else {
            $config = require __DIR__ . '/../../config/app.php';
            $baseUrl = rtrim($config['base_url'], '/');
        }
    }

    $cleanPath = '/' . ltrim($path, '/');
    return $baseUrl . ($path === '' ? '' : $cleanPath);
}

/**
 * Menghasilkan URL menuju berkas aset publik (CSS, JS, Gambar).
 *
 * @param string $path
 * @return string
 */
function asset(string $path): string
{
    return url('public/' . ltrim($path, '/'));
}

/**
 * Mengambil nilai lama input formulir saat terjadi kesalahan validasi.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function old(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

/**
 * Mengambil string token CSRF aktif.
 *
 * @return string
 */
function csrf_token(): string
{
    return Session::csrfToken();
}

/**
 * Menghasilkan elemen HTML input tersembunyi berisi token CSRF.
 *
 * @return string
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

/**
 * Memformat angka numerik ke format Rupiah standar Indonesia.
 *
 * @param float|int|string $amount
 * @param bool $withPrefix
 * @return string
 */
function format_rupiah($amount, bool $withPrefix = true): string
{
    $formatted = number_format((float)$amount, 0, ',', '.');
    return $withPrefix ? 'Rp ' . $formatted : $formatted;
}

/**
 * Memformat tanggal ke standar penanggalan Indonesia.
 *
 * @param string $dateString
 * @param bool $withTime
 * @return string
 */
function format_date(string $dateString, bool $withTime = false): string
{
    if (empty($dateString) || $dateString === '0000-00-00 00:00:00') {
        return '-';
    }

    $timestamp = strtotime($dateString);
    if (!$timestamp) {
        return $dateString;
    }

    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $day = date('d', $timestamp);
    $monthIndex = (int)date('m', $timestamp);
    $year = date('Y', $timestamp);

    $formatted = sprintf('%s %s %s', $day, $months[$monthIndex], $year);

    if ($withTime) {
        $formatted .= ' ' . date('H:i', $timestamp) . ' WIB';
    }

    return $formatted;
}

/**
 * Mengambil data pengguna login saat ini.
 *
 * @param string|null $key
 * @return mixed
 */
function auth(?string $key = null)
{
    return Auth::user($key);
}

/**
 * Mengecek apakah pengguna memiliki peran tertentu.
 *
 * @param string|array $roles
 * @return bool
 */
function has_role($roles): bool
{
    return Auth::hasRole($roles);
}

/**
 * Menghasilkan badge HTML status transaksi yang rapi dan elegan.
 *
 * @param string $status
 * @return string
 */
function badge_status(string $status): string
{
    $status = strtolower($status);
    $badgeClasses = [
        'selesai'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'success'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'pending'   => 'bg-amber-50 text-amber-700 border border-amber-200',
        'batal'     => 'bg-rose-50 text-rose-700 border border-rose-200',
        'dipinjam'  => 'bg-blue-50 text-blue-700 border border-blue-200',
        'dikembalikan' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    ];

    $class = $badgeClasses[$status] ?? 'bg-slate-100 text-slate-700 border border-slate-200';
    $label = ucfirst($status);

    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {$class}\">{$label}</span>";
}

/**
 * Menghasilkan badge HTML peran pengguna (Role).
 *
 * @param string $role
 * @return string
 */
function badge_role(string $role): string
{
    $role = strtolower($role);
    $roleMap = [
        'admin'   => ['Administrator', 'bg-indigo-50 text-indigo-700 border border-indigo-200'],
        'petugas' => ['Petugas / Operator', 'bg-sky-50 text-sky-700 border border-sky-200'],
        'manajer' => ['Manajer / Pimpinan', 'bg-violet-50 text-violet-700 border border-violet-200'],
    ];

    $label = $roleMap[$role][0] ?? ucfirst($role);
    $class = $roleMap[$role][1] ?? 'bg-slate-100 text-slate-700 border border-slate-200';

    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {$class}\">{$label}</span>";
}
