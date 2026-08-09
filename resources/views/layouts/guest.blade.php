<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($title)<title>{{ $title }}</title>@else<title>{{ config('app.name', 'MenuDigital') }}</title>@endisset
    @isset($description)<meta name="description" content="{{ $description }}">@endisset
    @isset($robots)<meta name="robots" content="{{ $robots }}">@endisset
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --tinta:#16211C;
            --papel:#F5F4EF;
            --papel-2:#EDEBE3;
            --oliva:#3E5A47;
            --aji:#C8452F;
            --linea:#D6D2C6;
        }
        html,body{margin:0;padding:0;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
        body{font-family:Archivo,system-ui,sans-serif;color:var(--tinta);background:var(--papel)}
    </style>
</head>
<body>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));min-height:100vh;">

    {{-- Left: form --}}
    <div style="background:var(--papel);padding:clamp(24px,3vw,36px) clamp(22px,4vw,52px);display:flex;flex-direction:column;">

        {{-- Brand --}}
        <a href="/" style="display:inline-flex;align-items:center;gap:10px;align-self:flex-start;">
            <div style="width:32px;height:32px;border-radius:10px;background:var(--oliva);border:1.5px solid var(--tinta);box-shadow:2px 2px 0 var(--tinta);display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#F5F4EF" stroke-width="1.9" stroke-linecap="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <span style="font-family:Fraunces,Georgia,serif;font-size:18px;font-weight:700;letter-spacing:-.02em;">MenuDigital</span>
        </a>

        {{-- Form slot --}}
        <div style="flex:1;display:flex;flex-direction:column;justify-content:center;max-width:390px;width:100%;margin:0 auto;padding:36px 0;">
            {{ $slot }}
        </div>
    </div>

    {{-- Right: dark panel --}}
    <div style="background:var(--tinta);padding:clamp(36px,4vw,60px) clamp(26px,3.5vw,52px);display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(55% 55% at 50% 100%,rgba(200,69,47,.2),transparent 70%);pointer-events:none;"></div>

        <div style="position:relative;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:#4A5A50;">Por qué MenuDigital</div>
            <p style="margin:18px 0 0;font-family:Fraunces,Georgia,serif;font-size:clamp(19px,2.1vw,25px);line-height:1.45;color:var(--papel);max-width:400px;font-weight:400;">
                Tu carta digital lista en minutos, sin apps, sin comisiones.
            </p>

            <div style="display:flex;flex-direction:column;gap:9px;margin-top:34px;">
                @foreach([
                    ['2 min', 'y tu menú queda publicado en línea'],
                    ['QR + NFC', 'incluidos en todos los planes'],
                    ['0 %', 'de comisión por pedido o venta'],
                ] as [$num, $label])
                <div style="background:rgba(255,255,255,.04);border:1px solid #2A3830;border-radius:13px;padding:13px 15px;display:flex;align-items:center;gap:13px;">
                    <span style="font-family:Fraunces,Georgia,serif;font-size:22px;font-weight:700;color:var(--aji);min-width:60px;letter-spacing:-.01em;">{{ $num }}</span>
                    <span style="font-size:12.5px;color:#9EB0A4;">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
</body>
</html>
