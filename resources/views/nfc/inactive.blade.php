<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tag inactivo · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#F9FAFB;color:#111827;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
        a{color:#4F46E5;text-decoration:none;}
    </style>
</head>
<body>
<div style="max-width:360px;width:100%;text-align:center;">
    <div style="width:64px;height:64px;border-radius:20px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>
    <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#9CA3AF;margin-bottom:10px;">MenuDigital · NFC</div>
    <h1 style="font-size:24px;font-weight:700;letter-spacing:-.02em;margin-bottom:10px;">Tag inactivo</h1>
    <p style="font-size:15px;color:#6B7280;line-height:1.6;margin-bottom:8px;">
        @if(isset($tag) && $tag->label)
            El tag <strong>{{ $tag->label }}</strong> está temporalmente desactivado.
        @else
            Este tag NFC está temporalmente desactivado.
        @endif
    </p>
    <p style="font-size:14px;color:#9CA3AF;line-height:1.6;margin-bottom:28px;">
        Por favor contacta al establecimiento para más información.
    </p>
    <a href="https://menudigital.cl" style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:12px;background:#111827;color:#fff;font-size:14px;font-weight:600;">
        Ir a MenuDigital
    </a>
    <div style="margin-top:24px;font-size:11px;color:#D1D5DB;">
        menudigital.cl
    </div>
</div>
</body>
</html>
