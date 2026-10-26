<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars(term('report.title')) ?> (<?= format_date($startDate) ?> s/d <?= format_date($endDate) ?>)</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 12px; color: #111; padding: 30px; max-width: 900px; margin: 0 auto; background: white; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: 800; letter-spacing: -0.02em; }
        .header h2 { font-size: 14px; font-weight: 600; margin-top: 4px; text-transform: uppercase; }
        .header p { font-size: 11px; color: #555; margin-top: 2px; }
        .period-info { margin-bottom: 15px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left; padding: 8px 6px; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px 6px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .grand-total { border-top: 2px solid #000; border-bottom: 2px solid #000; font-weight: bold; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; }
        .sign-box { width: 220px; }
        .sign-line { margin-top: 70px; border-top: 1px solid #000; font-weight: bold; }
        .no-print { margin-bottom: 20px; padding: 12px 16px; background: #f1f5f9; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; }
        .btn-print { background: #4f46e5; color: white; border: none; padding: 8px 18px; border-radius: 4px; font-weight: 600; cursor: pointer; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <span>Pratinjau Cetak Laporan Rekapitulasi:</span>
        <button onclick="window.print()" class="btn-print">Cetak Laporan / Simpan PDF</button>
    </div>

    <div class="header">
        <h1><?= htmlspecialchars(term('app_name')) ?></h1>
        <h2><?= htmlspecialchars(term('report.title')) ?></h2>
        <p><?= htmlspecialchars(term('organization')) ?> &bull; <?= htmlspecialchars(term('system_title')) ?></p>
    </div>

    <div class="period-info">
        <strong>Periode Laporan:</strong> <?= format_date($startDate) ?> s/d <?= format_date($endDate) ?><br>
        <strong>Dicetak Pada:</strong> <?= format_date(date('Y-m-d H:i:s'), true) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35px;">#</th>
                <th><?= htmlspecialchars(term('transaction.code_label')) ?></th>
                <th>Tanggal</th>
                <th>Nama Pelanggan / Peminjam</th>
                <th>Petugas</th>
                <th class="text-right">Total Nilai</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transactions as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td style="font-family: monospace;"><?= htmlspecialchars($row['kode_transaksi']) ?></td>
                        <td><?= format_date($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_petugas'] ?? '-') ?></td>
                        <td class="text-right"><?= format_rupiah($row['total_bayar']) ?></td>
                        <td class="text-center"><?= strtoupper(htmlspecialchars($row['status'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="grand-total">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN (<?= count($transactions) ?> TRANSAKSI):</td>
                <td class="text-right"><?= format_rupiah($totalOmset) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sign-box">
            <div>Mengetahui,</div>
            <div>Pimpinan Apotek</div>
            <div class="sign-line">( ........................................ )</div>
        </div>
        <div class="sign-box">
            <div>Dibuat Oleh,</div>
            <div>Petugas Pengelola Sistem</div>
            <div class="sign-line">( <?= htmlspecialchars(auth('nama_lengkap') ?? 'Petugas') ?> )</div>
        </div>
    </div>
</body>
</html>
