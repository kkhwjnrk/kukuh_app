<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title"><?= htmlspecialchars(term('transaction.plural')) ?></h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Riwayat transaksi masuk, pencatatan nota, dan status operasional.
            </p>
        </div>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/transactions/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ <?= htmlspecialchars(term('transaction.singular')) ?> Baru</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- FILTER PENCARIAN & TANGGAL -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/transactions') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" style="font-size: 0.75rem;">Cari Kata Kunci</label>
                <input type="text" name="q" class="form-control" placeholder="No. faktur atau nama pelanggan..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.75rem;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate ?? '') ?>">
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.75rem;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate ?? '') ?>">
            </div>

            <div>
                <button type="submit" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cari</span>
                </button>
            </div>

            <?php if (!empty($keyword) || !empty($startDate) || !empty($endDate)): ?>
                <div>
                    <a href="<?= url('/transactions') ?>" class="btn btn-secondary" title="Reset Filter">Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL TRANSAKSI -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><?= htmlspecialchars(term('transaction.code_label')) ?></th>
                    <th><?= htmlspecialchars(term('transaction.date_label')) ?></th>
                    <th>Pelanggan / Peminjam</th>
                    <th><?= htmlspecialchars(term('transaction.user_label')) ?></th>
                    <th><?= htmlspecialchars(term('transaction.total_label')) ?></th>
                    <th>Status</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada riwayat transaksi yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td>
                                <a href="<?= url('/transactions/show/' . $t['id']) ?>" style="font-family: monospace; font-weight: 700;">
                                    <?= htmlspecialchars($t['kode_transaksi']) ?>
                                </a>
                            </td>
                            <td><?= format_date($t['tanggal']) ?></td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($t['nama_pelanggan']) ?></div>
                            </td>
                            <td style="color: #64748b; font-size: 0.85rem;">
                                <?= htmlspecialchars($t['nama_petugas'] ?? 'Sistem') ?>
                            </td>
                            <td style="font-weight: 700; color: #0f172a;">
                                <?= format_rupiah($t['total_bayar']) ?>
                            </td>
                            <td><?= badge_status($t['status']) ?></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= url('/transactions/show/' . $t['id']) ?>" class="btn btn-secondary btn-sm" title="Lihat Faktur">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="<?= url('/transactions/print/' . $t['id']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Nota">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINASI -->
    <?php if ($pagination['last_page'] > 1): ?>
        <div style="padding: 1.25rem 1.5rem;">
            <div class="pagination">
                <div>
                    Menampilkan halaman <strong><?= $pagination['page'] ?></strong> dari <strong><?= $pagination['last_page'] ?></strong> (Total: <?= $pagination['total'] ?> transaksi)
                </div>
                <div class="pagination-links">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <?php 
                            $qp = $_GET;
                            $qp['page'] = $i;
                            $pUrl = url('/transactions?' . http_build_query($qp));
                        ?>
                        <a href="<?= $pUrl ?>" class="page-item <?= ($i == $pagination['page']) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
