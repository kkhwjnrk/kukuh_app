<!-- Header Sambutan -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; letter-spacing: -0.02em;">
            Selamat Datang, <?= htmlspecialchars(auth('nama_lengkap') ?? 'Pengguna') ?> 👋
        </h2>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.15rem;">
            Berikut adalah ringkasan performa dan aktivitas sistem hari ini, <?= format_date(date('Y-m-d')) ?>.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/transactions/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Input <?= htmlspecialchars(term('transaction.singular')) ?></span>
        </a>
        <?php endif; ?>
        <a href="<?= url('/reports') ?>" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Rekap Laporan</span>
        </a>
    </div>
</div>

<!-- 4 KARTU STATISTIK (KPI CARDS) -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon indigo">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total <?= htmlspecialchars(term('item.singular')) ?></div>
            <div class="stat-value"><?= number_format($totalItems) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sky">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total <?= htmlspecialchars(term('category.singular')) ?></div>
            <div class="stat-value"><?= number_format($totalCategories) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon emerald">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Pendapatan / Nilai</div>
            <div class="stat-value"><?= format_rupiah($totalRevenue) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label"><?= htmlspecialchars(term('transaction.singular')) ?> Hari Ini</div>
            <div class="stat-value"><?= number_format($todayCount) ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">(<?= format_rupiah($todayRevenue) ?>)</span></div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- TABEL TRANSAKSI TERAKHIR -->
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?= htmlspecialchars(term('transaction.singular')) ?> Terkini</span>
            </div>
            <a href="<?= url('/transactions') ?>" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars(term('transaction.code_label')) ?></th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentTransactions)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada riwayat transaksi yang tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentTransactions as $rt): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($rt['kode_transaksi']) ?></div>
                                    <div style="font-size: 0.725rem; color: #64748b;"><?= format_date($rt['tanggal']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($rt['nama_pelanggan']) ?></td>
                                <td style="font-weight: 600;"><?= format_rupiah($rt['total_bayar']) ?></td>
                                <td><?= badge_status($rt['status']) ?></td>
                                <td style="text-align: right;">
                                    <a href="<?= url('/transactions/show/' . $rt['id']) ?>" class="btn btn-secondary btn-sm" title="Lihat Faktur">
                                        Faktur
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PERINGATAN STOK MENIPIS -->
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="display: flex; align-items: center; gap: 0.5rem; color: #b45309;">
                <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Peringatan Stok Menipis</span>
            </div>
            <a href="<?= url('/items') ?>" style="font-size: 0.8rem; font-weight: 600;">Kelola Stok &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars(term('item.singular')) ?></th>
                        <th>Kategori</th>
                        <th>Sisa Stok</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lowStockItems)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #10b981; padding: 2rem;">
                                &check; Seluruh persediaan barang dalam kondisi aman.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lowStockItems as $lsi): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($lsi['nama_item']) ?></div>
                                    <div style="font-size: 0.725rem; color: #64748b;"><?= htmlspecialchars($lsi['kode_item']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($lsi['nama_kategori'] ?? '-') ?></td>
                                <td>
                                    <span class="badge" style="background: #fef2f2; color: #b91c1c; border-color: #fecaca; font-weight: 700;">
                                        <?= $lsi['stok'] ?> <?= htmlspecialchars(term('item.unit')) ?>
                                    </span>
                                </td>
                                <td><?= format_rupiah($lsi['harga']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
