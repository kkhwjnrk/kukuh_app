<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a;">Faktur #<?= htmlspecialchars($faktur['no_faktur']) ?></h1>
            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600;">
                <?= htmlspecialchars($faktur['metode_bayar']) ?>
            </span>
        </div>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">
            Diterbitkan pada <?= date('d F Y', strtotime($faktur['tgl_faktur'])) ?> &bull; Kasir / Petugas: <?= htmlspecialchars($faktur['user']) ?>
        </p>
    </div>

    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="<?= url('/faktur') ?>" class="btn btn-secondary">
            &larr; Kembali
        </a>

        <!-- Tombol Cetak Faktur -->
        <a href="<?= url('/faktur/print/' . $faktur['no_faktur']) ?>" target="_blank" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Faktur Penjualan</span>
        </a>

        <?php if (has_role(['admin', 'petugas'])): ?>
        <a href="<?= url('/faktur/edit/' . $faktur['no_faktur']) ?>" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span>Ubah</span>
        </a>
        <?php endif; ?>

        <?php if (has_role('admin')): ?>
        <button type="button" class="btn btn-danger" onclick="confirmDelete('<?= url('/faktur/delete/' . $faktur['no_faktur']) ?>', 'Faktur <?= htmlspecialchars(addslashes($faktur['no_faktur'])) ?>')">
            <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            <span>Hapus</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- INFORMASI REKANAN (DUA KOLOM) -->
<div class="two-col-grid" style="margin-bottom: 1.5rem;">
    <!-- PERUSAHAAN PENERBIT -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Perusahaan Penerbit</h3>
        </div>
        <div class="card-body">
            <div style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">
                <?= htmlspecialchars($faktur['nama_perusahaan']) ?>
            </div>
            <p style="color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem; line-height: 1.5;">
                <?= htmlspecialchars($faktur['alamat_perusahaan']) ?>
            </p>
            <div style="font-size: 0.85rem; color: #64748b;">
                <strong>Telp:</strong> <?= htmlspecialchars($faktur['telp_perusahaan']) ?>
                <?php if (!empty($faktur['fax_perusahaan'])): ?>
                    &bull; <strong>Fax:</strong> <?= htmlspecialchars($faktur['fax_perusahaan']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CUSTOMER PEMBELI -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer / Ditujukan Kepada</h3>
        </div>
        <div class="card-body">
            <div style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">
                <?= htmlspecialchars($faktur['nama_customer']) ?>
            </div>
            <div style="display: inline-block; background: #f0fdf4; color: #166534; font-weight: 600; font-size: 0.8rem; padding: 0.2rem 0.5rem; border-radius: 4px; margin-bottom: 0.5rem;">
                <?= htmlspecialchars($faktur['perusahaan_cust']) ?>
            </div>
            <p style="color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem; line-height: 1.5;">
                <?= htmlspecialchars($faktur['alamat_customer']) ?>
            </p>
            <div style="font-size: 0.85rem; color: #64748b;">
                <strong>No. Kontak:</strong> <?= htmlspecialchars($faktur['telp_customer']) ?>
            </div>
        </div>
    </div>
</div>

<!-- RINCIAN TABEL BARANG FAKTUR -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Daftar Produk yang Dipesan</h3>
        <span style="font-size: 0.85rem; color: #64748b;">
            Jatuh Tempo: <strong style="color: #dc2626;"><?= date('d/m/Y', strtotime($faktur['due_date'])) ?></strong>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Produk</th>
                    <th>Jenis / Satuan</th>
                    <th style="width: 120px; text-align: center;">Qty</th>
                    <th style="width: 160px; text-align: right;">Harga Satuan</th>
                    <th style="width: 180px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faktur['items'] as $idx => $item): ?>
                    <tr>
                        <td style="text-align: center; color: #94a3b8;"><?= $idx + 1 ?></td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                <?= htmlspecialchars($item['nama_produk']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: #f1f5f9; color: #475569;">
                                <?= htmlspecialchars($item['jenis']) ?>
                            </span>
                        </td>
                        <td style="text-align: center; font-weight: 600;">
                            <?= (int)$item['qty'] ?>
                        </td>
                        <td style="text-align: right; color: #334155;">
                            <?= format_rupiah($item['price']) ?>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">
                            <?= format_rupiah($item['subtotal']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- RINGKASAN TOTAL -->
<div style="display: flex; justify-content: flex-end;">
    <div class="card" style="width: 380px;">
        <div class="card-body" style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                <span style="color: #64748b;">Total Nilai Barang:</span>
                <span style="font-weight: 600; color: #0f172a;"><?= format_rupiah($faktur['subtotal_items']) ?></span>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                <span style="color: #64748b;">PPN (Pajak):</span>
                <span style="font-weight: 600; color: #0f172a;">+ <?= format_rupiah($faktur['ppn']) ?></span>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                <span style="color: #64748b;">Uang Muka (DP):</span>
                <span style="font-weight: 600; color: #16a34a;">- <?= format_rupiah($faktur['dp']) ?></span>
            </div>

            <div style="border-top: 2px dashed #cbd5e1; margin: 0.35rem 0;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; color: #0f172a; font-size: 1.1rem;">Grand Total:</span>
                <strong style="color: #2563eb; font-size: 1.4rem;"><?= format_rupiah($faktur['grand_total']) ?></strong>
            </div>
        </div>
    </div>
</div>
