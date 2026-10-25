<header class="app-navbar no-print">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <button id="mobileMenuBtn" class="btn btn-secondary btn-sm" style="display: none;" aria-label="Buka Menu">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="navbar-page-title"><?= htmlspecialchars($pageTitle ?? 'Dasbor') ?></div>
    </div>

    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span style="font-size: 0.8rem; color: #64748b; display: flex; align-items: center; gap: 0.35rem;">
            <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <?= format_date(date('Y-m-d')) ?>
        </span>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/transactions/create') ?>" class="btn btn-primary btn-sm">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ <?= htmlspecialchars(term('transaction.singular')) ?></span>
        </a>
        <?php endif; ?>
    </div>
</header>
