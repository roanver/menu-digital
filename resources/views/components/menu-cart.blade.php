{{-- Menu Cart Component (Alpine.js) --}}
{{-- Only renders if restaurant accepts orders --}}
@if($restaurant->accepts_orders)

<script>
function menuCart(slug, acceptsOrders, whatsappNum, acceptsDelivery) {
    return {
        items: [],
        cartOpen: false,
        showVariantModal: false,
        pendingItem: null,

        init() {
            if (!acceptsOrders) return;
            const stored = localStorage.getItem('cart_' + slug);
            if (stored) {
                try { this.items = JSON.parse(stored); } catch(e) { this.items = []; }
            }
        },

        save() {
            localStorage.setItem('cart_' + slug, JSON.stringify(this.items));
        },

        variantKey(id, variantName) {
            return id + '_' + (variantName || '');
        },

        addItem(id, name, price, variantName) {
            if (!acceptsOrders) return;
            variantName = variantName || '';
            const key = this.variantKey(id, variantName);
            const existing = this.items.find(i => i.key === key);
            if (existing) {
                existing.qty++;
            } else {
                this.items.push({ key, id, name, price, qty: 1, variantName });
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

        totalCount() {
            return this.items.reduce((s, i) => s + i.qty, 0);
        },

        totalPrice() {
            return this.items.reduce((s, i) => s + (i.price * i.qty), 0);
        },

        formatPrice(n) {
            return '$' + n.toLocaleString('es-CL');
        },

        openVariantModal(item) {
            if (!acceptsOrders) return;
            this.pendingItem = item;
            this.showVariantModal = true;
        },

        addVariant(variant) {
            const price = this.pendingItem.price + (variant.price_delta || 0);
            this.addItem(this.pendingItem.id, this.pendingItem.name, price, variant.name);
            this.showVariantModal = false;
            this.pendingItem = null;
        },

        buildWhatsappMessage() {
            let msg = 'Hola! Pedido desde la carta:\n';
            this.items.forEach(i => {
                const label = i.variantName ? i.name + ' (' + i.variantName + ')' : i.name;
                msg += i.qty + 'x ' + label + ' ' + this.formatPrice(i.price) + '\n';
            });
            msg += 'Total: ' + this.formatPrice(this.totalPrice()) + '\n';
            if (acceptsDelivery) {
                msg += 'Retiro en local o delivery';
            } else {
                msg += 'Retiro en local';
            }
            return msg;
        },

        sendWhatsApp() {
            if (!whatsappNum) return;
            const msg = this.buildWhatsappMessage();
            window.open('https://wa.me/' + whatsappNum + '?text=' + encodeURIComponent(msg), '_blank');
        },
    };
}
</script>

{{-- Floating cart button --}}
<div x-show="acceptsOrders && totalCount() > 0"
     x-data="{ acceptsOrders: {{ $restaurant->accepts_orders ? 'true' : 'false' }} }"
     style="display:none;">
</div>

{{-- Cart FAB button --}}
<button
    @click="cartOpen = true"
    x-show="totalCount() > 0"
    x-cloak
    style="position:fixed;bottom:22px;right:18px;z-index:50;width:58px;height:58px;border-radius:50%;background:#1C1917;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,.35);outline:none;"
    aria-label="Ver carrito">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
    <span x-text="totalCount()"
          style="position:absolute;top:-3px;right:-3px;min-width:20px;height:20px;border-radius:999px;background:#E85D2F;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #fff;"></span>
</button>

{{-- Overlay --}}
<div @click="cartOpen = false"
     x-show="cartOpen"
     x-cloak
     style="position:fixed;inset:0;background:rgba(0,0,0,.42);z-index:51;backdrop-filter:blur(2px);">
</div>

{{-- Cart Drawer --}}
<div x-show="cartOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="transform translate-y-full"
     x-transition:enter-end="transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform translate-y-0"
     x-transition:leave-end="transform translate-y-full"
     style="position:fixed;bottom:0;left:0;right:0;z-index:52;background:#fff;border-radius:20px 20px 0 0;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 -4px 32px rgba(0,0,0,.18);">

    {{-- Drawer handle --}}
    <div style="display:flex;justify-content:center;padding:12px 0 0;">
        <div style="width:36px;height:4px;border-radius:2px;background:#E5E7EB;"></div>
    </div>

    {{-- Drawer header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px 10px;border-bottom:1px solid #F3F4F6;">
        <div style="font-size:16px;font-weight:700;color:#111827;">Tu pedido</div>
        <button @click="cartOpen = false"
                style="width:28px;height:28px;border-radius:8px;background:#F3F4F6;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Items list --}}
    <div style="overflow-y:auto;flex:1;padding:10px 18px;">
        <template x-for="item in items" :key="item.key">
            <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #F9FAFB;">
                <div style="flex:1;min-width:0;">
                    <div x-text="item.variantName ? item.name + ' (' + item.variantName + ')' : item.name"
                         style="font-size:13.5px;font-weight:600;color:#111827;"></div>
                    <div x-text="formatPrice(item.price)"
                         style="font-size:12px;color:#6B7280;margin-top:2px;"></div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <button @click="removeItem(item.key)"
                            style="width:26px;height:26px;border-radius:8px;background:#F3F4F6;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;color:#374151;font-weight:600;">
                        −
                    </button>
                    <span x-text="item.qty" style="font-size:13px;font-weight:700;min-width:18px;text-align:center;"></span>
                    <button @click="addItem(item.id, item.name, item.price, item.variantName)"
                            style="width:26px;height:26px;border-radius:8px;background:#111827;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:600;">
                        +
                    </button>
                </div>
                <div x-text="formatPrice(item.price * item.qty)"
                     style="font-size:13px;font-weight:700;color:#111827;min-width:60px;text-align:right;"></div>
            </div>
        </template>
    </div>

    {{-- Total & CTA --}}
    <div style="padding:14px 18px 24px;border-top:1px solid #F3F4F6;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:15px;font-weight:700;color:#111827;">Total</span>
            <span x-text="formatPrice(totalPrice())" style="font-size:17px;font-weight:800;color:#111827;"></span>
        </div>
        <button @click="sendWhatsApp()"
                style="width:100%;padding:14px;border-radius:12px;background:#25D366;color:#fff;border:none;cursor:pointer;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/>
            </svg>
            Pedir por WhatsApp
        </button>
    </div>
</div>

{{-- Variant Modal --}}
<div x-show="showVariantModal"
     x-cloak
     style="position:fixed;inset:0;z-index:60;display:flex;align-items:flex-end;justify-content:center;padding:0;">

    <div @click="showVariantModal = false; pendingItem = null;"
         style="position:absolute;inset:0;background:rgba(0,0,0,.45);"></div>

    <div style="position:relative;width:100%;max-width:440px;background:#fff;border-radius:20px 20px 0 0;padding:20px 18px 32px;z-index:61;">
        <div style="font-size:16px;font-weight:700;color:#111827;margin-bottom:4px;" x-text="pendingItem ? pendingItem.name : ''"></div>
        <div style="font-size:13px;color:#6B7280;margin-bottom:16px;">Elige una opción</div>

        <div style="display:flex;flex-direction:column;gap:8px;">
            <template x-for="variant in (pendingItem ? pendingItem.variants : [])" :key="variant.name">
                <button @click="addVariant(variant)"
                        style="width:100%;padding:12px 14px;border-radius:10px;background:#F9FAFB;border:1px solid #E5E7EB;cursor:pointer;display:flex;align-items:center;justify-content:space-between;text-align:left;">
                    <span x-text="variant.name" style="font-size:14px;font-weight:600;color:#111827;"></span>
                    <span x-text="variant.price_delta > 0 ? '+' + formatPrice(variant.price_delta) : (variant.price_delta < 0 ? formatPrice(variant.price_delta) : '')"
                          style="font-size:13px;font-weight:600;color:#6B7280;"></span>
                </button>
            </template>
        </div>

        <button @click="showVariantModal = false; pendingItem = null;"
                style="width:100%;margin-top:12px;padding:11px;border-radius:10px;background:#F3F4F6;border:none;cursor:pointer;font-size:13px;font-weight:600;color:#374151;">
            Cancelar
        </button>
    </div>
</div>

@endif
