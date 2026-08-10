{{-- Menu Cart Component (Alpine.js) --}}
{{-- Only renders if restaurant accepts orders --}}
@if($restaurant->accepts_orders)

@php
    $tableLabel   = session('nfc_table_label');
    $isTableEntry = (bool) $tableLabel;
    $deliveryCost = $restaurant->delivery_cost ?? 0;
    $minOrder     = $restaurant->min_order ?? 0;
    $waTrackUrl   = route('wa.click');
    $csrfToken    = csrf_token();
    $closedText   = (!$isOpen && $nextOpening) ? ('Cerrado · abre ' . $nextOpening) : 'Cerrado ahora';
@endphp

<script>
function menuCart(slug, acceptsOrders, whatsappNum, acceptsDelivery, deliveryCost, minOrder, tableLabel, waTrackUrl, csrfToken, isOpen, closedText) {
    return {
        items: [],
        cartOpen: false,
        showVariantModal: false,
        pendingItem: null,
        deliveryType: 'retiro',
        deliveryAddress: '',
        note: '',
        touchStartY: 0,
        touchDragging: false,
        translateY: 0,
        isOpen: isOpen,
        closedText: closedText,
        isDesktop: false,

        init() {
            if (!acceptsOrders) return;
            const stored = localStorage.getItem('cart_' + slug);
            if (stored) {
                try { this.items = JSON.parse(stored); } catch(e) { this.items = []; }
            }
            this.isDesktop = window.innerWidth >= 768;
            window.addEventListener('resize', () => { this.isDesktop = window.innerWidth >= 768; });
            window.addEventListener('keydown', (e) => { if (e.key === 'Escape' && this.cartOpen) this.cartOpen = false; });
        },

        save() {
            localStorage.setItem('cart_' + slug, JSON.stringify(this.items));
        },

        variantKey(id, variantName) {
            return id + '_' + (variantName || '');
        },

        addItem(id, name, price, variantName, image) {
            if (!acceptsOrders) return;
            variantName = variantName || '';
            image = image || '';
            const key = this.variantKey(id, variantName);
            const existing = this.items.find(i => i.key === key);
            if (existing) {
                existing.qty++;
            } else {
                this.items.push({ key, id, name, price, qty: 1, variantName, image });
            }
            this.save();
        },

        removeItem(key) {
            const existing = this.items.find(i => i.key === key);
            if (!existing) return;
            if (existing.qty > 1) {
                existing.qty--;
            } else {
                this.items = this.items.filter(i => i.key !== key);
            }
            this.save();
        },

        deleteItem(key) {
            this.items = this.items.filter(i => i.key !== key);
            this.save();
        },

        totalCount() {
            return this.items.reduce((s, i) => s + i.qty, 0);
        },

        subtotalPrice() {
            return this.items.reduce((s, i) => s + (i.price * i.qty), 0);
        },

        totalPrice() {
            const sub = this.subtotalPrice();
            return sub + (this.deliveryType === 'delivery' && deliveryCost > 0 ? deliveryCost : 0);
        },

        belowMinOrder() {
            return minOrder > 0 && this.subtotalPrice() < minOrder;
        },

        formatPrice(n) {
            return '$' + Number(n).toLocaleString('es-CL');
        },

        openVariantModal(item) {
            if (!acceptsOrders) return;
            this.pendingItem = item;
            this.showVariantModal = true;
        },

        addVariant(variant) {
            const price = this.pendingItem.price + (variant.price_delta || 0);
            const image = this.pendingItem.image || '';
            this.addItem(this.pendingItem.id, this.pendingItem.name, price, variant.name, image);
            this.showVariantModal = false;
            this.pendingItem = null;
        },

        buildWhatsappMessage() {
            let msg = tableLabel
                ? '\uD83E\uDE91 Pedido desde ' + tableLabel + '\n\n'
                : '\uD83D\uDC4B Hola! Pedido desde la carta digital:\n\n';

            this.items.forEach(i => {
                const label = i.variantName ? i.name + ' (' + i.variantName + ')' : i.name;
                msg += '\u2022 ' + i.qty + 'x ' + label + '  ' + this.formatPrice(i.price * i.qty) + '\n';
            });

            msg += '\n';

            if (this.deliveryType === 'delivery') {
                msg += '\uD83D\uDE9A *Delivery*\n';
                if (this.deliveryAddress.trim()) msg += '\uD83D\uDCCD Dirección: ' + this.deliveryAddress.trim() + '\n';
                msg += '\n';
                msg += 'Subtotal: ' + this.formatPrice(this.subtotalPrice()) + '\n';
                if (deliveryCost > 0) msg += 'Despacho: ' + this.formatPrice(deliveryCost) + '\n';
            } else {
                msg += '\uD83C\uDFEA *Retiro en local*\n\n';
            }

            msg += '*Total: ' + this.formatPrice(this.totalPrice()) + '*';

            if (this.note.trim()) msg += '\n\n\uD83D\uDCDD ' + this.note.trim();

            return msg;
        },

        trackClick() {
            fetch(waTrackUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ slug: slug, type: this.deliveryType }),
            }).catch(() => {});
        },

        sendWhatsApp() {
            if (!isOpen) return;
            if (!whatsappNum) return;
            if (this.deliveryType === 'delivery' && !this.deliveryAddress.trim()) {
                this.$refs.addressInput && this.$refs.addressInput.focus();
                return;
            }
            this.trackClick();
            const msg = this.buildWhatsappMessage();
            window.open('https://wa.me/' + whatsappNum + '?text=' + encodeURIComponent(msg), '_blank');
        },

        sendTableMessage(type) {
            if (!whatsappNum || !tableLabel) return;
            const msg = type === 'garzon'
                ? 'Llamada al garzón desde ' + tableLabel
                : 'Solicitud de cuenta desde ' + tableLabel;
            window.open('https://wa.me/' + whatsappNum + '?text=' + encodeURIComponent(msg), '_blank');
        },

        onTouchStart(e) {
            this.touchStartY = e.touches[0].clientY;
            this.touchDragging = true;
            this.translateY = 0;
        },

        onTouchMove(e) {
            if (!this.touchDragging) return;
            const delta = e.touches[0].clientY - this.touchStartY;
            if (delta > 0) this.translateY = delta;
        },

        onTouchEnd() {
            this.touchDragging = false;
            if (this.translateY > 80) this.cartOpen = false;
            this.translateY = 0;
        },
    };
}
</script>

