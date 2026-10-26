<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan - <?= htmlspecialchars(term('app_name')) ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 2rem 1rem;
            font-size: 11pt;
        }
        .print-container {
            max-width: 1050px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .kop-surat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .kop-brand h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .kop-brand p {
            font-size: 0.85rem;
            color: #475569;
            margin-top: 0.2rem;
        }
        .kop-meta {
            text-align: right;
            font-size: 0.825rem;
            color: #64748b;
        }
        .report-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .report-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .report-header p {
            font-size: 0.9rem;
            color: #475569;
            margin-top: 0.25rem;
        }
        .summary-boxes {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .box {
            border: 1px solid #cbd5e1;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            background: #f8fafc;
        }
        .box-title {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .box-val {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.25rem;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 2rem;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 0.5rem 0.65rem;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
            color: #334155;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            margin-top: 4.5rem;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 0.25rem;
            font-weight: 700;
        }
        .action-bar {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn-close {
            background: #64748b;
            color: white;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        @media print {
            body {
                background: transparent;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="print-container">
    <div class="action-bar">
        <a href="javascript:window.close()" class="btn-close">Tutup Halaman</a>
        <button onclick="window.print()" class="btn-print">Cetak / Simpan PDF</button>
    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-brand">
            <h1><?= htmlspecialchars(term('app_name')) ?></h1>
            <p><?= htmlspecialchars(term('tagline')) ?></p>
        </div>
        <div class="kop-meta">
            <div>Tanggal Cetak: <?= format_date(date('Y-m-d')) ?> <?= date('H:i') ?> WIB</div>
            <div>Dokumen Resmi Penjualan Apotek</div>
        </div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-header">
        <h2>Laporan Rekapitulasi Penjualan</h2>
        <p>Periode Transaksi: <strong><?= format_date($startDate) ?></strong> s/d <strong><?= format_date($endDate) ?></strong></p>
    </div>

    <!-- KOTAK STATISTIK -->
    <div class="summary-boxes">
        <div class="box">
            <div class="box-title">Total Transaksi</div>
            <div class="box-val"><?= number_format($summary['total_faktur'], 0, ',', '.') ?> Faktur</div>
        </div>
        <div class="box">
            <div class="box-title">Total Omset Penjualan</div>
            <div class="box-val"><?= format_rupiah($summary['total_grand_total']) ?></div>
        </div>
        <div class="box">
            <div class="box-title">Total PPN (11%)</div>
            <div class="box-val"><?= format_rupiah($summary['total_ppn']) ?></div>
        </div>
        <div class="box">
            <div class="box-title">Total Sisa Piutang</div>
            <div class="box-val" style="color: #dc2626;"><?= format_rupiah($summary['total_sisa']) ?></div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Perusahaan</th>
                <th style="text-align: center;">Metode</th>
                <th style="text-align: right;">Subtotal</th>
                <th style="text-align: right;">PPN 11%</th>
                <th style="text-align: right;">Grand Total</th>
                <th style="text-align: right;">Sisa Tagihan</th>
                <th style="text-align: center;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($fakturList)): ?>
                <tr>
                    <td colspan="11" style="text-align: center; color: #94a3b8; padding: 2rem;">
                        Tidak ada data transaksi pada periode yang dipilih.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($fakturList as $f): 
                    $sisa = max(0, (float)$f['grand_total'] - (float)$f['dp']);
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td style="font-family: monospace; font-weight: 700;"><?= htmlspecialchars($f['no_faktur']) ?></td>
                        <td><?= format_date($f['tgl_faktur']) ?></td>
                        <td><?= htmlspecialchars($f['nama_customer']) ?></td>
                        <td><?= htmlspecialchars($f['nama_perusahaan']) ?></td>
                        <td style="text-align: center; font-size: 8.5pt; font-weight: 600;"><?= htmlspecialchars($f['metode_bayar']) ?></td>
                        <td style="text-align: right;"><?= format_rupiah($f['subtotal']) ?></td>
                        <td style="text-align: right;"><?= format_rupiah($f['ppn']) ?></td>
                        <td style="text-align: right; font-weight: 700;"><?= format_rupiah($f['grand_total']) ?></td>
                        <td style="text-align: right;"><?= $sisa > 0 ? format_rupiah($sisa) : 'Lunas' ?></td>
                        <td style="text-align: center; font-size: 8.5pt;"><?= htmlspecialchars($f['user']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if (!empty($fakturList)): ?>
        <tfoot>
            <tr style="background: #f8fafc; font-weight: 700;">
                <td colspan="6" style="text-align: right;">TOTAL KESELURUHAN:</td>
                <td style="text-align: right;"><?= format_rupiah($summary['total_subtotal']) ?></td>
                <td style="text-align: right;"><?= format_rupiah($summary['total_ppn']) ?></td>
                <td style="text-align: right;"><?= format_rupiah($summary['total_grand_total']) ?></td>
                <td style="text-align: right;"><?= format_rupiah($summary['total_sisa']) ?></td>
                <td></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>

    <!-- LEMBAR PENGESAHAN / TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Dibuat Oleh,</div>
            <div style="font-size: 0.8rem; color: #64748b;">Petugas Kasir / Admin</div>
            <div class="signature-line"><?= htmlspecialchars(auth('nama_lengkap') ?? 'Petugas Kasir') ?></div>
            <div style="font-size: 0.75rem; color: #64748b;">NIP / ID: <?= htmlspecialchars(auth('username') ?? 'kasir') ?></div>
        </div>

        <div class="signature-box">
            <div>Mengetahui & Menyetujui,</div>
            <div style="font-size: 0.8rem; color: #64748b;">Pimpinan Apotek LSP</div>
            <div class="signature-line">(...................................................)</div>
            <div style="font-size: 0.75rem; color: #64748b;">Apoteker Penanggung Jawab</div>
        </div>
    </div>
</div>

</body>
</html>
