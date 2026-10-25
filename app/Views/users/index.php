<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Manajemen Akun Pengguna</h2>
            <p style="font-size: 0.775rem; color: #64748b; margin-top: 0.2rem;">
                Kelola hak akses pengguna, peranan sistem (Admin, Petugas, Manajer), dan kata sandi.
            </p>
        </div>

        <a href="<?= url('/users/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Peran (Role)</th>
                    <th>Terdaftar Sejak</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $u): ?>
                    <tr>
                        <td style="color: #94a3b8;"><?= $index + 1 ?></td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($u['nama_lengkap']) ?></div>
                        </td>
                        <td>
                            <span style="font-family: monospace; font-size: 0.85rem; color: #475569;">
                                <?= htmlspecialchars($u['username']) ?>
                            </span>
                        </td>
                        <td>
                            <?= badge_role($u['role']) ?>
                        </td>
                        <td style="color: #64748b; font-size: 0.8rem;">
                            <?= format_date($u['created_at']) ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="<?= url('/users/edit/' . $u['id']) ?>" class="btn btn-secondary btn-sm" title="Ubah Pengguna">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <?php if ((int)$u['id'] !== (int)auth('id')): ?>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus Pengguna" onclick="confirmDelete('<?= url('/users/delete/' . $u['id']) ?>', 'Pengguna <?= htmlspecialchars(addslashes($u['nama_lengkap'])) ?>')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