{{-- Floating cart button --}}
<button
    x-on:click="cartOpen = true"
    x-show="totalCount() > 0"
    x-cloak
    style="position:fixed;bottom:22px;right:18px;z-index:50;min-width:64px;height:54px;padding:0 18px;border-radius:27px;background:var(--cart-fab-bg);color:var(--cart-fab-text);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 8px 24px rgba(0,0,0,.35);outline:none;"
    aria-label="Ver carrito">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
    <span x-text="totalCount()" style="font-size:15px;font-weight:700;line-height:1;"></span>
</button>

{{-- Botones de mesa (solo si entró por NFC con label de mesa) --}}
@if($isTableEntry)
<div style="position:fixed;bottom:22px;left:18px;z-index:50;display:flex;flex-direction:column;gap:8px;">
    <button onclick="document.querySelector('[x-data]').__x.$data.sendTableMessage('garzon')"
            style="padding:10px 14px;border-radius:12px;background:var(--cart-fab-bg);color:var(--cart-fab-text);border:none;cursor:pointer;font-size:12px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,.25);">
        Llamar al garzón
    </button>
    <button onclick="document.querySelector('[x-data]').__x.$data.sendTableMessage('cuenta')"
            style="padding:10px 14px;border-radius:12px;background:var(--cart-bg);color:var(--cart-text);border:var(--cart-border);cursor:pointer;font-size:12px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,.1);">
        Pedir la cuenta
    </button>
</div>
@endif

{{-- Overlay — cierra al tocar fuera --}}
<div x-on:click="cartOpen = false"
     x-show="cartOpen"
     x-cloak
     style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:51;backdrop-filter:blur(3px);">
</div>

