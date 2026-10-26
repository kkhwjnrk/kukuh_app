<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Kelola Data Penjualan (Faktur)</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Daftar transaksi faktur penjualan, pelacakan jatuh tempo, dan cetak dokumen resmi.
            </p>
        </div>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/faktur/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Penjualan (Faktur Baru)</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- FILTER PENCARIAN & RENTANG TANGGAL -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/faktur') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 220px;">
                <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Kata Kunci</label>
                <input type="text" name="q" class="form-control" placeholder="No. Faktur, Customer, Perusahaan..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate ?? '') ?>">
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate ?? '') ?>">
            </div>

            <div>
                <button type="submit" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Filter</span>
                </button>
            </div>

            <?php if (!empty($keyword) || !empty($startDate) || !empty($endDate)): ?>
            <div>
                <a href="<?= url('/faktur') ?>" class="btn btn-secondary" title="Reset Filter">Reset</a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA FAKTUR -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Faktur</th>
                    <th>Tgl Faktur</th>
                    <th>Jatuh Tempo</th>
                    <th>Customer</th>
                    <th>Perusahaan Penerbit</th>
                    <th>Metode Bayar</th>
                    <th style="text-align: right;">Grand Total</th>
                    <th style="text-align: right; width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fakturList['data'])): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada transaksi faktur yang ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($fakturList['data'] as $f): ?>
                        <tr>
                            <td>
                                <a href="<?= url('/faktur/show/' . $f['no_faktur']) ?>" style="font-weight: 700; color: #2563eb; text-decoration: none;">
                                    <?= htmlspecialchars($f['no_faktur']) ?>
                                </a>
                            </td>
                            <td style="font-size: 0.875rem; color: #334155;">
                                <?= date('d/m/Y', strtotime($f['tgl_faktur'])) ?>
                            </td>
                            <td style="font-size: 0.875rem; color: #dc2626; font-weight: 500;">
                                <?= date('d/m/Y', strtotime($f['due_date'])) ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a; font-size: 0.9rem;">
                                    <?= htmlspecialchars($f['nama_customer']) ?>
                                </div>
                                <div style="font-size: 0.775rem; color: #64748b;">
                                    <?= htmlspecialchars($f['perusahaan_cust']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #334155; font-weight: 600;">
                                    <?= htmlspecialchars($f['nama_perusahaan']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: #eef2ff; color: #4338ca; font-weight: 600;">
                                    <?= htmlspecialchars($f['metode_bayar']) ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                <?= format_rupiah($f['grand_total']) ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <!-- Detail -->
                                    <a href="<?= url('/faktur/show/' . $f['no_faktur']) ?>" class="btn btn-secondary btn-sm" title="Lihat Detail Faktur">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Cetak Faktur (Menu Ujikom Photo 3) -->
                                    <a href="<?= url('/faktur/print/' . $f['no_faktur']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Faktur Penjualan">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>

                                    <?php if (has_role(['admin', 'petugas'])): ?>
                                    <!-- Ubah Penjualan (Menu Ujikom Photo 3) -->
                                    <a href="<?= url('/faktur/edit/' . $f['no_faktur']) ?>" class="btn btn-secondary btn-sm" title="Ubah Faktur">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (has_role('admin')): ?>
                                    <!-- Hapus Penjualan (Menu Ujikom Photo 3) -->
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Faktur" onclick="confirmDelete('<?= url('/faktur/delete/' . $f['no_faktur']) ?>', 'Faktur <?= htmlspecialchars(addslashes($f['no_faktur'])) ?> (Stok akan dikembalikan)')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINASI -->
    <?php if ($fakturList['last_page'] > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-size: 0.85rem; color: #64748b;">
                Menampilkan halaman <strong><?= $fakturList['page'] ?></strong> dari <strong><?= $fakturList['last_page'] ?></strong> (Total: <?= $fakturList['total'] ?> faktur)
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <?php if ($fakturList['page'] > 1): ?>
                    <a href="<?= url('/faktur?page=' . ($fakturList['page'] - 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($startDate) ? '&start_date=' . urlencode($startDate) : '') . (!empty($endDate) ? '&end_date=' . urlencode($endDate) : '')) ?>" class="btn btn-secondary btn-sm">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $fakturList['last_page']; $i++): ?>
                    <a href="<?= url('/faktur?page=' . $i . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($startDate) ? '&start_date=' . urlencode($startDate) : '') . (!empty($endDate) ? '&end_date=' . urlencode($endDate) : '')) ?>" class="btn btn-sm <?= ($i === $fakturList['page']) ? 'btn-primary' : 'btn-secondary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($fakturList['page'] < $fakturList['last_page']): ?>
                    <a href="<?= url('/faktur?page=' . ($fakturList['page'] + 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($startDate) ? '&start_date=' . urlencode($startDate) : '') . (!empty($endDate) ? '&end_date=' . urlencode($endDate) : '')) ?>" class="btn btn-secondary btn-sm">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
