<div style="max-width: 820px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Rincian Faktur Transaksi</h2>
            <p style="font-size: 0.8rem; color: #64748b;">Faktur resmi bukti transaksi yang sah dalam sistem.</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= url('/transactions') ?>" class="btn btn-secondary">&larr; Daftar Transaksi</a>
            <a href="<?= url('/transactions/print/' . $transaction['id']) ?>" target="_blank" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Nota / PDF</span>
            </a>
        </div>
    </div>

    <div class="card" style="box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.05);">
        <!-- HEADER NOTA -->
        <div style="padding: 2rem 2rem 1.5rem; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <div style="width: 38px; height: 38px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <svg fill="none" stroke="currentColor" width="22" height="22" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h1 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;"><?= htmlspecialchars(term('app_name')) ?></h1>
                        <p style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars(term('organization')) ?></p>
                    </div>
                </div>
            </div>

            <div style="text-align: right;">
                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8;">
                    <?= htmlspecialchars(term('transaction.invoice_title')) ?>
                </div>
                <div style="font-size: 1.2rem; font-weight: 800; font-family: monospace; color: var(--primary); margin: 0.25rem 0;">
                    <?= htmlspecialchars($transaction['kode_transaksi']) ?>
                </div>
                <div><?= badge_status($transaction['status']) ?></div>
            </div>
        </div>

        <!-- INFO PELANGGAN & PETUGAS -->
        <div style="padding: 1.5rem 2rem; background: #fafafa; border-bottom: 1px solid var(--border-subtle); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <span style="font-size: 0.725rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 0.2rem;">Pelanggan / Peminjam:</span>
                <strong style="color: #0f172a; font-size: 0.95rem;"><?= htmlspecialchars($transaction['nama_pelanggan']) ?></strong>
            </div>

            <div>
                <span style="font-size: 0.725rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 0.2rem;">Tanggal Transaksi:</span>
                <span style="color: #0f172a; font-size: 0.9rem; font-weight: 500;"><?= format_date($transaction['tanggal'], false) ?></span>
            </div>

            <div>
                <span style="font-size: 0.725rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 0.2rem;">Petugas Kasir:</span>
                <span style="color: #0f172a; font-size: 0.9rem; font-weight: 500;"><?= htmlspecialchars($transaction['nama_petugas'] ?? 'Sistem') ?></span>
            </div>
        </div>

        <!-- TABEL RINCIAN ITEM -->
        <div class="table-responsive">
            <table class="table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th><?= htmlspecialchars(term('item.singular')) ?></th>
                        <th>Kode</th>
                        <th>Harga Satuan</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaction['items'] as $index => $item): ?>
                        <tr>
                            <td style="color: #94a3b8;"><?= $index + 1 ?></td>
                            <td>
                                <strong style="color: #0f172a;"><?= htmlspecialchars($item['nama_item']) ?></strong>
                            </td>
                            <td style="font-family: monospace; font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($item['kode_item']) ?></td>
                            <td><?= format_rupiah($item['harga_satuan']) ?></td>
                            <td style="text-align: center; font-weight: 600;"><?= $item['qty'] ?></td>
                            <td style="text-align: right; font-weight: 600; color: #0f172a;">
                                <?= format_rupiah($item['subtotal']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER TOTAL & CATATAN -->
        <div style="padding: 1.5rem 2rem; background: #ffffff; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 200px;">
                <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #94a3b8;">Catatan Transaksi:</span>
                <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">
                    <?= !empty($transaction['catatan']) ? htmlspecialchars($transaction['catatan']) : 'Tidak ada catatan tambahan.' ?>
                </p>
            </div>

            <div style="text-align: right;">
                <span style="font-size: 0.775rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Total Pembayaran:</span>
                <div style="font-size: 1.85rem; font-weight: 800; color: var(--primary); letter-spacing: -0.02em; margin-top: 0.15rem;">
                    <?= format_rupiah($transaction['total_bayar']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
