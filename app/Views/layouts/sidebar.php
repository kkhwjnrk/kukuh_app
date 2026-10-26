<aside class="app-sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg fill="none" stroke="currentColor" width="22" height="22" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <h1><?= htmlspecialchars(term('app_name')) ?></h1>
            <p><?= htmlspecialchars(term('system_title')) ?></p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>
        
        <!-- 1. Beranda -->
        <a href="<?= url('/dashboard') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Beranda</span>
        </a>

        <div class="nav-section-title">Master Data</div>
        
        <!-- 2. Kelola Data Perusahaan -->
        <a href="<?= url('/perusahaan') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/perusahaan') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span>Data Perusahaan</span>
        </a>

        <!-- 3. Kelola Data Customer -->
        <a href="<?= url('/customer') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/customer') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span>Data Customer</span>
        </a>

        <!-- 4. Kelola Data Produk -->
        <a href="<?= url('/produk') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/produk') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span>Data Produk</span>
        </a>

        <div class="nav-section-title">Transaksi Penjualan</div>

        <!-- 5. Kelola Data Penjualan (Faktur) -->
        <a href="<?= url('/faktur') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/faktur') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span>Faktur Penjualan</span>
        </a>

        <?php if (has_role('admin')): ?>
        <div class="nav-section-title">Sistem & Akses</div>
        <!-- 6. Kelola Pengguna -->
        <a href="<?= url('/users') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span>Kelola Pengguna</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-avatar">
            <?= strtoupper(substr(auth('nama_lengkap') ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-meta">
            <div class="user-name"><?= htmlspecialchars(auth('nama_lengkap') ?? 'Pengguna') ?></div>
            <div><?= badge_role(auth('role') ?? 'petugas') ?></div>
        </div>
        <a href="<?= url('/logout') ?>" title="Keluar dari sistem" style="color: #94a3b8; display: flex;" onclick="return confirm('Keluar dari aplikasi?')">
            <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </a>
    </div>
</aside>
