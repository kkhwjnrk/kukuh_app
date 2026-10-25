<?php

namespace App\Core;

/**
 * Class Router
 * 
 * Mengelola pendaftaran dan pencocokan rute URL bersih (Clean URL),
 * parsing parameter dinamis (seperti {id}), dan penegakan middleware keamanan.
 */
class Router
{
    /** @var array Kumpulan rute terdaftar */
    private static array $routes = [];

    /**
     * Mendaftarkan rute HTTP GET.
     *
     * @param string $path
     * @param string|callable $handler Contoh: 'ItemController@index'
     * @param array $middleware Contoh: ['auth', 'role:admin']
     * @return void
     */
    public static function get(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Mendaftarkan rute HTTP POST.
     *
     * @param string $path
     * @param string|callable $handler Contoh: 'ItemController@store'
     * @param array $middleware Contoh: ['auth']
     * @return void
     */
    public static function post(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Menyimpan konfigurasi rute ke memori.
     *
     * @param string $method
     * @param string $path
     * @param string|callable $handler
     * @param array $middleware
     * @return void
     */
    private static function addRoute(string $method, string $path, $handler, array $middleware): void
    {
        $cleanPath = '/' . trim($path, '/');
        self::$routes[] = [
            'method'     => $method,
            'path'       => $cleanPath,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    /**
     * Menjalankan pencocokan rute dan memanggil controller yang tepat.
     *
     * @param Request $request
     * @return void
     */
    public static function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = '/' . trim($request->uri(), '/');

        // Otomatis verifikasi CSRF Token pada setiap request POST
        if ($method === 'POST') {
            $token = $request->input('_csrf_token');
            if (!Session::validateCsrf($token)) {
                Logger::warning("Percobaan pengiriman form tanpa CSRF token valid dari IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                Session::flash('error', 'Sesi formulir kedaluwarsa atau token keamanan tidak valid. Silakan coba kembali.');
                Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
                return;
            }
        }

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Ubah pattern {id} menjadi regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                // Ekstrak parameter bernama
                $params = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                // Jalankan Middleware Pipeline
                if (!self::runMiddleware($route['middleware'])) {
                    return;
                }

                // Eksekusi Handler
                self::executeHandler($route['handler'], $params, $request);
                return;
            }
        }

        // Jika rute tidak ditemukan (404)
        Response::setStatusCode(404);
        require __DIR__ . '/../Views/errors/404.php';
        exit;
    }

    /**
     * Menjalankan verifikasi hak akses middleware.
     *
     * @param array $middlewares
     * @return bool
     */
    private static function runMiddleware(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            if ($middleware === 'auth') {
                if (!Auth::check()) {
                    Session::flash('error', 'Silakan masuk (login) terlebih dahulu untuk mengakses menu tersebut.');
                    Response::redirect('/login');
                    return false;
                }
            }

            if ($middleware === 'guest') {
                if (Auth::check()) {
                    Response::redirect('/dashboard');
                    return false;
                }
            }

            if (strpos($middleware, 'role:') === 0) {
                $requiredRole = substr($middleware, 5);
                $allowedRoles = explode('|', $requiredRole);

                if (!Auth::hasRole($allowedRoles)) {
                    Response::setStatusCode(403);
                    require __DIR__ . '/../Views/errors/403.php';
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Memanggil Controller dan Action yang sesuai.
     *
     * @param string|callable $handler
     * @param array $params
     * @param Request $request
     * @return void
     */
    private static function executeHandler($handler, array $params, Request $request): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, array_merge([$request], $params));
            return;
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controllerName, $action] = explode('@', $handler, 2);
            $fullClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($fullClass)) {
                Logger::error("Controller class tidak ditemukan: " . $fullClass);
                die("Controller [{$fullClass}] tidak ditemukan.");
            }

            $controller = new $fullClass();
            if (!method_exists($controller, $action)) {
                Logger::error("Action method tidak ditemukan: {$action} pada {$fullClass}");
                die("Aksi [{$action}] tidak ditemukan pada controller [{$fullClass}].");
            }

            // Panggil method controller dengan menyertakan Request dan parameter URL
            call_user_func_array([$controller, $action], array_merge([$request], array_values($params)));
        }
    }
}
