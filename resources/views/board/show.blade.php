<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $restaurant->name }}</title>
@vite(['resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{width:100vw;height:100vh;overflow:hidden;cursor:none;-webkit-font-smoothing:antialiased}
body{
    background:{{ $bgColor }};
    color:{{ $textColor }};
    font-family:'Inter',system-ui,-apple-system,sans-serif;
}

/* ─── Wrap con pixel-shift anti burn-in ─── */
.board-wrap{
    width:100%;height:100%;
    display:flex;flex-direction:column;
    transition:transform 3s ease-in-out;
}

/* ─── Header ─── */
.board-header{
    display:flex;align-items:center;gap:1.6vw;
    padding:1.2vh 2.5vw;
    border-bottom:1px solid rgba(255,255,255,.07);
    height:9vh;
    flex:none;
}
.board-logo{
    width:5.5vh;height:5.5vh;
    border-radius:.7vh;object-fit:cover;flex:none;
}
.board-name{
    font-size:2.4vh;font-weight:700;
    letter-spacing:-.02em;opacity:.9;
    flex:1;
}
.board-clock{
    font-size:1.8vh;font-weight:600;
    opacity:.35;font-variant-numeric:tabular-nums;
    letter-spacing:.04em;
}

/* ─── Promo banner ─── */
.promo-banner{
    display:flex;align-items:center;gap:2.5vw;
    padding:0 2.5vw;
    height:14vh;
    border-bottom:1px solid rgba(255,255,255,.06);
    flex:none;
    overflow:hidden;
}
.promo-tag{
    font-size:1vh;font-weight:800;
    letter-spacing:.12em;text-transform:uppercase;
    background:{{ $accentColor }};color:#000;
    padding:.4vh 1vw;border-radius:.4vh;
    white-space:nowrap;flex:none;
}
.promo-image{
    width:10vh;height:10vh;
    object-fit:cover;border-radius:1vh;flex:none;
}
.promo-name{
    font-size:3.6vh;font-weight:700;
    letter-spacing:-.02em;line-height:1.1;
    flex:1;overflow:hidden;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
}
.promo-prices{
    display:flex;flex-direction:column;align-items:flex-end;gap:.3vh;flex:none;
}
.promo-old{
    font-size:1.8vh;opacity:.4;
    text-decoration:line-through;
    font-variant-numeric:tabular-nums;
}
.promo-price{
    font-size:5vh;font-weight:800;
    letter-spacing:-.03em;
    color:{{ $accentColor }};
    font-variant-numeric:tabular-nums;
}

/* ─── Body ─── */
.board-body{
    flex:1;overflow:hidden;
    padding:1.8vh 2vw;
    columns:{{ $screen->columns }};
    column-gap:2.5vw;
    column-fill:auto;
}

/* ─── Categoría ─── */
.cat-section{
    break-inside:avoid;
    page-break-inside:avoid;
    margin-bottom:2.8vh;
}
.cat-header{
    font-size:1.3vh;font-weight:800;
    letter-spacing:.14em;text-transform:uppercase;
    opacity:.35;
    padding-bottom:.7vh;
    border-bottom:1px solid rgba(255,255,255,.1);
    margin-bottom:1.2vh;
}

/* ─── Item ─── */
.item-row{
    display:flex;align-items:center;
    gap:1vw;
    padding:.5vh 0;
    border-bottom:1px solid rgba(255,255,255,.04);
}
.item-row:last-child{border-bottom:none}
.item-thumb{
    width:5.5vh;height:5.5vh;
    object-fit:cover;border-radius:.5vh;
    flex:none;opacity:.9;
}
.item-name{
    font-size:2.2vh;font-weight:600;
    letter-spacing:-.01em;line-height:1.2;
    flex:1;min-width:0;
    overflow:hidden;
    white-space:nowrap;text-overflow:ellipsis;
}
.item-sep{
    flex:1;border-bottom:2px dotted rgba(255,255,255,.1);
    margin:0 .5vw;transform:translateY(-.3vh);min-width:1vw;
}
.item-price-wrap{
    display:flex;align-items:baseline;
    gap:.6vw;flex:none;
}
.item-old-price{
    font-size:1.6vh;opacity:.35;
    text-decoration:line-through;
    font-variant-numeric:tabular-nums;
}
.item-price{
    font-size:3vh;font-weight:800;
    letter-spacing:-.02em;
    font-variant-numeric:tabular-nums;
    white-space:nowrap;
}

/* ─── Agotado ─── */
.item-row.unavailable .item-name{opacity:.3;text-decoration:line-through}
.item-row.unavailable .item-price,.item-row.unavailable .item-old-price{opacity:.25}
.agotado-badge{
    font-size:.9vh;font-weight:800;
    letter-spacing:.1em;text-transform:uppercase;
    background:rgba(220,38,38,.18);color:#FCA5A5;
    padding:.2vh .7vw;border-radius:.3vh;
    white-space:nowrap;flex:none;
}

/* ─── Indicador offline (punto discreto) ─── */
.offline-dot{
    position:fixed;bottom:1.5vh;right:1.5vw;
    width:5px;height:5px;border-radius:50%;
    transition:background .8s;
    z-index:100;
}
.offline-dot.is-online{background:rgba(74,222,128,.2)}
.offline-dot.is-offline{background:rgba(239,68,68,.55)}

/* ─── Aviso plan vencido ─── */
.expired-strip{
    position:fixed;bottom:0;left:0;right:0;
    padding:.6vh 2vw;
    background:rgba(0,0,0,.6);
    font-size:1.1vh;color:rgba(255,255,255,.3);
    text-align:center;letter-spacing:.06em;
    pointer-events:none;
}
</style>
</head>
<body
    x-data="boardApp()"
    x-init="init()"
>
@php
$promoData = $promos->map(fn($p) => [
    'name'           => $p->name,
    'original_price' => '$' . number_format($p->price, 0, ',', '.'),
    'promo_price'    => '$' . number_format($p->promo_price, 0, ',', '.'),
    'image'          => $p->image ? Storage::url($p->image) : null,
])->values()->toJson();
@endphp

<div class="board-wrap"
     :style="`transform:translate(${shift.x}px,${shift.y}px)`">

    {{-- Header --}}
    <header class="board-header">
        @if($restaurant->logo)
        <img src="{{ Storage::url($restaurant->logo) }}" class="board-logo" alt="">
        @endif
        <span class="board-name">{{ $restaurant->name }}</span>
        <span class="board-clock" x-text="clock"></span>
    </header>

    {{-- Promo rotation --}}
    @if($screen->show_promos_rotation && $promos->isNotEmpty())
    <div class="promo-banner">
        <span class="promo-tag">Oferta</span>
        <template x-if="currentPromo?.image">
            <img :src="currentPromo.image" class="promo-image" alt="">
        </template>
        <span class="promo-name" x-text="currentPromo?.name"></span>
        <div class="promo-prices">
            <span class="promo-old" x-text="currentPromo?.original_price"></span>
            <span class="promo-price" x-text="currentPromo?.promo_price"></span>
        </div>
    </div>
    @endif

    {{-- Items --}}
    <div class="board-body">
        @foreach($categories as $category)
        @if($category->menuItems->isNotEmpty())
        <div class="cat-section">
            <div class="cat-header">{{ $category->name }}</div>
            @foreach($category->menuItems as $item)
            <div class="item-row {{ !$item->is_available ? 'unavailable' : '' }}">
                @if($screen->show_images && $item->image)
                <img src="{{ Storage::url($item->image) }}"
                     class="item-thumb" loading="lazy" alt="">
                @endif
                <span class="item-name">{{ $item->name }}</span>
                @if(!$item->is_available)
                    <span class="agotado-badge">Agotado</span>
                @else
                    <span class="item-sep"></span>
                @endif
                @if($restaurant->show_price)
                <div class="item-price-wrap">
                    @if($item->is_promo && $item->promo_price)
                    <span class="item-old-price">${{ number_format($item->price, 0, ',', '.') }}</span>
                    <span class="item-price" style="color:{{ $accentColor }}">${{ number_format($item->promo_price, 0, ',', '.') }}</span>
                    @else
                    <span class="item-price">${{ number_format($item->price, 0, ',', '.') }}</span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
    </div>

</div>

{{-- Offline indicator --}}
<div class="offline-dot" :class="isOnline ? 'is-online' : 'is-offline'"></div>

{{-- Plan vencido aviso --}}
@unless($gracePeriod)
<div class="expired-strip">MENUDIGITAL · PLAN VENCIDO</div>
@endunless

<script>
function boardApp() {
    return {
        hash:       null,
        isOnline:   navigator.onLine,
        promoIndex: 0,
        promos:     {{ $promoData }},
        token:      '{{ $screen->token }}',
        clockTimer: null,
        clock:      '',
        shifts: [{x:0,y:0},{x:2,y:1},{x:-1,y:2},{x:1,y:-2},{x:-2,y:1},{x:0,y:3}],
        shiftIdx: 0,

        get shift() { return this.shifts[this.shiftIdx]; },
        get currentPromo() { return this.promos[this.promoIndex] ?? null; },

        init() {
            // Reloj
            this.tickClock();
            setInterval(() => this.tickClock(), 30000);

            // Online / offline
            window.addEventListener('online',  () => this.isOnline = true);
            window.addEventListener('offline', () => this.isOnline = false);

            // Polling de version
            this.startPolling();

            // Rotación de promos
            @if($screen->show_promos_rotation && $promos->count() > 1)
            setInterval(() => {
                this.promoIndex = (this.promoIndex + 1) % this.promos.length;
            }, 15000);
            @endif

            // Anti burn-in: pixel shift cada 15 min
            setInterval(() => {
                this.shiftIdx = (this.shiftIdx + 1) % this.shifts.length;
            }, 15 * 60 * 1000);
        },

        tickClock() {
            const now = new Date();
            this.clock = now.toLocaleTimeString('es-CL', {hour:'2-digit', minute:'2-digit'});
        },

        startPolling() {
            const token = this.token;
            let delay = 30000;

            const attempt = async () => {
                try {
                    const r = await fetch('/board/' + token + '/version', { cache: 'no-store' });
                    if (!r.ok) throw new Error('bad status');
                    const { hash } = await r.json();
                    this.isOnline = true;
                    if (this.hash === null) {
                        this.hash = hash;
                    } else if (this.hash !== hash) {
                        location.reload();
                        return; // no seguir poling
                    }
                    delay = 30000;
                } catch {
                    this.isOnline = false;
                    delay = Math.min(delay * 2, 5 * 60 * 1000); // máx 5 min
                }
                setTimeout(attempt, delay);
            };

            // Primera consulta a los 30s de carga
            setTimeout(attempt, 30000);
        }
    };
}

// Registrar service worker para modo offline
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw-board.js', { scope: '/board/' });
}
</script>
</body>
</html>
