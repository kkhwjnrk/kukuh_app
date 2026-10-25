<?php

namespace App\Core;

/**
 * Class Controller (Base Controller)
 * 
 * Fondasi utama seluruh Controller aplikasi.
 * Bertanggung jawab merender tampilan (views) dengan layout standar modern,
 * menyalurkan data istilah dinamis (terms), serta penanganan respon HTTP.
 */
abstract class Controller
{
    /** @var array Konfigurasi istilah dinamis yang dimuat otomatis */
    protected array $terms;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->terms = require __DIR__ . '/../../config/terms.php';
    }

    /**
     * Merender template tampilan di dalam layout utama dashboard.
     *
     * @param string $viewPath Path relatif dari app/Views/ (misal: 'items/index')
     * @param array $data Data yang dikirim ke view
     * @param string|null $layout Nama layout khusus jika bukan default dashboard
     * @return void
     */
    protected function view(string $viewPath, array $data = [], ?string $layout = 'layouts/app'): void
    {
        // Ekstraksi data agar dapat diakses sebagai variabel langsung di view
        extract($data);

        // Sertakan terms dan session user secara global di view
        $terms = $this->terms;
        $currentUser = Auth::user();
        $flash = Session::getFlash();

        // Buffer konten view spesifik
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            Logger::error("Tampilan view tidak ditemukan: " . $viewFile);
            die("View [{$viewPath}] tidak ditemukan.");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Jika layout adalah null, langsung tampilkan konten murni (misal untuk cetak nota)
        if ($layout === null) {
            echo $content;
            return;
        }

        // Tampilkan kerangka tata letak (Header, Sidebar, Navbar, Konten, Footer)
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/layouts/navbar.php';
        echo '<div class="app-wrapper flex flex-col md:flex-row min-h-screen">';
        require __DIR__ . '/../Views/layouts/sidebar.php';
        echo '<main class="app-main flex-1 p-6 bg-slate-50 overflow-y-auto">';
        require __DIR__ . '/../Views/layouts/alert.php';
        echo $content;
        echo '</main></div>';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Merender tampilan khusus tanpa sidebar/navbar (misal halaman Login).
     *
     * @param string $viewPath
     * @param array $data
     * @return void
     */
    protected function viewAuth(string $viewPath, array $data = []): void
    {
        extract($data);
        $terms = $this->terms;
        $flash = Session::getFlash();

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/layouts/alert.php';
        require __DIR__ . '/../Views/' . $viewPath . '.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Melakukan pengalihan URL dengan pesan sukses.
     *
     * @param string $path
     * @param string $message
     * @return void
     */
    protected function redirectWithSuccess(string $path, string $message): void
    {
        Session::flash('success', $message);
        Response::redirect($path);
    }

    /**
     * Melakukan pengalihan URL dengan pesan kesalahan.
     *
     * @param string $path
     * @param string $message
     * @return void
     */
    protected function redirectWithError(string $path, string $message): void
    {
        Session::flash('error', $message);
        Response::redirect($path);
    }

    /**
     * Mengembalikan respon JSON.
     *
     * @param mixed $data
     * @param int $status
     * @return void
     */
    protected function json($data, int $status = 200): void
    {
        Response::json($data, $status);
    }
}
