<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Cetak Data Customer') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #1e293b; padding: 2rem; }
        .print-container { max-width: 900px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 1.25rem; margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.5rem; font-weight: 700; color: #0f172a; text-transform: uppercase; }
        .header p { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
        .meta-info { display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.875rem; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        th, td { border: 1px solid #cbd5e1; padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; }
        th { background: #f1f5f9; font-weight: 600; color: #0f172a; text-transform: uppercase; font-size: 0.8rem; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer-sign { display: flex; justify-content: space-between; margin-top: 3rem; }
        .sign-box { text-align: center; width: 200px; }
        .sign-space { height: 70px; }
        .sign-name { font-weight: 600; border-top: 1px solid #94a3b8; padding-top: 0.25rem; }
        .actions-bar { max-width: 900px; margin: 0 auto 1rem auto; display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; cursor: pointer; text-decoration: none; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        @media print {
            body { background: #fff; padding: 0; }
            .actions-bar { display: none; }
            .print-container { box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="<?= url('/customer') ?>" class="btn btn-secondary">&larr; Kembali ke Daftar Customer</a>
        <button onclick="window.print()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Dokumen (Print / PDF)</span>
        </button>
    </div>

    <div class="print-container">
        <div class="header">
            <div>
                <h1><?= htmlspecialchars(term('app_name')) ?> - LAPORAN DATA CUSTOMER</h1>
                <p><?= htmlspecialchars(term('system_title')) ?> &bull; Lembaga Sertifikasi Profesi (LSP)</p>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 1.1rem; color: #2563eb;">REKAPITULASI</div>
                <div style="font-size: 0.8rem; color: #64748b;">Tanggal Cetak: <?= date('d/m/Y H:i') ?></div>
            </div>
        </div>

        <div class="meta-info">
            <div><strong>Filter / Status:</strong> <?= !empty($keyword) ? 'Pencarian: "' . htmlspecialchars($keyword) . '"' : 'Seluruh Data Customer Aktif' ?></div>
            <div><strong>Total Pelanggan:</strong> <?= count($customers) ?> Customer</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 70px;">ID</th>
                    <th>Nama Customer</th>
                    <th>Perusahaan Rekanan</th>
                    <th>Alamat Lengkap</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2rem;">
                            Tidak ada data customer yang tersedia untuk dicetak.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $idx => $c): ?>
                        <tr>
                            <td style="text-align: center;"><?= $idx + 1 ?></td>
                            <td style="font-weight: 600;">#<?= $c['id_customer'] ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($c['nama_customer']) ?></td>
                            <td><?= htmlspecialchars($c['perusahaan_cust']) ?></td>
                            <td><?= htmlspecialchars($c['alamat']) ?></td>
                            <td><?= htmlspecialchars($c['no_telp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer-sign">
            <div class="sign-box">
                <p>Mengetahui / Verifikator,</p>
                <div class="sign-space"></div>
                <p class="sign-name">( Supervisor / Asesor )</p>
            </div>
            <div class="sign-box">
                <p>Dicetak Oleh,</p>
                <div class="sign-space"></div>
                <p class="sign-name">( <?= htmlspecialchars(auth('nama_lengkap') ?? 'Petugas Administrasi') ?> )</p>
            </div>
        </div>
    </div>

</body>
</html>
