<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penjualan - <?= htmlspecialchars($faktur['no_faktur']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #0f172a; padding: 2rem; font-size: 13px; line-height: 1.4; }
        .invoice-card { max-width: 880px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        
        /* HEADER PERUSAHAAN & TITLE */
        .header-grid { display: grid; grid-template-columns: 1.4fr 1fr; border-bottom: 2px solid #0f172a; padding-bottom: 1.25rem; margin-bottom: 1.5rem; }
        .company-info h1 { font-size: 1.4rem; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 0.25rem; }
        .company-info p { color: #475569; font-size: 0.85rem; line-height: 1.4; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 1.7rem; font-weight: 800; color: #1e3a8a; letter-spacing: 0.5px; text-transform: uppercase; }
        .invoice-title .status { display: inline-block; margin-top: 0.35rem; font-weight: 700; color: #15803d; background: #dcfce7; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; }

        /* DUA KOLOM REKANAN & METADATA */
        .meta-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        .box-info { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem 1.25rem; }
        .box-title { font-weight: 700; color: #0f172a; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; margin-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.35rem; }
        .box-content table { width: 100%; border-collapse: collapse; }
        .box-content td { padding: 0.25rem 0; font-size: 0.85rem; border: none; vertical-align: top; }
        .box-content td.label { width: 110px; color: #64748b; font-weight: 500; }
        .box-content td.val { color: #0f172a; font-weight: 600; }

        /* TABEL BARANG */
        table.items-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        table.items-table th, table.items-table td { border: 1px solid #cbd5e1; padding: 0.65rem 0.85rem; font-size: 0.85rem; }
        table.items-table th { background: #f1f5f9; color: #0f172a; text-transform: uppercase; font-size: 0.75rem; font-weight: 700; }
        table.items-table tr:nth-child(even) td { background: #fafafa; }

        /* RINGKASAN PEMBAYARAN */
        .summary-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem; align-items: start; margin-bottom: 2.5rem; }
        .terbilang-box { background: #f1f5f9; border-radius: 6px; padding: 1rem; border-left: 4px solid #2563eb; }
        .terbilang-title { font-weight: 700; font-size: 0.75rem; color: #475569; text-transform: uppercase; margin-bottom: 0.25rem; }
        .terbilang-text { font-size: 0.85rem; color: #1e293b; font-style: italic; }

        table.summary-table { width: 100%; border-collapse: collapse; }
        table.summary-table td { padding: 0.4rem 0.5rem; font-size: 0.9rem; border: none; }
        table.summary-table td.label { color: #64748b; }
        table.summary-table td.val { text-align: right; font-weight: 600; color: #0f172a; }
        table.summary-table tr.grand-row td { border-top: 2px solid #0f172a; font-size: 1.15rem; font-weight: 800; color: #1e3a8a; padding-top: 0.6rem; }

        /* TANDA TANGAN DUA PIHAK */
        .signature-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; text-align: center; }
        .sig-box { border-top: 1px dashed #cbd5e1; padding-top: 0.5rem; }
        .sig-space { height: 75px; }
        .sig-name { font-weight: 700; color: #0f172a; border-top: 1px solid #0f172a; display: inline-block; min-width: 180px; padding-top: 0.25rem; }

        /* ACTION BAR */
        .actions-bar { max-width: 880px; margin: 0 auto 1.25rem auto; display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; cursor: pointer; text-decoration: none; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #334155; }

        @media print {
            body { background: #fff; padding: 0; }
            .actions-bar { display: none; }
            .invoice-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="<?= url('/faktur/show/' . $faktur['no_faktur']) ?>" class="btn btn-secondary">
            &larr; Kembali ke Detail Faktur
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Dokumen Sekarang (Print / PDF)</span>
        </button>
    </div>

    <div class="invoice-card">
        <!-- HEADER KOP PERUSAHAAN & FAKTUR TITLE (PERSIS CONTOH PHOTO 3) -->
        <div class="header-grid">
            <div class="company-info">
                <h1><?= htmlspecialchars($faktur['nama_perusahaan']) ?></h1>
                <p><?= htmlspecialchars($faktur['alamat_perusahaan']) ?></p>
                <p><strong>Telp:</strong> <?= htmlspecialchars($faktur['telp_perusahaan']) ?> &bull; <strong>Fax:</strong> <?= htmlspecialchars($faktur['fax_perusahaan'] ?: '-') ?></p>
            </div>
            <div class="invoice-title">
                <h2>FAKTUR PENJUALAN</h2>
                <div class="status">RESMI / VERIFIED</div>
            </div>
        </div>

        <!-- REKANAN CUSTOMER & METADATA FAKTUR -->
        <div class="meta-grid">
            <!-- Kolom Kiri: Customer -->
            <div class="box-info">
                <div class="box-title">Kepada Yth. (Customer)</div>
                <div class="box-content">
                    <table>
                        <tr>
                            <td class="label">Nama Customer</td>
                            <td class="val">: <?= htmlspecialchars($faktur['nama_customer']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Perusahaan/Klinik</td>
                            <td class="val">: <?= htmlspecialchars($faktur['perusahaan_cust']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Alamat</td>
                            <td class="val">: <?= htmlspecialchars($faktur['alamat_customer']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">No. Telepon</td>
                            <td class="val">: <?= htmlspecialchars($faktur['telp_customer']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Kolom Kanan: Metadata Faktur -->
            <div class="box-info">
                <div class="box-title">Data Transaksi</div>
                <div class="box-content">
                    <table>
                        <tr>
                            <td class="label">No. Faktur</td>
                            <td class="val" style="color: #1e3a8a;">: <?= htmlspecialchars($faktur['no_faktur']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Tanggal Faktur</td>
                            <td class="val">: <?= date('d/m/Y', strtotime($faktur['tgl_faktur'])) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Jatuh Tempo</td>
                            <td class="val" style="color: #dc2626;">: <?= date('d/m/Y', strtotime($faktur['due_date'])) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Cara Bayar</td>
                            <td class="val">: <?= htmlspecialchars($faktur['metode_bayar']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Kasir / User</td>
                            <td class="val">: <?= htmlspecialchars($faktur['user']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN PRODUK (PERSIS DENGAN TABEL APOTEK DEMO PADA FOTO 3) -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Nama Barang / Produk</th>
                    <th style="width: 100px; text-align: center;">Satuan</th>
                    <th style="width: 80px; text-align: center;">Qty</th>
                    <th style="width: 140px; text-align: right;">Harga Satuan</th>
                    <th style="width: 160px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faktur['items'] as $idx => $item): ?>
                    <tr>
                        <td style="text-align: center; color: #64748b;"><?= $idx + 1 ?></td>
                        <td style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($item['nama_produk']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($item['jenis']) ?></td>
                        <td style="text-align: center; font-weight: 700;"><?= (int)$item['qty'] ?></td>
                        <td style="text-align: right;"><?= format_rupiah($item['price']) ?></td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;"><?= format_rupiah($item['subtotal']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- RINGKASAN PEMBAYARAN -->
        <div class="summary-grid">
            <div class="terbilang-box">
                <div class="terbilang-title">Catatan Faktur:</div>
                <div class="terbilang-text">
                    Barang yang sudah dibeli dan diterima dalam kondisi baik tidak dapat ditukar/dikembalikan. Pembayaran jatuh tempo wajib dilunasi sebelum tanggal <?= date('d/m/Y', strtotime($faktur['due_date'])) ?>.
                </div>
            </div>

            <div>
                <table class="summary-table">
                    <tr>
                        <td class="label">Total Nilai Barang:</td>
                        <td class="val"><?= format_rupiah($faktur['subtotal_items']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">PPN (Pajak):</td>
                        <td class="val">+ <?= format_rupiah($faktur['ppn']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Uang Muka (DP):</td>
                        <td class="val" style="color: #15803d;">- <?= format_rupiah($faktur['dp']) ?></td>
                    </tr>
                    <tr class="grand-row">
                        <td class="label" style="font-weight: 800; color: #0f172a;">GRAND TOTAL:</td>
                        <td class="val"><?= format_rupiah($faktur['grand_total']) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- TANDA TANGAN DUA PIHAK (CUSTOMER & KASIR) -->
        <div class="signature-grid">
            <div>
                <p>Tanda Terima / Pelanggan,</p>
                <div class="sig-space"></div>
                <p class="sig-name">( <?= htmlspecialchars($faktur['nama_customer']) ?> )</p>
            </div>
            <div>
                <p>Hormat Kami,</p>
                <div class="sig-space"></div>
                <p class="sig-name">( <?= htmlspecialchars($faktur['user']) ?> )</p>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.2rem;">Petugas Kasir</p>
            </div>
        </div>
    </div>

</body>
</html>
