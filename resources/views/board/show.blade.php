<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $restaurant->name }}</title>
@vite(['resources/js/app.js'])
@php
$hasBgImage  = (bool) $bgImage;
$cols        = (int) $screen->columns;
$density     = $screen->density ?? 'comfortable';
$showDesc    = $screen->show_descriptions ?? false;
$ooStyle     = $screen->out_of_stock_style ?? 'dimmed';
$showLogo    = $screen->show_logo ?? true;
$pageSecs    = max(5, (int) ($screen->page_seconds ?? 15));
$footerMsg   = $screen->footer_message ?? '';
$showQr      = $screen->show_qr ?? false;
$minRowPx    = $density === 'compact' ? 36 : 48;
$maxRowPx    = $density === 'compact' ? 72 : 96;
$catRowPx    = $density === 'compact' ? 28 : 36;
@endphp
<style>
:root{
    --bg:     {{ $bgColor }};
    --text:   {{ $textColor }};
    --accent: {{ $accentColor }};
    --row-h:  {{ $minRowPx }}px;
    --cat-h:  {{ $catRowPx }}px;
    --font-name:  calc(var(--row-h) * .38);
    --font-price: calc(var(--row-h) * .46);
    --font-desc:  calc(var(--row-h) * .26);
    --font-cat:   calc(var(--cat-h) * .42);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{width:100vw;height:100vh;overflow:hidden;cursor:none;-webkit-font-smoothing:antialiased}
body{
    background-color:var(--bg);
    @if($hasBgImage)
    background-image:url('{{ $bgImage }}');
    background-size:cover;
    background-position:center;
    @endif
    color:var(--text);
    font-family:'Inter',system-ui,-apple-system,sans-serif;
}

.board-overlay{
    position:fixed;inset:0;z-index:0;pointer-events:none;
    @if($hasBgImage)
    background:rgba(0,0,0,.72);
    backdrop-filter:blur(22px);
    -webkit-backdrop-filter:blur(22px);
    @else
    display:none;
    @endif
}

.board-wrap{
    width:100%;height:100%;
    display:flex;flex-direction:column;
    transition:transform 3s ease-in-out;
    position:relative;z-index:1;
}

/* ─── Header ─── */
.board-header{
    display:flex;align-items:center;gap:1.4vw;
    padding:.8vh 2.2vw;
    height:8vh;flex:none;
    border-bottom:1px solid rgba(255,255,255,.08);
    @if($hasBgImage)
    background:rgba(0,0,0,.35);
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    @endif
}
.board-logo{height:5.2vh;width:auto;max-width:8vw;border-radius:.6vh;object-fit:cover;flex:none;}
.board-name{font-size:2.6vh;font-weight:800;letter-spacing:-.03em;flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
.board-clock{font-size:2vh;font-weight:600;opacity:.4;font-variant-numeric:tabular-nums;letter-spacing:.04em;flex:none;}

/* ─── Promo banner ─── */
.promo-banner{
    display:flex;align-items:center;gap:2.2vw;
    padding:0 2.2vw;height:14vh;flex:none;overflow:hidden;
    border-bottom:1px solid rgba(255,255,255,.07);
    @if($hasBgImage)background:rgba(0,0,0,.22);@endif
}
.promo-tag{font-size:1vh;font-weight:800;letter-spacing:.14em;text-transform:uppercase;background:var(--accent);color:#000;padding:.4vh .9vw;border-radius:.4vh;white-space:nowrap;flex:none;}
.promo-image{width:9.5vh;height:9.5vh;object-fit:cover;border-radius:.9vh;flex:none;box-shadow:0 4px 14px rgba(0,0,0,.5);}
.promo-name{font-size:3.4vh;font-weight:700;letter-spacing:-.02em;line-height:1.15;flex:1;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
.promo-prices{display:flex;flex-direction:column;align-items:flex-end;gap:.3vh;flex:none;}
.promo-old{font-size:1.8vh;opacity:.38;text-decoration:line-through;font-variant-numeric:tabular-nums;}
.promo-price{font-size:5vh;font-weight:900;letter-spacing:-.03em;color:var(--accent);font-variant-numeric:tabular-nums;}

/* ─── Contenedor de páginas ─── */
.board-content{flex:1;overflow:hidden;position:relative;}

/* ─── Página: flex row de N columnas ─── */
.board-page{
    position:absolute;inset:0;
    display:flex;flex-direction:row;
    gap:2.5vw;
    padding:1.8vh 2.2vw;
    opacity:0;
    transition:opacity .8s ease;
    pointer-events:none;
}
.board-page.is-active{opacity:1;pointer-events:auto;}

/* ─── Columna ─── */
.board-col{
    flex:1;min-width:0;
    display:flex;flex-direction:column;
    overflow:hidden;
}

/* ─── Categoría: banda ancha ─── */
.cat-band{
    height:var(--cat-h);flex:none;
    display:flex;align-items:center;
    padding:0 .8vw;
    margin-bottom:.3vh;
    border-radius:.4vh;
    background:color-mix(in srgb, var(--accent) 14%, transparent);
    border-left:3px solid var(--accent);
}
.cat-band-name{
    font-size:var(--font-cat);font-weight:800;
    letter-spacing:.1em;text-transform:uppercase;
    color:var(--accent);
    overflow:hidden;white-space:nowrap;text-overflow:ellipsis;
}

/* ─── Item row ─── */
.item-row{
    display:flex;align-items:center;
    height:var(--row-h);flex:none;
    gap:.8vw;
    border-bottom:1px solid rgba(255,255,255,.055);
    overflow:hidden;
}
.item-row:last-child{border-bottom:none;}

.item-thumb{
    flex:none;
    width:calc(var(--row-h) * .82);
    height:calc(var(--row-h) * .82);
    object-fit:cover;border-radius:.4vh;opacity:.92;
}
.item-text{flex:1;min-width:0;display:flex;flex-direction:column;justify-content:center;gap:.1vh;overflow:hidden;}
.item-name{font-size:var(--font-name);font-weight:600;letter-spacing:-.01em;line-height:1.2;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
.item-desc{font-size:var(--font-desc);opacity:.48;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;line-height:1.2;}
.item-price-wrap{flex:none;display:flex;flex-direction:column;align-items:flex-end;gap:.15vh;}
.item-old-price{font-size:calc(var(--font-price) * .7);opacity:.32;text-decoration:line-through;font-variant-numeric:tabular-nums;line-height:1;}
.item-price{font-size:var(--font-price);font-weight:900;letter-spacing:-.02em;font-variant-numeric:tabular-nums;color:var(--accent);white-space:nowrap;line-height:1;}

.item-row.oo-dimmed .item-name,.item-row.oo-dimmed .item-desc{opacity:.3}
.item-row.oo-dimmed .item-price{opacity:.25}
.item-row.oo-strikethrough .item-name{text-decoration:line-through;opacity:.45}
.item-row.oo-strikethrough .item-price{opacity:.35}
.agotado-badge{
    font-size:calc(var(--font-desc) * .95);font-weight:800;
    letter-spacing:.08em;text-transform:uppercase;
    background:rgba(220,38,38,.18);color:#FCA5A5;
    padding:.2vh .5vw;border-radius:.3vh;
    white-space:nowrap;flex:none;
}

/* ─── Footer ─── */
.board-footer{
    height:5.5vh;flex:none;
    display:flex;align-items:center;
    padding:0 2.2vw;gap:2vw;
    border-top:1px solid rgba(255,255,255,.07);
    @if($hasBgImage)background:rgba(0,0,0,.32);@endif
}
.footer-msg{flex:1;font-size:1.6vh;opacity:.55;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
.page-dots{display:flex;align-items:center;gap:.5vw;flex:none;}
.page-dot{width:6px;height:6px;border-radius:50%;background:var(--text);opacity:.2;transition:opacity .4s;}
.page-dot.active{opacity:.75}
.qr-corner{flex:none;width:5.5vh;height:5.5vh;border-radius:.5vh;overflow:hidden;background:white;padding:2px;display:flex;align-items:center;justify-content:center;}
.qr-corner svg{width:100%;height:100%;display:block}

.offline-dot{position:fixed;bottom:1.5vh;right:1.5vw;width:5px;height:5px;border-radius:50%;transition:background .8s;z-index:100;}
.offline-dot.is-online{background:rgba(74,222,128,.2)}
.offline-dot.is-offline{background:rgba(239,68,68,.55)}

.expired-strip{position:fixed;bottom:0;left:0;right:0;padding:.5vh 2vw;background:rgba(0,0,0,.6);font-size:1vh;color:rgba(255,255,255,.28);text-align:center;letter-spacing:.06em;pointer-events:none;z-index:200;}
</style>
</head>
<body x-data="boardApp()" x-init="init()">

<div class="board-overlay"></div>

@php
$promoData = $promos->map(fn($p) => [
    'name'         => $p->name,
    'original_price'=> '$' . number_format($p->price, 0, ',', '.'),
    'promo_price'  => '$' . number_format($p->promo_price, 0, ',', '.'),
    'image'        => $p->image ? Storage::url($p->image) : null,
])->values()->toJson();

$catData = $categories->map(fn($cat) => [
    'name'  => $cat->name,
    'items' => $cat->menuItems->map(fn($item) => [
        'name'      => $item->name,
        'desc'      => ($showDesc && $item->description) ? $item->description : '',
        'price'     => $restaurant->show_price
            ? ('$' . number_format(($item->is_promo && $item->promo_price) ? $item->promo_price : $item->price, 0, ',', '.'))
            : '',
        'old_price' => ($restaurant->show_price && $item->is_promo && $item->promo_price)
            ? ('$' . number_format($item->price, 0, ',', '.'))
            : '',
        'available' => (bool) $item->is_available,
        'image'     => ($screen->show_images && $item->image) ? Storage::url($item->image) : null,
    ])->values()->toArray(),
])->values()->toJson();
@endphp

<div class="board-wrap" :style="`transform:translate(${shift.x}px,${shift.y}px)`">

    <header class="board-header">
        @if($showLogo && $restaurant->logo)
        <img src="{{ Storage::url($restaurant->logo) }}" class="board-logo" alt="">
        @endif
        <span class="board-name">{{ $restaurant->name }}</span>
        <span class="board-clock" x-text="clock"></span>
    </header>

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

    <div class="board-content" id="board-content"></div>

    <footer class="board-footer">
        <span class="footer-msg">{{ $footerMsg }}</span>
        <div class="page-dots" id="page-dots"></div>
        @if($showQr && $menuQrSvg)
        <div class="qr-corner">{!! $menuQrSvg !!}</div>
        @endif
    </footer>

</div>

<div class="offline-dot" :class="isOnline ? 'is-online' : 'is-offline'"></div>

@unless($gracePeriod)
<div class="expired-strip">MENUDIGITAL · PLAN VENCIDO</div>
@endunless

<script>
const CAT_DATA   = {!! $catData !!};
const PROMO_DATA = {!! $promoData !!};
const COLS       = {{ $cols }};
const MIN_ROW    = {{ $minRowPx }};
const MAX_ROW    = {{ $maxRowPx }};
const CAT_ROW    = {{ $catRowPx }};
const OO_STYLE   = '{{ $ooStyle }}';
const PAGE_SECS  = {{ $pageSecs }};

// ─── Auto-sizer: calcula rowH y distribuye en páginas/columnas ──────────────
function buildPages() {
    const content = document.getElementById('board-content');
    const availH  = content.clientHeight;

    // Contar filas totales (header de cat cuenta como 1)
    let totalRows = 0;
    CAT_DATA.forEach(c => { if (c.items.length) totalRows += 1 + c.items.length; });
    if (totalRows === 0) return [];

    // Altura de fila ideal para que todo quepa en una página
    let rowH = Math.floor(availH / Math.ceil(totalRows / COLS));
    rowH = Math.max(MIN_ROW, Math.min(MAX_ROW, rowH));
    document.documentElement.style.setProperty('--row-h', rowH + 'px');

    const rowsPerCol = Math.floor(availH / rowH);

    // Distribuir en páginas: array de páginas, cada página = array de COLS columnas
    // Cada columna = array de bloques { name, showHeader, items[] }
    const pages   = [];
    let page      = Array.from({length: COLS}, () => []);
    let col       = 0;
    let colRows   = 0;

    function nextCol() {
        col++;
        colRows = 0;
        if (col >= COLS) {
            pages.push(page);
            page    = Array.from({length: COLS}, () => []);
            col     = 0;
        }
    }

    CAT_DATA.forEach(cat => {
        if (!cat.items.length) return;
        let idx        = 0;
        let isFirst    = true;

        while (idx < cat.items.length) {
            const remaining = rowsPerCol - colRows;
            const headerCost = isFirst ? 1 : 0;

            // Si no caben ni header + 1 item, avanzar columna
            if (remaining < headerCost + 1) {
                nextCol();
                continue;
            }

            const take = Math.min(cat.items.length - idx, remaining - headerCost);

            page[col].push({
                name:       cat.name,
                showHeader: isFirst,
                items:      cat.items.slice(idx, idx + take),
            });

            colRows += headerCost + take;
            idx     += take;
            isFirst  = false;

            if (colRows >= rowsPerCol) nextCol();
        }
    });

    // Guardar última página si tiene contenido
    if (page.some(c => c.length > 0)) pages.push(page);

    return pages;
}

// ─── Renderizar páginas ───────────────────────────────────────────────────────
function renderPages(pages) {
    const content = document.getElementById('board-content');
    content.innerHTML = '';

    pages.forEach((cols, pi) => {
        const pageEl = document.createElement('div');
        pageEl.className = 'board-page' + (pi === 0 ? ' is-active' : '');

        cols.forEach(blocks => {
            const colEl = document.createElement('div');
            colEl.className = 'board-col';

            blocks.forEach(block => {
                if (block.showHeader) {
                    const band = document.createElement('div');
                    band.className = 'cat-band';
                    const nm = document.createElement('span');
                    nm.className = 'cat-band-name';
                    nm.textContent = block.name;
                    band.appendChild(nm);
                    colEl.appendChild(band);
                }

                block.items.forEach(item => {
                    const row = document.createElement('div');
                    const ooClass = !item.available
                        ? (OO_STYLE === 'strikethrough' ? ' oo-strikethrough' : ' oo-dimmed')
                        : '';
                    row.className = 'item-row' + ooClass;

                    if (item.image) {
                        const img = document.createElement('img');
                        img.src = item.image;
                        img.className = 'item-thumb';
                        img.loading = 'lazy';
                        row.appendChild(img);
                    }

                    const textWrap = document.createElement('div');
                    textWrap.className = 'item-text';

                    const name = document.createElement('span');
                    name.className = 'item-name';
                    name.textContent = item.name;
                    textWrap.appendChild(name);

                    if (item.desc) {
                        const desc = document.createElement('span');
                        desc.className = 'item-desc';
                        desc.textContent = item.desc;
                        textWrap.appendChild(desc);
                    }
                    row.appendChild(textWrap);

                    if (!item.available) {
                        const badge = document.createElement('span');
                        badge.className = 'agotado-badge';
                        badge.textContent = 'Agotado';
                        row.appendChild(badge);
                    }

                    if (item.price) {
                        const pw = document.createElement('div');
                        pw.className = 'item-price-wrap';

                        if (item.old_price) {
                            const op = document.createElement('span');
                            op.className = 'item-old-price';
                            op.textContent = item.old_price;
                            pw.appendChild(op);
                        }

                        const pr = document.createElement('span');
                        pr.className = 'item-price';
                        pr.textContent = item.price;
                        pw.appendChild(pr);
                        row.appendChild(pw);
                    }

                    colEl.appendChild(row);
                });
            });

            pageEl.appendChild(colEl);
        });

        content.appendChild(pageEl);
    });

    // Dots
    const dotsEl = document.getElementById('page-dots');
    dotsEl.innerHTML = '';
    if (pages.length > 1) {
        pages.forEach((_, i) => {
            const d = document.createElement('div');
            d.className = 'page-dot' + (i === 0 ? ' active' : '');
            d.dataset.dot = i;
            dotsEl.appendChild(d);
        });
    }
}

// ─── Alpine app ──────────────────────────────────────────────────────────────
function boardApp() {
    return {
        hash:       null,
        isOnline:   navigator.onLine,
        promoIndex: 0,
        promos:     PROMO_DATA,
        token:      '{{ $screen->token }}',
        clock:      '',
        pageIndex:  0,
        shifts: [{x:0,y:0},{x:2,y:1},{x:-1,y:2},{x:1,y:-2},{x:-2,y:1},{x:0,y:3}],
        shiftIdx: 0,

        get shift()       { return this.shifts[this.shiftIdx]; },
        get currentPromo(){ return this.promos[this.promoIndex] ?? null; },

        init() {
            this.tickClock();
            setInterval(() => this.tickClock(), 30000);

            window.addEventListener('online',  () => this.isOnline = true);
            window.addEventListener('offline', () => this.isOnline = false);

            requestAnimationFrame(() => {
                const pages = buildPages();
                renderPages(pages);
                if (pages.length > 1) {
                    setInterval(() => this.nextPage(), PAGE_SECS * 1000);
                }
            });

            this.startPolling();

            @if($screen->show_promos_rotation && $promos->count() > 1)
            setInterval(() => {
                this.promoIndex = (this.promoIndex + 1) % this.promos.length;
            }, 15000);
            @endif

            setInterval(() => {
                this.shiftIdx = (this.shiftIdx + 1) % this.shifts.length;
            }, 15 * 60 * 1000);
        },

        nextPage() {
            const pages = document.querySelectorAll('.board-page');
            const dots  = document.querySelectorAll('.page-dot');
            if (pages.length <= 1) return;

            pages[this.pageIndex].classList.remove('is-active');
            if (dots[this.pageIndex]) dots[this.pageIndex].classList.remove('active');

            this.pageIndex = (this.pageIndex + 1) % pages.length;

            pages[this.pageIndex].classList.add('is-active');
            if (dots[this.pageIndex]) dots[this.pageIndex].classList.add('active');
        },

        tickClock() {
            const now = new Date();
            this.clock = now.toLocaleTimeString('es-CL', {hour:'2-digit', minute:'2-digit'});
        },

        startPolling() {
            const token = this.token;
            const NORMAL = 12000;
            const MAX_BACKOFF = 2 * 60 * 1000;
            let delay = NORMAL;

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
                        return;
                    }
                    delay = NORMAL;
                } catch {
                    this.isOnline = false;
                    delay = Math.min(delay * 2, MAX_BACKOFF);
                }
                setTimeout(attempt, delay);
            };

            setTimeout(attempt, 3000);
        }
    };
}

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw-board.js', { scope: '/board/' });
}
</script>
</body>
</html>
