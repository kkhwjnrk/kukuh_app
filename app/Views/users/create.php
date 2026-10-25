<div style="max-width: 640px; margin: 0 auto;">
    <div style="margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Tambah Pengguna Baru</h2>
            <p style="font-size: 0.8rem; color: #64748b;">Daftarkan akun operator/petugas baru ke dalam sistem.</p>
        </div>
        <a href="<?= url('/users') ?>" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 1.75rem;">
            <form action="<?= url('/users/store') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Contoh: Rina Anggraini" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Contoh: rina" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi (Password) <span style="color: var(--danger);">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 5 karakter" required minlength="5">
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Peran / Hak Akses (Role) <span style="color: var(--danger);">*</span></label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="petugas">Petugas / Operator (Hanya input transaksi & lihat master data)</option>
                        <option value="admin">Administrator (Akses penuh ke semua menu sistem)</option>
                        <option value="manajer">Manajer / Pimpinan (Hanya akses dasbor statistik & laporan)</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                    <a href="<?= url('/users') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
