<?php
/**
 * Kamus Istilah Dinamis (Dynamic Terminology Dictionary)
 * 
 * Sesuai Skema Pemrogram LSP Unpam / BNSP
 * Studi Kasus: Sistem Faktur Penjualan
 */

return [
    'system_title' => 'Sistem Informasi Apotek LSP',
    'app_name'     => 'Apotek LSP',
    'tagline'      => 'Aplikasi Pengelolaan Faktur & Penjualan Apotek',
    'organization' => 'Apotek LSP',

    // Entitas Perusahaan
    'perusahaan' => [
        'singular' => 'Perusahaan',
        'plural'   => 'Data Perusahaan',
        'name'     => 'Nama Perusahaan',
        'address'  => 'Alamat',
        'phone'    => 'No. Telepon',
        'fax'      => 'Fax',
    ],

    // Entitas Customer
    'customer' => [
        'singular' => 'Customer',
        'plural'   => 'Data Customer',
        'name'     => 'Nama Customer',
        'company'  => 'Perusahaan Customer',
        'address'  => 'Alamat',
        'phone'    => 'No. Telepon',
    ],

    // Entitas Produk
    'produk' => [
        'singular' => 'Produk',
        'plural'   => 'Data Produk',
        'name'     => 'Nama Produk',
        'price'    => 'Harga Satuan',
        'type'     => 'Jenis / Satuan',
        'stock'    => 'Stok Tersedia',
    ],

    // Entitas Faktur Penjualan
    'faktur' => [
        'singular'      => 'Faktur',
        'plural'        => 'Data Penjualan (Faktur)',
        'invoice_no'    => 'No. Faktur',
        'invoice_date'  => 'Tanggal Faktur',
        'due_date'      => 'Jatuh Tempo',
        'payment_method'=> 'Metode Bayar',
        'subtotal'      => 'Subtotal',
        'ppn'           => 'PPN (Pajak)',
        'dp'            => 'Uang Muka (DP)',
        'grand_total'   => 'Grand Total',
        'user'          => 'Petugas / Kasir',
    ],

    // Backward-compatibility aliases for existing views
    'item' => [
        'singular'      => 'Produk',
        'plural'        => 'Data Produk',
        'menu_icon'     => 'box',
        'code_label'    => 'ID Produk',
        'name_label'    => 'Nama Produk',
        'category_label'=> 'Jenis',
        'price_label'   => 'Harga',
        'stock_label'   => 'Stok',
        'image_label'   => 'Foto',
        'desc_label'    => 'Keterangan',
        'unit'          => 'Unit',
    ],
    'category' => [
        'singular'   => 'Perusahaan',
        'plural'     => 'Data Perusahaan',
        'menu_icon'  => 'briefcase',
        'name_label' => 'Nama Perusahaan',
        'desc_label' => 'Alamat Perusahaan',
    ],
    'transaction' => [
        'singular'     => 'Faktur Penjualan',
        'plural'       => 'Faktur Penjualan',
        'menu_icon'    => 'file-text',
        'code_label'   => 'No. Faktur',
        'date_label'   => 'Tanggal Faktur',
        'user_label'   => 'Petugas',
        'total_label'  => 'Grand Total',
        'action_verb'  => 'Proses Faktur',
        'invoice_title'=> 'FAKTUR PENJUALAN',
    ],
    'report' => [
        'title'       => 'Laporan Rekapitulasi',
        'filter_label'=> 'Periode Tanggal',
        'export_pdf'  => 'Unduh PDF',
        'export_excel'=> 'Ekspor Excel / CSV',
        'print_view'  => 'Cetak Dokumen',
    ],
];
