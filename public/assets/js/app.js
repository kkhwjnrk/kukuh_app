/**
 * Apotek LSP - JavaScript Core Interactivity
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
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            // Cek jika tidak ada modal lain yang masih aktif
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.style.overflow = '';
            }
        }
    };

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) {
                closeModal(activeModal.id);
            }
        }
    });

    // Konfirmasi Hapus Modal Modern Global
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
            // Fallback interaktif tanpa browser confirm bawaan
            AppModal.confirm({
                title: 'Hapus Data?',
                message: 'Apakah Anda yakin ingin menghapus <strong>' + (itemName || 'data ini') + '</strong>? Tindakan ini tidak dapat dibatalkan.',
                type: 'danger',
                confirmText: 'Ya, Hapus',
                onConfirm: function () {
                    const f = document.createElement('form');
                    f.method = 'POST';
                    f.action = actionUrl;
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
            });
        }
    };

    // Konfirmasi Keluar / Logout Modern
    window.confirmLogout = function (event, logoutUrl) {
        if (event) {
            event.preventDefault();
        }
        const modal = document.getElementById('logoutConfirmModal');
        const logoutBtn = document.getElementById('logoutConfirmBtn');
        if (modal && logoutBtn) {
            logoutBtn.href = logoutUrl;
            openModal('logoutConfirmModal');
        } else {
            AppModal.confirm({
                title: 'Keluar dari Sistem?',
                message: 'Sesi login Anda di <strong>Apotek LSP</strong> akan diakhiri. Pastikan semua data transaksi telah tersimpan.',
                type: 'warning',
                confirmText: 'Ya, Keluar',
                onConfirm: function () {
                    window.location.href = logoutUrl;
                }
            });
        }
    };

    // Universal Modal System (Modern Confirmation & Alert)
    window.AppModal = {
        confirm: function (options) {
            return new Promise(function (resolve) {
                const modal = document.getElementById('universalConfirmModal');
                if (!modal) {
                    resolve(true);
                    return;
                }

                const titleEl = document.getElementById('universalModalTitle');
                const msgEl = document.getElementById('universalModalMessage');
                const confirmBtn = document.getElementById('universalModalConfirmBtn');
                const cancelBtn = document.getElementById('universalModalCancelBtn');
                const iconBadge = document.getElementById('universalModalIconBadge');

                const type = options.type || 'warning';
                titleEl.textContent = options.title || 'Konfirmasi Tindakan';
                msgEl.innerHTML = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

                confirmBtn.textContent = options.confirmText || 'Lanjutkan';
                cancelBtn.textContent = options.cancelText || 'Batal';
                cancelBtn.style.display = options.hideCancel ? 'none' : 'inline-flex';

                // Terapkan tipe tombol & ikon
                confirmBtn.className = 'btn ' + (options.confirmClass || (type === 'danger' ? 'btn-danger' : (type === 'warning' ? 'btn-warning' : 'btn-primary')));
                iconBadge.className = 'modal-icon-badge ' + type;

                document.getElementById('universalModalIconWarning').style.display = (type === 'warning') ? 'block' : 'none';
                document.getElementById('universalModalIconDanger').style.display = (type === 'danger') ? 'block' : 'none';
                document.getElementById('universalModalIconInfo').style.display = (type === 'info') ? 'block' : 'none';
                document.getElementById('universalModalIconSuccess').style.display = (type === 'success') ? 'block' : 'none';

                function cleanup() {
                    confirmBtn.removeEventListener('click', onConfirm);
                    cancelBtn.removeEventListener('click', onCancel);
                }

                function onConfirm() {
                    closeModal('universalConfirmModal');
                    cleanup();
                    resolve(true);
                    if (typeof options.onConfirm === 'function') options.onConfirm();
                }

                function onCancel() {
                    closeModal('universalConfirmModal');
                    cleanup();
                    resolve(false);
                    if (typeof options.onCancel === 'function') options.onCancel();
                }

                confirmBtn.addEventListener('click', onConfirm);
                cancelBtn.addEventListener('click', onCancel);

                openModal('universalConfirmModal');
            });
        },

        alert: function (message, title, type) {
            return window.AppModal.confirm({
                title: title || 'Pemberitahuan',
                message: message,
                type: type || 'warning',
                confirmText: 'Mengerti',
                hideCancel: true
            });
        }
    };

    // Intersepsi Deklaratif untuk Elemen dengan atribut [data-confirm]
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;
        e.preventDefault();

        const message = trigger.getAttribute('data-confirm');
        const title = trigger.getAttribute('data-confirm-title') || 'Konfirmasi';
        const type = trigger.getAttribute('data-confirm-type') || 'warning';
        const confirmText = trigger.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';

        AppModal.confirm({
            title: title,
            message: message,
            type: type,
            confirmText: confirmText
        }).then(function (confirmed) {
            if (confirmed) {
                if (trigger.tagName === 'A' && trigger.href) {
                    window.location.href = trigger.href;
                } else if (trigger.form) {
                    trigger.form.submit();
                }
            }
        });
    });

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
                AppModal.alert('Silakan pilih barang terlebih dahulu.', 'Peringatan', 'warning');
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
                    AppModal.alert(`Stok tidak mencukupi! Maksimal tersedia: ${itemStock}`, 'Stok Terbatas', 'warning');
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
                    AppModal.alert(`Jumlah melebihi stok yang tersedia (${max})`, 'Batas Stok', 'warning');
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
