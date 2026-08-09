@extends('layouts.menu')

@push('head')
<style>
html,body{background:#F8F8F6}
body{color:#1A1A1A;font-family:'Inter',system-ui,sans-serif}
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
.store-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
@media(min-width:560px){.store-grid{grid-template-columns:repeat(3,1fr)}}
.product-card{background:#fff;border-radius:16px;overflow:hidden;border:1px solid #EBEBEB;position:relative;display:flex;flex-direction:column;}
.product-card.unavailable{opacity:.6}
.stock-badge{position:absolute;top:9px;left:9px;font-size:10px;font-weight:700;letter-spacing:.04em;border-radius:6px;padding:3px 8px;pointer-events:none;}
.stock-badge.low{background:#FEF3C7;color:#92400E;}
.stock-badge.out{background:#FEE2E2;color:#DC2626;}
</style>
@endpush

@section('body')

<div style="max-width:600px;margin:0 auto;padding-bottom:100px;">

    {{-- Header --}}
    <div style="padding:32px 20px 20px;text-align:center;">
        @if($restaurant->logo)
            <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}"
                 width="64" height="64" loading="eager" decoding="async"
                 style="width:64px;height:64px;border-radius:14px;object-fit:cover;margin:0 auto 14px;display:block;border:1px solid #EBEBEB;">
        @endif
        <h1 style="margin:0;font-size:26px;font-weight:700;letter-spacing:-.02em;line-height:1.15;">{{ $restaurant->name }}</h1>
        @if($restaurant->address)
            <div style="margin-top:6px;font-size:12.5px;color:#888;font-weight:500;">{{ $restaurant->address }}</div>
        @endif
        @if($restaurant->welcome_message)
            <p style="margin:10px 0 0;font-size:14px;color:#555;line-height:1.55;">{{ $restaurant->welcome_message }}</p>
        @endif
    </div>

    {{-- Category nav --}}
    @if($categories->count() > 1)
        <div class="hsc" style="display:flex;gap:8px;padding:4px 20px 12px;overflow-x:auto;">
            @foreach($categories as $i => $category)
                <a href="#cat-{{ $category->id }}" class="cat-link" data-cat="{{ $category->id }}"
                   style="white-space:nowrap;font-size:12.5px;font-weight:600;padding:6px 14px;border-radius:999px;border:1.5px solid {{ $i === 0 ? '#1A1A1A' : '#DCDCDC' }};background:{{ $i === 0 ? '#1A1A1A' : 'transparent' }};color:{{ $i === 0 ? '#fff' : '#555' }};text-decoration:none;transition:all .15s;">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sections --}}
    <div style="padding:4px 16px;">
        @foreach($categories as $si => $category)
            <div id="cat-{{ $category->id }}" style="padding-top:{{ $si > 0 ? '32px' : '8px' }};">

                @if($categories->count() > 1 || $si === 0)
                    <div style="margin-bottom:14px;padding:0 4px;">
                        <h2 style="margin:0;font-size:17px;font-weight:700;letter-spacing:-.01em;">{{ $category->name }}</h2>
                        @if($category->description ?? null)
                            <p style="margin:4px 0 0;font-size:13px;color:#888;">{{ $category->description }}</p>
                        @endif
                    </div>
                @endif

                <div class="store-grid">
                    @foreach($category->menuItems as $item)
                        @php
                            $stockLow  = $item->stock !== null && $item->stock > 0 && $item->stock <= 5;
                            $stockOut  = $item->stock !== null && $item->stock === 0;
                            $unavail   = !$item->is_available || $stockOut;
                            $finalPrice = $item->is_promo && $item->promo_price ? $item->promo_price : $item->price;
                        @endphp

                        <div class="product-card{{ $unavail ? ' unavailable' : '' }}">

                            {{-- Product image --}}
                            <div style="position:relative;aspect-ratio:1/1;background:#F3F3F0;overflow:hidden;">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                         loading="lazy" decoding="async"
                                         style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#CECECE" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Stock badge --}}
                                @if($stockOut)
                                    <span class="stock-badge out">Agotado</span>
                                @elseif($stockLow)
                                    <span class="stock-badge low">Últimas {{ $item->stock }}</span>
                                @endif

                                {{-- Promo badge --}}
                                @if($item->is_promo && $item->promo_price && !$stockOut)
                                    <span style="position:absolute;top:9px;right:9px;font-size:10px;font-weight:700;background:#DC2626;color:#fff;border-radius:6px;padding:3px 7px;">OFERTA</span>
                                @endif
                            </div>

                            {{-- Product info --}}
                            <div style="padding:11px 12px 12px;display:flex;flex-direction:column;flex:1;gap:4px;">
                                <div style="font-size:13.5px;font-weight:600;line-height:1.3;letter-spacing:-.01em;">{{ $item->name }}</div>

                                @if($item->sku)
                                    <div style="font-size:10.5px;color:#AAA;font-weight:500;letter-spacing:.04em;">{{ $item->sku }}</div>
                                @endif

                                @if($restaurant->show_description && $item->description)
                                    <div style="font-size:12px;color:#777;line-height:1.45;margin-top:1px;">{{ Str::limit($item->description, 80) }}</div>
                                @endif

                                <div style="margin-top:auto;padding-top:8px;display:flex;align-items:center;justify-content:space-between;gap:6px;">
                                    @if($restaurant->show_price)
                                        <div style="display:flex;align-items:baseline;gap:5px;flex-wrap:wrap;">
                                            @if($item->is_promo && $item->promo_price)
                                                <span style="font-size:15px;font-weight:700;letter-spacing:-.02em;">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                                                <span style="font-size:12px;color:#AAA;text-decoration:line-through;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            @else
                                                <span style="font-size:15px;font-weight:700;letter-spacing:-.02em;">${{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if(!$unavail && $restaurant->accepts_orders)
                                        @if($item->variants->isNotEmpty())
                                            <button @click="openVariantModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'variants' => $item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()]) }})"
                                                    style="width:30px;height:30px;border-radius:10px;background:#1A1A1A;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;line-height:1;">+</button>
                                        @else
                                            <button @click="addItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $finalPrice }})"
                                                    style="width:30px;height:30px;border-radius:10px;background:#1A1A1A;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;line-height:1;">+</button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        @if(($restaurant->plan ?? 'free') === 'free')
        <div style="text-align:center;margin-top:40px;font-size:11.5px;letter-spacing:.12em;text-transform:uppercase;color:#CCC;"><a href="https://menudigital.cl" style="color:inherit;text-decoration:none;">Hecho con MenuDigital</a></div>
        @endif
    </div>
</div>

{{-- WhatsApp pill --}}
@if($whatsappNum && !$restaurant->accepts_orders)
    @if($isOpen ?? true)
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:9px;padding:13px 22px;border-radius:999px;background:#1A1A1A;color:#fff;font-size:13px;font-weight:600;letter-spacing:.02em;box-shadow:0 10px 28px rgba(0,0,0,.25);pointer-events:auto;text-decoration:none;">
            <span style="width:22px;height:22px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;flex:0 0 auto;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            Consultar por WhatsApp
        </a>
    </div>
    @else
    <div style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;padding:0 20px 20px;pointer-events:none;z-index:10;">
        <div style="display:inline-flex;align-items:center;gap:9px;padding:13px 22px;border-radius:999px;background:#F0F0EE;color:#555;font-size:13px;font-weight:600;letter-spacing:.02em;pointer-events:auto;">
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
            l.style.background = 'transparent';
            l.style.borderColor = '#DCDCDC';
            l.style.color = '#555';
        });
        this.style.background = '#1A1A1A';
        this.style.borderColor = '#1A1A1A';
        this.style.color = '#fff';
    });
});
</script>
@endsection
