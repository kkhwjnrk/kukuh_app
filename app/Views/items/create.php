<div style="max-width: 760px; margin: 0 auto;">
    <div style="margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Tambah <?= htmlspecialchars(term('item.singular')) ?> Baru</h2>
            <p style="font-size: 0.8rem; color: #64748b;">Silakan masukkan informasi lengkap data ke dalam sistem.</p>
        </div>
        <a href="<?= url('/items') ?>" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 1.75rem;">
            <form action="<?= url('/items/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="kode_item"><?= htmlspecialchars(term('item.code_label')) ?> <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="kode_item" id="kode_item" class="form-control" required value="<?= htmlspecialchars($autoCode) ?>">
                        <span class="form-text">Dihasilkan otomatis atau sesuaikan dengan kode fisik.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category_id"><?= htmlspecialchars(term('category.singular')) ?> <span style="color: var(--danger);">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_item"><?= htmlspecialchars(term('item.name_label')) ?> <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_item" id="nama_item" class="form-control" placeholder="Contoh: Monitor LED 24 Inch" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="harga"><?= htmlspecialchars(term('item.price_label')) ?> (Rp) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="harga" id="harga" class="form-control" placeholder="0" min="0" step="500" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stok"><?= htmlspecialchars(term('item.stock_label')) ?> <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="stok" id="stok" class="form-control" placeholder="0" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="imageUploadInput"><?= htmlspecialchars(term('item.image_label')) ?> (Opsional)</label>
                    <input type="file" name="gambar" id="imageUploadInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <span class="form-text">Format yang didukung: JPG, PNG, WEBP. Maksimal 2MB.</span>
                    
                    <div style="margin-top: 0.75rem;">
                        <img id="imageUploadPreview" src="#" alt="Pratinjau Foto" style="display: none; max-width: 140px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi"><?= htmlspecialchars(term('item.desc_label')) ?></label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Tuliskan spesifikasi atau catatan tambahan mengenai item ini..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                    <a href="<?= url('/items') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
