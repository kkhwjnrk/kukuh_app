<?php
/**
 * Kamus Istilah Dinamis (Dynamic Terminology Dictionary)
 * 
 * FILE INI ADALAH KUNCI UTAMA ADAPTASI KILAT SAAT UJIKOM!
 * Cukup ubah kata-kata di bawah ini sesuai studi kasus yang diberikan penguji,
 * maka seluruh label antarmuka (Sidebar, Header, Form, Tabel, Cetak PDF)
 * akan otomatis berganti seketika tanpa perlu mengedit puluhan file tampilan!
 * 
 * Contoh Skenario:
 * - Soal Perpustakaan: 'item' => 'Buku', 'category' => 'Kategori Buku', 'transaction' => 'Peminjaman'
 * - Soal Rental Mobil: 'item' => 'Mobil', 'category' => 'Tipe Kendaraan', 'transaction' => 'Sewa Kendaraan'
 * - Soal Kasir/Toko  : 'item' => 'Barang', 'category' => 'Kategori', 'transaction' => 'Penjualan'
 * - Soal Apotek/Obat : 'item' => 'Obat', 'category' => 'Golongan Obat', 'transaction' => 'Penjualan Resep'
 * - Soal Laundry     : 'item' => 'Paket Layanan', 'category' => 'Jenis Cucian', 'transaction' => 'Order Cuci'
 */

return [
    'system_title' => 'Sistem Informasi Manajemen Terpadu',
    'app_name'     => 'SIM-PRO',
    'tagline'      => 'Solusi Manajemen Data & Transaksi Modern',
    'organization' => 'Lembaga Sertifikasi Profesi / SMK',

    // Entitas Utama (Master Item)
    'item' => [
        'singular'      => 'Barang',
        'plural'        => 'Data Barang',
        'menu_icon'     => 'box',
        'code_label'    => 'Kode Barang / SKU',
        'name_label'    => 'Nama Barang',
        'category_label'=> 'Kategori',
        'price_label'   => 'Harga Satuan',
        'stock_label'   => 'Stok Tersedia',
        'image_label'   => 'Foto Barang',
        'desc_label'    => 'Spesifikasi / Keterangan',
        'unit'          => 'Pcs',
    ],

    // Entitas Kategori
    'category' => [
        'singular'   => 'Kategori',
        'plural'     => 'Kategori Barang',
        'menu_icon'  => 'folder',
        'name_label' => 'Nama Kategori',
        'desc_label' => 'Deskripsi Kategori',
    ],

    // Entitas Transaksi
    'transaction' => [
        'singular'     => 'Transaksi',
        'plural'       => 'Data Transaksi',
        'menu_icon'    => 'shopping-bag',
        'code_label'   => 'No. Faktur / Resi',
        'date_label'   => 'Tanggal Transaksi',
        'user_label'   => 'Petugas / Kasir',
        'total_label'  => 'Total Tagihan',
        'action_verb'  => 'Bayar & Proses',
        'invoice_title'=> 'FAKTUR TRANSAKSI RESMI',
    ],

    // Entitas Laporan
    'report' => [
        'title'       => 'Laporan Rekapitulasi',
        'filter_label'=> 'Periode Tanggal',
        'export_pdf'  => 'Unduh PDF',
        'export_excel'=> 'Ekspor Excel / CSV',
        'print_view'  => 'Cetak Dokumen',
    ],
];
