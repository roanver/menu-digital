<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Instrument+Serif&display=swap" rel="stylesheet">
    <style>
        html,body{margin:0;padding:0;background:#FFF6DE;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
        .hsc::-webkit-scrollbar{height:0}
        @keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        .marquee-inner{display:inline-block;white-space:nowrap;animation:marquee 18s linear infinite;}
    </style>
</head>
<body style="font-family:'Bricolage Grotesque',Inter,sans-serif;color:#191410;background:#FFF6DE;min-height:100vh;">

@php
    $whatsappNum = $restaurant->whatsapp ? preg_replace('/\D/', '', $restaurant->whatsapp) : null;
    $words = preg_split('/\s+/', trim($restaurant->name));
    $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
    $phFeria = [
        'linear-gradient(140deg,#FFE08A,#FFC24D)',
        'linear-gradient(140deg,#FFC7B3,#FF9A76)',
        'linear-gradient(140deg,#B9E8C6,#7BD096)',
        'linear-gradient(140deg,#C9D7FF,#9FB5F5)',
        'linear-gradient(140deg,#F5CFE0,#E9A3C4)',
        'linear-gradient(140deg,#D6EAD2,#A9D3A0)',
    ];
    $itemIdx = 0;
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Header --}}
    <div style="padding:20px 18px 0;">
        <div style="display:flex;align-items:center;gap:12px;">
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     style="width:58px;height:58px;border-radius:16px;object-fit:cover;border:2px solid #191410;box-shadow:3px 3px 0 #191410;transform:rotate(-4deg);flex:0 0 auto;">
            @else
                <div style="width:58px;height:58px;border-radius:16px;background:#4F46E5;border:2px solid #191410;box-shadow:3px 3px 0 #191410;display:flex;align-items:center;justify-content:center;transform:rotate(-4deg);flex:0 0 auto;">
                    <span style="font-size:19px;font-weight:800;color:#fff;letter-spacing:-.02em;">{{ $initials }}</span>
                </div>
            @endif
            <div style="flex:1;min-width:0;">
                <h1 style="margin:0;font-size:29px;font-weight:800;letter-spacing:-.03em;line-height:1;">{{ $restaurant->name }}</h1>
                <div style="display:flex;align-items:center;gap:6px;margin-top:6px;flex-wrap:wrap;">
                    <span style="font-size:10.5px;font-weight:700;color:#191410;background:#7BE29B;border:1.5px solid #191410;border-radius:999px;padding:3px 9px;">Abierto</span>
                    @if($restaurant->phone)
                        <span style="font-size:10.5px;font-weight:700;color:#191410;background:#fff;border:1.5px solid #191410;border-radius:999px;padding:3px 9px;">{{ $restaurant->phone }}</span>
                    @endif
                </div>
            </div>
        </div>
        @if($restaurant->welcome_message)
            <p style="margin:12px 0 0;font-size:12.5px;font-weight:500;color:#8A8073;line-height:1.5;">{{ $restaurant->welcome_message }}</p>
        @endif
    </div>

    {{-- Marquee banner --}}
    <div style="margin:16px 0 0;background:#191410;color:#FFF6DE;padding:7px 0;overflow:hidden;">
        <div class="marquee-inner">
            <span style="font-size:10.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;">
                &nbsp;✶ Pedidos al tiro por WhatsApp ✶ Sin comisiones ✶ {{ $restaurant->name }} ✶ Pedidos al tiro por WhatsApp ✶ Sin comisiones ✶ {{ $restaurant->name }} ✶&nbsp;
            </span>
        </div>
    </div>

    {{-- Sticky category chips --}}
    @if($categories->count() > 0)
        <div style="display:flex;gap:8px;padding:14px 18px 12px;overflow-x:auto;position:sticky;top:0;background:#FFF6DE;z-index:3;" class="hsc">
            <a href="#" class="chip-all"
               style="white-space:nowrap;font-size:11.5px;font-weight:800;padding:7px 13px;border-radius:999px;border:1.5px solid #191410;color:#FFD84D;background:#191410;box-shadow:2px 2px 0 rgba(25,20,16,.3);cursor:pointer;">
                Todo
            </a>
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-chip" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-size:11.5px;font-weight:800;padding:7px 13px;border-radius:999px;border:1.5px solid #191410;color:#191410;background:#fff;cursor:pointer;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:0 18px;">
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" style="padding-top:16px;">
                <div style="display:inline-block;margin-bottom:12px;position:relative;">
                    <span style="position:relative;z-index:1;font-size:17px;font-weight:800;letter-spacing:-.02em;">{{ $category->name }}</span>
                    <span style="position:absolute;left:-3px;right:-6px;bottom:0;height:9px;background:#FFD84D;z-index:0;transform:rotate(-.6deg);display:block;"></span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    @foreach($category->menuItems as $item)
                        @php
                            $grad = $phFeria[$itemIdx % count($phFeria)];
                            $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                            $itemIdx++;
                        @endphp
                        <div style="background:#fff;border:1.5px solid #191410;border-radius:16px;overflow:hidden;box-shadow:3px 3px 0 #191410;display:flex;flex-direction:column;">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                     style="height:92px;width:100%;object-fit:cover;border-bottom:1.5px solid #191410;">
                            @else
                                <div style="height:92px;background:{{ $grad }};border-bottom:1.5px solid #191410;display:flex;align-items:center;justify-content:center;">
                                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:36px;color:rgba(25,20,16,.22);">{{ $initial }}</span>
                                </div>
                            @endif
                            <div style="padding:10px 11px 11px;display:flex;flex-direction:column;gap:4px;flex:1;">
                                <div style="font-size:12.5px;font-weight:800;line-height:1.25;letter-spacing:-.015em;">{{ $item->name }}</div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:10.5px;font-weight:500;color:#8A8073;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $item->description }}</div>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <div style="font-size:10px;font-weight:600;color:#A89880;margin-top:2px;">
                                        @foreach($item->variants as $variant)
                                            {{ $variant->name }}@if(!$loop->last) · @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div style="flex:1;"></div>
                                <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                                    @if($restaurant->show_price)
                                        <span style="flex:1;min-width:0;">
                                            <span style="display:inline-block;font-size:11.5px;font-weight:800;background:#FFD84D;border:1.5px solid #191410;border-radius:7px;padding:3px 7px;transform:rotate(-2deg);">${{ number_format($item->price, 0, ',', '.') }}</span>
                                        </span>
                                    @endif
                                    @if($whatsappNum)
                                        <a href="https://wa.me/{{ $whatsappNum }}?text={{ urlencode('Hola, quiero pedir: ' . $item->name) }}" target="_blank" rel="noopener"
                                           style="width:27px;height:27px;border-radius:9px;background:#191410;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#FFD84D" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <div style="text-align:center;font-size:10px;font-weight:700;color:#C9B98F;padding-top:26px;letter-spacing:.1em;text-transform:uppercase;">Menú creado con MenuDigital</div>
    </div>
