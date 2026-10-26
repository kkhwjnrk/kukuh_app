<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Kelola Data Perusahaan</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Daftar entitas perusahaan penerbit Faktur Penjualan.
            </p>
        </div>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/perusahaan/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Data Perusahaan</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- FILTER PENCARIAN -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/perusahaan') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama perusahaan, alamat, atau no telp..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>Cari</span>
            </button>

            <?php if (!empty($keyword)): ?>
                <a href="<?= url('/perusahaan') ?>" class="btn btn-secondary" title="Reset Pencarian">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA PERUSAHAAN -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Fax</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($perusahaan['data'])): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada data perusahaan yang ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($perusahaan['data'] as $p): ?>
                        <tr>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700;">
                                    #<?= $p['id_perusahaan'] ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                    <?= htmlspecialchars($p['nama_perusahaan']) ?>
                                </div>
                            </td>
                            <td style="color: #475569; font-size: 0.875rem; max-width: 280px;">
                                <?= htmlspecialchars($p['alamat']) ?>
                            </td>
                            <td style="color: #0f172a; font-weight: 500; font-size: 0.875rem;">
                                <?= htmlspecialchars($p['no_telp']) ?>
                            </td>
                            <td style="color: #64748b; font-size: 0.875rem;">
                                <?= htmlspecialchars($p['fax'] ?: '-') ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <?php if (has_role(['admin', 'petugas'])): ?>
                                    <a href="<?= url('/perusahaan/edit/' . $p['id_perusahaan']) ?>" class="btn btn-secondary btn-sm" title="Ubah Perusahaan">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (has_role('admin')): ?>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Perusahaan" onclick="confirmDelete('<?= url('/perusahaan/delete/' . $p['id_perusahaan']) ?>', 'Perusahaan <?= htmlspecialchars(addslashes($p['nama_perusahaan'])) ?>')">
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
    <?php if ($perusahaan['last_page'] > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-size: 0.85rem; color: #64748b;">
                Menampilkan halaman <strong><?= $perusahaan['page'] ?></strong> dari <strong><?= $perusahaan['last_page'] ?></strong> (Total: <?= $perusahaan['total'] ?> data)
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <?php if ($perusahaan['page'] > 1): ?>
                    <a href="<?= url('/perusahaan?page=' . ($perusahaan['page'] - 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-secondary btn-sm">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $perusahaan['last_page']; $i++): ?>
                    <a href="<?= url('/perusahaan?page=' . $i . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-sm <?= ($i === $perusahaan['page']) ? 'btn-primary' : 'btn-secondary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($perusahaan['page'] < $perusahaan['last_page']): ?>
                    <a href="<?= url('/perusahaan?page=' . ($perusahaan['page'] + 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-secondary btn-sm">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
