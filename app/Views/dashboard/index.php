<!-- Header Sambutan -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; letter-spacing: -0.02em;">
            Selamat Datang, <?= htmlspecialchars(auth('nama_lengkap') ?? 'Pengguna') ?> 👋
        </h2>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.15rem;">
            Ringkasan operasional Sistem Faktur Penjualan hari ini, <?= format_date(date('Y-m-d')) ?>.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/faktur/create') ?>" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Faktur Baru</span>
        </a>
        <?php endif; ?>
        <a href="<?= url('/customer/print') ?>" target="_blank" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Data Customer</span>
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
            <div class="stat-label">Total Produk</div>
            <div class="stat-value"><?= number_format($totalProduk) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sky">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Customer</div>
            <div class="stat-value"><?= number_format($totalCustomer) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon emerald">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Omset Penjualan</div>
            <div class="stat-value"><?= format_rupiah($totalRevenue) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Faktur Diterbitkan</div>
            <div class="stat-value"><?= number_format($totalFaktur) ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">(Hari ini: <?= $todayCount ?>)</span></div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- TABEL FAKTUR TERAKHIR -->
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Faktur Penjualan Terkini</span>
            </div>
            <a href="<?= url('/faktur') ?>" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Customer</th>
                        <th>Jatuh Tempo</th>
                        <th style="text-align: right;">Grand Total</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentFaktur)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada riwayat faktur yang tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentFaktur as $rf): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('/faktur/show/' . $rf['no_faktur']) ?>" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                        <?= htmlspecialchars($rf['no_faktur']) ?>
                                    </a>
                                    <div style="font-size: 0.725rem; color: #64748b;"><?= date('d/m/Y', strtotime($rf['tgl_faktur'])) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #0f172a;"><?= htmlspecialchars($rf['nama_customer']) ?></div>
                                    <div style="font-size: 0.725rem; color: #64748b;"><?= htmlspecialchars($rf['perusahaan_cust']) ?></div>
                                </td>
                                <td style="color: #dc2626; font-size: 0.85rem; font-weight: 500;">
                                    <?= date('d/m/Y', strtotime($rf['due_date'])) ?>
                                </td>
                                <td style="font-weight: 700; text-align: right; color: #0f172a;">
                                    <?= format_rupiah($rf['grand_total']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= url('/faktur/print/' . $rf['no_faktur']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Faktur">
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PERINGATAN STOK PRODUK MENIPIS -->
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="display: flex; align-items: center; gap: 0.5rem; color: #b45309;">
                <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Peringatan Stok Menipis</span>
            </div>
            <a href="<?= url('/produk') ?>" style="font-size: 0.8rem; font-weight: 600;">Kelola Produk &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Satuan</th>
                        <th style="text-align: center;">Sisa Stok</th>
                        <th style="text-align: right;">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lowStockItems)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #10b981; padding: 2rem;">
                                &check; Seluruh stok produk dalam kondisi aman.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lowStockItems as $lsp): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($lsp['nama_produk']) ?></div>
                                </td>
                                <td>
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">
                                        <?= htmlspecialchars($lsp['jenis']) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge" style="background: #fef2f2; color: #b91c1c; border-color: #fecaca; font-weight: 700;">
                                        <?= $lsp['stock'] ?>
                                    </span>
                                </td>
                                <td style="text-align: right; color: #334155;">
                                    <?= format_rupiah($lsp['price']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
