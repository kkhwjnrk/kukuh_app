<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Ubah Data Produk</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Perbarui rincian produk #<?= $produk['id_produk'] ?>.
            </p>
        </div>
        <a href="<?= url('/produk') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= url('/produk/update/' . $produk['id_produk']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama_produk">Nama Produk <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="jenis">Jenis / Satuan <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="jenis" id="jenis" class="form-control" value="<?= htmlspecialchars($produk['jenis']) ?>" required list="jenisSuggestions">
                    <datalist id="jenisSuggestions">
                        <?php foreach ($jenisList as $j): ?>
                            <option value="<?= htmlspecialchars($j) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Harga Satuan (Rp) <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="price" id="price" min="0" step="500" class="form-control" value="<?= (float)$produk['price'] ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="stock">Stok Tersedia <span style="color: var(--danger);">*</span></label>
                <input type="number" name="stock" id="stock" min="0" class="form-control" value="<?= (int)$produk['stock'] ?>" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="<?= url('/produk') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
