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

        // Tangani sub-folder instalasi jika dijalankan di /kukuh_app/public atau /kukuh_app
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
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
