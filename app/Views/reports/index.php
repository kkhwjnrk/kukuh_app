<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title"><?= htmlspecialchars(term('report.title')) ?></h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Rekapitulasi berkala dan ekspor dokumen pertanggungjawaban operasional.
            </p>
        </div>

        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?= url('/reports/excel?start_date=' . $startDate . '&end_date=' . $endDate) ?>" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span><?= htmlspecialchars(term('report.export_excel')) ?></span>
            </a>

            <a href="<?= url('/reports/print?start_date=' . $startDate . '&end_date=' . $endDate) ?>" target="_blank" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span><?= htmlspecialchars(term('report.print_view')) ?> / PDF</span>
            </a>
        </div>
    </div>

    <!-- FILTER PERIODE TANGGAL -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/reports') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="width: 180px;">
                <label class="form-label" style="font-size: 0.75rem;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" required value="<?= htmlspecialchars($startDate) ?>">
            </div>

            <div style="width: 180px;">
                <label class="form-label" style="font-size: 0.75rem;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" required value="<?= htmlspecialchars($endDate) ?>">
            </div>

            <div>
                <button type="submit" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Terapkan Periode</span>
                </button>
            </div>
        </form>
    </div>

    <!-- KARTU RINGKASAN REKAPITULASI -->
    <div style="padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; border-bottom: 1px solid var(--border-subtle);">
        <div style="background: var(--primary-light); padding: 1.25rem; border-radius: 12px; border: 1px solid #c7d2fe;">
            <span style="font-size: 0.775rem; font-weight: 700; text-transform: uppercase; color: var(--primary-text);">Total Akumulasi Nilai:</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary); margin-top: 0.25rem;">
                <?= format_rupiah($totalOmset) ?>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0;">
            <span style="font-size: 0.775rem; font-weight: 700; text-transform: uppercase; color: #64748b;">Jumlah Transaksi:</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                <?= number_format($totalCount) ?> Transaksi
            </div>
        </div>
    </div>

    <!-- TABEL REKAP -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th><?= htmlspecialchars(term('transaction.code_label')) ?></th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Petugas</th>
                    <th>Total Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada transaksi pada rentang tanggal yang dipilih.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $index => $t): ?>
                        <tr>
                            <td style="color: #94a3b8;"><?= $index + 1 ?></td>
                            <td>
                                <a href="<?= url('/transactions/show/' . $t['id']) ?>" style="font-family: monospace; font-weight: 700;">
                                    <?= htmlspecialchars($t['kode_transaksi']) ?>
                                </a>
                            </td>
                            <td><?= format_date($t['tanggal']) ?></td>
                            <td>
                                <strong style="color: #0f172a;"><?= htmlspecialchars($t['nama_pelanggan']) ?></strong>
                            </td>
                            <td style="color: #64748b;"><?= htmlspecialchars($t['nama_petugas'] ?? 'Sistem') ?></td>
                            <td style="font-weight: 700; color: #0f172a;">
                                <?= format_rupiah($t['total_bayar']) ?>
                            </td>
                            <td><?= badge_status($t['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
