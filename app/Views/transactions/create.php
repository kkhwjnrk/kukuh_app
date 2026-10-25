<form action="<?= url('/transactions/store') ?>" method="POST" id="transactionForm">
    <?= csrf_field() ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">Entri <?= htmlspecialchars(term('transaction.singular')) ?> Baru</h2>
            <p style="font-size: 0.8rem; color: #64748b;">Pilih barang dan tentukan kuantitas dalam satu nota transaksi terpadu.</p>
        </div>
        <a href="<?= url('/transactions') ?>" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    <!-- DATA HEADER TRANSAKSI -->
    <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
            <h3 class="card-title">Informasi Dasar Transaksi</h3>
            <span class="badge" style="background: #eef2ff; color: #4338ca; font-weight: 700; font-family: monospace;">
                <?= htmlspecialchars($autoCode) ?>
            </span>
            <input type="hidden" name="kode_transaksi" value="<?= htmlspecialchars($autoCode) ?>">
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="tanggal"><?= htmlspecialchars(term('transaction.date_label')) ?> <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="nama_pelanggan">Nama Pelanggan / Peminjam <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" placeholder="Contoh: Bapak Hendra / Pelanggan Umum" required value="Pelanggan Umum">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="catatan">Catatan / Keterangan (Opsional)</label>
                    <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Catatan tambahan transaksi...">
                </div>
            </div>
        </div>
    </div>

    <!-- AREA KERANJANG BELANJA (INTERACTIVE CART) -->
    <div class="cart-layout-grid">
        <!-- PANEL PILIH ITEM -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pilih <?= htmlspecialchars(term('item.singular')) ?></h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="cartItemPicker">Daftar <?= htmlspecialchars(term('item.singular')) ?> Tersedia</label>
                    <select id="cartItemPicker" class="form-select" size="8" style="height: 240px;">
                        <option value="" disabled selected>-- Klik salah satu item --</option>
                        <?php foreach ($availableItems as $ai): ?>
                            <option value="<?= $ai['id'] ?>" 
                                    data-name="<?= htmlspecialchars($ai['nama_item']) ?>" 
                                    data-code="<?= htmlspecialchars($ai['kode_item']) ?>" 
                                    data-price="<?= $ai['harga'] ?>" 
                                    data-stock="<?= $ai['stok'] ?>">
                                <?= htmlspecialchars($ai['nama_item']) ?> (Stok: <?= $ai['stok'] ?>) - <?= format_rupiah($ai['harga']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="button" id="addCartItemBtn" class="btn btn-primary" style="width: 100%;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Tambahkan ke Nota</span>
                </button>
            </div>
        </div>

        <!-- TABEL RINCIAN ITEM NOTA -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rincian <?= htmlspecialchars(term('transaction.singular')) ?></h3>
            </div>
            <div class="table-responsive">
                <table class="table" id="transactionCartTable">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars(term('item.singular')) ?></th>
                            <th>Harga</th>
                            <th style="width: 110px;">Qty</th>
                            <th>Subtotal</th>
                            <th style="text-align: right; width: 60px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris akan dimasukkan secara dinamis via JavaScript -->
                    </tbody>
                </table>
            </div>

            <div id="cartEmptyMessage" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                <svg fill="none" stroke="currentColor" width="36" height="36" viewBox="0 0 24 24" style="margin: 0 auto 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <div>Keranjang transaksi masih kosong. Silakan pilih item dari panel sebelah kiri.</div>
            </div>

            <!-- TOTAL KESELURUHAN & PROSES -->
            <div style="padding: 1.5rem; background: #fafafa; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <div style="font-size: 0.775rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700;">
                        <?= htmlspecialchars(term('transaction.total_label')) ?>:
                    </div>
                    <div id="cartGrandTotal" style="font-size: 1.85rem; font-weight: 800; color: var(--primary); letter-spacing: -0.02em;">
                        Rp 0
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" style="padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 600;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span><?= htmlspecialchars(term('transaction.action_verb')) ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
