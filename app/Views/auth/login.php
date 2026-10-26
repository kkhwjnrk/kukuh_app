<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); width: 100%;">
    <div style="width: 100%; max-width: 440px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 54px; height: 54px; background: linear-gradient(135deg, var(--primary), #818cf8); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25); margin-bottom: 1rem;">
                <svg fill="none" stroke="white" width="30" height="30" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.03em;"><?= htmlspecialchars(term('app_name')) ?></h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;"><?= htmlspecialchars(term('tagline')) ?></p>
        </div>

        <div class="card" style="border-radius: 18px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.08), 0 8px 10px -6px rgb(0 0 0 / 0.04);">
            <div class="card-body" style="padding: 2rem;">
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem;">Masuk ke Akun Anda</h2>
                
                <form action="<?= url('/login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Contoh: admin" required autofocus value="<?= htmlspecialchars(old('username')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; font-weight: 600; margin-top: 0.5rem; border-radius: 10px;">
                        Masuk Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div style="text-align: center; font-size: 0.75rem; color: #94a3b8; margin-top: 1.5rem;">
            <?= htmlspecialchars(term('organization')) ?> &bull; Sistem Pengelolaan Faktur dan Penjualan
        </div>
    </div>
</div>
