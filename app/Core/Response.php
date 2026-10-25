<?php

namespace App\Core;

/**
 * Class Response
 * 
 * Mengatur pengiriman respon HTTP (status code, redirect, JSON output)
 * ke peramban web klien.
 */
class Response
{
    /**
     * Mengatur kode status HTTP.
     *
     * @param int $code
     * @return void
     */
    public static function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Melakukan pengalihan (redirect) ke URL tertentu.
     *
     * @param string $path
     * @return void
     */
    public static function redirect(string $path): void
    {
        $config = require __DIR__ . '/../../config/app.php';
        $baseUrl = rtrim($config['base_url'], '/');
        
        // Cek apakah $path sudah merupakan full URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            header("Location: " . $path);
        } else {
            $cleanPath = '/' . ltrim($path, '/');
            header("Location: " . $baseUrl . $cleanPath);
        }
        exit;
    }

    /**
     * Mengirimkan respon berformat JSON.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    public static function json($data, int $statusCode = 200): void
    {
        self::setStatusCode($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
