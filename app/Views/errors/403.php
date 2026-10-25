<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body style="background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="text-align: center; max-width: 480px;">
        <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #dc2626; margin-bottom: 1.5rem;">
            <svg fill="none" stroke="currentColor" width="36" height="36" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m12-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">403 - Akses Ditolak</h1>
        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;">
            Maaf, akun Anda tidak memiliki hak akses/wewenang untuk membuka halaman atau melakukan tindakan ini.
        </p>
        <a href="<?= url('/dashboard') ?>" class="btn btn-primary">Kembali ke Dasbor Utama</a>
    </div>
</body>
</html>
