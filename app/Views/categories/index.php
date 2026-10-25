<div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: flex-start;">
    <!-- KOLOM KIRI: FORM TAMBAH KATEGORI BARU -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Tambah <?= htmlspecialchars(term('category.singular')) ?> Baru</h2>
        </div>
        <div class="card-body">
            <form action="<?= url('/categories/store') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="nama_kategori"><?= htmlspecialchars(term('category.name_label')) ?> <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Contoh: Periferal Komputer" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi"><?= htmlspecialchars(term('category.desc_label')) ?></label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Keterangan singkat kategori..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Simpan <?= htmlspecialchars(term('category.singular')) ?>
                </button>
            </form>
        </div>
    </div>

    <!-- KOLOM KANAN: TABEL DAFTAR KATEGORI -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><?= htmlspecialchars(term('category.plural')) ?></h2>
            <span style="font-size: 0.8rem; color: #64748b;">Total: <?= count($categories) ?> kategori</span>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th><?= htmlspecialchars(term('category.name_label')) ?></th>
                        <th>Deskripsi</th>
                        <th style="text-align: center;">Jumlah Item</th>
                        <th style="text-align: right; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $index => $cat): ?>
                            <tr>
                                <td style="color: #94a3b8; font-size: 0.8rem;"><?= $index + 1 ?></td>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($cat['nama_kategori']) ?></div>
                                </td>
                                <td style="color: #64748b; font-size: 0.85rem;">
                                    <?= htmlspecialchars($cat['deskripsi'] ?? '-') ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge" style="background: #eef2ff; color: #4338ca; font-weight: 600;">
                                        <?= (int)($cat['total_items'] ?? 0) ?> Item
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 0.35rem;">
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="editCategoryModal(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['nama_kategori'])) ?>', '<?= htmlspecialchars(addslashes($cat['deskripsi'] ?? '')) ?>')" title="Ubah Kategori">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <?php if (has_role('admin')): ?>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus Kategori" onclick="confirmDelete('<?= url('/categories/delete/' . $cat['id']) ?>', 'Kategori <?= htmlspecialchars(addslashes($cat['nama_kategori'])) ?>')">
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
    </div>
</div>

<!-- MODAL EDIT KATEGORI -->
<div id="editCategoryModal" class="modal-overlay">
    <div class="modal-content">
        <div class="card-header">
            <div class="card-title">Ubah <?= htmlspecialchars(term('category.singular')) ?></div>
            <button type="button" onclick="closeModal('editCategoryModal')" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
        <div class="card-body">
            <form id="editCategoryForm" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="edit_nama_kategori"><?= htmlspecialchars(term('category.name_label')) ?> <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_deskripsi"><?= htmlspecialchars(term('category.desc_label')) ?></label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="form-control"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editCategoryModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategoryModal(id, name, desc) {
    document.getElementById('editCategoryForm').action = '<?= url('/categories/update/') ?>' + id;
    document.getElementById('edit_nama_kategori').value = name;
    document.getElementById('edit_deskripsi').value = desc;
    openModal('editCategoryModal');
}
</script>
