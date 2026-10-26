<form action="<?= url('/faktur/update/' . $faktur['no_faktur']) ?>" method="POST" id="formFakturEdit">
    <?= csrf_field() ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a;">Ubah Faktur #<?= htmlspecialchars($faktur['no_faktur']) ?></h1>
            <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">
                Perbarui data faktur, penyesuaian rincian barang, dan perhitungan nilai penjualan.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= url('/faktur/show/' . $faktur['no_faktur']) ?>" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- BAGIAN 1: INFORMASI HEADER FAKTUR -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">1. Informasi Faktur & Rekanan</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <!-- No Faktur (Read-only) -->
                <div class="form-group">
                    <label class="form-label" for="no_faktur">No. Faktur (Permanen)</label>
                    <input type="text" id="no_faktur" class="form-control" value="<?= htmlspecialchars($faktur['no_faktur']) ?>" readonly style="background: #f1f5f9; cursor: not-allowed; font-weight: 700;">
                </div>

                <!-- Perusahaan Penerbit -->
                <div class="form-group">
                    <label class="form-label" for="id_perusahaan">Perusahaan Penerbit <span style="color: var(--danger);">*</span></label>
                    <select name="id_perusahaan" id="id_perusahaan" class="form-select" required>
                        <?php foreach ($perusahaanList as $p): ?>
                            <option value="<?= $p['id_perusahaan'] ?>" <?= ($faktur['id_perusahaan'] == $p['id_perusahaan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama_perusahaan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Customer Pembeli -->
                <div class="form-group">
                    <label class="form-label" for="id_customer">Customer / Pembeli <span style="color: var(--danger);">*</span></label>
                    <select name="id_customer" id="id_customer" class="form-select" required>
                        <?php foreach ($customerList as $c): ?>
                            <option value="<?= $c['id_customer'] ?>" <?= ($faktur['id_customer'] == $c['id_customer']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nama_customer']) ?> - <?= htmlspecialchars($c['perusahaan_cust']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal Faktur -->
                <div class="form-group">
                    <label class="form-label" for="tgl_faktur">Tanggal Faktur <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="tgl_faktur" id="tgl_faktur" class="form-control" value="<?= htmlspecialchars($faktur['tgl_faktur']) ?>" required>
                </div>

                <!-- Jatuh Tempo (Due Date) -->
                <div class="form-group">
                    <label class="form-label" for="due_date">Jatuh Tempo (Due Date) <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="<?= htmlspecialchars($faktur['due_date']) ?>" required>
                </div>

                <!-- Metode Pembayaran -->
                <div class="form-group">
                    <label class="form-label" for="metode_bayar">Metode Pembayaran <span style="color: var(--danger);">*</span></label>
                    <select name="metode_bayar" id="metode_bayar" class="form-select" required>
                        <?php 
                        $metodes = ['Tunai / Cash', 'Transfer Bank BCA', 'Transfer Bank Mandiri', 'Tempo 14 Hari', 'Tempo 30 Hari'];
                        foreach ($metodes as $m): ?>
                            <option value="<?= $m ?>" <?= ($faktur['metode_bayar'] == $m) ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: RINCIAN PRODUK (MULTI-ITEM CART) -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">2. Rincian Produk</h2>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" id="btnAddRow">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Baris Produk</span>
            </button>
        </div>
        <div class="table-responsive">
            <table class="table" id="tableItems">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Produk</th>
                        <th style="width: 120px;">Satuan</th>
                        <th style="width: 100px; text-align: center;">Stok</th>
                        <th style="width: 160px; text-align: right;">Harga Satuan (Rp)</th>
                        <th style="width: 120px; text-align: center;">Kuantitas (Qty)</th>
                        <th style="width: 160px; text-align: right;">Subtotal (Rp)</th>
                        <th style="width: 50px; text-align: center;">Hapus</th>
                    </tr>
                </thead>
                <tbody id="itemRowsContainer">
                    <!-- Baris item di-render oleh script -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 3: KALKULASI FINANSIAL -->
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start;">
        <div></div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                    <span style="color: #64748b;">Subtotal Barang:</span>
                    <strong id="displaySubtotal" style="color: #0f172a; font-size: 1.05rem;">Rp 0</strong>
                </div>

                <div>
                    <label class="form-label" for="ppn" style="font-size: 0.875rem; margin-bottom: 0.35rem;">PPN (Pajak):</label>
                    <input type="number" name="ppn" id="ppn" class="form-control" min="0" step="100" value="<?= (float)$faktur['ppn'] ?>">
                </div>

                <div>
                    <label class="form-label" for="dp" style="font-size: 0.875rem; margin-bottom: 0.35rem;">Uang Muka / DP (Rp):</label>
                    <input type="number" name="dp" id="dp" class="form-control" min="0" step="500" value="<?= (float)$faktur['dp'] ?>">
                </div>

                <div style="border-top: 2px dashed #cbd5e1; margin: 0.5rem 0;"></div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700; color: #0f172a; font-size: 1.1rem;">Grand Total:</span>
                    <strong id="displayGrandTotal" style="color: #2563eb; font-size: 1.4rem;">Rp 0</strong>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 0.75rem 1rem;">
                    Perbarui Data Faktur
                </button>
            </div>
        </div>
    </div>
</form>

<script>
const availableProducts = <?= json_encode($produkList) ?>;
const existingItems = <?= json_encode($faktur['items']) ?>;

let rowCount = 0;

function addProductRow(selectedId = null, initialQty = 1, initialPrice = null) {
    rowCount++;
    const container = document.getElementById('itemRowsContainer');
    const tr = document.createElement('tr');
    tr.id = 'row_' + rowCount;

    let optionsHtml = '<option value="">-- Pilih Produk --</option>';
    availableProducts.forEach(p => {
        const selected = (selectedId && p.id_produk == selectedId) ? 'selected' : '';
        optionsHtml += `<option value="${p.id_produk}" data-price="${p.price}" data-stock="${p.stock}" data-jenis="${p.jenis}" ${selected}>
            ${p.nama_produk} (Stok: ${p.stock})
        </option>`;
    });

    tr.innerHTML = `
        <td>
            <select name="id_produk[]" class="form-select product-select" required onchange="onProductChange(this, ${rowCount})">
                ${optionsHtml}
            </select>
        </td>
        <td>
            <span class="badge item-jenis" style="background: #f1f5f9; color: #475569;">-</span>
        </td>
        <td style="text-align: center;">
            <span class="item-stock" style="font-weight: 600; color: #334155;">-</span>
        </td>
        <td>
            <input type="number" name="price[]" class="form-control item-price" min="0" step="500" value="${initialPrice !== null ? initialPrice : 0}" required oninput="calculateTotals()" style="text-align: right;">
        </td>
        <td>
            <input type="number" name="qty[]" class="form-control item-qty" min="1" value="${initialQty}" required oninput="calculateTotals()" style="text-align: center;">
        </td>
        <td style="text-align: right; font-weight: 700; color: #0f172a;" class="item-subtotal">
            Rp 0
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})" title="Hapus Baris">
                &times;
            </button>
        </td>
    `;

    container.appendChild(tr);

    if (selectedId) {
        const selectElem = tr.querySelector('.product-select');
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        if (selectedOption) {
            tr.querySelector('.item-jenis').textContent = selectedOption.getAttribute('data-jenis') || '-';
            tr.querySelector('.item-stock').textContent = selectedOption.getAttribute('data-stock') || '-';
        }
    }
}

function onProductChange(selectElem, rowId) {
    const tr = document.getElementById('row_' + rowId);
    const selectedOption = selectElem.options[selectElem.selectedIndex];

    if (selectElem.value) {
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const jenis = selectedOption.getAttribute('data-jenis') || '-';

        tr.querySelector('.item-jenis').textContent = jenis;
        tr.querySelector('.item-stock').textContent = stock;
        tr.querySelector('.item-price').value = price;
    } else {
        tr.querySelector('.item-jenis').textContent = '-';
        tr.querySelector('.item-stock').textContent = '-';
        tr.querySelector('.item-price').value = 0;
    }

    calculateTotals();
}

function removeRow(rowId) {
    const rows = document.querySelectorAll('#itemRowsContainer tr');
    if (rows.length <= 1) {
        alert('Minimal harus ada satu baris produk dalam faktur!');
        return;
    }
    const tr = document.getElementById('row_' + rowId);
    if (tr) tr.remove();
    calculateTotals();
}

function formatRupiah(number) {
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

function calculateTotals() {
    let subtotal = 0;
    const rows = document.querySelectorAll('#itemRowsContainer tr');

    rows.forEach(tr => {
        const priceInput = tr.querySelector('.item-price');
        const qtyInput = tr.querySelector('.item-qty');
        const subtotalCell = tr.querySelector('.item-subtotal');

        const price = parseFloat(priceInput ? priceInput.value : 0) || 0;
        const qty = parseInt(qtyInput ? qtyInput.value : 0) || 0;
        const rowSubtotal = price * qty;

        if (subtotalCell) {
            subtotalCell.textContent = formatRupiah(rowSubtotal);
        }
        subtotal += rowSubtotal;
    });

    document.getElementById('displaySubtotal').textContent = formatRupiah(subtotal);

    const ppnInput = parseFloat(document.getElementById('ppn').value) || 0;
    const dpInput = parseFloat(document.getElementById('dp').value) || 0;

    const grandTotal = Math.max(0, subtotal + ppnInput - dpInput);
    document.getElementById('displayGrandTotal').textContent = formatRupiah(grandTotal);
}

document.getElementById('ppn').addEventListener('input', calculateTotals);
document.getElementById('dp').addEventListener('input', calculateTotals);
document.getElementById('btnAddRow').addEventListener('click', () => addProductRow());

// Load existing items
document.addEventListener('DOMContentLoaded', function() {
    if (existingItems && existingItems.length > 0) {
        existingItems.forEach(item => {
            addProductRow(item.id_produk, item.qty, item.price);
        });
    } else {
        addProductRow();
    }
    calculateTotals();
});
</script>
