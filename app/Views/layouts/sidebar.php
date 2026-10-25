<aside class="app-sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg fill="none" stroke="currentColor" width="22" height="22" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <h1><?= htmlspecialchars(term('app_name')) ?></h1>
            <p><?= htmlspecialchars(term('organization')) ?></p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>
        
        <a href="<?= url('/dashboard') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dasbor</span>
        </a>

        <div class="nav-section-title">Master Data</div>
        
        <a href="<?= url('/items') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/items') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span><?= htmlspecialchars(term('item.plural')) ?></span>
        </a>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/categories') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/categories') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span><?= htmlspecialchars(term('category.plural')) ?></span>
        </a>
        <?php endif; ?>

        <div class="nav-section-title">Operasional</div>

        <a href="<?= url('/transactions') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/transactions') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span><?= htmlspecialchars(term('transaction.plural')) ?></span>
        </a>

        <a href="<?= url('/reports') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span><?= htmlspecialchars(term('report.title')) ?></span>
        </a>

        <?php if (has_role('admin')): ?>
        <div class="nav-section-title">Sistem & Pengguna</div>
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
<div class="app-content">
