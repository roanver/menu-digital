<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR {{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#fff;color:#111827;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px;}
        .print-btn{display:flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:#111827;color:#fff;border:none;cursor:pointer;font-size:14px;font-weight:600;margin-bottom:32px;}
        .card{display:flex;flex-direction:column;align-items:center;gap:16px;text-align:center;max-width:320px;}
        .logo{width:80px;height:80px;border-radius:20px;object-fit:cover;border:1px solid #E5E7EB;}
        .logo-initials{width:80px;height:80px;border-radius:20px;background:#EEF2FF;border:1px solid #E0E7FF;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#4F46E5;}
        .name{font-size:22px;font-weight:700;letter-spacing:-.02em;}
        .qr-wrap{padding:12px;background:#fff;border:1px solid #E5E7EB;border-radius:16px;box-shadow:0 4px 16px rgba(16,24,40,.08);}
        .tagline{font-size:14px;font-weight:600;color:#374151;}
        .url{font-size:11px;color:#9CA3AF;letter-spacing:.04em;}
        @media print {
            @page { margin: 1cm; }
            body { padding: 0; background: #fff; }
            .print-btn { display: none !important; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
    Imprimir
</button>

<div class="card">
    {{-- Logo --}}
    @if($restaurant->logo)
        <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}" class="logo">
    @else
        @php
            $words = preg_split('/\s+/', trim($restaurant->name));
            $ini = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
        @endphp
        <div class="logo-initials">{{ $ini }}</div>
    @endif

    <div class="name">{{ $restaurant->name }}</div>

    {{-- QR --}}
    <div class="qr-wrap">
        {!! $qr !!}
    </div>

    <div class="tagline">Escanea para ver nuestro menú</div>
    <div class="url">{{ $url }}</div>
</div>

</body>
</html>
