<div style="max-width: 640px; margin: 0 auto;">
    <div style="margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Ubah Data Pengguna: <?= htmlspecialchars($user['nama_lengkap']) ?></h2>
            <p style="font-size: 0.8rem; color: #64748b;">Perbarui profil atau reset password pengguna.</p>
        </div>
        <a href="<?= url('/users') ?>" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 1.75rem;">
            <form action="<?= url('/users/update/' . $user['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required value="<?= htmlspecialchars($user['nama_lengkap']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="username" id="username" class="form-control" required value="<?= htmlspecialchars($user['username']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Ganti Kata Sandi (Kosongkan bila tidak ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru">
                    <span class="form-text">Biarkan kosong jika tetap menggunakan password yang lama.</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Peran / Hak Akses (Role) <span style="color: var(--danger);">*</span></label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas / Operator</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                        <option value="manajer" <?= $user['role'] === 'manajer' ? 'selected' : '' ?>>Manajer / Pimpinan</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                    <a href="<?= url('/users') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
