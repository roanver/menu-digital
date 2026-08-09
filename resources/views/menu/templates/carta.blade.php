@extends('layouts.menu')

@push('head')
<style>html,body{background:#F6F1E7}body{color:#211A12}</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
    $roman = ['I','II','III','IV','V','VI','VII','VIII','IX','X'];
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:120px;">

    {{-- Frame --}}
    <div style="margin:16px 16px 0;border:1px solid #211A12;position:relative;">
        <div style="position:absolute;inset:5px;border:1px solid rgba(33,26,18,.22);pointer-events:none;"></div>

        {{-- Header --}}
        <div style="padding:34px 24px 24px;text-align:center;">
            <div style="font-size:12px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:#8A7B66;">
                @if($restaurant->address){{ $restaurant->address }}@else Carta · Temporada @endif
            </div>

            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     width="68" height="68" loading="eager" decoding="async"
                     style="width:68px;height:68px;border-radius:50%;object-fit:cover;margin:16px auto 0;display:block;border:1px solid rgba(33,26,18,.2);">
            @endif

            <h1 style="margin:16px 0 0;font-family:'Instrument Serif',Georgia,serif;font-size:52px;font-weight:400;line-height:.95;letter-spacing:-.015em;">{{ $restaurant->name }}</h1>

            @if($restaurant->welcome_message)
                <p style="margin:15px 0 0;font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:15px;color:#8A7B66;line-height:1.5;">{{ $restaurant->welcome_message }}</p>
            @endif

            <div style="display:flex;align-items:center;justify-content:center;gap:9px;margin-top:17px;">
                <span style="width:30px;height:1px;background:#211A12;display:block;"></span>
                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:15px;line-height:1;">❦</span>
                <span style="width:30px;height:1px;background:#211A12;display:block;"></span>
            </div>

            @if($restaurant->phone)
                <div style="margin-top:13px;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:#8A7B66;">{{ $restaurant->phone }}</div>
            @endif
        </div>

        {{-- Category nav --}}
        @if($categories->count() > 0)
            <div style="border-top:1px solid rgba(33,26,18,.25);border-bottom:1px solid rgba(33,26,18,.25);padding:10px 0;display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
                @foreach($categories as $i => $category)
                    <a href="#cat-{{ $category->id }}" class="cat-link" data-cat="{{ $category->id }}"
                       style="font-size:12px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;color:{{ $i === 0 ? '#211A12' : '#8A7B66' }};border-bottom:1px solid {{ $i === 0 ? '#211A12' : 'transparent' }};padding-bottom:2px;">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Menu sections --}}
        <div style="padding:0 24px 26px;">
            @foreach($categories as $si => $category)
                <div id="cat-{{ $category->id }}" style="padding-top:24px;">
                    <div style="text-align:center;">
                        <div style="font-size:12px;font-weight:600;letter-spacing:.26em;color:#8A7B66;">{{ $roman[$si] ?? ('/ 0'.($si+1)) }}</div>
                        <div style="font-family:'Instrument Serif',Georgia,serif;font-size:25px;margin-top:3px;letter-spacing:-.01em;">{{ $category->name }}</div>
                        <div style="width:22px;height:1px;background:#211A12;margin:9px auto 0;"></div>
                    </div>
                    <div style="margin-top:16px;display:flex;flex-direction:column;gap:18px;">
                        @foreach($category->menuItems as $item)
                            <div style="text-align:center;{{ !$item->is_available ? 'opacity:.55;' : '' }}">
                                <div style="font-family:'Instrument Serif',Georgia,serif;font-size:18.5px;line-height:1.25;">{{ $item->name }}</div>
                                @if(!$item->is_available)
                                    <div style="display:inline-block;margin-top:3px;font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;background:#FEE2E2;color:#DC2626;border-radius:4px;padding:2px 7px;">Agotado</div>
                                @endif
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:13px;color:#8A7B66;line-height:1.5;margin-top:2px;">{{ $item->description }}</div>
                                @endif
                                @if($restaurant->show_price || $item->variants->isNotEmpty() || ($item->is_available && $restaurant->accepts_orders))
                                    <div style="display:inline-flex;align-items:center;gap:7px;margin-top:6px;flex-wrap:wrap;justify-content:center;">
                                        @if($restaurant->show_price)
                                            @if($item->is_promo && $item->promo_price)
                                                <span style="font-size:12px;color:#8A7B66;text-decoration:line-through;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                                <span style="font-size:12px;font-weight:600;letter-spacing:.14em;color:#DC2626;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                            @else
                                                <span style="font-size:12px;font-weight:600;letter-spacing:.14em;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        @endif
                                        @foreach($item->variants as $variant)
                                            <span style="font-size:12px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#F6F1E7;background:#211A12;padding:2.5px 7px;">{{ $variant->name }}@if($variant->price_delta != 0) +${{ number_format($variant->price_delta, 0, ',', '.') }}@endif</span>
                                        @endforeach
                                        @if($item->is_available && $restaurant->accepts_orders)
                                            @if($item->variants->isNotEmpty())
                                                <button @click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                        style="width:24px;height:24px;border-radius:6px;background:#211A12;color:#F6F1E7;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-size:16px;line-height:1;">+</button>
                                            @else
                                                <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }})"
                                                        style="width:24px;height:24px;border-radius:6px;background:#211A12;color:#F6F1E7;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-size:16px;line-height:1;">+</button>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div style="text-align:center;margin-top:32px;padding-bottom:6px;">
                <div style="font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:#8A7B66;line-height:2;">
                    @if($restaurant->address){{ $restaurant->address }}<br>@endif
                    @if($restaurant->phone){{ $restaurant->phone }}@endif
                </div>
            </div>
        </div>
    </div>

    @if(($restaurant->plan ?? 'free') === 'free')
    <div style="text-align:center;font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:#C9BCA6;padding:18px 0;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
    @endif
</div>

{{-- WhatsApp pill (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#211A12;color:#F6F1E7;font-size:12px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;box-shadow:0 10px 26px rgba(33,26,18,.4);pointer-events:auto;">
            <span style="width:20px;height:20px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            Reservas por WhatsApp
        </a>
    </div>
    @else
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <div style="display:inline-flex;align-items:center;gap:9px;padding:12px 20px;border-radius:999px;background:#F3F4F6;color:#374151;font-size:12px;font-weight:600;letter-spacing:.04em;pointer-events:auto;">
            Cerrado ahora{{ $nextOpening ? ' · abre el ' . $nextOpening : '' }}
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
            l.style.color = '#8A7B66';
            l.style.borderBottom = '1px solid transparent';
        });
        this.style.color = '#211A12';
        this.style.borderBottom = '1px solid #211A12';
    });
});
</script>
@endsection
