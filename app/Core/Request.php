<?php

namespace App\Core;

/**
 * Class Request
 * 
 * Mengenkapsulasi data permintaan HTTP (GET, POST, FILES, Server),
 * melakukan pembersihan (sanitasi) data demi mencegah serangan XSS,
 * serta mempermudah akses parameter formulir.
 */
class Request
{
    /** @var array Data POST yang telah disanitasi */
    private array $postData;

    /** @var array Data GET query params yang disanitasi */
    private array $getData;

    /** @var array Data file unggahan */
    private array $filesData;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->postData  = $this->sanitizeArray($_POST);
        $this->getData   = $this->sanitizeArray($_GET);
        $this->filesData = $_FILES;
    }

    /**
     * Mengambil metode HTTP (GET, POST, dll).
     *
     * @return string
     */
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Mengecek apakah metode HTTP adalah POST.
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Mengecek apakah metode HTTP adalah GET.
     *
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * Mengambil URI jalur yang diminta pengguna.
     *
     * @return string
     */
    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Hapus query string jika ada
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Normalisasi path separator
        $uri = str_replace('\\', '/', $uri);

        // Ambil path script dinamis (contoh: /kukuh_app/public/index.php)
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir  = dirname($scriptName);
        $projectDir = dirname($scriptDir);

        $candidatePrefixes = [];

        // 1. Cek dari konfigurasi base_url
        $config = require __DIR__ . '/../../config/app.php';
        if (!empty($config['base_url'])) {
            $basePath = parse_url($config['base_url'], PHP_URL_PATH);
            if (!empty($basePath) && $basePath !== '/') {
                $candidatePrefixes[] = rtrim($basePath, '/');
                $candidatePrefixes[] = rtrim($basePath, '/') . '/public';
            }
        }

        // 2. Tambahkan kandidat dari SCRIPT_NAME Apache
        if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '') {
            $candidatePrefixes[] = $scriptDir;
        }
        if ($projectDir !== '/' && $projectDir !== '.' && $projectDir !== '') {
            $candidatePrefixes[] = $projectDir;
        }

        // Urutkan dari prefix terpanjang ke terpendek
        usort($candidatePrefixes, function ($a, $b) {
            return strlen($b) <=> strlen($a);
        });

        foreach ($candidatePrefixes as $prefix) {
            if (!empty($prefix) && strpos($uri, $prefix) === 0) {
                $uri = substr($uri, strlen($prefix));
                break;
            }
        }

        // Bersihkan jika masih diawali /public
        if (strpos($uri, '/public') === 0) {
            $uri = substr($uri, 7);
        }

        $uri = trim($uri, '/');
        return empty($uri) ? '/' : '/' . $uri;
    }

    /**
     * Mengambil satu input dengan nilai fallback.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, $default = null)
    {
        return $this->postData[$key] ?? $this->getData[$key] ?? $default;
    }

    /**
     * Mengambil parameter query string (GET).
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function query(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->getData;
        }
        return $this->getData[$key] ?? $default;
    }

    /**
     * Mengambil parameter formulir (POST).
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function post(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->postData;
        }
        return $this->postData[$key] ?? $default;
    }

    /**
     * Mengambil seluruh data input gabungan POST dan GET.
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->getData, $this->postData);
    }

    /**
     * Mengambil data file yang diunggah.
     *
     * @param string $key
     * @return array|null
     */
    public function file(string $key): ?array
    {
        return $this->filesData[$key] ?? null;
    }

    /**
     * Melakukan sanitasi array terhadap string berbahaya (XSS Protection).
     *
     * @param array $data
     * @return array
     */
    private function sanitizeArray(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
}