</div>

{{-- WhatsApp FAB + cart bar --}}
<div style="position:fixed;bottom:0;left:0;right:0;display:flex;align-items:center;gap:10px;padding:0 16px 18px;pointer-events:none;z-index:10;max-width:440px;margin:0 auto;">
    @if($whatsappNum)
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="flex:1;display:flex;align-items:center;gap:11px;padding:12px 15px;border-radius:14px;background:#191410;color:#FFF6DE;border:1.5px solid #191410;box-shadow:3px 3px 0 rgba(25,20,16,.25);pointer-events:auto;">
            <span style="width:22px;height:22px;border-radius:7px;background:#FFD84D;color:#191410;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;flex:0 0 auto;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#191410" stroke-width="2.4" stroke-linecap="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            <span style="flex:1;min-width:0;font-size:13px;font-weight:800;">Pedir por WhatsApp</span>
        </a>
    @endif
</div>

<script>
const allChips = document.querySelectorAll('.cat-chip');
const chipAll = document.querySelector('.chip-all');

function setActive(el) {
    chipAll && Object.assign(chipAll.style, { background: '#fff', color: '#191410' });
    allChips.forEach(c => Object.assign(c.style, { background: '#fff', color: '#191410' }));
    Object.assign(el.style, { background: '#191410', color: '#FFD84D', boxShadow: '2px 2px 0 rgba(25,20,16,.3)' });
}

allChips.forEach(chip => {
    chip.addEventListener('click', function(e) {
        e.preventDefault();
        const anchor = document.getElementById('cat-' + this.getAttribute('data-cat'));
        if (anchor) anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setActive(this);
    });
});

chipAll && chipAll.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setActive(this);
});
</script>
</body>
</html>
