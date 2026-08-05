<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Imprimir Tags NFC · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#F9FAFB;color:#111827;padding:28px;}
        .no-print-bar{display:flex;align-items:center;gap:12px;margin-bottom:24px;}
        .print-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:#111827;color:#fff;border:none;cursor:pointer;font-size:13px;font-weight:600;}
        .back-link{font-size:13px;color:#6B7280;text-decoration:none;font-weight:600;}
        .count-label{font-size:12px;color:#9CA3AF;margin-bottom:16px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;}
        .card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;padding:16px;display:flex;flex-direction:column;align-items:center;gap:10px;text-align:center;box-shadow:0 1px 3px rgba(16,24,40,.05);}
        .code{font-family:monospace;font-size:15px;font-weight:700;letter-spacing:.06em;color:#4F46E5;background:#EEF2FF;padding:5px 12px;border-radius:7px;}
        .type-badge{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:4px 9px;border-radius:6px;}
        .type-menu{background:#EEF2FF;color:#4F46E5;}
        .type-review{background:#FEF3C7;color:#B45309;}
        .restaurant-name{font-size:12px;color:#6B7280;font-weight:600;}
        .label-text{font-size:13.5px;font-weight:700;color:#111827;}
        .url-text{font-size:10px;color:#9CA3AF;word-break:break-all;margin-top:4px;line-height:1.4;}
        @media print {
            @page { margin: 1cm; size: A4; }
            body { background: #fff; padding: 0; }
            .no-print-bar, .count-label { display: none !important; }
            .grid { grid-template-columns: repeat(3, 1fr); }
            .card { break-inside: avoid; border: 1px solid #ddd; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="no-print-bar">
    <button class="print-btn" onclick="window.print()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
    </button>
    <a href="{{ route('superadmin.nfc.index') }}" class="back-link">← Volver a Tags NFC</a>
</div>

<p class="count-label">{{ count($tags) }} tag(s) para imprimir</p>

<div class="grid">
    @foreach($tags as $tag)
    <div class="card">
        {{-- QR generado --}}
        @php
            $url = $tag->type === 'menu' && $tag->restaurant
                ? url('/' . $tag->restaurant->slug)
                : ($tag->type === 'review'
                    ? route('nfc.review', $tag->code)
                    : route('nfc.menu', $tag->code));
        @endphp
        <div style="padding:4px;border:1px solid #E5E7EB;border-radius:10px;background:#fff;">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(1)->generate($url) !!}
        </div>

        <div class="code">{{ $tag->code }}</div>

        <span class="type-badge {{ $tag->type === 'menu' ? 'type-menu' : 'type-review' }}">
            {{ $tag->type === 'menu' ? 'Menú' : 'Reseña' }}
        </span>

        @if($tag->label)
            <div class="label-text">{{ $tag->label }}</div>
        @endif

        @if($tag->restaurant)
            <div class="restaurant-name">{{ $tag->restaurant->name }}</div>
        @endif

        <div class="url-text">{{ $url }}</div>
    </div>
    @endforeach
</div>

</body>
</html>
