<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kit sin activar · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#F9FAFB;color:#111827;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
        a{color:#4F46E5;text-decoration:none}
    </style>
</head>
<body>
<div style="max-width:360px;width:100%;text-align:center">
    <div style="width:64px;height:64px;border-radius:20px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
            <rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/>
            <rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
        </svg>
    </div>
    <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#9CA3AF;margin-bottom:10px">MenuDigital · NFC</div>
    <h1 style="font-size:24px;font-weight:700;letter-spacing:-.02em;margin-bottom:10px">Este kit no está activado</h1>
    <p style="font-size:15px;color:#6B7280;line-height:1.6;margin-bottom:28px">
        El menú digital de esta mesa todavía no está configurado.<br>
        Si recibiste este kit, actívalo para empezar.
    </p>
    <a href="{{ route('kit.show', $kitToken) }}"
       style="display:inline-flex;align-items:center;gap:8px;padding:13px 24px;border-radius:12px;background:#4F46E5;color:#fff;font-size:14px;font-weight:700">
        Activar kit
    </a>
    <div style="margin-top:24px;font-size:11px;color:#D1D5DB">
        menudigital.cl
    </div>
</div>
</body>
</html>
