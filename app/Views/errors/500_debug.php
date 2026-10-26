<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Application Debugger - <?= htmlspecialchars($exception->getMessage()) ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, monospace; background: #0f172a; color: #f8fafc; padding: 2.5rem; line-height: 1.6; margin: 0; }
        .debug-container { max-width: 1000px; margin: 0 auto; }
        .badge { display: inline-block; background: #ef4444; color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        h1 { font-size: 1.6rem; color: #f87171; margin-top: 0; word-break: break-word; }
        .file-info { background: #1e293b; padding: 12px 18px; border-radius: 8px; border-left: 4px solid #38bdf8; margin: 15px 0 25px; font-size: 13px; }
        .trace-box { background: #1e293b; border-radius: 8px; padding: 18px; overflow-x: auto; font-size: 12px; border: 1px solid #334155; }
        pre { margin: 0; color: #94a3b8; }
        .btn-back { display: inline-block; background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="debug-container">
        <span class="badge">UNHANDLED EXCEPTION (DEBUG MODE)</span>
        <h1><?= htmlspecialchars($exception->getMessage()) ?></h1>
        
        <div class="file-info">
            <strong>Terjadi pada berkas:</strong> <?= htmlspecialchars($exception->getFile()) ?> : baris <strong><?= $exception->getLine() ?></strong>
        </div>

        <h3 style="color: #cbd5e1; font-size: 14px; margin-bottom: 8px;">Stack Trace (Detail Error):</h3>
        <div class="trace-box">
            <pre><?= htmlspecialchars($exception->getTraceAsString()) ?></pre>
        </div>

        <a href="<?= url('/dashboard') ?>" class="btn-back">&larr; Kembali ke Dasbor</a>
    </div>
</body>
</html>
