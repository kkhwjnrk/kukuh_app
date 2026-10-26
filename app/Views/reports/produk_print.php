<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Produk & Stok - <?= htmlspecialchars(term('app_name')) ?></title>
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
            grid-template-columns: repeat(3, 1fr);
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
            font-size: 1.1rem;
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
            <div>Dokumen Resmi Persediaan & Aset Apotek</div>
        </div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-header">
        <h2>Laporan Data Produk & Valuasi Stok</h2>
        <p>Rekapitulasi Fisik Persediaan dan Estimasi Nilai Aset Obat/Produk</p>
    </div>

    <!-- KOTAK STATISTIK -->
    <div class="summary-boxes">
        <div class="box">
            <div class="box-title">Total Varian Produk</div>
            <div class="box-val"><?= number_format($summary['total_produk'], 0, ',', '.') ?> Item</div>
        </div>
        <div class="box">
            <div class="box-title">Total Fisik Persediaan</div>
            <div class="box-val"><?= number_format($summary['total_stok'], 0, ',', '.') ?> Unit</div>
        </div>
        <div class="box">
            <div class="box-title">Total Nilai Aset / Persediaan</div>
            <div class="box-val" style="color: #16a34a;"><?= format_rupiah($summary['total_nilai_aset']) ?></div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th style="width: 65px; text-align: center;">ID</th>
                <th>Nama Produk / Obat</th>
                <th>Jenis / Sediaan</th>
                <th style="text-align: right;">Harga Satuan</th>
                <th style="text-align: center;">Stok Fisik</th>
                <th style="text-align: right;">Nilai Aset</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produkList)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 2rem;">
                        Tidak ada data produk yang terdaftar.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($produkList as $p): 
                    $stok = (int)$p['stock'];
                    $status = ($stok <= 0) ? 'Habis' : (($stok <= 10) ? 'Menipis' : 'Tersedia');
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td style="text-align: center; font-family: monospace;">#<?= str_pad($p['id_produk'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($p['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($p['jenis'] ?? '-') ?></td>
                        <td style="text-align: right;"><?= format_rupiah($p['price']) ?></td>
                        <td style="text-align: center; font-weight: 700;"><?= number_format($stok, 0, ',', '.') ?></td>
                        <td style="text-align: right; font-weight: 600;"><?= format_rupiah($p['nilai_aset']) ?></td>
                        <td style="text-align: center; font-size: 8.5pt;"><?= $status ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if (!empty($produkList)): ?>
        <tfoot>
            <tr style="background: #f8fafc; font-weight: 700;">
                <td colspan="5" style="text-align: right;">TOTAL PERSAMAAN:</td>
                <td style="text-align: center;"><?= number_format($summary['total_stok'], 0, ',', '.') ?> Unit</td>
                <td style="text-align: right; color: #16a34a;"><?= format_rupiah($summary['total_nilai_aset']) ?></td>
                <td></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>

    <!-- LEMBAR PENGESAHAN / TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Dibuat Oleh,</div>
            <div style="font-size: 0.8rem; color: #64748b;">Pengelola Persediaan / Gudang</div>
            <div class="signature-line"><?= htmlspecialchars(auth('nama_lengkap') ?? 'Petugas Logistik') ?></div>
            <div style="font-size: 0.75rem; color: #64748b;">NIP / ID: <?= htmlspecialchars(auth('username') ?? 'kasir') ?></div>
        </div>

        <div class="signature-box">
            <div>Mengetahui & Memverifikasi,</div>
            <div style="font-size: 0.8rem; color: #64748b;">Apoteker Penanggung Jawab</div>
            <div class="signature-line">(...................................................)</div>
            <div style="font-size: 0.75rem; color: #64748b;">Penanggung Jawab Teknis Kefarmasian</div>
        </div>
    </div>
</div>

</body>
</html>
