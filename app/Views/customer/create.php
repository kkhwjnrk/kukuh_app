<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Tambah Data Customer</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Tambahkan data pelanggan baru yang akan menerima faktur penjualan.
            </p>
        </div>
        <a href="<?= url('/customer') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= url('/customer/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama_customer">Nama Lengkap Customer <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_customer" id="nama_customer" class="form-control" placeholder="Contoh: dr. H. Supriyanto, Sp.A" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="perusahaan_cust">Nama Perusahaan / Klinik / Apotek Customer <span style="color: var(--danger);">*</span></label>
                <input type="text" name="perusahaan_cust" id="perusahaan_cust" class="form-control" placeholder="Contoh: Apotek Sehat Farma" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap Customer <span style="color: var(--danger);">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Jalan, Nomor, Kelurahan, Kota..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="no_telp">No. Telepon / WhatsApp <span style="color: var(--danger);">*</span></label>
                <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="<?= url('/customer') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Customer</button>
            </div>
        </form>
    </div>
</div>
