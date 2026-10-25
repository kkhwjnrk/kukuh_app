<?php if (!empty($flash)): ?>
    <?php foreach ($flash as $type => $message): ?>
        <div class="alert alert-<?= $type === 'error' ? 'error' : htmlspecialchars($type) ?> no-print">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <?php if ($type === 'success'): ?>
                    <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php elseif ($type === 'error'): ?>
                    <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php else: ?>
                    <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php endif; ?>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: inherit; opacity: 0.7;">&times;</button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- MODAL KONFIRMASI HAPUS GLOBAL -->
<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-content">
        <div class="card-header">
            <div class="card-title" style="color: var(--danger); display: flex; align-items: center; gap: 0.5rem;">
                <svg fill="none" stroke="currentColor" width="20" height="20" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span>Konfirmasi Penghapusan</span>
            </div>
            <button type="button" onclick="closeModal('deleteConfirmModal')" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
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
