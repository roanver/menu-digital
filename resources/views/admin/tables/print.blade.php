<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Mesas · {{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#fff;color:#111827;padding:32px;}
        .top-bar{display:flex;align-items:center;gap:16px;margin-bottom:32px;}
        .print-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:#111827;color:#fff;border:none;cursor:pointer;font-size:14px;font-weight:600;}
        .restaurant-name{font-size:18px;font-weight:700;color:#111827;}
        .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:24px;}
        .card{display:flex;flex-direction:column;align-items:center;gap:12px;text-align:center;padding:24px 16px;border:1px solid #E5E7EB;border-radius:16px;page-break-inside:avoid;break-inside:avoid;}
        .rest-name-small{font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;}
        .qr-wrap{padding:10px;background:#fff;border:1px solid #E5E7EB;border-radius:12px;box-shadow:0 2px 8px rgba(16,24,40,.06);}
        .table-name{font-size:20px;font-weight:700;letter-spacing:-.02em;color:#111827;}
        .url{font-size:10px;color:#9CA3AF;letter-spacing:.03em;word-break:break-all;}
        @media print {
            @page{margin:1.5cm;}
            body{padding:0;}
            .top-bar{display:none!important;}
            .grid{grid-template-columns:repeat(2,1fr);gap:16px;}
            .card{border:1px solid #E5E7EB;box-shadow:none;}
        }
        @media (max-width:600px){
            .grid{grid-template-columns:1fr;}
        }
    </style>
</head>
<body>

<div class="top-bar">
    <button class="print-btn" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
    </button>
    <span class="restaurant-name">{{ $restaurant->name }} · Mesas con QR</span>
</div>

@if($tablesWithQr->isEmpty())
<p style="text-align:center;color:#9CA3AF;font-size:14px;padding:60px 0;">No hay mesas activas para imprimir.</p>
@else
<div class="grid">
    @foreach($tablesWithQr as $item)
    <div class="card">
        <div class="rest-name-small">{{ $restaurant->name }}</div>
        <div class="qr-wrap">
            {!! $item['qr'] !!}
        </div>
        <div class="table-name">{{ $item['table']->name }}</div>
        <div class="url">{{ $item['url'] }}</div>
    </div>
    @endforeach
</div>
@endif

</body>
</html>
