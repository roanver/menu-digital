@extends('layouts.menu')

@push('head')
<style>html,body{background:#0C0A09}body{color:#FAFAF9}</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
    $words = preg_split('/\s+/', trim($restaurant->name));
    $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
    $phBrasa = [
        'radial-gradient(80% 80% at 30% 20%,rgba(245,158,11,.28),transparent 60%),linear-gradient(150deg,#2E2018,#150E09)',
        'radial-gradient(80% 80% at 70% 25%,rgba(220,38,38,.22),transparent 60%),linear-gradient(150deg,#2B1B16,#140D0A)',
        'radial-gradient(80% 80% at 40% 70%,rgba(217,119,6,.24),transparent 60%),linear-gradient(150deg,#292016,#130E08)',
    ];
    $itemIdx = 0;
    $allItems = $categories->flatMap(fn($c) => $c->menuItems);
    $heroItem = $allItems->first();
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:100px;">

    {{-- Header --}}
    <div style="padding:24px 20px 0;position:relative;">
        <div style="position:absolute;inset:0;background:radial-gradient(110% 70% at 50% -8%,rgba(220,90,20,.22),transparent 60%);pointer-events:none;"></div>
        <div style="position:relative;text-align:center;">
            <div style="font-size:12px;font-weight:700;letter-spacing:.34em;text-transform:uppercase;color:#A8A29E;">
                @if($restaurant->address){{ $restaurant->address }}@else Parrilla · a · leña @endif
            </div>
            <div style="font-family:Anton,Inter,sans-serif;font-size:56px;line-height:.94;letter-spacing:.01em;margin-top:10px;background:linear-gradient(175deg,#FAFAF9 40%,#F59E0B 78%,#DC2626 105%);-webkit-background-clip:text;background-clip:text;color:transparent;">{{ mb_strtoupper($restaurant->name) }}</div>
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:12px;">
                <span style="width:44px;height:1px;background:linear-gradient(90deg,transparent,#78350F);display:block;"></span>
                @if($restaurant->phone)
                    <span style="font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#D97706;">{{ $restaurant->phone }}</span>
                @else
                    <span style="font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#D97706;">Desde 1998</span>
                @endif
                <span style="width:44px;height:1px;background:linear-gradient(90deg,#78350F,transparent);display:block;"></span>
            </div>
        </div>

        {{-- Featured hero --}}
        @if($heroItem)
        <div style="position:relative;margin-top:20px;border-radius:20px;overflow:hidden;border:1px solid #292524;">
            @if($heroItem->image)
                <img src="{{ Storage::url($heroItem->image) }}" alt="{{ $heroItem->name }}"
                     loading="eager" decoding="async" width="440" height="172"
                     style="width:100%;height:172px;object-fit:cover;display:block;">
            @else
                <div style="height:172px;background:radial-gradient(85% 85% at 30% 18%,rgba(245,158,11,.32),transparent 58%),linear-gradient(150deg,#2E2018,#150E09);display:flex;align-items:center;justify-content:center;">
                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:64px;color:rgba(245,158,11,.2);">{{ mb_strtoupper(mb_substr($heroItem->name, 0, 1)) }}</span>
                </div>
            @endif
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 26%,rgba(12,10,9,.94) 88%);"></div>
            <div style="position:absolute;left:16px;right:16px;bottom:13px;display:flex;align-items:flex-end;gap:10px;">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:700;letter-spacing:.24em;text-transform:uppercase;color:#F59E0B;">El plato de la casa</div>
                    <div style="font-family:Anton,Inter,sans-serif;font-size:22px;letter-spacing:.015em;margin-top:4px;">{{ mb_strtoupper($heroItem->name) }}</div>
                </div>
                @if($restaurant->show_price)
                    <div style="flex:0 0 auto;background:linear-gradient(120deg,#F59E0B,#DC2626);color:#0C0A09;font-family:Anton,Inter,sans-serif;font-size:14px;letter-spacing:.02em;padding:7px 11px;border-radius:9px;">${{ number_format($heroItem->price, 0, ',', '.') }}</div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sticky category chips --}}
    @if($categories->count() > 0)
        <div class="hsc" style="display:flex;gap:8px;padding:16px 20px 12px;overflow-x:auto;position:sticky;top:0;background:rgba(12,10,9,.88);backdrop-filter:blur(10px);z-index:3;">
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
    <div style="padding:2px 20px 40px;">
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" style="padding-top:22px;">
                <div style="display:flex;align-items:baseline;gap:10px;margin-bottom:6px;">
                    <span style="font-family:Anton,Inter,sans-serif;font-size:21px;letter-spacing:.03em;text-transform:uppercase;">{{ $category->name }}</span>
                    <span style="font-family:Anton,Inter,sans-serif;font-size:13px;color:#44403C;">{{ $category->menuItems->count() }}</span>
                    <span style="flex:1;height:1px;background:#292524;display:block;"></span>
                </div>
                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach($category->menuItems as $item)
                        @php
                            $ph = $phBrasa[$itemIdx % count($phBrasa)];
                            $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                            $itemIdx++;
                        @endphp
                        <div style="display:flex;gap:13px;align-items:center;padding:13px 0;border-bottom:1px solid #1C1917;{{ !$item->is_available ? 'opacity:.55;' : '' }}">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                     loading="lazy" decoding="async" width="52" height="52"
                                     style="width:52px;height:52px;border-radius:12px;flex:0 0 auto;object-fit:cover;border:1px solid #292524;">
                            @else
                                <div style="width:52px;height:52px;border-radius:12px;flex:0 0 auto;background:{{ $ph }};border:1px solid #292524;display:flex;align-items:center;justify-content:center;">
                                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:21px;color:rgba(245,158,11,.35);">{{ $initial }}</span>
                                </div>
                            @endif
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                    <span style="font-size:14px;font-weight:600;letter-spacing:-.01em;">{{ $item->name }}</span>
                                    @if(!$item->is_available)
                                        <span style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:#7F1D1D;color:#FCA5A5;border-radius:4px;padding:2px 6px;">Agotado</span>
                                    @endif
                                </div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:12px;color:#78716C;line-height:1.5;margin-top:3px;">{{ $item->description }}</div>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <div style="font-size:12px;color:#44403C;margin-top:3px;">
                                        @foreach($item->variants as $variant){{ $variant->name }}@if(!$loop->last) · @endif@endforeach
                                    </div>
                                @endif
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex:0 0 auto;">
                                @if($restaurant->show_price)
                                    @if($item->is_promo && $item->promo_price)
                                        <span style="font-size:12px;color:#78716C;text-decoration:line-through;font-family:Anton,Inter,sans-serif;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                        <span style="font-family:Anton,Inter,sans-serif;font-size:15px;letter-spacing:.03em;color:#FCA5A5;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                    @else
                                        <span style="font-family:Anton,Inter,sans-serif;font-size:15px;letter-spacing:.03em;color:#F59E0B;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                    @endif
                                @endif
                                @if($item->is_available && $restaurant->accepts_orders)
                                    @if($item->variants->isNotEmpty())
                                        <button @click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                style="width:28px;height:28px;border-radius:8px;background:linear-gradient(120deg,#F59E0B,#DC2626);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#0C0A09;font-size:18px;font-weight:700;line-height:1;">+</button>
                                    @else
                                        <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }})"
                                                style="width:28px;height:28px;border-radius:8px;background:linear-gradient(120deg,#F59E0B,#DC2626);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#0C0A09;font-size:18px;font-weight:700;line-height:1;">+</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if(($restaurant->plan ?? 'free') === 'free')
        <div style="text-align:center;font-size:12px;color:#44403C;padding-top:28px;letter-spacing:.2em;text-transform:uppercase;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp FAB (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;right:0;padding:0 18px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="width:56px;height:56px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 5px rgba(37,211,102,.14),0 12px 28px rgba(37,211,102,.4);pointer-events:auto;">
            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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

function setActiveChip(el) {
    chipAll && Object.assign(chipAll.style, { background: 'transparent', color: '#78716C', borderColor: '#292524' });
    allChips.forEach(c => Object.assign(c.style, { background: 'transparent', color: '#78716C', borderColor: '#292524' }));
    if (el.classList.contains('chip-all')) {
        Object.assign(el.style, { background: 'linear-gradient(120deg,#F59E0B,#DC2626)', color: '#0C0A09', borderColor: '#F59E0B' });
    } else {
        Object.assign(el.style, { background: 'linear-gradient(120deg,#F59E0B,#DC2626)', color: '#0C0A09', borderColor: '#F59E0B' });
    }
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
