/**
 * SIM-PRO JavaScript Core Interactivity
 * Pure Vanilla JS, zero dependencies, offline-first.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.app-sidebar');

    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // 2. Modal Helper
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
        }
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
        }
    };

    // Konfirmasi Hapus Modal Global
    window.confirmDelete = function (actionUrl, itemName) {
        const modal = document.getElementById('deleteConfirmModal');
        const form = document.getElementById('deleteConfirmForm');
        const textElem = document.getElementById('deleteConfirmItemName');

        if (modal && form) {
            form.action = actionUrl;
            if (textElem) {
                textElem.textContent = itemName || 'data ini';
            }
            openModal('deleteConfirmModal');
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus ' + (itemName || 'data ini') + '?')) {
                const f = document.createElement('form');
                f.method = 'POST';
                f.action = actionUrl;
                
                // Masukkan token CSRF
                const csrfInput = document.querySelector('input[name="_csrf_token"]');
                if (csrfInput) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = '_csrf_token';
                    hidden.value = csrfInput.value;
                    f.appendChild(hidden);
                }
                document.body.appendChild(f);
                f.submit();
            }
        }
    };

    // 3. Image Preview Before Upload
    const imageInput = document.getElementById('imageUploadInput');
    const imagePreview = document.getElementById('imageUploadPreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 4. Interactive Transaction Cart Engine
    const cartTable = document.getElementById('transactionCartTable');
    const addItemBtn = document.getElementById('addCartItemBtn');
    const itemSelect = document.getElementById('cartItemPicker');
    const grandTotalElem = document.getElementById('cartGrandTotal');

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    function calculateCartTotal() {
        if (!cartTable) return;
        let total = 0;
        const rows = cartTable.querySelectorAll('tbody tr.cart-row');

        rows.forEach(function (row) {
            const price = parseFloat(row.dataset.price) || 0;
            const qtyInput = row.querySelector('.cart-qty-input');
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 0;
            const subtotal = price * qty;

            const subtotalElem = row.querySelector('.cart-subtotal');
            if (subtotalElem) {
                subtotalElem.textContent = formatRupiah(subtotal);
            }
            total += subtotal;
        });

        if (grandTotalElem) {
            grandTotalElem.textContent = formatRupiah(total);
        }

        const emptyMessage = document.getElementById('cartEmptyMessage');
        if (emptyMessage) {
            emptyMessage.style.display = rows.length === 0 ? 'block' : 'none';
        }
    }

    if (addItemBtn && itemSelect && cartTable) {
        addItemBtn.addEventListener('click', function () {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                alert('Silakan pilih barang terlebih dahulu.');
                return;
            }

            const itemId = selectedOption.value;
            const itemName = selectedOption.dataset.name;
            const itemCode = selectedOption.dataset.code;
            const itemPrice = parseFloat(selectedOption.dataset.price);
            const itemStock = parseInt(selectedOption.dataset.stock);

            // Cek apakah item sudah ada di keranjang
            const existingRow = cartTable.querySelector(`tr[data-id="${itemId}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.cart-qty-input');
                const curQty = parseInt(qtyInput.value) || 1;
                if (curQty + 1 > itemStock) {
                    alert(`Stok tidak mencukupi! Maksimal tersedia: ${itemStock}`);
                    return;
                }
                qtyInput.value = curQty + 1;
                calculateCartTotal();
                return;
            }

            // Buat baris baru
            const tbody = cartTable.querySelector('tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'cart-row';
            newRow.dataset.id = itemId;
            newRow.dataset.price = itemPrice;

            newRow.innerHTML = `
                <td>
                    <div style="font-weight: 600;">${itemName}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">${itemCode} (Tersedia: ${itemStock})</div>
                    <input type="hidden" name="item_id[]" value="${itemId}">
                    <input type="hidden" name="harga[]" value="${itemPrice}">
                </td>
                <td>${formatRupiah(itemPrice)}</td>
                <td style="width: 120px;">
                    <input type="number" name="qty[]" class="form-control cart-qty-input" value="1" min="1" max="${itemStock}">
                </td>
                <td class="cart-subtotal" style="font-weight: 600;">${formatRupiah(itemPrice)}</td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-danger btn-sm remove-cart-row">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
            calculateCartTotal();

            // Reset dropdown
            itemSelect.selectedIndex = 0;
        });

        // Event listener delegasi untuk perubahan kuantitas dan tombol hapus
        cartTable.addEventListener('input', function (e) {
            if (e.target.classList.contains('cart-qty-input')) {
                const max = parseInt(e.target.max) || 9999;
                let val = parseInt(e.target.value) || 1;
                if (val > max) {
                    alert(`Jumlah melebihi stok yang tersedia (${max})`);
                    e.target.value = max;
                } else if (val < 1) {
                    e.target.value = 1;
                }
                calculateCartTotal();
            }
        });

        cartTable.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-cart-row');
            if (removeBtn) {
                const row = removeBtn.closest('tr');
                if (row) {
                    row.remove();
                    calculateCartTotal();
                }
            }
        });
    }
});
