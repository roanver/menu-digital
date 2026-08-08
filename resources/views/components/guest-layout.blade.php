@props([
    'title'       => 'MenuDigital',
    'description' => 'Carta digital con QR y NFC para tu restaurante. Cambiá precios desde el celular en segundos.',
    'robots'      => 'noindex,nofollow',
])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="robots" content="{{ $robots }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="MenuDigital">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root{
            --tinta:#16211C;
            --papel:#F5F4EF;
            --papel-2:#EDEBE3;
            --oliva:#3E5A47;
            --oliva-cl:#6E8A76;
            --aji:#C8452F;
            --linea:#D6D2C6;
        }
        html,body{margin:0;padding:0;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        body{font-family:Archivo,system-ui,sans-serif;color:var(--tinta);background:var(--papel);min-height:100vh}
        h1,h2,h3{font-family:Fraunces,Georgia,serif;font-weight:600;letter-spacing:-.015em;margin:0}
    </style>
</head>
<body style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));min-height:100vh">

    <!-- Izquierda: formulario -->
    <div style="background:var(--papel);padding:clamp(22px,3vw,34px) clamp(20px,4vw,48px);display:flex;flex-direction:column">
        <a href="/" style="display:inline-flex;align-items:center;gap:9px;align-self:flex-start;text-decoration:none;color:var(--tinta)">
            <div style="width:33px;height:33px;border-radius:10px;background:var(--oliva);border:1.5px solid var(--tinta);box-shadow:2px 2px 0 var(--tinta);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#F5F4EF" stroke-width="2" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span style="font-family:Fraunces,Georgia,serif;font-size:18px;font-weight:700;letter-spacing:-.02em">MenuDigital</span>
        </a>

        <div style="flex:1;display:flex;flex-direction:column;justify-content:center;max-width:394px;width:100%;margin:0 auto;padding:32px 0">
            {{ $slot }}
        </div>
    </div>

    <!-- Derecha: panel oscuro -->
    <div style="background:var(--tinta);padding:clamp(32px,4vw,56px) clamp(24px,3.5vw,48px);display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden">
        <div style="position:absolute;inset:0;background:radial-gradient(50% 60% at 50% 100%,rgba(62,90,71,.35),transparent 72%);pointer-events:none"></div>

        <div style="position:relative">
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--oliva-cl)">Restaurantes que ya lo usan</div>
            <blockquote style="margin:20px 0 0;font-family:Fraunces,Georgia,serif;font-size:clamp(20px,2.2vw,26px);line-height:1.42;color:var(--papel);max-width:420px">
                "Imprimíamos 300 cartas por temporada. Ahora cambio el precio del día <em>desde la caja</em> y los pedidos por WhatsApp se triplicaron."
            </blockquote>
            <div style="display:flex;align-items:center;gap:11px;margin-top:20px">
                <div style="width:38px;height:38px;border-radius:50%;background:var(--oliva);border:1.5px solid var(--papel);color:var(--papel);font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:Archivo,sans-serif;flex-shrink:0">CV</div>
                <div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--papel)">Carolina Vera</div>
                    <div style="font-size:12px;color:var(--oliva-cl)">Sazón de Barrio · Valparaíso</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;margin-top:36px">
                @foreach([['1.200+','restaurantes en Chile'],['30 s','para cambiar un precio'],['0 %','comisión por pedido']] as [$num,$label])
                <div style="background:rgba(255,255,255,.04);border:1px solid rgba(110,138,118,.25);border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px">
                    <span style="font-family:Fraunces,Georgia,serif;font-size:22px;font-weight:700;color:var(--oliva-cl);min-width:56px">{{ $num }}</span>
                    <span style="font-size:13px;color:#A0B4A0">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>
