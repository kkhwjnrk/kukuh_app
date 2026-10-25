<!-- MODAL KONFIRMASI HAPUS GLOBAL -->
<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-content">
        <div class="card-header">
            <div class="card-title" style="color: var(--danger); display: flex; align-items: center; gap: 0.5rem;">
                <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span>Konfirmasi Penghapusan</span>
            </div>
            <button type="button" onclick="closeModal('deleteConfirmModal')" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Apakah Anda benar-benar yakin ingin menghapus data <strong id="deleteConfirmItemName" style="color: var(--text-main);"></strong>? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
            </p>
            <form id="deleteConfirmForm" method="POST" style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <?= csrf_field() ?>
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus Sekarang</button>
            </form>
        </div>
    </div>
</div>

<!-- Core Vanilla JavaScript -->
<script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
