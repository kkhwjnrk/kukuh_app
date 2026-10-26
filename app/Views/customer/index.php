<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Kelola Data Customer</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Kelola daftar pelanggan, instansi rekanan, dan data kontak.
            </p>
        </div>

        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <!-- Tombol Preview / Cetak Data Customer sesuai Menu Administrator Photo 3 -->
            <a href="<?= url('/customer/print' . (!empty($keyword) ? '?q=' . urlencode($keyword) : '')) ?>" target="_blank" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Preview / Cetak Data Customer</span>
            </a>

            <?php if (has_role(['admin', 'petugas'])): ?>
            <a href="<?= url('/customer/create') ?>" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Customer</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FILTER PENCARIAN -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/customer') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama customer, perusahaan, atau alamat..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>Cari</span>
            </button>

            <?php if (!empty($keyword)): ?>
                <a href="<?= url('/customer') ?>" class="btn btn-secondary" title="Reset Pencarian">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA CUSTOMER -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Nama Customer</th>
                    <th>Perusahaan Rekanan</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customer['data'])): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada data customer yang ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customer['data'] as $c): ?>
                        <tr>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700;">
                                    #<?= $c['id_customer'] ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                    <?= htmlspecialchars($c['nama_customer']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f0fdf4; color: #166534; font-weight: 600;">
                                    <?= htmlspecialchars($c['perusahaan_cust']) ?>
                                </span>
                            </td>
                            <td style="color: #475569; font-size: 0.875rem; max-width: 280px;">
                                <?= htmlspecialchars($c['alamat']) ?>
                            </td>
                            <td style="color: #0f172a; font-weight: 500; font-size: 0.875rem;">
                                <?= htmlspecialchars($c['no_telp']) ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <?php if (has_role(['admin', 'petugas'])): ?>
                                    <a href="<?= url('/customer/edit/' . $c['id_customer']) ?>" class="btn btn-secondary btn-sm" title="Ubah Customer">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (has_role('admin')): ?>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Customer" onclick="confirmDelete('<?= url('/customer/delete/' . $c['id_customer']) ?>', 'Customer <?= htmlspecialchars(addslashes($c['nama_customer'])) ?>')">
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
    <?php if ($customer['last_page'] > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-size: 0.85rem; color: #64748b;">
                Menampilkan halaman <strong><?= $customer['page'] ?></strong> dari <strong><?= $customer['last_page'] ?></strong> (Total: <?= $customer['total'] ?> data)
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <?php if ($customer['page'] > 1): ?>
                    <a href="<?= url('/customer?page=' . ($customer['page'] - 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-secondary btn-sm">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $customer['last_page']; $i++): ?>
                    <a href="<?= url('/customer?page=' . $i . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-sm <?= ($i === $customer['page']) ? 'btn-primary' : 'btn-secondary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($customer['page'] < $customer['last_page']): ?>
                    <a href="<?= url('/customer?page=' . ($customer['page'] + 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" class="btn btn-secondary btn-sm">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
