<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Kelola Data Produk</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Daftar barang / obat / produk penjualan beserta harga dan persediaan stok.
            </p>
        </div>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/produk/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Data Produk</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- FILTER PENCARIAN & JENIS -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/produk') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 220px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama produk atau jenis..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <div style="width: 200px;">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis / Satuan</option>
                    <?php foreach ($jenisList as $j): ?>
                        <option value="<?= htmlspecialchars($j) ?>" <?= ($jenis == $j) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>Filter</span>
            </button>

            <?php if (!empty($keyword) || !empty($jenis)): ?>
                <a href="<?= url('/produk') ?>" class="btn btn-secondary" title="Reset Filter">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA PRODUK -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Nama Produk</th>
                    <th>Jenis / Satuan</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produk['data'])): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada data produk yang ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produk['data'] as $p): ?>
                        <tr>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700;">
                                    #<?= $p['id_produk'] ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                    <?= htmlspecialchars($p['nama_produk']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f0fdf4; color: #166534; font-weight: 600;">
                                    <?= htmlspecialchars($p['jenis']) ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a;">
                                <?= format_rupiah($p['price']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($p['stock'] <= 5): ?>
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-weight: 700;">
                                        <?= $p['stock'] ?> (Menipis)
                                    </span>
                                <?php elseif ($p['stock'] <= 15): ?>
                                    <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 600;">
                                        <?= $p['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: #eef2ff; color: #4338ca; font-weight: 600;">
                                        <?= $p['stock'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <?php if (has_role(['admin', 'petugas'])): ?>
                                    <a href="<?= url('/produk/edit/' . $p['id_produk']) ?>" class="btn btn-secondary btn-sm" title="Ubah Produk">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (has_role('admin')): ?>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Produk" onclick="confirmDelete('<?= url('/produk/delete/' . $p['id_produk']) ?>', 'Produk <?= htmlspecialchars(addslashes($p['nama_produk'])) ?>')">
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
    <?php if ($produk['last_page'] > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-size: 0.85rem; color: #64748b;">
                Menampilkan halaman <strong><?= $produk['page'] ?></strong> dari <strong><?= $produk['last_page'] ?></strong> (Total: <?= $produk['total'] ?> produk)
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <?php if ($produk['page'] > 1): ?>
                    <a href="<?= url('/produk?page=' . ($produk['page'] - 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($jenis) ? '&jenis=' . urlencode($jenis) : '')) ?>" class="btn btn-secondary btn-sm">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $produk['last_page']; $i++): ?>
                    <a href="<?= url('/produk?page=' . $i . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($jenis) ? '&jenis=' . urlencode($jenis) : '')) ?>" class="btn btn-sm <?= ($i === $produk['page']) ? 'btn-primary' : 'btn-secondary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($produk['page'] < $produk['last_page']): ?>
                    <a href="<?= url('/produk?page=' . ($produk['page'] + 1) . (!empty($keyword) ? '&q=' . urlencode($keyword) : '') . (!empty($jenis) ? '&jenis=' . urlencode($jenis) : '')) ?>" class="btn btn-secondary btn-sm">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
