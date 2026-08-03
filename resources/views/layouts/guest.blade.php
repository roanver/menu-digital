<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MenuDigital') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Bricolage+Grotesque:wght@500;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,body{margin:0;padding:0;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
    </style>
</head>
<body style="font-family:Inter,system-ui,sans-serif;color:#201914;background:#FBF3E4;min-height:100vh;">

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));min-height:100vh;">

    {{-- Left: form --}}
    <div style="background:#FBF3E4;padding:clamp(22px,3vw,34px) clamp(20px,4vw,48px);display:flex;flex-direction:column;">

        {{-- Brand --}}
        <a href="/" style="display:inline-flex;align-items:center;gap:10px;align-self:flex-start;">
            <div style="width:34px;height:34px;border-radius:11px;background:#E85D2F;border:1.5px solid #201914;box-shadow:2.5px 2.5px 0 #201914;display:flex;align-items:center;justify-content:center;transform:rotate(-5deg);flex:0 0 auto;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:17px;font-weight:800;letter-spacing:-.025em;">MenuDigital</span>
        </a>

        {{-- Form slot --}}
        <div style="flex:1;display:flex;flex-direction:column;justify-content:center;max-width:394px;width:100%;margin:0 auto;padding:32px 0;">
            {{ $slot }}
        </div>
    </div>

    {{-- Right: dark panel --}}
    <div style="background:#201914;padding:clamp(32px,4vw,56px) clamp(24px,3.5vw,48px);display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(50% 60% at 50% 100%,rgba(232,93,47,.26),transparent 72%);pointer-events:none;"></div>

        <div style="position:relative;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#5C5245;">Restaurantes que ya lo usan</div>
            <blockquote style="margin:20px 0 0;font-family:'Instrument Serif',Georgia,serif;font-size:clamp(20px,2.2vw,26px);line-height:1.42;color:#FBF3E4;max-width:420px;">
                "Imprimíamos 300 cartas por temporada. Ahora cambio el precio del día <em>desde la caja</em> y los pedidos por WhatsApp se triplicaron."
            </blockquote>
            <div style="display:flex;align-items:center;gap:11px;margin-top:20px;">
                <div style="width:38px;height:38px;border-radius:50%;background:#E85D2F;border:1.5px solid #FBF3E4;color:#FBF3E4;font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;font-family:'Bricolage Grotesque',Inter,sans-serif;flex:0 0 auto;">CV</div>
                <div>
                    <div style="font-size:13.5px;font-weight:700;color:#FBF3E4;">Carolina Vera</div>
                    <div style="font-size:12px;color:#8A7B62;">Sazón de Barrio · Valparaíso</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;margin-top:36px;">
                @foreach([['1.200+', 'restaurantes en Chile'], ['30 s', 'para cambiar un precio'], ['0 %', 'comisión por pedido']] as [$num, $label])
                    <div style="background:rgba(255,255,255,.04);border:1px solid #3A2F28;border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px;">
                        <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:22px;font-weight:800;color:#FFD84D;min-width:56px;">{{ $num }}</span>
                        <span style="font-size:13px;color:#B8AA92;">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
</body>
</html>
