<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Tambah Data Perusahaan</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Tambahkan instansi / perusahaan penerbit Faktur Penjualan baru.
            </p>
        </div>
        <a href="<?= url('/perusahaan') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= url('/perusahaan/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="nama_perusahaan">Nama Perusahaan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="Contoh: PT. Sumber Makmur Sentosa" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap <span style="color: var(--danger);">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Jalan, Nomor, Kota..." required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="no_telp">No. Telepon <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="021-xxxxxxx" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="fax">No. Fax</label>
                    <input type="text" name="fax" id="fax" class="form-control" placeholder="021-xxxxxxx (Opsional)">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="<?= url('/perusahaan') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Perusahaan</button>
            </div>
        </form>
    </div>
</div>
