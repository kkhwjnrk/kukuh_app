<!-- TAB NAVIGASI LAPORAN -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
    <a href="<?= url('/reports/penjualan') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
        <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span>Laporan Penjualan (Faktur)</span>
    </a>
    <a href="<?= url('/reports/produk') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
        <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        <span>Laporan Data Produk & Stok</span>
    </a>
</div>

<!-- RINGKASAN KPI LAPORAN PENJUALAN -->
<div class="stats-grid">
    <!-- 1. Total Transaksi Faktur -->
    <div class="stat-card">
        <div class="stat-icon sky">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Faktur Penjualan</div>
            <div class="stat-value"><?= number_format($summary['total_faktur'], 0, ',', '.') ?> <span style="font-size: 0.85rem; font-weight: normal; color: #64748b;">Trx</span></div>
        </div>
    </div>

    <!-- 2. Total Omset / Grand Total -->
    <div class="stat-card">
        <div class="stat-icon emerald">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Omset Penjualan</div>
            <div class="stat-value" style="color: #16a34a; font-size: 1.25rem;"><?= format_rupiah($summary['total_grand_total']) ?></div>
        </div>
    </div>

    <!-- 3. Total PPN 11% -->
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Pajak (PPN 11%)</div>
            <div class="stat-value" style="color: #ea580c; font-size: 1.25rem;"><?= format_rupiah($summary['total_ppn']) ?></div>
        </div>
    </div>

    <!-- 4. Uang Masuk / Piutang -->
    <div class="stat-card">
        <div class="stat-icon indigo">
            <svg fill="none" stroke="currentColor" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Terbayar / Sisa Piutang</div>
            <div class="stat-value" style="font-size: 1.15rem;">
                <?= format_rupiah($summary['total_dp']) ?>
                <div style="font-size: 0.75rem; color: #dc2626; font-weight: 600; margin-top: 0.15rem;">
                    Sisa: <?= format_rupiah($summary['total_sisa']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KARTU TABEL DATA LAPORAN & FILTER -->
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title">Rekapitulasi Faktur Penjualan</h2>
            <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.2rem;">
                Periode data: <strong><?= format_date($startDate) ?></strong> s/d <strong><?= format_date($endDate) ?></strong>
            </p>
        </div>

        <!-- Tombol Aksi Cetak & Ekspor -->
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <?php 
                $queryParams = http_build_query([
                    'start_date'   => $startDate,
                    'end_date'     => $endDate,
                    'metode_bayar' => $metodeBayar,
                    'id_customer'  => $customerId,
                ]);
            ?>
            <a href="<?= url('/reports/penjualan/excel?' . $queryParams) ?>" class="btn btn-secondary" title="Ekspor data ke file Excel / CSV">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Unduh Excel (CSV)</span>
            </a>

            <a href="<?= url('/reports/penjualan/print?' . $queryParams) ?>" target="_blank" class="btn btn-primary" title="Cetak laporan resmi atau simpan sebagai PDF">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan / PDF</span>
            </a>
        </div>
    </div>

    <!-- FILTER FORM -->
    <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid var(--border-subtle);">
        <form method="GET" action="<?= url('/reports/penjualan') ?>" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="width: 155px;">
                <label class="form-label" style="font-size: 0.75rem;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
            </div>

            <div style="width: 155px;">
                <label class="form-label" style="font-size: 0.75rem;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.75rem;">Metode Bayar</label>
                <select name="metode_bayar" class="form-control">
                    <option value="">Semua Metode</option>
                    <option value="TUNAI" <?= ($metodeBayar === 'TUNAI') ? 'selected' : '' ?>>TUNAI</option>
                    <option value="TRANSFER" <?= ($metodeBayar === 'TRANSFER') ? 'selected' : '' ?>>TRANSFER</option>
                    <option value="KREDIT" <?= ($metodeBayar === 'KREDIT') ? 'selected' : '' ?>>KREDIT</option>
                    <option value="TEMPO" <?= ($metodeBayar === 'TEMPO') ? 'selected' : '' ?>>TEMPO</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label class="form-label" style="font-size: 0.75rem;">Customer</label>
                <select name="id_customer" class="form-control">
                    <option value="">Semua Customer</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id_customer'] ?>" <?= ($customerId == $c['id_customer']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nama_customer']) ?> <?= !empty($c['perusahaan_cust']) ? '('.htmlspecialchars($c['perusahaan_cust']).')' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-secondary" style="height: 38px;">
                    <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter Data</span>
                </button>
            </div>

            <?php if (!empty($metodeBayar) || !empty($customerId) || $startDate !== date('Y-m-01') || $endDate !== date('Y-m-d')): ?>
                <div>
                    <a href="<?= url('/reports/penjualan') ?>" class="btn btn-secondary" style="height: 38px;" title="Reset Filter">Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA LAPORAN PENJUALAN -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">No</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Metode</th>
                    <th style="text-align: right;">Subtotal</th>
                    <th style="text-align: right;">PPN (11%)</th>
                    <th style="text-align: right;">Grand Total</th>
                    <th style="text-align: right;">Bayar / DP</th>
                    <th style="text-align: right;">Sisa Tagihan</th>
                    <th style="text-align: center;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fakturList)): ?>
                    <tr>
                        <td colspan="11" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                            Tidak ada transaksi faktur penjualan pada periode dan kriteria filter yang dipilih.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($fakturList as $f): 
                        $sisa = max(0, (float)$f['grand_total'] - (float)$f['dp']);
                    ?>
                        <tr>
                            <td style="text-align: center; color: #94a3b8; font-size: 0.85rem;"><?= $no++ ?></td>
                            <td>
                                <a href="<?= url('/faktur/show/' . $f['no_faktur']) ?>" style="font-family: monospace; font-weight: 700; color: #2563eb;" title="Rincian Faktur">
                                    <?= htmlspecialchars($f['no_faktur']) ?>
                                </a>
                            </td>
                            <td>
                                <div><?= format_date($f['tgl_faktur']) ?></div>
                                <?php if (!empty($f['due_date'])): ?>
                                    <div style="font-size: 0.725rem; color: #64748b;">Tempo: <?= format_date($f['due_date']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($f['nama_customer']) ?></div>
                                <div style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars($f['perusahaan_cust'] ?? '-') ?></div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 0.75rem; font-weight: 600;">
                                    <?= htmlspecialchars($f['metode_bayar']) ?>
                                </span>
                            </td>
                            <td style="text-align: right; color: #475569; font-size: 0.875rem;">
                                <?= format_rupiah($f['subtotal']) ?>
                            </td>
                            <td style="text-align: right; color: #ea580c; font-size: 0.875rem;">
                                <?= format_rupiah($f['ppn']) ?>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                <?= format_rupiah($f['grand_total']) ?>
                            </td>
                            <td style="text-align: right; color: #16a34a; font-size: 0.875rem;">
                                <?= format_rupiah($f['dp']) ?>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: <?= $sisa > 0 ? '#dc2626' : '#16a34a' ?>; font-size: 0.875rem;">
                                <?= $sisa > 0 ? format_rupiah($sisa) : '<span style="color:#16a34a;">Lunas</span>' ?>
                            </td>
                            <td style="text-align: center; color: #64748b; font-size: 0.8rem;">
                                <?= htmlspecialchars($f['user']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($fakturList)): ?>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 700; border-top: 2px solid var(--border-color);">
                    <td colspan="5" style="text-align: right; padding: 1rem 0.75rem;">TOTAL KESELURUHAN (<?= count($fakturList) ?> Faktur):</td>
                    <td style="text-align: right; padding: 1rem 0.75rem; color: #475569;"><?= format_rupiah($summary['total_subtotal']) ?></td>
                    <td style="text-align: right; padding: 1rem 0.75rem; color: #ea580c;"><?= format_rupiah($summary['total_ppn']) ?></td>
                    <td style="text-align: right; padding: 1rem 0.75rem; color: #0f172a; font-size: 1rem;"><?= format_rupiah($summary['total_grand_total']) ?></td>
                    <td style="text-align: right; padding: 1rem 0.75rem; color: #16a34a;"><?= format_rupiah($summary['total_dp']) ?></td>
                    <td style="text-align: right; padding: 1rem 0.75rem; color: #dc2626;"><?= format_rupiah($summary['total_sisa']) ?></td>
                    <td></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
