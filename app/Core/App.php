<?php

namespace App\Core;

use Throwable;

/**
 * Class App
 * 
 * Penggerak utama siklus hidup aplikasi (Application Bootstrap).
 * Mengatur pelaporan error, timezone, session start, dan inisiasi routing.
 */
class App
{
    /** @var array Konfigurasi aplikasi */
    private array $config;

    /**
     * Inisialisasi lingkungan aplikasi.
     */
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/app.php';
        $this->configureEnvironment();
        $this->registerErrorHandlers();
        Session::start();
    }

    /**
     * Mengatur timezone dan pelaporan kesalahan (error reporting).
     *
     * @return void
     */
    private function configureEnvironment(): void
    {
        date_default_timezone_set($this->config['timezone'] ?? 'Asia/Jakarta');

        if ($this->config['debug']) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            error_reporting(0);
        }
    }

    /**
     * Mendaftarkan penangan exception dan fatal error global (SKKNI J.620100.025.02).
     *
     * @return void
     */
    private function registerErrorHandlers(): void
    {
        set_exception_handler(function (Throwable $e) {
            Logger::error($e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            Response::setStatusCode(500);

            if ($this->config['debug']) {
                $exception = $e;
                require __DIR__ . '/../Views/errors/500_debug.php';
            } else {
                require __DIR__ . '/../Views/errors/500.php';
            }
            exit;
        });
    }

    /**
     * Menjalankan pemrosesan permintaan HTTP.
     *
     * @return void
     */
    public function run(): void
    {
        $request = new Request();
        Router::dispatch($request);
    }
}
