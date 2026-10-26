<?php
/**
 * Konfigurasi Utama Aplikasi
 * 
 * Memuat pengaturan umum sistem seperti nama aplikasi, URL dasar,
 * mode debug, dan zona waktu.
 */

return [
    'name'      => 'Apotek LSP',
    'env'       => 'development', // 'development' atau 'production'
    'debug'     => true,          // Tampilkan rincian error saat pengembangan
    'base_url'  => 'http://localhost/kukuh_app', // Sesuaikan dengan virtual host / path Laragon
    'timezone'  => 'Asia/Jakarta',
    'locale'    => 'id_ID',
];
