<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Ubah Data Customer</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Perbarui rincian data customer #<?= $customer['id_customer'] ?>.
            </p>
        </div>
        <a href="<?= url('/customer') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= url('/customer/update/' . $customer['id_customer']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama_customer">Nama Lengkap Customer <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_customer" id="nama_customer" class="form-control" value="<?= htmlspecialchars($customer['nama_customer']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="perusahaan_cust">Nama Perusahaan / Klinik / Apotek Customer <span style="color: var(--danger);">*</span></label>
                <input type="text" name="perusahaan_cust" id="perusahaan_cust" class="form-control" value="<?= htmlspecialchars($customer['perusahaan_cust']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap Customer <span style="color: var(--danger);">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" required><?= htmlspecialchars($customer['alamat']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="no_telp">No. Telepon / WhatsApp <span style="color: var(--danger);">*</span></label>
                <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?= htmlspecialchars($customer['no_telp']) ?>" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="<?= url('/customer') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
