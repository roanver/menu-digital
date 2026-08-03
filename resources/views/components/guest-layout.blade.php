<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MenuDigital') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html,body{margin:0;padding:0;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        body{font-family:Inter,system-ui,sans-serif;color:#111827}
    </style>
</head>
<body style="min-height:100vh;display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr))">

    <!-- Left: form -->
    <div style="display:flex;flex-direction:column;padding:clamp(22px,3vw,34px) clamp(20px,4vw,48px)">
        <a href="/" style="display:inline-flex;align-items:center;gap:9px;align-self:flex-start;text-decoration:none;color:#111827">
            <span style="width:29px;height:29px;border-radius:9px;background:#4F46E5;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 2px rgba(79,70,229,.4);flex-shrink:0">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </span>
            <span style="font-size:15px;font-weight:700;letter-spacing:-.015em">MenuDigital</span>
        </a>

        <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:34px 0">
            <div style="width:100%;max-width:394px">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Right: dark panel -->
    <div style="background:#0F1020;padding:clamp(28px,4vw,52px);display:flex;flex-direction:column;justify-content:center;gap:30px;position:relative;overflow:hidden">
        <div style="position:absolute;inset:auto -20% -30% -10%;height:60%;background:radial-gradient(50% 60% at 50% 100%,rgba(79,70,229,.34),transparent 72%);pointer-events:none"></div>
        <div style="position:relative">
            <div style="font-size:11.5px;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:#6C6C7A">Historias de clientes</div>
            <p style="margin:16px 0 0;font-size:clamp(19px,2.2vw,24px);line-height:1.45;font-weight:600;color:#fff;letter-spacing:-.02em;max-width:420px">
                "Imprimíamos 300 cartas cada temporada. Ahora cambio un precio desde la caja y listo — el primer mes ahorramos más de lo que cuesta el plan."
            </p>
            <div style="display:flex;align-items:center;gap:11px;margin-top:20px">
                <div style="width:34px;height:34px;border-radius:50%;background:#1E1E27;border:1px solid #2A2A35;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#A5B4FC;flex-shrink:0">CV</div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#fff">Carolina Vera</div>
                    <div style="font-size:11.5px;color:#8A8A99">Dueña · Sazón de Barrio, Valparaíso</div>
                </div>
            </div>
        </div>
        <div style="position:relative;display:flex;gap:12px;flex-wrap:wrap">
            @foreach([['1.200+','restaurantes en Chile'],['30 s','para cambiar un precio'],['0 %','comisión por pedido']] as [$val,$label])
            <div style="flex:1;min-width:110px;background:rgba(255,255,255,.04);border:1px solid #24242E;border-radius:14px;padding:14px">
                <div style="font-size:21px;font-weight:700;color:#fff;letter-spacing:-.03em">{{ $val }}</div>
                <div style="font-size:11.5px;color:#8A8A99;margin-top:4px;line-height:1.4">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>
