<!-- 1. MODAL KONFIRMASI HAPUS MODERN -->
<div id="deleteConfirmModal" class="modal-overlay" onclick="if(event.target === this) closeModal('deleteConfirmModal')">
    <div class="modal-dialog-modern">
        <button type="button" class="modal-close-x" onclick="closeModal('deleteConfirmModal')" aria-label="Tutup">&times;</button>
        <div class="modal-body-content">
            <div class="modal-icon-badge danger">
                <svg fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <div class="modal-title-text">Hapus Data Permanen?</div>
            <p class="modal-desc-text">
                Apakah Anda yakin ingin menghapus <strong id="deleteConfirmItemName" style="color: #b91c1c; background: #fee2e2; padding: 2px 8px; border-radius: 6px; font-weight: 700; word-break: break-word;"></strong>? Data yang dihapus tidak dapat dipulihkan kembali.
            </p>
        </div>
        <form id="deleteConfirmForm" method="POST" class="modal-footer-actions">
            <?= csrf_field() ?>
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
            <button type="submit" class="btn btn-danger" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span>Ya, Hapus Data</span>
            </button>
        </form>
    </div>
</div>

<!-- 2. MODAL KONFIRMASI KELUAR / LOGOUT MODERN -->
<div id="logoutConfirmModal" class="modal-overlay" onclick="if(event.target === this) closeModal('logoutConfirmModal')">
    <div class="modal-dialog-modern">
        <button type="button" class="modal-close-x" onclick="closeModal('logoutConfirmModal')" aria-label="Tutup">&times;</button>
        <div class="modal-body-content">
            <div class="modal-icon-badge warning">
                <svg fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <div class="modal-title-text">Keluar dari Sistem?</div>
            <p class="modal-desc-text">
                Sesi login Anda di <strong style="color: #0f172a;">Apotek LSP</strong> akan diakhiri. Pastikan seluruh transaksi dan dokumen faktur Anda sudah disimpan.
            </p>
        </div>
        <div class="modal-footer-actions">
            <button type="button" class="btn btn-secondary" onclick="closeModal('logoutConfirmModal')">Batal</button>
            <a id="logoutConfirmBtn" href="<?= url('/logout') ?>" class="btn btn-danger" style="display: inline-flex; align-items: center; gap: 0.4rem; background: #dc2626; border-color: #dc2626;">
                <svg fill="none" stroke="currentColor" width="16" height="16" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Ya, Keluar</span>
            </a>
        </div>
    </div>
</div>

<!-- 3. MODAL KONFIRMASI / PERINGATAN UNIVERSAL INTERAKTIF -->
<div id="universalConfirmModal" class="modal-overlay" onclick="if(event.target === this) closeModal('universalConfirmModal')">
    <div class="modal-dialog-modern">
        <button type="button" class="modal-close-x" onclick="closeModal('universalConfirmModal')" aria-label="Tutup">&times;</button>
        <div class="modal-body-content">
            <div id="universalModalIconBadge" class="modal-icon-badge warning">
                <svg id="universalModalIconWarning" fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <svg id="universalModalIconDanger" style="display:none;" fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <svg id="universalModalIconInfo" style="display:none;" fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <svg id="universalModalIconSuccess" style="display:none;" fill="none" stroke="currentColor" width="28" height="28" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div id="universalModalTitle" class="modal-title-text">Konfirmasi Tindakan</div>
            <p id="universalModalMessage" class="modal-desc-text">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        </div>
        <div class="modal-footer-actions">
            <button type="button" id="universalModalCancelBtn" class="btn btn-secondary" onclick="closeModal('universalConfirmModal')">Batal</button>
            <button type="button" id="universalModalConfirmBtn" class="btn btn-primary">Lanjutkan</button>
        </div>
    </div>
</div>

<!-- Core Vanilla JavaScript -->
<script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
