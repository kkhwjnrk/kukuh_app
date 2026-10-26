<?php

use App\Core\Router;
use App\Core\Response;

/**
 * Pendaftaran Rute Aplikasi (Clean URL)
 * 
 * Sesuai Skema Pemrogram LSP Unpam / BNSP
 * Menu Administrator:
 * 1. Beranda (Dashboard)
 * 2. Kelola Data Perusahaan
 * 3. Kelola Data Customer (Preview/Cetak Data Customer)
 * 4. Kelola Data Produk
 * 5. Kelola Data Penjualan (Faktur) (Cetak Faktur)
 * 6. Kelola Pengguna (Admin)
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

// 3. Menu 2: Kelola Data Perusahaan
Router::get('/perusahaan', 'PerusahaanController@index', ['auth']);
Router::get('/perusahaan/create', 'PerusahaanController@create', ['auth', 'role:admin|petugas']);
Router::post('/perusahaan/store', 'PerusahaanController@store', ['auth', 'role:admin|petugas']);
Router::get('/perusahaan/edit/{id}', 'PerusahaanController@edit', ['auth', 'role:admin|petugas']);
Router::post('/perusahaan/update/{id}', 'PerusahaanController@update', ['auth', 'role:admin|petugas']);
Router::post('/perusahaan/delete/{id}', 'PerusahaanController@delete', ['auth', 'role:admin']);

// 4. Menu 3: Kelola Data Customer
Router::get('/customer', 'CustomerController@index', ['auth']);
Router::get('/customer/create', 'CustomerController@create', ['auth', 'role:admin|petugas']);
Router::post('/customer/store', 'CustomerController@store', ['auth', 'role:admin|petugas']);
Router::get('/customer/edit/{id}', 'CustomerController@edit', ['auth', 'role:admin|petugas']);
Router::post('/customer/update/{id}', 'CustomerController@update', ['auth', 'role:admin|petugas']);
Router::post('/customer/delete/{id}', 'CustomerController@delete', ['auth', 'role:admin']);
Router::get('/customer/print', 'CustomerController@printPreview', ['auth']);

// 5. Menu 4: Kelola Data Produk
Router::get('/produk', 'ProdukController@index', ['auth']);
Router::get('/produk/create', 'ProdukController@create', ['auth', 'role:admin|petugas']);
Router::post('/produk/store', 'ProdukController@store', ['auth', 'role:admin|petugas']);
Router::get('/produk/edit/{id}', 'ProdukController@edit', ['auth', 'role:admin|petugas']);
Router::post('/produk/update/{id}', 'ProdukController@update', ['auth', 'role:admin|petugas']);
Router::post('/produk/delete/{id}', 'ProdukController@delete', ['auth', 'role:admin']);
Router::get('/api/produk/search', 'ProdukController@searchJson', ['auth']);

// 6. Menu 5: Kelola Data Penjualan (Faktur)
Router::get('/faktur', 'FakturController@index', ['auth']);
Router::get('/faktur/create', 'FakturController@create', ['auth', 'role:admin|petugas']);
Router::post('/faktur/store', 'FakturController@store', ['auth', 'role:admin|petugas']);
Router::get('/faktur/show/{id}', 'FakturController@show', ['auth']);
Router::get('/faktur/edit/{id}', 'FakturController@edit', ['auth', 'role:admin|petugas']);
Router::post('/faktur/update/{id}', 'FakturController@update', ['auth', 'role:admin|petugas']);
Router::post('/faktur/delete/{id}', 'FakturController@delete', ['auth', 'role:admin']);
Router::get('/faktur/print/{id}', 'FakturController@printView', ['auth']);

// 7. Modul Manajemen Pengguna (Khusus Admin)
Router::get('/users', 'UserController@index', ['auth', 'role:admin']);
Router::get('/users/create', 'UserController@create', ['auth', 'role:admin']);
Router::post('/users/store', 'UserController@store', ['auth', 'role:admin']);
Router::get('/users/edit/{id}', 'UserController@edit', ['auth', 'role:admin']);
Router::post('/users/update/{id}', 'UserController@update', ['auth', 'role:admin']);
Router::post('/users/delete/{id}', 'UserController@delete', ['auth', 'role:admin']);

// 8. Rute Alias untuk Kompatibilitas
Router::get('/items', function() { Response::redirect('/produk'); });
Router::get('/categories', function() { Response::redirect('/perusahaan'); });
Router::get('/transactions', function() { Response::redirect('/faktur'); });
Router::get('/reports', function() { Response::redirect('/faktur'); });
