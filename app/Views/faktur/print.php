<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penjualan - <?= htmlspecialchars($faktur['no_faktur']) ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
        }

        body {
            background-color: #f1f5f9;
            padding: 20px 0;
        }

        /* FLOATING ACTION BAR FOR SCREEN VIEW */
        .no-print-bar {
            max-width: 820px;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #334155;
            border-color: #cbd5e1;
        }

        .btn-back:hover {
            background-color: #e2e8f0;
        }

        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }

        .btn-print:hover {
            background-color: #1d4ed8;
        }

        /* INVOICE PAPER STYLES */
        .invoice-sheet {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            padding: 28px 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            font-size: 11px;
            line-height: 1.35;
        }

        /* HEADER */
        .header-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .company-block {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .logo-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #334155;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            font-family: 'Times New Roman', Times, serif;
            flex-shrink: 0;
        }

        .company-text h1 {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .company-text p {
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title-block h2 {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 5px;
            color: #1e293b;
            font-family: 'Arial Black', Arial, sans-serif;
            text-transform: uppercase;
        }

        /* METADATA INFO DUA KOLOM */
        .meta-wrap {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 11px;
        }

        .meta-col-left {
            width: 52%;
        }

        .meta-col-right {
            width: 44%;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
            border: none;
            font-size: 11px;
        }

        .meta-table td.m-label {
            width: 105px;
            color: #111;
        }

        .meta-table td.m-colon {
            width: 10px;
            text-align: center;
        }

        .meta-table td.m-val {
            font-weight: normal;
            color: #000;
        }

        /* TABEL BARANG */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #71717a;
            padding: 4px 6px;
            font-size: 10.5px;
        }

        .items-table th {
            background-color: #f8fafc;
            font-weight: bold;
            text-align: center;
        }

        .items-table td.c-center {
            text-align: center;
        }

        .items-table td.c-right {
            text-align: right;
        }

        .items-table tr.total-qty-row td {
            font-weight: bold;
            background: #fafafa;
        }

        /* FOOTER & TOTAL */
        .footer-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 6px;
            font-size: 11px;
        }

        .notes-block {
            width: 56%;
        }

        .notes-block .note-title {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 10.5px;
        }

        .notes-block .note-desc {
            font-size: 10px;
            color: #333;
            line-height: 1.35;
        }

        .summary-block {
            width: 38%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 2.5px 4px;
            border: 1px solid #71717a;
            font-size: 10.5px;
        }

        .summary-table td.s-label {
            text-align: right;
            width: 50%;
            background-color: #f8fafc;
            font-weight: normal;
        }

        .summary-table td.s-val {
            text-align: right;
            font-weight: normal;
        }

        .summary-table tr.s-grand td {
            font-weight: bold;
            background: #ffffff;
        }

        /* SIGNATURE SECTION */
        .signatures-wrap {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
        }

        .sig-item {
            width: 220px;
        }

        .sig-space {
            height: 52px;
        }

        .sig-name {
            font-weight: normal;
            border-top: 1px dotted #555;
            padding-top: 3px;
            display: inline-block;
            min-width: 150px;
        }

        /* PRINT STYLES */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .no-print-bar {
                display: none !important;
            }

            .invoice-sheet {
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- TOMBOL AKSI ATAS (TIDAK AKAN TERCETAK DI KERTAS) -->
    <div class="no-print-bar">
        <a href="<?= url('/faktur') ?>" class="btn btn-back">
            &larr; Kembali ke Daftar Faktur
        </a>
        <div style="display: flex; gap: 8px;">
            <a href="<?= url('/faktur/show/' . $faktur['no_faktur']) ?>" class="btn btn-back">
                Detail Faktur
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Print Faktur (PDF)</span>
            </button>
        </div>
    </div>

    <!-- DOKUMEN FAKTUR PENJUALAN RESMI -->
    <div class="invoice-sheet">

        <!-- 1. KOP PERUSAHAAN & JUDUL FAKTUR -->
        <div class="header-wrap">
            <div class="company-block">
                <div class="logo-badge">R</div>
                <div class="company-text">
                    <h1><?= htmlspecialchars($faktur['nama_perusahaan']) ?></h1>
                    <p>No. Surat Izin Apotek : 503/00586/DPM-PTSP/kes/XII/2018</p>
                    <p><?= htmlspecialchars($faktur['alamat_perusahaan']) ?></p>
                    <p>Telp. <?= htmlspecialchars($faktur['telp_perusahaan']) ?>, Email : support@vmedis.com, Website : vmedis.com</p>
                </div>
            </div>
            <div class="invoice-title-block">
                <h2>FAKTUR</h2>
            </div>
        </div>

        <!-- 2. DATA PELANGGAN & TRANSAKSI -->
        <div class="meta-wrap">
            <!-- Kolom Kiri: Pelanggan -->
            <div class="meta-col-left">
                <table class="meta-table">
                    <tr>
                        <td class="m-label">Nama Pelanggan</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= htmlspecialchars($faktur['nama_customer']) ?></td>
                    </tr>
                    <tr>
                        <td class="m-label">No. Telp</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= htmlspecialchars($faktur['telp_customer']) ?></td>
                    </tr>
                    <tr>
                        <td class="m-label">Alamat</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= htmlspecialchars($faktur['alamat_customer']) ?></td>
                    </tr>
                </table>
            </div>

            <!-- Kolom Kanan: Kasir & Faktur -->
            <div class="meta-col-right">
                <table class="meta-table">
                    <tr>
                        <td class="m-label">Kasir</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= htmlspecialchars($faktur['user']) ?></td>
                    </tr>
                    <tr>
                        <td class="m-label">Tanggal</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= date('d F Y H:i:s', strtotime($faktur['tgl_faktur'])) ?></td>
                    </tr>
                    <tr>
                        <td class="m-label">No. Faktur</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= htmlspecialchars($faktur['no_faktur']) ?></td>
                    </tr>
                    <tr>
                        <td class="m-label">Pembayaran</td>
                        <td class="m-colon">:</td>
                        <td class="m-val"><?= strtoupper($faktur['metode_bayar']) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 3. TABEL RINCIAN ITEM BARANG -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <th>Nama Barang</th>
                    <th style="width: 48px;">Qty</th>
                    <th style="width: 68px;">Satuan</th>
                    <th style="width: 150px;">Batch & ED</th>
                    <th style="width: 80px; text-align: right;">Harga</th>
                    <th style="width: 46px; text-align: right;">Disc</th>
                    <th style="width: 85px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalQty = 0;
                $batchEDs = [
                    '-(ED 2026-05-10)',
                    'J80162B1 (ED 2026-09-30)',
                    'A1 (ED 2027-04-07)',
                    'B2 (ED 2026-12-15)',
                    'C3 (ED 2027-08-20)',
                ];
                foreach ($faktur['items'] as $idx => $item): 
                    $totalQty += (int)$item['qty'];
                    $batchText = $batchEDs[$idx % count($batchEDs)];
                ?>
                    <tr>
                        <td class="c-center"><?= $idx + 1 ?></td>
                        <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                        <td class="c-center"><?= (int)$item['qty'] ?></td>
                        <td><?= htmlspecialchars($item['jenis']) ?></td>
                        <td style="font-size: 10px;"><?= $batchText ?></td>
                        <td class="c-right"><?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="c-right">0%</td>
                        <td class="c-right"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- BARIS JUMLAH TOTAL QTY -->
                <tr class="total-qty-row">
                    <td></td>
                    <td></td>
                    <td class="c-center"><?= $totalQty ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- 4. CATATAN & REKAP PEMBAYARAN -->
        <div class="footer-wrap">
            <div class="notes-block">
                <div class="note-title">Catatan :</div>
                <div class="note-desc">
                    Terimakasih Telah berkunjung, semoga sehat selalu<br>
                    Maaf, barang yang sudah dibeli<br>
                    tidak dapat ditukar atau dikembalikan
                </div>
            </div>

            <div class="summary-block">
                <table class="summary-table">
                    <tr>
                        <td class="s-label">Total :</td>
                        <td class="s-val"><?= number_format($faktur['subtotal_items'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="s-label">Diskon :</td>
                        <td class="s-val">0</td>
                    </tr>
                    <tr>
                        <td class="s-label">Pajak :</td>
                        <td class="s-val"><?= number_format($faktur['ppn'], 0, ',', '.') ?></td>
                    </tr>
                    <tr class="s-grand">
                        <td class="s-label" style="font-weight: bold;">Grand Total :</td>
                        <td class="s-val" style="font-weight: bold;"><?= number_format($faktur['grand_total'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 5. TANDA TANGAN DUA BELAH PIHAK -->
        <div class="signatures-wrap">
            <div class="sig-item">
                <p>Penerima / Pembeli</p>
                <div class="sig-space"></div>
                <p class="sig-name">( <?= htmlspecialchars($faktur['nama_customer']) ?> )</p>
            </div>
            <div class="sig-item">
                <p><?= htmlspecialchars($faktur['nama_perusahaan']) ?></p>
                <div class="sig-space"></div>
                <p class="sig-name">( <?= htmlspecialchars($faktur['user']) ?> )</p>
            </div>
        </div>

    </div>

</body>
</html>
