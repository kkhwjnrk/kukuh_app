<?php

use App\Core\Router;
use App\Core\Response;

/**
 * Pendaftaran Rute Aplikasi (Clean URL)
 * 
 * Mengaitkan alamat URL ke Controller dan Action yang sesuai,
 * serta menerapkan Middleware autentikasi dan peran (Role-Based Access Control).
 */

// 1. Rute Beranda & Autentikasi
Router::get('/', function() {
    Response::redirect('/dashboard');
});

Router::get('/login', 'AuthController@showLogin', ['guest']);
Router::post('/login', 'AuthController@processLogin', ['guest']);
Router::get('/logout', 'AuthController@logout', ['auth']);

// 2. Dasbor Utama
Router::get('/dashboard', 'DashboardController@index', ['auth']);

// 3. Modul Master Data (Barang/Item)
Router::get('/items', 'ItemController@index', ['auth']);
Router::get('/items/create', 'ItemController@create', ['auth', 'role:admin|petugas']);
Router::post('/items/store', 'ItemController@store', ['auth', 'role:admin|petugas']);
Router::get('/items/edit/{id}', 'ItemController@edit', ['auth', 'role:admin|petugas']);
Router::post('/items/update/{id}', 'ItemController@update', ['auth', 'role:admin|petugas']);
Router::post('/items/delete/{id}', 'ItemController@delete', ['auth', 'role:admin']);
Router::get('/api/items/search', 'ItemController@searchJson', ['auth']);

// 4. Modul Kategori
Router::get('/categories', 'CategoryController@index', ['auth', 'role:admin|petugas']);
Router::post('/categories/store', 'CategoryController@store', ['auth', 'role:admin|petugas']);
Router::post('/categories/update/{id}', 'CategoryController@update', ['auth', 'role:admin|petugas']);
Router::post('/categories/delete/{id}', 'CategoryController@delete', ['auth', 'role:admin']);

// 5. Modul Transaksi & Keranjang
Router::get('/transactions', 'TransactionController@index', ['auth']);
Router::get('/transactions/create', 'TransactionController@create', ['auth', 'role:admin|petugas']);
Router::post('/transactions/store', 'TransactionController@store', ['auth', 'role:admin|petugas']);
Router::get('/transactions/show/{id}', 'TransactionController@show', ['auth']);
Router::get('/transactions/print/{id}', 'TransactionController@printView', ['auth']);

// 6. Modul Laporan & Ekspor
Router::get('/reports', 'ReportController@index', ['auth']);
Router::get('/reports/excel', 'ReportController@exportExcel', ['auth']);
Router::get('/reports/print', 'ReportController@printView', ['auth']);

// 7. Modul Manajemen Pengguna (Khusus Admin)
Router::get('/users', 'UserController@index', ['auth', 'role:admin']);
Router::get('/users/create', 'UserController@create', ['auth', 'role:admin']);
Router::post('/users/store', 'UserController@store', ['auth', 'role:admin']);
Router::get('/users/edit/{id}', 'UserController@edit', ['auth', 'role:admin']);
Router::post('/users/update/{id}', 'UserController@update', ['auth', 'role:admin']);
Router::post('/users/delete/{id}', 'UserController@delete', ['auth', 'role:admin']);
