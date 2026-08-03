<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
        html,body{margin:0;padding:0;background:#F6F1E7;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
    </style>
</head>
<body style="font-family:Inter,system-ui,sans-serif;color:#211A12;background:#F6F1E7;min-height:100vh;">

@php
    $whatsappNum = $restaurant->whatsapp ? preg_replace('/\D/', '', $restaurant->whatsapp) : null;
    $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Outer frame with inner inset --}}
    <div style="margin:14px 16px 0;border:1px solid #211A12;position:relative;">
        <div style="position:absolute;inset:4px;border:1px solid rgba(33,26,18,.25);pointer-events:none;"></div>

        {{-- Header --}}
        <div style="padding:34px 24px 24px;text-align:center;">
            <div style="font-size:9.5px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:#8A7B66;">Carta · 2026</div>
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     style="width:64px;height:64px;border-radius:12px;object-fit:cover;margin:16px auto 0;display:block;">
            @else
                <h1 style="margin:16px 0 0;font-family:'Instrument Serif',Georgia,serif;font-size:54px;font-weight:400;line-height:.95;letter-spacing:-.015em;">{{ $restaurant->name }}</h1>
            @endif
            @if($restaurant->logo)
                <h1 style="margin:10px 0 0;font-family:'Instrument Serif',Georgia,serif;font-size:40px;font-weight:400;line-height:1;letter-spacing:-.015em;">{{ $restaurant->name }}</h1>
            @endif
            @if($restaurant->welcome_message)
                <p style="margin:15px 0 0;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:15px;color:#8A7B66;line-height:1.5;">{{ $restaurant->welcome_message }}</p>
            @endif
            @if($restaurant->address)
                <p style="margin:10px 0 0;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:13px;color:#8A7B66;line-height:1.5;">{{ $restaurant->address }}</p>
            @endif
            <div style="display:flex;align-items:center;justify-content:center;gap:9px;margin-top:17px;">
                <span style="width:30px;height:1px;background:#211A12;display:block;"></span>
                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:15px;line-height:1;">❦</span>
                <span style="width:30px;height:1px;background:#211A12;display:block;"></span>
            </div>
        </div>

        {{-- Sections --}}
        <div style="padding:0 24px 26px;">
            @foreach($categories as $si => $category)
                <div style="padding-top:22px;">
                    <div style="text-align:center;">
                        <div style="font-size:9px;font-weight:600;letter-spacing:.26em;color:#8A7B66;text-transform:uppercase;">{{ $romans[$si] ?? ($si + 1) }}</div>
                        <div style="font-family:'Instrument Serif',Georgia,serif;font-size:25px;margin-top:3px;letter-spacing:-.01em;">{{ $category->name }}</div>
                        <div style="width:22px;height:1px;background:#211A12;margin:9px auto 0;"></div>
                    </div>
                    <div style="margin-top:16px;display:flex;flex-direction:column;gap:16px;">
                        @foreach($category->menuItems as $item)
                            <div style="text-align:center;">
                                <div style="font-family:'Instrument Serif',Georgia,serif;font-size:18.5px;line-height:1.25;">{{ $item->name }}</div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:13px;color:#8A7B66;line-height:1.5;margin-top:2px;">{{ $item->description }}</div>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:12px;color:#8A7B66;margin-top:4px;">
                                        @foreach($item->variants as $variant)
                                            {{ $variant->name }}@if(!$loop->last) · @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if($restaurant->show_price)
                                    <div style="display:inline-flex;align-items:center;gap:7px;margin-top:6px;">
                                        <span style="font-size:11.5px;font-weight:600;letter-spacing:.14em;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Footer info --}}
            <div style="text-align:center;margin-top:24px;padding-bottom:8px;">
                @if($restaurant->phone)
                    <div style="font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:#8A7B66;line-height:2;">{{ $restaurant->phone }}</div>
                @endif
            </div>
        </div>
    </div>

    <div style="text-align:center;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#C9BCA6;padding:16px 0;">Menú creado con MenuDigital</div>
</div>

{{-- WhatsApp pill --}}
@if($whatsappNum)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#211A12;color:#F6F1E7;font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;box-shadow:0 10px 26px rgba(33,26,18,.4);pointer-events:auto;">
            <span style="width:20px;height:20px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            Reservas por WhatsApp
        </a>
    </div>
@endif

</body>
</html>
