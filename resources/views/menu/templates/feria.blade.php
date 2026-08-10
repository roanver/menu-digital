@extends('layouts.menu')

@push('head')
<style>
html,body{background:#FFF6DE}
body{font-family:'Bricolage Grotesque',Inter,system-ui,sans-serif;color:#191410}
@keyframes md-marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}
:root{
    --cart-bg:#FFF6DE;
    --cart-text:#191410;
    --cart-text-muted:#5A4F3F;
    --cart-border:2px solid #191410;
    --cart-radius:14px;
    --cart-fab-bg:#191410;
    --cart-fab-text:#FFF6DE;
    --cart-accent:#25D366;
    --cart-accent-text:#fff;
    --cart-surface:#FFE08A;
    --cart-chip-bg:#191410;
    --cart-chip-text:#FFF6DE;
    --cart-chip-idle-bg:#FFE08A;
    --cart-chip-idle-text:#191410;
    --cart-qty-bg:#FFE08A;
    --cart-qty-active-bg:#191410;
    --cart-qty-active-text:#FFF6DE;
    --cart-input-border:#191410;
    --cart-input-focus:#191410;
    --cart-drawer-border-top:3px solid #191410;
}
</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
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
    <div style="padding:18px 18px 0;">
        <div style="display:flex;align-items:center;gap:12px;">
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     width="58" height="58" loading="eager" decoding="async"
                     style="width:58px;height:58px;border-radius:16px;object-fit:cover;border:2px solid #191410;box-shadow:3px 3px 0 #191410;flex:0 0 auto;transform:rotate(-4deg);">
            @else
                <div style="width:58px;height:58px;border-radius:16px;background:#E85D2F;border:2px solid #191410;box-shadow:3px 3px 0 #191410;display:flex;align-items:center;justify-content:center;transform:rotate(-4deg);flex:0 0 auto;">
                    <span style="font-size:19px;font-weight:800;color:#fff;letter-spacing:-.02em;">{{ $initials }}</span>
                </div>
            @endif
            <div style="flex:1;min-width:0;">
                <h1 style="margin:0;font-size:28px;font-weight:800;letter-spacing:-.03em;line-height:1;">{{ $restaurant->name }}</h1>
                <div style="display:flex;align-items:center;gap:6px;margin-top:6px;flex-wrap:wrap;">
                    @if($restaurant->welcome_message)
                        <span style="font-size:12px;font-weight:700;color:#191410;background:#7BE29B;border:1.5px solid #191410;border-radius:999px;padding:3px 9px;">{{ $restaurant->welcome_message }}</span>
                    @endif
                    @if($hasHours)
                    <div style="position:relative;">
                        <x-menu-hours :hasHours="$hasHours" :isOpen="$isOpen" :closesAt="$closesAt" :nextOpening="$nextOpening" :weekSchedule="$weekSchedule" :dark="false" />
                    </div>
                    @endif
                    @if($restaurant->phone)
                        <span style="font-size:12px;font-weight:700;color:#191410;background:#fff;border:1.5px solid #191410;border-radius:999px;padding:3px 9px;">{{ $restaurant->phone }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Marquee --}}
    <div style="margin:16px 0 0;background:#191410;color:#FFF6DE;padding:8px 0;overflow:hidden;white-space:nowrap;">
        <div style="display:inline-block;animation:md-marquee 18s linear infinite;">
            @php
                $marqueeText = '&nbsp;✶ Pedidos por WhatsApp ✶ Sin comisiones ✶ ';
                if ($restaurant->address) $marqueeText .= htmlspecialchars($restaurant->address) . ' ✶ ';
                $marqueeText = str_repeat($marqueeText, 6);
            @endphp
            <span style="font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;">{!! $marqueeText !!}</span>
        </div>
    </div>

    {{-- Category chips --}}
    @if($categories->count() > 0)
        <div class="hsc" style="display:flex;gap:8px;padding:14px 18px 12px;overflow-x:auto;position:sticky;top:0;background:#FFF6DE;z-index:3;">
            <a href="#" class="chip-all"
               style="white-space:nowrap;font-size:12px;font-weight:800;padding:7px 13px;border-radius:999px;border:1.5px solid #191410;color:#FFD84D;background:#191410;box-shadow:2px 2px 0 rgba(25,20,16,.3);cursor:pointer;">
                Todo
            </a>
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-chip" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-size:12px;font-weight:800;padding:7px 13px;border-radius:999px;border:1.5px solid #191410;color:#191410;background:#fff;cursor:pointer;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:0 18px 40px;">
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" style="padding-top:18px;">
                <div style="display:inline-block;margin-bottom:13px;position:relative;">
                    <span style="position:relative;z-index:1;font-size:17px;font-weight:800;letter-spacing:-.02em;">{{ $category->name }}</span>
                    <span style="position:absolute;left:-3px;right:-6px;bottom:0;height:9px;background:#FFD84D;z-index:0;transform:rotate(-.6deg);display:block;"></span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    @foreach($category->menuItems as $item)
                        @php
                            $ph = $phFeria[$itemIdx % count($phFeria)];
                            $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                            $itemIdx++;
                        @endphp
                        <div style="background:#fff;border:1.5px solid #191410;border-radius:16px;overflow:hidden;box-shadow:3px 3px 0 #191410;display:flex;flex-direction:column;{{ !$item->is_available ? 'opacity:.6;' : '' }}">
                            <div style="position:relative;">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                         loading="lazy" decoding="async" width="200" height="92"
                                         style="height:92px;width:100%;object-fit:cover;border-bottom:1.5px solid #191410;display:block;">
                                @else
                                    <div style="height:92px;background:{{ $ph }};border-bottom:1.5px solid #191410;display:flex;align-items:center;justify-content:center;position:relative;">
                                        <span style="font-family:'Instrument Serif',Georgia,serif;font-size:36px;color:rgba(25,20,16,.18);">{{ $initial }}</span>
                                    </div>
                                @endif
                                @if(!$item->is_available)
                                    <div style="position:absolute;top:6px;left:6px;background:#191410;color:#FFF6DE;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px;border-radius:5px;">Agotado</div>
                                @endif
                                @if($item->is_promo && $item->promo_price)
                                    <div style="position:absolute;top:6px;right:6px;background:#E85D2F;color:#fff;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px;border-radius:5px;">Promo</div>
                                @endif
                            </div>
                            <div style="padding:10px 11px 11px;display:flex;flex-direction:column;gap:4px;flex:1;">
                                <div style="font-size:12.5px;font-weight:800;line-height:1.25;letter-spacing:-.015em;">{{ $item->name }}</div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:12px;font-weight:500;color:#8A8073;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $item->description }}</div>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <div style="font-size:11px;font-weight:700;color:#8A8073;margin-top:3px;">
                                        @foreach($item->variants as $variant){{ $variant->name }}@if(!$loop->last) · @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div style="flex:1;"></div>
                                <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                                    @if($restaurant->show_price)
                                        <span style="flex:1;min-width:0;">
                                            @if($item->is_promo && $item->promo_price)
                                                <span style="display:block;font-size:12px;font-weight:700;color:#8A8073;text-decoration:line-through;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                                <span style="display:inline-block;font-size:12px;font-weight:800;background:#E85D2F;color:#fff;border:1.5px solid #191410;border-radius:7px;padding:3px 7px;transform:rotate(-2deg);">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                            @else
                                                <span style="display:inline-block;font-size:12px;font-weight:800;background:#FFD84D;border:1.5px solid #191410;border-radius:7px;padding:3px 7px;transform:rotate(-2deg);">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    @if($item->is_available && $restaurant->accepts_orders)
                                        @php $__img = $item->image ? Storage::url($item->image) : ''; @endphp
                                        @if($item->variants->isNotEmpty())
                                            @php $__vd = json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'image' => $__img, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]); @endphp
                                            <button x-on:click="openVariantModal({{ $__vd }})"
                                                    style="width:27px;height:27px;border-radius:9px;background:#191410;display:flex;align-items:center;justify-content:center;flex:0 0 auto;border:none;cursor:pointer;color:#FFD84D;font-size:18px;font-weight:800;line-height:1;">+</button>
                                        @else
                                            <button x-on:click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }}, '', '{{ $__img }}')"
                                                    style="width:27px;height:27px;border-radius:9px;background:#191410;display:flex;align-items:center;justify-content:center;flex:0 0 auto;border:none;cursor:pointer;color:#FFD84D;font-size:18px;font-weight:800;line-height:1;">+</button>
                                        @endif
                                    @elseif(!$item->is_available)
                                        {{-- no button --}}
                                    @elseif($whatsappNum)
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
        @if(($restaurant->plan ?? 'free') === 'free')
        <div style="text-align:center;font-size:12px;font-weight:700;color:#C9B98F;padding-top:26px;letter-spacing:.1em;text-transform:uppercase;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp FAB (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;align-items:center;gap:10px;padding:0 16px 18px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="flex:1;display:flex;align-items:center;gap:11px;padding:12px 15px;border-radius:14px;background:#191410;color:#FFF6DE;border:1.5px solid #191410;box-shadow:3px 3px 0 rgba(25,20,16,.25);pointer-events:auto;">
            <span style="width:32px;height:32px;border-radius:10px;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/>
                    <path d="M8.6 9.2c.4 2.4 2.4 4.8 4.9l1-1.4 2 1c-.4 1.4-2 1.9-3.3 1.5a8 8 0 0 1-5.2-5.2c-.4-1.3.1-2.9 1.5-3.3l1 2-1.8.5Z"/>
                </svg>
            </span>
            <span style="flex:1;min-width:0;font-size:13px;font-weight:800;">Pedir por WhatsApp</span>
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

function setActiveChip(el) {
    chipAll && Object.assign(chipAll.style, { background: '#fff', color: '#191410', boxShadow: 'none' });
    allChips.forEach(c => Object.assign(c.style, { background: '#fff', color: '#191410', boxShadow: 'none' }));
    Object.assign(el.style, { background: '#191410', color: '#FFD84D', boxShadow: '2px 2px 0 rgba(25,20,16,.3)' });
}

allChips.forEach(chip => {
    chip.addEventListener('click', function(e) {
        e.preventDefault();
        const anchor = document.getElementById('cat-' + this.getAttribute('data-cat'));
        if (anchor) anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setActiveChip(this);
    });
});

chipAll && chipAll.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setActiveChip(this);
});
</script>
@endsection
