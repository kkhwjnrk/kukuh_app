<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title"><?= htmlspecialchars(term('item.plural')) ?></h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Kelola data inventaris, harga satuan, dan kuantitas persediaan.
            </p>
        </div>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/items/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah <?= htmlspecialchars(term('item.singular')) ?></span>
        </a>
        <?php endif; ?>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/items') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 220px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, kode, atau spesifikasi..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>

            <div style="width: 200px;">
                <select name="category_id" class="form-select">
                    <option value="">Semua <?= htmlspecialchars(term('category.singular')) ?></option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($categoryId == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>Filter</span>
            </button>

            <?php if (!empty($keyword) || !empty($categoryId)): ?>
                <a href="<?= url('/items') ?>" class="btn btn-secondary" title="Reset Filter">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA MASTER -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th><?= htmlspecialchars(term('item.code_label')) ?></th>
                    <th><?= htmlspecialchars(term('item.name_label')) ?></th>
                    <th><?= htmlspecialchars(term('category.singular')) ?></th>
                    <th><?= htmlspecialchars(term('item.price_label')) ?></th>
                    <th><?= htmlspecialchars(term('item.stock_label')) ?></th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada data <?= htmlspecialchars(term('item.singular')) ?> yang ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['gambar'])): ?>
                                    <img src="<?= asset('uploads/' . $item['gambar']) ?>" alt="Foto" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <?php else: ?>
                                    <div style="width: 44px; height: 44px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.7rem; border: 1px solid #e2e8f0;">
                                        No Pic
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.85rem; font-weight: 600; color: var(--primary);">
                                    <?= htmlspecialchars($item['kode_item']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($item['nama_item']) ?></div>
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <div style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars(mb_strimwidth($item['deskripsi'], 0, 50, '...')) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569;">
                                    <?= htmlspecialchars($item['nama_kategori'] ?? 'Uncategorized') ?>
                                </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">
                                <?= format_rupiah($item['harga']) ?>
                            </td>
                            <td>
                                <?php if ($item['stok'] <= 10): ?>
                                    <span class="badge" style="background: #fef2f2; color: #b91c1c; border-color: #fecaca; font-weight: 700;">
                                        <?= $item['stok'] ?> <?= htmlspecialchars(term('item.unit')) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0; font-weight: 600;">
                                        <?= $item['stok'] ?> <?= htmlspecialchars(term('item.unit')) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <?php if (has_role(['admin', 'petugas'])): ?>
                                    <a href="<?= url('/items/edit/' . $item['id']) ?>" class="btn btn-secondary btn-sm" title="Ubah Data">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (has_role('admin')): ?>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Data" onclick="confirmDelete('<?= url('/items/delete/' . $item['id']) ?>', '<?= htmlspecialchars(addslashes($item['nama_item'])) ?>')">
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

    <!-- PAGINASI DATA -->
    <?php if ($pagination['last_page'] > 1): ?>
        <div style="padding: 1.25rem 1.5rem;">
            <div class="pagination">
                <div>
                    Menampilkan halaman <strong><?= $pagination['page'] ?></strong> dari <strong><?= $pagination['last_page'] ?></strong> (Total: <?= $pagination['total'] ?> data)
                </div>
                <div class="pagination-links">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <?php 
                            $queryParams = $_GET;
                            $queryParams['page'] = $i;
                            $pageUrl = url('/items?' . http_build_query($queryParams));
                        ?>
                        <a href="<?= $pageUrl ?>" class="page-item <?= ($i == $pagination['page']) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
