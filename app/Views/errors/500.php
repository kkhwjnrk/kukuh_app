<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 - Terjadi Kesalahan Sistem</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body style="background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="text-align: center; max-width: 500px;">
        <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #dc2626; margin-bottom: 1.5rem;">
            <svg fill="none" stroke="currentColor" width="36" height="36" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h1 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">500 - Terjadi Kesalahan</h1>
        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;">
            Sistem mendeteksi kendala pada pemrosesan permintaan Anda. Detail error telah dicatat ke berkas log sistem.
        </p>
        <a href="<?= url('/dashboard') ?>" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>
