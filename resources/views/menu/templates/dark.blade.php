@extends('layouts.menu')

@push('head')
<style>html,body{background:#09090E}body{color:#F4F4F5}</style>
@endpush

@section('body')
@php
    $whatsappNum = $whatsappNum ?? null;
    $words = preg_split('/\s+/', trim($restaurant->name));
    $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
    $phDark = [
        'linear-gradient(140deg,#2E2620,#1D1712)',
        'linear-gradient(140deg,#2E2124,#1D1315)',
        'linear-gradient(140deg,#242A20,#151A12)',
        'linear-gradient(140deg,#2B2820,#1B1810)',
        'linear-gradient(140deg,#292325,#181315)',
        'linear-gradient(140deg,#222826,#141917)',
    ];
    $itemIdx = 0;
@endphp

<div style="max-width:440px;margin:0 auto;padding-bottom:100px;">

    {{-- Header --}}
    <div style="padding:22px 20px 4px;background:radial-gradient(130% 80% at 15% 0%,rgba(99,102,241,.22),transparent 58%);">
        <div style="display:flex;align-items:center;gap:13px;">
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                     width="54" height="54" loading="eager" decoding="async"
                     style="width:54px;height:54px;border-radius:17px;object-fit:cover;border:1px solid rgba(165,180,252,.28);flex:0 0 auto;">
            @else
                <div style="width:54px;height:54px;border-radius:17px;background:linear-gradient(140deg,#312E81,#1E1B4B);border:1px solid rgba(165,180,252,.28);display:flex;align-items:center;justify-content:center;font-family:'Instrument Serif',Georgia,serif;font-size:21px;color:#C7D2FE;flex:0 0 auto;">{{ $initials }}</div>
            @endif
            <div style="flex:1;min-width:0;">
                <h1 style="margin:0;font-size:24px;font-weight:700;letter-spacing:-.025em;">{{ $restaurant->name }}</h1>
                <div style="display:flex;align-items:center;gap:8px;margin-top:5px;flex-wrap:wrap;">
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#6EE7B7;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#34D399;box-shadow:0 0 8px #34D399;display:block;"></span>
                        Abierto
                    </span>
                    @if($restaurant->address)
                        <span style="font-size:12px;color:#565664;">·</span>
                        <span style="font-size:12px;color:#8A8A99;">{{ $restaurant->address }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Featured carousel --}}
        @php $featuredItems = $categories->flatMap(fn($c) => $c->menuItems)->take(4); @endphp
        @if($featuredItems->isNotEmpty())
        <div style="margin-top:18px;">
            <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:10px;">
                <span style="font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#A5B4FC;">Destacados</span>
                <span style="flex:1;height:1px;background:linear-gradient(90deg,rgba(165,180,252,.3),transparent);display:block;"></span>
            </div>
            <div class="hsc" style="display:flex;gap:11px;overflow-x:auto;margin:0 -20px;padding:0 20px 6px;">
                @foreach($featuredItems as $fi => $feat)
                    @php $phF = $phDark[$fi % count($phDark)]; $finitial = mb_strtoupper(mb_substr($feat->name, 0, 1)); @endphp
                    <div style="flex:0 0 218px;border-radius:18px;overflow:hidden;position:relative;border:1px solid #26262F;">
                        @if($feat->image)
                            <img src="{{ Storage::url($feat->image) }}" alt="{{ $feat->name }}"
                                 loading="lazy" decoding="async" width="218" height="128"
                                 style="width:100%;height:128px;object-fit:cover;display:block;">
                        @else
                            <div style="height:128px;background:{{ $phF }};display:flex;align-items:center;justify-content:center;">
                                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:40px;color:rgba(255,255,255,.22);">{{ $finitial }}</span>
                            </div>
                        @endif
                        <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 30%,rgba(5,5,10,.88) 82%);"></div>
                        <div style="position:absolute;left:13px;right:13px;bottom:11px;">
                            <div style="font-size:13.5px;font-weight:700;letter-spacing:-.01em;text-shadow:0 1px 8px rgba(0,0,0,.5);">{{ $feat->name }}</div>
                            @if($restaurant->show_price)
                                <div style="font-size:13px;font-weight:700;color:#C7D2FE;margin-top:4px;">${{ number_format($feat->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Sticky category chips --}}
    @if($categories->count() > 0)
        <div style="display:flex;gap:7px;padding:12px 20px;overflow-x:auto;position:sticky;top:0;background:rgba(9,9,14,.86);backdrop-filter:blur(10px);z-index:3;" class="hsc">
            <a href="#" class="chip-all chip-active"
               style="white-space:nowrap;font-size:12px;font-weight:600;padding:7px 13px;border-radius:999px;border:1px solid #E0E7FF;color:#1E1B4B;background:#E0E7FF;cursor:pointer;">
                Todo
            </a>
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-chip" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-size:12px;font-weight:600;padding:7px 13px;border-radius:999px;border:1px solid #26262F;color:#8A8A99;background:rgba(255,255,255,.04);cursor:pointer;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:2px 20px;">
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" style="padding-top:20px;">
                <div style="font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#565664;margin-bottom:11px;">{{ $category->name }}</div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($category->menuItems as $item)
                        @php
                            $ph = $phDark[$itemIdx % count($phDark)];
                            $initial = mb_strtoupper(mb_substr($item->name, 0, 1));
                            $itemIdx++;
                        @endphp
                        <div style="display:flex;gap:13px;padding:12px;border-radius:17px;background:linear-gradient(160deg,#15151C,#101017);border:1px solid #23232D;{{ !$item->is_available ? 'opacity:.55;' : '' }}">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                     loading="lazy" decoding="async" width="64" height="64"
                                     style="width:64px;height:64px;border-radius:13px;flex:0 0 auto;object-fit:cover;border:1px solid rgba(255,255,255,.05);">
                            @else
                                <div style="width:64px;height:64px;border-radius:13px;flex:0 0 auto;background:{{ $ph }};display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.05);">
                                    <span style="font-family:'Instrument Serif',Georgia,serif;font-size:24px;color:rgba(255,255,255,.24);">{{ $initial }}</span>
                                </div>
                            @endif
                            <div style="flex:1;min-width:0;display:flex;flex-direction:column;">
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <span style="font-size:13.5px;font-weight:600;letter-spacing:-.01em;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;">{{ $item->name }}</span>
                                    @if(!$item->is_available)
                                        <span style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:#450A0A;color:#FCA5A5;border-radius:4px;padding:2px 6px;white-space:nowrap;flex-shrink:0;">Agotado</span>
                                    @endif
                                </div>
                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:12px;color:#71717E;line-height:1.5;margin-top:3px;">{{ $item->description }}</div>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <div style="font-size:12px;color:#56565E;margin-top:4px;">
                                        @foreach($item->variants as $vi => $variant)
                                            {{ $variant->name }}@if(!$loop->last) · @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div style="flex:1;"></div>
                                <div style="display:flex;align-items:center;gap:8px;margin-top:7px;">
                                    @if($restaurant->show_price)
                                        @if($item->is_promo && $item->promo_price)
                                            <span style="font-size:12px;color:#56565E;text-decoration:line-through;font-variant-numeric:tabular-nums;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            <span style="flex:1;font-size:14px;font-weight:700;color:#FCA5A5;font-variant-numeric:tabular-nums;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                        @else
                                            <span style="flex:1;font-size:14px;font-weight:700;color:#C7D2FE;font-variant-numeric:tabular-nums;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                        @endif
                                    @endif
                                    @if($item->is_available && $restaurant->accepts_orders)
                                        @if($item->variants->isNotEmpty())
                                            <button x-on:click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                    style="width:26px;height:26px;border-radius:9px;background:rgba(129,140,248,.14);border:1px solid rgba(165,180,252,.3);display:flex;align-items:center;justify-content:center;flex:0 0 auto;cursor:pointer;color:#C7D2FE;font-size:18px;line-height:1;">+</button>
                                        @else
                                            <button x-on:click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->is_promo && $item->promo_price ? $item->promo_price : $item->price }})"
                                                    style="width:26px;height:26px;border-radius:9px;background:rgba(129,140,248,.14);border:1px solid rgba(165,180,252,.3);display:flex;align-items:center;justify-content:center;flex:0 0 auto;cursor:pointer;color:#C7D2FE;font-size:18px;line-height:1;">+</button>
                                        @endif
                                    @elseif(!$item->is_available)
                                        {{-- no button --}}
                                    @elseif($whatsappNum)
                                        <a href="https://wa.me/{{ $whatsappNum }}?text={{ urlencode('Hola, quiero pedir: ' . $item->name) }}" target="_blank" rel="noopener"
                                           style="width:26px;height:26px;border-radius:9px;background:rgba(129,140,248,.14);border:1px solid rgba(165,180,252,.3);display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#C7D2FE" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
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
        <div style="text-align:center;font-size:12px;color:#33333E;padding-top:28px;letter-spacing:.12em;text-transform:uppercase;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp FAB (solo si NO acepta pedidos por carrito) --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;right:0;padding:0 18px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="width:56px;height:56px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 6px rgba(37,211,102,.14),0 12px 28px rgba(37,211,102,.4);pointer-events:auto;">
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
    [chipAll, ...allChips].forEach(c => {
        c.style.background = 'rgba(255,255,255,.04)';
        c.style.color = '#8A8A99';
        c.style.borderColor = '#26262F';
    });
    el.style.background = '#E0E7FF';
    el.style.color = '#1E1B4B';
    el.style.borderColor = '#E0E7FF';
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
