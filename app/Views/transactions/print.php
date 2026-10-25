<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nota - <?= htmlspecialchars($transaction['kode_transaksi']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 13px; color: #111; padding: 25px; max-width: 780px; margin: 0 auto; background: white; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: 800; }
        .header p { font-size: 11px; color: #555; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 12px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left; padding: 8px 6px; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px 6px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box { display: flex; justify-content: flex-end; margin-top: 10px; }
        .total-box table { width: 300px; margin-bottom: 0; }
        .total-box td { border: none; padding: 4px 6px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; font-size: 12px; }
        .sign-box { width: 180px; }
        .sign-line { margin-top: 60px; border-top: 1px solid #000; }
        .no-print { margin-bottom: 20px; padding: 10px; background: #f1f5f9; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; }
        .btn-print { background: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 600; cursor: pointer; text-decoration: none; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <span>Tekan tombol di sebelah kanan untuk mencetak nota atau simpan sebagai PDF:</span>
        <button onclick="window.print()" class="btn-print">Cetak Dokumen / Simpan PDF</button>
    </div>

    <div class="header">
        <div>
            <h1><?= htmlspecialchars(term('app_name')) ?></h1>
            <p><?= htmlspecialchars(term('organization')) ?></p>
            <p><?= htmlspecialchars(term('system_title')) ?></p>
        </div>
        <div class="text-right">
            <h2 style="font-size: 16px;"><?= htmlspecialchars(term('transaction.invoice_title')) ?></h2>
            <div style="font-family: monospace; font-size: 14px; font-weight: bold; margin-top: 4px;"><?= htmlspecialchars($transaction['kode_transaksi']) ?></div>
            <div style="font-size: 11px; margin-top: 2px;">Status: <strong><?= strtoupper(htmlspecialchars($transaction['status'])) ?></strong></div>
        </div>
    </div>

    <div class="meta">
        <div>
            <div><strong>Pelanggan / Peminjam:</strong> <?= htmlspecialchars($transaction['nama_pelanggan']) ?></div>
            <div><strong>Catatan:</strong> <?= !empty($transaction['catatan']) ? htmlspecialchars($transaction['catatan']) : '-' ?></div>
        </div>
        <div class="text-right">
            <div><strong>Tanggal Transaksi:</strong> <?= format_date($transaction['tanggal']) ?></div>
            <div><strong>Petugas:</strong> <?= htmlspecialchars($transaction['nama_petugas'] ?? 'Sistem') ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th><?= htmlspecialchars(term('item.singular')) ?></th>
                <th>Kode</th>
                <th class="text-right">Harga</th>
                <th class="text-center" style="width: 50px;">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaction['items'] as $idx => $it): ?>
                <tr>
                    <td><?= $idx + 1 ?></td>
                    <td><strong><?= htmlspecialchars($it['nama_item']) ?></strong></td>
                    <td style="font-family: monospace; font-size: 11px;"><?= htmlspecialchars($it['kode_item']) ?></td>
                    <td class="text-right"><?= format_rupiah($it['harga_satuan']) ?></td>
                    <td class="text-center"><?= $it['qty'] ?></td>
                    <td class="text-right"><strong><?= format_rupiah($it['subtotal']) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td><strong>TOTAL PEMBAYARAN:</strong></td>
                <td class="text-right" style="font-size: 16px; font-weight: bold;"><?= format_rupiah($transaction['total_bayar']) ?></td>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <div class="sign-box">
            <div>Penerima / Pelanggan</div>
            <div class="sign-line"><?= htmlspecialchars($transaction['nama_pelanggan']) ?></div>
        </div>
        <div class="sign-box">
            <div>Petugas Pengelola</div>
            <div class="sign-line"><?= htmlspecialchars($transaction['nama_petugas'] ?? 'Petugas') ?></div>
        </div>
    </div>
</body>
</html>
