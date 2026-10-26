<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Ubah Data Perusahaan</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Perbarui rincian data perusahaan #<?= $perusahaan['id_perusahaan'] ?>.
            </p>
        </div>
        <a href="<?= url('/perusahaan') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= url('/perusahaan/update/' . $perusahaan['id_perusahaan']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama_perusahaan">Nama Perusahaan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($perusahaan['nama_perusahaan']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap <span style="color: var(--danger);">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" required><?= htmlspecialchars($perusahaan['alamat']) ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="no_telp">No. Telepon <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?= htmlspecialchars($perusahaan['no_telp']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="fax">No. Fax</label>
                    <input type="text" name="fax" id="fax" class="form-control" value="<?= htmlspecialchars($perusahaan['fax'] ?? '') ?>">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="<?= url('/perusahaan') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
