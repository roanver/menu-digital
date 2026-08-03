<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Instrument+Serif:ital@0;1&family=Anton&display=swap" rel="stylesheet">
    <style>
        html,body{margin:0;padding:0;background:#0C0A09;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
        .hsc::-webkit-scrollbar{height:0}
    </style>
</head>
<body style="font-family:Inter,system-ui,sans-serif;color:#FAFAF9;background:#0C0A09;min-height:100vh;">

@php
    $whatsappNum = $restaurant->whatsapp ? preg_replace('/\D/', '', $restaurant->whatsapp) : null;
    $phBrasa = [
        'radial-gradient(80% 80% at 30% 20%,rgba(245,158,11,.28),transparent 60%),linear-gradient(150deg,#2E2018,#150E09)',
        'radial-gradient(80% 80% at 70% 25%,rgba(220,38,38,.22),transparent 60%),linear-gradient(150deg,#2B1B16,#140D0A)',
        'radial-gradient(80% 80% at 40% 70%,rgba(217,119,6,.24),transparent 60%),linear-gradient(150deg,#292016,#130E08)',
    ];
    $itemIdx = 0;
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:100px;">

    {{-- Header --}}
    <div style="padding:24px 20px 0;position:relative;">
        <div style="position:absolute;inset:0;background:radial-gradient(110% 70% at 50% -8%,rgba(220,90,20,.22),transparent 60%);pointer-events:none;"></div>
        <div style="position:relative;text-align:center;">
            <div style="font-size:9.5px;font-weight:700;letter-spacing:.34em;text-transform:uppercase;color:#A8A29E;">
                @if($restaurant->address){{ $restaurant->address }}@else Parrilla · a · Leña@endif
            </div>
            <div style="font-family:Anton,Inter,sans-serif;font-size:58px;line-height:.94;letter-spacing:.01em;margin-top:10px;background:linear-gradient(175deg,#FAFAF9 40%,#F59E0B 78%,#DC2626 105%);-webkit-background-clip:text;background-clip:text;color:transparent;">
                {{ mb_strtoupper($restaurant->name) }}
            </div>
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:12px;">
                <span style="width:44px;height:1px;background:linear-gradient(90deg,transparent,#78350F);display:block;"></span>
                @if($restaurant->phone)
                    <span style="font-size:10px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#D97706;">{{ $restaurant->phone }}</span>
                @else
                    <span style="font-size:10px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#D97706;">Desde 1998</span>
                @endif
                <span style="width:44px;height:1px;background:linear-gradient(90deg,#78350F,transparent);display:block;"></span>
            </div>
        </div>
        @if($restaurant->welcome_message)
            <p style="text-align:center;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:14px;color:#A8A29E;margin:12px 0 0;line-height:1.5;">{{ $restaurant->welcome_message }}</p>
        @endif
    </div>

    {{-- Sticky category chips --}}
    @if($categories->count() > 0)
        <div style="display:flex;gap:8px;padding:16px 20px 12px;overflow-x:auto;position:sticky;top:0;background:rgba(12,10,9,.88);backdrop-filter:blur(10px);z-index:3;" class="hsc">
            <a href="#" class="chip-all"
               style="white-space:nowrap;font-family:Anton,Inter,sans-serif;font-size:12px;letter-spacing:.08em;text-transform:uppercase;padding:7px 13px;border-radius:8px;border:1px solid #F59E0B;color:#0C0A09;background:linear-gradient(120deg,#F59E0B,#DC2626);cursor:pointer;">
                Todo
            </a>
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-chip" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-family:Anton,Inter,sans-serif;font-size:12px;letter-spacing:.08em;text-transform:uppercase;padding:7px 13px;border-radius:8px;border:1px solid #292524;color:#78716C;background:transparent;cursor:pointer;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:2px 20px;">
        @foreach($categories as $si => $category)
            <div id="cat-{{ $category->id }}" style="padding-top:22px;">
                <div style="display:flex;align-items:baseline;gap:10px;margin-bottom:6px;">
                    <span style="font-family:Anton,Inter,sans-serif;font-size:21px;letter-spacing:.03em;text-transform:uppercase;">{{ $category->name }}</span>
                    <span style="font-family:Anton,Inter,sans-serif;font-size:13px;color:#44403C;">/ 0{{ $si + 1 }}</span>
                    <span style="flex:1;height:1px;background:#292524;display:block;"></span>
                </div>
                @foreach($category->menuItems as $item)
                    @php
                        $grad = $phBrasa[$itemIdx % count($phBrasa)];
                        $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                        $itemIdx++;
                    @endphp
                    <div style="display:flex;gap:13px;align-items:center;padding:13px 0;border-bottom:1px solid #1C1917;">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                 style="width:52px;height:52px;border-radius:12px;flex:0 0 auto;object-fit:cover;border:1px solid #292524;">
                        @else
                            <div style="width:52px;height:52px;border-radius:12px;flex:0 0 auto;background:{{ $grad }};border:1px solid #292524;display:flex;align-items:center;justify-content:center;">
                                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:21px;color:rgba(245,158,11,.35);">{{ $initial }}</span>
                            </div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:14px;font-weight:600;letter-spacing:-.01em;">{{ $item->name }}</div>
                            @if($restaurant->show_description && $item->description)
                                <div style="font-size:11.5px;color:#78716C;line-height:1.5;margin-top:3px;">{{ $item->description }}</div>
                            @endif
                            @if($item->variants->isNotEmpty())
                                <div style="font-size:11px;color:#57534E;margin-top:3px;">
                                    @foreach($item->variants as $variant)
                                        {{ $variant->name }}@if(!$loop->last) · @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if($restaurant->show_price)
                            <div style="flex:0 0 auto;font-family:Anton,Inter,sans-serif;font-size:15px;letter-spacing:.03em;color:#F59E0B;">${{ number_format($item->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
        <div style="text-align:center;font-size:9.5px;color:#44403C;padding-top:28px;letter-spacing:.2em;text-transform:uppercase;">Menú creado con MenuDigital</div>
    </div>
</div>

{{-- WhatsApp FAB --}}
@if($whatsappNum)
    <div style="position:fixed;bottom:0;right:0;padding:0 18px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="width:56px;height:56px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 5px rgba(37,211,102,.14),0 12px 28px rgba(37,211,102,.4);pointer-events:auto;">
            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/>
                <path d="M8.6 9.2c.4 2.4 2.4 4.4 4.8 4.9l1-1.4 2 1c-.4 1.4-2 1.9-3.3 1.5a8 8 0 0 1-5.2-5.2c-.4-1.3.1-2.9 1.5-3.3l1 2-1.8.5Z"/>
            </svg>
        </a>
    </div>
@endif

<script>
const allChips = document.querySelectorAll('.cat-chip');
const chipAll = document.querySelector('.chip-all');

function setActive(el) {
    chipAll && Object.assign(chipAll.style, { background: 'transparent', color: '#78716C', borderColor: '#292524' });
    allChips.forEach(c => Object.assign(c.style, { background: 'transparent', color: '#78716C', borderColor: '#292524' }));
    Object.assign(el.style, { background: 'linear-gradient(120deg,#F59E0B,#DC2626)', color: '#0C0A09', borderColor: '#F59E0B' });
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
