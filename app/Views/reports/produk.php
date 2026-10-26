<!-- TAB NAVIGASI LAPORAN -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
    <a href="<?= url('/reports/penjualan') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
        <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span>Laporan Penjualan (Faktur)</span>
    </a>
    <a href="<?= url('/reports/produk') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
        <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        <span>Laporan Data Produk & Stok</span>
    </a>
</div>

<!-- RINGKASAN KPI LAPORAN PRODUK -->
<div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 1.5rem;">
    <!-- 1. Total Macam Produk -->
    <div class="kpi-card">
        <div class="kpi-icon primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div class="kpi-content">
            <div class="kpi-label">Total Varian Produk</div>
            <div class="kpi-value"><?= number_format($summary['total_produk'], 0, ',', '.') ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">Item</span></div>
        </div>
    </div>

    <!-- 2. Total Fisik Stok Unit -->
    <div class="kpi-card">
        <div class="kpi-icon info">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
        </div>
        <div class="kpi-content">
            <div class="kpi-label">Total Fisik Persediaan</div>
            <div class="kpi-value"><?= number_format($summary['total_stok'], 0, ',', '.') ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">Unit / Pcs</span></div>
        </div>
    </div>

    <!-- 3. Total Nilai Aset / Valuasi -->
    <div class="kpi-card">
        <div class="kpi-icon success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="kpi-content">
            <div class="kpi-label">Total Nilai Aset Persediaan</div>
            <div class="kpi-value" style="color: #16a34a;"><?= format_rupiah($summary['total_nilai_aset']) ?></div>
        </div>
    </div>

    <!-- 4. Peringatan Stok Menipis & Habis -->
    <div class="kpi-card">
        <div class="kpi-icon <?= ($summary['count_menipis'] > 0 || $summary['count_habis'] > 0) ? 'warning' : 'info' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="kpi-content">
            <div class="kpi-label">Status Stok Kritis</div>
            <div class="kpi-value" style="font-size: 1.15rem; color: <?= ($summary['count_habis'] > 0) ? '#dc2626' : '#ea580c' ?>;">
                <?= $summary['count_menipis'] + $summary['count_habis'] ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">Perlu Restock</span>
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-top: 0.15rem;">
                    (<?= $summary['count_menipis'] ?> Menipis, <?= $summary['count_habis'] ?> Habis)
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KARTU TABEL DATA LAPORAN PRODUK & FILTER -->
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Rekapitulasi Persediaan Produk & Obat</h2>
            <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.2rem;">
                Monitoring persediaan fisik, harga jual, dan valuasi aset Apotek LSP.
            </p>
        </div>

        <!-- Tombol Aksi Cetak & Ekspor -->
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <?php 
                $queryParams = http_build_query([
                    'q'           => $keyword,
                    'jenis'       => $jenis,
                    'status_stok' => $statusStok,
                ]);
            ?>
            <a href="<?= url('/reports/produk/excel?' . $queryParams) ?>" class="btn btn-secondary" title="Ekspor data ke file Excel / CSV">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Unduh Excel (CSV)</span>
            </a>

            <a href="<?= url('/reports/produk/print?' . $queryParams) ?>" target="_blank" class="btn btn-primary" title="Cetak laporan resmi atau simpan sebagai PDF">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan / PDF</span>
            </a>
        </div>
    </div>

    <!-- FILTER FORM -->
    <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/reports/produk') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" style="font-size: 0.75rem;">Cari Nama Produk</label>
                <input type="text" name="q" class="form-control" placeholder="Ketik nama produk / obat..." value="<?= htmlspecialchars($keyword) ?>">
            </div>

            <div style="width: 180px;">
                <label class="form-label" style="font-size: 0.75rem;">Jenis / Sediaan</label>
                <select name="jenis" class="form-control">
                    <option value="">Semua Jenis</option>
                    <?php foreach ($jenisList as $j): ?>
                        <option value="<?= htmlspecialchars($j) ?>" <?= ($jenis === $j) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 180px;">
                <label class="form-label" style="font-size: 0.75rem;">Status Stok</label>
                <select name="status_stok" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="tersedia" <?= ($statusStok === 'tersedia') ? 'selected' : '' ?>>Tersedia (&gt; 10)</option>
                    <option value="menipis" <?= ($statusStok === 'menipis') ? 'selected' : '' ?>>Menipis (&le; 10)</option>
                    <option value="habis" <?= ($statusStok === 'habis') ? 'selected' : '' ?>>Habis (0)</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-secondary" style="height: 38px;">
                    <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cari & Filter</span>
                </button>
            </div>

            <?php if (!empty($keyword) || !empty($jenis) || !empty($statusStok)): ?>
                <div>
                    <a href="<?= url('/reports/produk') ?>" class="btn btn-secondary" style="height: 38px;" title="Reset Filter">Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA LAPORAN PRODUK -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">No</th>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Produk / Obat</th>
                    <th>Jenis / Satuan</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Stok Fisik</th>
                    <th style="text-align: center;">Terjual (Unit)</th>
                    <th style="text-align: right;">Total Nilai Aset</th>
                    <th style="text-align: center;">Status Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produkList)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada data produk yang sesuai kriteria pencarian dan filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($produkList as $p): 
                        $stok = (int)$p['stock'];
                        if ($stok <= 0) {
                            $badge = '<span class="badge" style="background:#fee2e2; color:#b91c1c; font-weight:700;">Habis</span>';
                        } elseif ($stok <= 10) {
                            $badge = '<span class="badge" style="background:#ffedd5; color:#c2410c; font-weight:700;">Menipis</span>';
                        } else {
                            $badge = '<span class="badge" style="background:#dcfce7; color:#15803d; font-weight:700;">Tersedia</span>';
                        }
                    ?>
                        <tr>
                            <td style="text-align: center; color: #94a3b8; font-size: 0.85rem;"><?= $no++ ?></td>
                            <td style="font-family: monospace; font-weight: 600; color: #64748b;">
                                #<?= str_pad($p['id_produk'], 3, '0', STR_PAD_LEFT) ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($p['nama_produk']) ?></div>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: #475569;">
                                    <?= htmlspecialchars($p['jenis'] ?? '-') ?>
                                </span>
                            </td>
                            <td style="text-align: right; color: #334155;">
                                <?= format_rupiah($p['price']) ?>
                            </td>
                            <td style="text-align: center; font-weight: 700; font-size: 0.95rem; color: <?= $stok <= 10 ? '#ea580c' : '#0f172a' ?>;">
                                <?= number_format($stok, 0, ',', '.') ?>
                            </td>
                            <td style="text-align: center; color: #64748b; font-size: 0.875rem;">
                                <?= number_format($p['total_terjual'], 0, ',', '.') ?>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                <?= format_rupiah($p['nilai_aset']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= $badge ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($produkList)): ?>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 700; border-top: 2px solid var(--border-color);">
                    <td colspan="5" style="text-align: right; padding: 1rem 0.75rem;">TOTAL KESELURUHAN (<?= count($produkList) ?> Varian Produk):</td>
                    <td style="text-align: center; padding: 1rem 0.75rem; font-size: 1rem; color: #0f172a;"><?= number_format($summary['total_stok'], 0, ',', '.') ?> Unit</td>
                    <td></td>
                    <td style="text-align: right; padding: 1rem 0.75rem; font-size: 1rem; color: #16a34a;"><?= format_rupiah($summary['total_nilai_aset']) ?></td>
                    <td></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