{{-- Cart — bottom sheet en móvil, popover en escritorio --}}
<div x-show="cartOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-260"
     x-transition:enter-start="transform translate-y-full"
     x-transition:enter-end="transform translate-y-0"
     x-transition:leave="transition ease-in duration-210"
     x-transition:leave-start="transform translate-y-0"
     x-transition:leave-end="transform translate-y-full"
     :style="isDesktop
         ? 'position:fixed;bottom:90px;right:18px;z-index:52;pointer-events:none;'
         : 'position:fixed;bottom:0;left:0;right:0;z-index:52;display:flex;justify-content:center;pointer-events:none;'">

    <div :style="isDesktop
             ? 'pointer-events:auto;width:400px;max-width:calc(100vw - 36px);background:var(--cart-bg);border:1px solid var(--cart-input-border);border-radius:16px;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 8px 40px rgba(0,0,0,.30);position:relative;'
             : ('pointer-events:auto;width:100%;max-width:440px;background:var(--cart-bg);border-top:var(--cart-drawer-border-top);border-radius:var(--cart-radius) var(--cart-radius) 0 0;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 -6px 40px rgba(0,0,0,.22);transform:translateY(' + translateY + 'px);' + (touchDragging ? '' : 'transition:transform .2s;'))"
         @touchstart.passive="!isDesktop && onTouchStart($event)"
         @touchmove="!isDesktop && onTouchMove($event)"
         @touchend="!isDesktop && onTouchEnd()">

        {{-- Handle — solo en móvil --}}
        <div x-show="!isDesktop" style="display:flex;justify-content:center;padding:10px 0 0;flex-shrink:0;cursor:grab;">
            <div style="width:36px;height:4px;border-radius:2px;background:var(--cart-input-border);opacity:.7;"></div>
        </div>

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 18px 12px;border-bottom:var(--cart-border);flex-shrink:0;">
            <div>
                <div style="font-size:16px;font-weight:700;color:var(--cart-text);">
                    Tu pedido
                    <span x-text="'(' + totalCount() + ')'" style="font-size:14px;font-weight:500;color:var(--cart-text-muted);margin-left:3px;"></span>
                </div>
                @if($isTableEntry)
                <div style="font-size:11px;color:var(--cart-text-muted);margin-top:2px;">{{ $tableLabel }}</div>
                @endif
            </div>
            <button x-on:click="cartOpen = false"
                    style="width:32px;height:32px;border-radius:8px;background:var(--cart-qty-bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--cart-text);flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Tipo de entrega --}}
        <div style="padding:10px 18px;border-bottom:var(--cart-border);flex-shrink:0;">
            @if($restaurant->accepts_delivery)
            <div style="display:flex;gap:8px;">
                <button x-on:click="deliveryType = 'retiro'"
                        :style="deliveryType === 'retiro' ? 'background:var(--cart-chip-bg);color:var(--cart-chip-text);' : 'background:var(--cart-chip-idle-bg);color:var(--cart-chip-idle-text);'"
                        style="flex:1;padding:9px 6px;border-radius:9px;border:none;cursor:pointer;font-size:12.5px;font-weight:700;transition:all .15s;min-height:44px;">
                    🏪 Retiro en local
                </button>
                <button x-on:click="deliveryType = 'delivery'"
                        :style="deliveryType === 'delivery' ? 'background:var(--cart-chip-bg);color:var(--cart-chip-text);' : 'background:var(--cart-chip-idle-bg);color:var(--cart-chip-idle-text);'"
                        style="flex:1;padding:9px 6px;border-radius:9px;border:none;cursor:pointer;font-size:12.5px;font-weight:700;transition:all .15s;min-height:44px;">
                    🚚 Delivery{{ $deliveryCost > 0 ? ' (+$' . number_format($deliveryCost, 0, ',', '.') . ')' : '' }}
                </button>
            </div>
            @else
            <div style="display:flex;align-items:center;gap:7px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--cart-text-muted);flex-shrink:0;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span style="font-size:13px;font-weight:500;color:var(--cart-text-muted);">Retiro en local</span>
            </div>
            @endif
        </div>

        {{-- Items list --}}
        <div style="overflow-y:auto;flex:1;padding:0 18px;">

            {{-- Empty state --}}
            <div x-show="items.length === 0" style="padding:40px 0;text-align:center;">
                <div style="font-size:32px;margin-bottom:10px;opacity:.5;">🛒</div>
                <div style="font-size:14px;font-weight:600;color:var(--cart-text);">El carrito está vacío</div>
                <div style="font-size:13px;color:var(--cart-text-muted);margin-top:4px;">Agregá algo desde el menú</div>
            </div>

            <template x-for="item in items" :key="item.key">
                <div style="padding:12px 0;border-bottom:var(--cart-border);">

                    {{-- Top row: foto + nombre + subtotal --}}
                    <div style="display:flex;gap:12px;align-items:flex-start;">

                        {{-- Foto 56×56 --}}
                        <div style="width:56px;height:56px;border-radius:10px;overflow:hidden;flex-shrink:0;background:var(--cart-surface);display:flex;align-items:center;justify-content:center;">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" style="width:100%;height:100%;object-fit:cover;display:block;">
                            </template>
                            <template x-if="!item.image">
                                <span x-text="item.name.charAt(0).toUpperCase()" style="font-size:20px;font-weight:700;color:var(--cart-text-muted);line-height:1;"></span>
                            </template>
                        </div>

                        {{-- Nombre + variante + precio unitario --}}
                        <div style="flex:1;min-width:0;">
                            <div x-text="item.name" style="font-size:13.5px;font-weight:600;color:var(--cart-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                            <div x-show="item.variantName" x-text="item.variantName" style="font-size:11.5px;color:var(--cart-text-muted);margin-top:1px;"></div>
                            <div x-text="formatPrice(item.price)" style="font-size:11.5px;color:var(--cart-text-muted);margin-top:2px;"></div>
                        </div>

                        {{-- Subtotal --}}
                        <div x-text="formatPrice(item.price * item.qty)" style="font-size:14px;font-weight:700;color:var(--cart-text);white-space:nowrap;flex-shrink:0;padding-top:1px;"></div>
                    </div>

                    {{-- Controles: −, qty, +, basura — alineados a la derecha --}}
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;margin-top:9px;">
                        <button x-on:click="removeItem(item.key)"
                                style="width:32px;height:32px;border-radius:8px;background:var(--cart-qty-bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:17px;color:var(--cart-text);font-weight:700;flex-shrink:0;line-height:1;">−</button>
                        <span x-text="item.qty" style="font-size:13px;font-weight:700;min-width:22px;text-align:center;color:var(--cart-text);"></span>
                        <button x-on:click="addItem(item.id, item.name, item.price, item.variantName, item.image)"
                                style="width:32px;height:32px;border-radius:8px;background:var(--cart-qty-active-bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--cart-qty-active-text);font-size:17px;font-weight:700;flex-shrink:0;line-height:1;">+</button>
                        <button x-on:click="deleteItem(item.key)"
                                style="width:32px;height:32px;border-radius:8px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--cart-text-muted);flex-shrink:0;opacity:.65;"
                                title="Eliminar">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </button>
                    </div>
                </div>
            </template>

            {{-- Dirección (solo delivery) --}}
            <div x-show="deliveryType === 'delivery'" style="padding:10px 0 0;">
                <textarea x-ref="addressInput"
                          x-model="deliveryAddress"
                          placeholder="Dirección de entrega (calle, número, depto)..."
                          rows="2"
                          style="width:100%;padding:10px 12px;border:1px solid var(--cart-input-border);border-radius:10px;font-size:13px;color:var(--cart-text);background:transparent;resize:none;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color .15s;"
                          onfocus="this.style.borderColor='var(--cart-input-focus)'" onblur="this.style.borderColor='var(--cart-input-border)'"></textarea>
            </div>

            {{-- Comentario --}}
            <div style="padding:10px 0 16px;">
                <textarea x-model="note"
                          placeholder="Comentario: sin cebolla, bien cocido..."
                          rows="2"
                          style="width:100%;padding:10px 12px;border:1px solid var(--cart-input-border);border-radius:10px;font-size:13px;color:var(--cart-text);background:transparent;resize:none;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color .15s;"
                          onfocus="this.style.borderColor='var(--cart-input-focus)'" onblur="this.style.borderColor='var(--cart-input-border)'"></textarea>
            </div>
        </div>

        {{-- Footer: desglose + CTA --}}
        <div style="padding:14px 18px 30px;border-top:var(--cart-border);flex-shrink:0;">

            {{-- Desglose delivery --}}
            <template x-if="deliveryType === 'delivery' && {{ $deliveryCost }} > 0">
                <div style="margin-bottom:8px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                        <span style="font-size:13px;color:var(--cart-text-muted);">Subtotal</span>
                        <span x-text="formatPrice(subtotalPrice())" style="font-size:13px;color:var(--cart-text-muted);"></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                        <span style="font-size:13px;color:var(--cart-text-muted);">Despacho</span>
                        <span style="font-size:13px;color:var(--cart-text-muted);">{{ $deliveryCost > 0 ? '$' . number_format($deliveryCost, 0, ',', '.') : 'Gratis' }}</span>
                    </div>
                </div>
            </template>

            {{-- Total --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <span style="font-size:13px;color:var(--cart-text-muted);">Total</span>
                <span x-text="formatPrice(totalPrice())" style="font-size:15px;font-weight:700;color:var(--cart-text);letter-spacing:-.01em;"></span>
            </div>

            {{-- Aviso pedido mínimo --}}
            @if($minOrder > 0)
            <div x-show="belowMinOrder()" style="background:#FEF3C7;border:1px solid #FDE68A;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:12px;color:#92400E;font-weight:600;">
                Pedido mínimo: ${{ number_format($minOrder, 0, ',', '.') }} · te faltan <span x-text="formatPrice({{ $minOrder }} - subtotalPrice())"></span>
            </div>
            @endif

            {{-- CTA principal --}}
            <button x-on:click="items.length > 0 && !belowMinOrder() && sendWhatsApp()"
                    :style="!isOpen ? 'opacity:.4;cursor:not-allowed;filter:grayscale(1);' : (items.length === 0 || belowMinOrder() ? 'opacity:.4;cursor:not-allowed;' : 'opacity:1;cursor:pointer;')"
                    style="width:100%;min-height:56px;padding:14px 18px;border-radius:12px;background:var(--cart-accent);color:var(--cart-accent-text);border:none;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:space-between;letter-spacing:-.01em;line-height:1.2;">
                <span style="display:flex;align-items:center;gap:9px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
                    Pedir por WhatsApp
                </span>
                <span x-text="formatPrice(totalPrice())" style="font-weight:800;"></span>
            </button>
            <template x-if="!isOpen">
                <p style="margin:6px 0 0;text-align:center;font-size:11px;color:var(--cart-text-muted);" x-text="closedText"></p>
            </template>
        </div>

        {{-- Flecha conectora al botón FAB — solo en escritorio --}}
        <div x-show="isDesktop" style="position:absolute;bottom:-9px;right:32px;width:18px;height:9px;overflow:hidden;pointer-events:none;">
            <div style="width:13px;height:13px;background:var(--cart-bg);border-right:1px solid var(--cart-input-border);border-bottom:1px solid var(--cart-input-border);transform:rotate(45deg);margin:-6px auto 0;"></div>
        </div>

    </div>
</div>

{{-- Variant Modal --}}
<div x-show="showVariantModal"
     x-cloak
     style="position:fixed;inset:0;z-index:60;display:flex;align-items:flex-end;justify-content:center;padding:0;">

    <div x-on:click="showVariantModal = false; pendingItem = null;"
         style="position:absolute;inset:0;background:rgba(0,0,0,.5);"></div>

    <div style="position:relative;width:100%;max-width:440px;background:var(--cart-bg);border-top:var(--cart-drawer-border-top);border-radius:var(--cart-radius) var(--cart-radius) 0 0;padding:20px 18px 36px;z-index:61;">
        <div style="font-size:16px;font-weight:700;color:var(--cart-text);margin-bottom:3px;" x-text="pendingItem ? pendingItem.name : ''"></div>
        <div style="font-size:13px;color:var(--cart-text-muted);margin-bottom:14px;">Elige una opción</div>

        <div style="display:flex;flex-direction:column;gap:8px;">
            <template x-for="variant in (pendingItem ? pendingItem.variants : [])" :key="variant.name">
                <button x-on:click="addVariant(variant)"
                        style="width:100%;min-height:48px;padding:12px 14px;border-radius:10px;background:var(--cart-chip-idle-bg);border:1px solid var(--cart-input-border);cursor:pointer;display:flex;align-items:center;justify-content:space-between;text-align:left;">
                    <span x-text="variant.name" style="font-size:14px;font-weight:600;color:var(--cart-text);"></span>
                    <span x-text="variant.price_delta > 0 ? '+' + formatPrice(variant.price_delta) : (variant.price_delta < 0 ? formatPrice(variant.price_delta) : '')"
                          style="font-size:13px;font-weight:600;color:var(--cart-text-muted);"></span>
                </button>
            </template>
        </div>

        <button x-on:click="showVariantModal = false; pendingItem = null;"
                style="width:100%;min-height:44px;margin-top:10px;padding:11px;border-radius:10px;background:var(--cart-qty-bg);border:none;cursor:pointer;font-size:13px;font-weight:600;color:var(--cart-text-muted);">
            Cancelar
        </button>
    </div>
</div>

@endif
