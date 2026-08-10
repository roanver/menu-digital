@extends('layouts.menu')

@push('head')
<style>
html,body{background:#FBFAF7}
body{color:#1C1917}
:root{
    --cart-bg:#FBFAF7;
    --cart-text:#1C1917;
    --cart-text-muted:#A8A29E;
    --cart-border:1px solid #E7E5E4;
    --cart-radius:14px;
    --cart-fab-bg:#1C1917;
    --cart-fab-text:#FBFAF7;
    --cart-accent:#25D366;
    --cart-accent-text:#fff;
    --cart-surface:#EDE8E3;
    --cart-chip-bg:#1C1917;
    --cart-chip-text:#FBFAF7;
    --cart-chip-idle-bg:#EDE8E3;
    --cart-chip-idle-text:#78716C;
    --cart-qty-bg:#EDE8E3;
    --cart-qty-active-bg:#1C1917;
    --cart-qty-active-text:#FBFAF7;
    --cart-input-border:#D6D3D1;
    --cart-input-focus:#1C1917;
    --cart-drawer-border-top:none;
}
</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Header --}}
    <div style="padding:34px 26px 26px;text-align:center;">
        @if($restaurant->address)
            <div style="font-size:12px;font-weight:600;letter-spacing:.28em;color:#A8A29E;text-transform:uppercase;">{{ $restaurant->address }}</div>
        @endif
        @if($restaurant->logo)
            <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                 width="72" height="72" loading="eager" decoding="async"
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
            <div style="margin-top:14px;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:#A8A29E;">{{ $restaurant->phone }}</div>
        @endif
        @if($hasHours)
        <div style="position:relative;display:flex;justify-content:center;margin-top:10px;">
            <x-menu-hours :hasHours="$hasHours" :isOpen="$isOpen" :closesAt="$closesAt" :nextOpening="$nextOpening" :weekSchedule="$weekSchedule" :dark="false" />
        </div>
        @endif
    </div>

    {{-- Category nav --}}
    @if($categories->count() > 0)
        <div style="border-top:1px solid #1C1917;border-bottom:1px solid #1C1917;margin:0 26px;padding:10px 0;display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
            @foreach($categories as $i => $category)
                <a href="#cat-{{ $category->id }}" class="cat-link" data-cat="{{ $category->id }}"
                   style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:{{ $i === 0 ? '#1C1917' : '#A8A29E' }};border-bottom:1px solid {{ $i === 0 ? '#1C1917' : 'transparent' }};padding-bottom:2px;">
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
                                    <span style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:#FEE2E2;color:#DC2626;border-radius:4px;padding:2px 6px;white-space:nowrap;flex-shrink:0;">Agotado</span>
                                @endif
                                <span style="flex:1;border-bottom:1px dotted #D6D3D1;transform:translateY(-4px);min-width:14px;display:block;"></span>
                                @if($restaurant->show_price)
                                    @if($item->is_promo && $item->promo_price)
                                        <span style="font-size:12px;color:#A8A29E;text-decoration:line-through;white-space:nowrap;flex-shrink:0;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                        <span style="font-size:13px;font-weight:600;color:#DC2626;font-variant-numeric:tabular-nums;letter-spacing:.02em;white-space:nowrap;flex-shrink:0;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                    @else
                                        <span style="font-size:13px;font-weight:600;font-variant-numeric:tabular-nums;letter-spacing:.02em;white-space:nowrap;flex-shrink:0;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                    @endif
                                @endif
                                @if($item->is_available && $restaurant->accepts_orders)
                                    @php $__img = $item->image ? Storage::url($item->image) : ''; @endphp
                                    @if($item->variants->isNotEmpty())
                                        @php $__vd = json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'image' => $__img, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]); @endphp
                                        <button x-on:click="openVariantModal({{ $__vd }})"
                                                style="width:24px;height:24px;border-radius:6px;background:#1C1917;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;line-height:1;">+</button>
                                    @else
                                        <button x-on:click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }}, '', '{{ $__img }}')"
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
        @if(($restaurant->plan ?? 'free') === 'free')
        <div style="text-align:center;margin-top:14px;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:#D6D3D1;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp pill (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#1C1917;color:#fff;font-size:12px;font-weight:600;letter-spacing:.04em;box-shadow:0 10px 26px rgba(28,25,23,.35);pointer-events:auto;">
            <span style="width:22px;height:22px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            Reservar o pedir por WhatsApp
        </a>
    </div>
    @else
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <div style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#F3F4F6;color:#374151;font-size:12px;font-weight:600;letter-spacing:.04em;pointer-events:auto;">
            Cerrado ahora{{ $nextOpening ? ' · abre ' . $nextOpening : '' }}
        </div>
    </div>
    @endif
@endif

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
@endsection
