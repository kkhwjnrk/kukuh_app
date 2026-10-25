<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body style="background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="text-align: center; max-width: 480px;">
        <div style="width: 64px; height: 64px; background: #e0e7ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: var(--primary); margin-bottom: 1.5rem;">
            <svg fill="none" stroke="currentColor" width="36" height="36" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">404 - Halaman Tidak Ditemukan</h1>
        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;">
            Alamat URL yang Anda tuju tidak terdaftar atau telah dipindahkan ke lokasi lain.
        </p>
        <a href="<?= url('/dashboard') ?>" class="btn btn-primary">Kembali ke Dasbor Utama</a>
    </div>
</body>
</html>
