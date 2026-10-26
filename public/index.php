<?php
/**
 * Apotek LSP - Front Controller & Single Entry Point
 * 
 * Mengatur pemuatan kelas otomatis (Autoloading PSR-4),
 * inisialisasi helper terstruktur, konfigurasi rute, dan bootstrap aplikasi.
 */

// 1. Daftarkan Autoloader PSR-4 Native (Bebas dari keharusan Composer)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2. Muat fungsi helper prosedural terstruktur
require_once __DIR__ . '/../app/Helpers/utility.php';

// 3. Inisialisasi Bootstrap Aplikasi
$app = new App\Core\App();

// 4. Daftarkan seluruh rute sistem
require_once __DIR__ . '/../app/routes.php';

// 5. Jalankan aplikasi
$app->run();
