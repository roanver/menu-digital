@extends('layouts.menu')

@push('head')
<style>html,body{background:#F7F8FA}body{color:#111827}</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
    $words = preg_split('/\s+/', trim($restaurant->name));
    $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
    $ph = [
        'linear-gradient(140deg,#F3E7D8,#E4C9A8)',
        'linear-gradient(140deg,#F0E0DA,#DCB4A6)',
        'linear-gradient(140deg,#E9EBDD,#C8D0AC)',
        'linear-gradient(140deg,#EFE6D4,#D8C89E)',
        'linear-gradient(140deg,#EAE3DF,#CCB9B0)',
        'linear-gradient(140deg,#E7E9E4,#BFC8C0)',
    ];
    $itemIdx = 0;
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Hero --}}
    <div style="height:148px;background:linear-gradient(130deg,#4F46E5 0%,#4338CA 46%,#312E81 100%);position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(90% 90% at 85% -10%,rgba(255,255,255,.2),transparent 58%);"></div>
        <div style="position:absolute;left:18px;bottom:14px;right:18px;">
            @if($restaurant->welcome_message)
                <div style="font-family:'Instrument Serif',Georgia,serif;font-size:15px;font-style:italic;color:rgba(255,255,255,.85);">{{ $restaurant->welcome_message }}</div>
            @else
                <div style="font-family:'Instrument Serif',Georgia,serif;font-size:15px;font-style:italic;color:rgba(255,255,255,.85);">{{ $restaurant->name }}</div>
            @endif
        </div>
    </div>

    {{-- Restaurant info bar --}}
    <div style="background:#fff;padding:0 18px 15px;border-bottom:1px solid #EEF0F4;position:relative;">
        <div style="display:flex;align-items:flex-end;gap:12px;transform:translateY(-22px);margin-bottom:-10px;">
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     width="64" height="64" loading="eager" decoding="async"
                     style="width:64px;height:64px;border-radius:18px;object-fit:cover;border:1px solid #E0E7FF;box-shadow:0 6px 18px rgba(16,24,40,.14);flex:0 0 auto;background:#fff;">
            @else
                <div style="width:64px;height:64px;border-radius:18px;background:#fff;padding:5px;box-shadow:0 6px 18px rgba(16,24,40,.14);flex:0 0 auto;">
                    <div style="width:100%;height:100%;border-radius:13px;background:#EEF2FF;border:1px solid #E0E7FF;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#4F46E5;">{{ $initials }}</div>
                </div>
            @endif
            <div style="flex:1;min-width:0;padding-bottom:6px;">
                <h1 style="margin:0;font-size:21px;font-weight:700;letter-spacing:-.025em;">{{ $restaurant->name }}</h1>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap;">
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#047857;background:#ECFDF5;border:1px solid #A7F3D0;border-radius:999px;padding:4px 9px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#10B981;display:block;"></span>
                Abierto
            </span>
            @if($restaurant->phone)
                <span style="font-size:12px;font-weight:600;color:#4B5563;background:#F3F4F6;border-radius:999px;padding:4px 9px;">{{ $restaurant->phone }}</span>
            @endif
            @if($restaurant->address)
                <span style="font-size:12px;font-weight:600;color:#4B5563;background:#F3F4F6;border-radius:999px;padding:4px 9px;">{{ $restaurant->address }}</span>
            @endif
        </div>
    </div>

    {{-- Sticky category chips --}}
    @if($categories->count() > 0)
        <div style="display:flex;gap:7px;padding:12px 18px;overflow-x:auto;position:sticky;top:0;background:rgba(247,248,250,.92);backdrop-filter:blur(10px);z-index:3;" class="hsc">
            <a href="#" class="chip-all"
               style="white-space:nowrap;font-size:12px;font-weight:600;padding:7px 13px;border-radius:999px;border:1px solid #4338CA;color:#fff;background:#4F46E5;box-shadow:0 1px 2px rgba(16,24,40,.04);cursor:pointer;">
                Todo
            </a>
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-chip" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-size:12px;font-weight:600;padding:7px 13px;border-radius:999px;border:1px solid #E5E7EB;color:#4B5563;background:#fff;box-shadow:0 1px 2px rgba(16,24,40,.04);cursor:pointer;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:0 18px;">
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" style="padding-top:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:11px;">
                    <span style="font-size:15px;font-weight:700;letter-spacing:-.015em;">{{ $category->name }}</span>
                    <span style="font-size:12px;font-weight:700;color:#6B7280;background:#EDEEF2;border-radius:999px;padding:2px 8px;">{{ $category->menuItems->count() }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:11px;">
                    @foreach($category->menuItems as $item)
                        @php
                            $grad = $ph[$itemIdx % count($ph)];
                            $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                            $itemIdx++;
                        @endphp
                        <div style="background:#fff;border:1px solid #EAECF0;border-radius:17px;overflow:hidden;box-shadow:0 2px 8px rgba(16,24,40,.05);display:flex;flex-direction:column;{{ !$item->is_available ? 'opacity:.6;' : '' }}">
                            <div style="position:relative;">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                         loading="lazy" decoding="async" width="200" height="96"
                                         style="height:96px;width:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="height:96px;background:{{ $grad }};display:flex;align-items:center;justify-content:center;">
                                        <span style="font-family:'Instrument Serif',Georgia,serif;font-size:34px;color:rgba(70,50,30,.18);">{{ $initial }}</span>
                                    </div>
                                @endif
                                @if(!$item->is_available)
                                    <div style="position:absolute;top:6px;left:6px;background:#DC2626;color:#fff;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px;border-radius:5px;">Agotado</div>
                                @endif
                                @if($item->is_promo && $item->promo_price)
                                    <div style="position:absolute;top:6px;right:6px;background:#F59E0B;color:#fff;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px;border-radius:5px;">Promo</div>
                                @endif
                            </div>
                            <div style="padding:10px 11px 11px;display:flex;flex-direction:column;gap:4px;flex:1;">
                                <div style="font-size:12.5px;font-weight:600;line-height:1.3;letter-spacing:-.01em;">{{ $item->name }}</div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:12px;color:#9CA3AF;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $item->description }}</div>
                                @endif
                                <div style="flex:1;"></div>
                                <div style="display:flex;align-items:center;gap:8px;margin-top:5px;">
                                    @if($restaurant->show_price)
                                        <span style="flex:1;min-width:0;">
                                            @if($item->is_promo && $item->promo_price)
                                                <span style="font-size:12px;color:#9CA3AF;text-decoration:line-through;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                                <span style="font-size:13.5px;font-weight:700;color:#DC2626;font-variant-numeric:tabular-nums;display:block;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                            @else
                                                <span style="font-size:13.5px;font-weight:700;color:#111827;font-variant-numeric:tabular-nums;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    @if($item->is_available && $restaurant->accepts_orders)
                                        @if($item->variants->isNotEmpty())
                                            <button x-on:click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                    style="width:27px;height:27px;border-radius:9px;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex:0 0 auto;box-shadow:0 2px 6px rgba(79,70,229,.4);border:none;cursor:pointer;color:#fff;font-size:18px;line-height:1;">+</button>
                                        @else
                                            <button x-on:click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }})"
                                                    style="width:27px;height:27px;border-radius:9px;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex:0 0 auto;box-shadow:0 2px 6px rgba(79,70,229,.4);border:none;cursor:pointer;color:#fff;font-size:18px;line-height:1;">+</button>
                                        @endif
                                    @elseif(!$item->is_available)
                                        {{-- no button --}}
                                    @elseif($whatsappNum)
                                        <a href="https://wa.me/{{ $whatsappNum }}?text={{ urlencode('Hola, quiero pedir: ' . $item->name) }}" target="_blank" rel="noopener"
                                           style="width:27px;height:27px;border-radius:9px;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex:0 0 auto;box-shadow:0 2px 6px rgba(79,70,229,.4);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if(($restaurant->plan ?? 'free') === 'free')
        <div style="text-align:center;font-size:12px;color:#C3C8D2;padding-top:26px;letter-spacing:.06em;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp FAB (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;right:0;padding:0 18px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="width:50px;height:50px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(37,211,102,.42);pointer-events:auto;">
            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/>
                <path d="M8.6 9.2c.4 2.4 2.4 4.4 4.8 4.9l1-1.4 2 1c-.4 1.4-2 1.9-3.3 1.5a8 8 0 0 1-5.2-5.2c-.4-1.3.1-2.9 1.5-3.3l1 2-1.8.5Z"/>
            </svg>
        </a>
    </div>
    @else
    <div style="position:fixed;bottom:0;right:0;padding:0 18px 20px;z-index:10;">
        <div style="padding:8px 14px;border-radius:999px;background:rgba(0,0,0,.7);color:#fff;font-size:12px;font-weight:600;">
            Cerrado{{ $nextOpening ? ' · ' . $nextOpening : '' }}
        </div>
    </div>
    @endif
@endif

<script>
const allChips = document.querySelectorAll('.cat-chip');
const chipAll = document.querySelector('.chip-all');

function setActive(el) {
    chipAll && Object.assign(chipAll.style, { background: '#fff', color: '#4B5563', borderColor: '#E5E7EB' });
    allChips.forEach(c => Object.assign(c.style, { background: '#fff', color: '#4B5563', borderColor: '#E5E7EB' }));
    Object.assign(el.style, { background: '#4F46E5', color: '#fff', borderColor: '#4338CA' });
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
@endsection
