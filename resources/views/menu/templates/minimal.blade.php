<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }}</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $restaurant->name }}">
    <meta property="og:description" content="{{ $restaurant->description ?? 'Menú digital de ' . $restaurant->name . ' en Chile.' }}">
    @if($restaurant->logo)
    <meta property="og:image" content="{{ Storage::url($restaurant->logo) }}">
    @endif
    <meta property="og:url" content="{{ url('/' . $restaurant->slug) }}">
    <meta property="og:site_name" content="MenuDigital">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        html,body{margin:0;padding:0;background:#FBFAF7;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
        [x-cloak]{display:none!important}
    </style>
</head>
<body style="font-family:Inter,system-ui,sans-serif;color:#1C1917;background:#FBFAF7;min-height:100vh;">

@php
    $whatsappNum = $restaurant->whatsapp ? preg_replace('/\D/', '', $restaurant->whatsapp) : null;
@endphp

<div x-data="menuCart('{{ $restaurant->slug }}', {{ $restaurant->accepts_orders ? 'true' : 'false' }}, '{{ $whatsappNum }}', {{ $restaurant->accepts_delivery ? 'true' : 'false' }})"
     style="min-height:100vh;">

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Header --}}
    <div style="padding:34px 26px 26px;text-align:center;">
        @if($restaurant->address)
            <div style="font-size:10px;font-weight:600;letter-spacing:.28em;color:#A8A29E;text-transform:uppercase;">{{ $restaurant->address }}</div>
        @endif
        @if($restaurant->logo)
            <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                 style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin:14px auto 0;display:block;border:1px solid #E7E5E4;">
        @endif
        <h1 style="margin:14px 0 0;font-family:'Instrument Serif',Georgia,serif;font-size:44px;font-weight:400;letter-spacing:-.01em;line-height:1;">{{ $restaurant->name }}</h1>
        @if($restaurant->welcome_message)
            <p style="margin:13px 0 0;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:15.5px;color:#78716C;line-height:1.5;">{{ $restaurant->welcome_message }}</p>
        @endif
        <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:18px;">
            <span style="width:34px;height:1px;background:#D6D3D1;display:block;"></span>
            <span style="width:5px;height:5px;background:#1C1917;transform:rotate(45deg);display:block;"></span>
            <span style="width:34px;height:1px;background:#D6D3D1;display:block;"></span>
        </div>
        @if($restaurant->phone)
            <div style="margin-top:14px;font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:#A8A29E;">{{ $restaurant->phone }}</div>
        @endif
    </div>

    {{-- Category nav --}}
    @if($categories->count() > 0)
        <div style="border-top:1px solid #1C1917;border-bottom:1px solid #1C1917;margin:0 26px;padding:10px 0;display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
            @foreach($categories as $i => $category)
                <a href="#cat-{{ $category->id }}" class="cat-link" data-cat="{{ $category->id }}"
                   style="font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:{{ $i === 0 ? '#1C1917' : '#A8A29E' }};border-bottom:1px solid {{ $i === 0 ? '#1C1917' : 'transparent' }};padding-bottom:2px;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:8px 26px;">
        @foreach($categories as $si => $category)
            <div id="cat-{{ $category->id }}" style="padding-top:32px;">
                <div style="display:flex;align-items:baseline;gap:12px;">
                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:15px;color:#D6D3D1;">0{{ $si + 1 }}</span>
                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:24px;letter-spacing:-.01em;">{{ $category->name }}</span>
                </div>
                <div style="margin-top:16px;display:flex;flex-direction:column;gap:17px;">
                    @foreach($category->menuItems as $item)
                        <div style="{{ !$item->is_available ? 'opacity:.55;' : '' }}">
                            <div style="display:flex;align-items:baseline;gap:10px;">
                                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:17.5px;letter-spacing:-.005em;min-width:0;flex-shrink:1;">{{ $item->name }}</span>
                                @if(!$item->is_available)
                                    <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:#FEE2E2;color:#DC2626;border-radius:4px;padding:2px 6px;white-space:nowrap;flex-shrink:0;">Agotado</span>
                                @endif
                                <span style="flex:1;border-bottom:1px dotted #D6D3D1;transform:translateY(-4px);min-width:14px;display:block;"></span>
                                @if($restaurant->show_price)
                                    @if($item->is_promo && $item->promo_price)
                                        <span style="font-size:11px;color:#A8A29E;text-decoration:line-through;white-space:nowrap;flex-shrink:0;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                        <span style="font-size:13px;font-weight:600;color:#DC2626;font-variant-numeric:tabular-nums;letter-spacing:.02em;white-space:nowrap;flex-shrink:0;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                    @else
                                        <span style="font-size:13px;font-weight:600;font-variant-numeric:tabular-nums;letter-spacing:.02em;white-space:nowrap;flex-shrink:0;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                    @endif
                                @endif
                                @if($item->is_available && $restaurant->accepts_orders)
                                    @if($item->variants->isNotEmpty())
                                        <button @click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                style="width:24px;height:24px;border-radius:6px;background:#1C1917;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;line-height:1;">+</button>
                                    @else
                                        <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }})"
                                                style="width:24px;height:24px;border-radius:6px;background:#1C1917;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;line-height:1;">+</button>
                                    @endif
                                @endif
                            </div>
                            @if($restaurant->show_description && $item->description)
                                <div style="margin-top:4px;">
                                    <span style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:13.5px;color:#A8A29E;line-height:1.5;">{{ $item->description }}</span>
                                </div>
                            @endif
                            @if($item->variants->isNotEmpty())
                                <ul style="margin:5px 0 0;padding:0;list-style:none;display:flex;flex-direction:column;gap:2px;">
                                    @foreach($item->variants as $variant)
                                        <li style="display:flex;justify-content:space-between;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:12px;color:#A8A29E;">
                                            <span>{{ $variant->name }}</span>
                                            @if($variant->price_delta != 0)
                                                <span>{{ $variant->price_delta > 0 ? '+' : '' }}${{ number_format($variant->price_delta, 0, ',', '.') }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:36px;">
            <span style="width:26px;height:1px;background:#D6D3D1;display:block;"></span>
            <span style="width:4px;height:4px;background:#A8A29E;transform:rotate(45deg);display:block;"></span>
            <span style="width:26px;height:1px;background:#D6D3D1;display:block;"></span>
        </div>
        <div style="text-align:center;margin-top:14px;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#D6D3D1;">Menú creado con MenuDigital</div>
    </div>
</div>

{{-- WhatsApp pill (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#1C1917;color:#fff;font-size:12px;font-weight:600;letter-spacing:.04em;box-shadow:0 10px 26px rgba(28,25,23,.35);pointer-events:auto;">
            <span style="width:22px;height:22px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            Reservar o pedir por WhatsApp
        </a>
    </div>
@endif

@include('components.menu-cart', ['restaurant' => $restaurant])

</div>{{-- end alpine x-data --}}

<script>
document.querySelectorAll('.cat-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById('cat-' + this.getAttribute('data-cat'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.querySelectorAll('.cat-link').forEach(l => {
            l.style.color = '#A8A29E';
            l.style.borderBottom = '1px solid transparent';
        });
        this.style.color = '#1C1917';
        this.style.borderBottom = '1px solid #1C1917';
    });
});
</script>
</body>
</html>
