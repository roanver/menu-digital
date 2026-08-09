<x-admin-layout>
@php
$verticalsJs = collect(config('verticals'))->map(fn($v) => [
    'label'       => $v['label'],
    'items_label' => $v['items_label'],
    'url_label'   => $v['url_label'],
])->toArray();
@endphp

<div class="max-w-[760px] space-y-4"
     x-data="{
        acceptsOrders: {{ old('accepts_orders', $restaurant->accepts_orders) ? 'true' : 'false' }},
        acceptsDelivery: {{ old('accepts_delivery', $restaurant->accepts_delivery) ? 'true' : 'false' }},
        logoPreview: null,
        selectedType: '{{ old('type', $restaurant->type ?: 'restaurant') }}',
        savedType: '{{ $restaurant->type ?: 'restaurant' }}',
        verticals: @js($verticalsJs),
        get menuLabel() {
            return 'Dirección del ' + ((this.verticals[this.selectedType] || {}).url_label || 'menú');
        },
        selectType(key) {
            if (key === this.selectedType) return;
            if (key !== this.savedType) {
                var from  = (this.verticals[this.savedType]  || {}).label       || this.savedType;
                var to    = (this.verticals[key]             || {}).label       || key;
                var items = ((this.verticals[this.savedType] || {}).items_label || 'ítems').toLowerCase();
                var ok = confirm(
                    'Cambiar de ' + from + ' a ' + to + '\n\n' +
                    'Tus ' + items + ' y categorías se conservan sin cambios. ' +
                    'Solo cambian las etiquetas en el panel y en el menú público.'
                );
                if (!ok) return;
            }
            this.selectedType = key;
        },
        handleLogo(e) {
            var file = e.target.files[0];
            if (file) this.logoPreview = URL.createObjectURL(file);
        }
     }">
    <form method="POST" action="{{ route('admin.restaurant.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="type" :value="selectedType">

        {{-- Card: Tipo de negocio --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6] flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-bold text-[#111827]">Tipo de negocio</div>
                    <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Define cómo se organiza tu contenido y qué funciones están disponibles</p>
                </div>
                <a href="{{ route('admin.restaurants.create') }}"
                   class="flex-none flex items-center gap-[5px] bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[9px] px-[11px] py-[7px] text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors whitespace-nowrap">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Agregar negocio
                </a>
            </div>
            <div class="px-5 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach(config('verticals') as $key => $v)
                    @if($key === 'services' && ($restaurant->type ?? 'restaurant') !== 'services')
                        @continue
                    @endif
                    @if($key === 'store')
                        @continue {{-- tienda: temporalmente deshabilitada --}}
                    @endif
                    <div class="relative flex flex-col gap-2 p-4 rounded-[12px] border-2 cursor-pointer transition-colors"
                         :class="selectedType === '{{ $key }}' ? 'border-[#4F46E5] bg-[#EEF2FF]' : 'border-[#E5E7EB] bg-white hover:border-[#C7D2FE]'"
                         @click="selectType('{{ $key }}')">
                        <svg class="w-[20px] h-[20px]"
                             :class="selectedType === '{{ $key }}' ? 'text-[#4F46E5]' : 'text-[#9CA3AF]'"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $v['icon'] }}"/>
                        </svg>
                        <div>
                            <div class="text-[13px] font-bold"
                                 :class="selectedType === '{{ $key }}' ? 'text-[#4F46E5]' : 'text-[#111827]'">{{ $v['label'] }}</div>
                            <div class="text-[11px] text-[#9CA3AF] mt-[2px]">{{ $v['items_label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card: Información básica --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Información básica</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Logo, nombre y datos de contacto</p>
            </div>

            <div class="px-5 py-5">
                {{-- Logo upload block --}}
                <div class="flex items-start gap-5 flex-wrap pb-5 border-b border-[#F3F4F6] mb-5">
                    {{-- Logo preview / zone --}}
                    <div class="w-[96px] h-[96px] rounded-[16px] bg-[#F9FAFB] border-2 border-dashed border-[#D1D5DB] flex-none overflow-hidden relative">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!logoPreview">
                            @if($restaurant->logo)
                                <img src="{{ Storage::url($restaurant->logo) }}" class="w-full h-full object-cover" alt="Logo">
                            @else
                            <div class="w-full h-full flex flex-col items-center justify-center gap-1">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span class="text-[10px] font-semibold text-[#9CA3AF]">Logo</span>
                            </div>
                            @endif
                        </template>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <div class="text-[13.5px] font-bold text-[#111827] mb-1">Logo del negocio</div>
                        <div class="text-[12px] text-[#6B7280] leading-relaxed mb-3">PNG o JPG cuadrado. Mínimo 512×512 px, hasta 2 MB.</div>
                        <label class="inline-flex items-center gap-2 cursor-pointer bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                            </svg>
                            Subir logo
                            <input type="file" name="logo" class="sr-only" accept="image/*" @change="handleLogo($event)">
                        </label>
                        @if($restaurant->logo)
                        <p class="text-[11px] text-[#9CA3AF] mt-2">Logo actual · Sube uno nuevo para reemplazarlo</p>
                        @endif
                    </div>
                </div>

                {{-- Fields grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Nombre --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Nombre del negocio</span>
                        <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" required
                               placeholder="Ej. La Trattoria"
                               class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                        @error('name')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Slug (read-only) --}}
                    <div class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]" x-text="menuLabel">Dirección del menú</span>
                        <div class="flex border border-[#E5E7EB] rounded-[10px] overflow-hidden shadow-[0_1px_2px_rgba(16,24,40,.03)] bg-[#F9FAFB]">
                            <span class="border-r border-[#E5E7EB] px-[10px] flex items-center text-[12px] text-[#6B7280] whitespace-nowrap font-medium">{{ parse_url(url('/'), PHP_URL_HOST) }}/</span>
                            <span class="px-3 py-[10px] text-[13.5px] text-[#374151] flex-1 min-w-0 truncate">{{ $restaurant->slug }}</span>
                            <span class="border-l border-[#E5E7EB] px-[10px] flex items-center text-[#9CA3AF]" title="Fijo — no se puede editar">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-[#9CA3AF]">Fijo para mantener los QR vigentes.</span>
                    </div>

                    {{-- Teléfono --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Teléfono</span>
                        <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}"
                               placeholder="+56 9 8412 7730"
                               class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                        @error('phone')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- WhatsApp --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">WhatsApp para pedidos</span>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $restaurant->whatsapp) }}"
                               placeholder="56912345678"
                               class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                        <span class="text-[11px] text-[#9CA3AF]">Número con código de país, sin + ni espacios.</span>
                        @error('whatsapp')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Dirección --}}
                    <label class="flex flex-col gap-[6px] sm:col-span-2">
                        <span class="text-[12px] font-semibold text-[#374151]">Dirección</span>
                        <input type="text" name="address" value="{{ old('address', $restaurant->address) }}"
                               placeholder="Av. Providencia 1284, Santiago"
                               class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                        @error('address')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                </div>
            </div>
        </div>

        {{-- Card: Pedidos y delivery --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Pedidos y delivery</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Configura cómo tus clientes hacen pedidos por WhatsApp</p>
            </div>

            <div class="px-5 py-5 flex flex-col gap-4">

                {{-- Toggle: Aceptar pedidos --}}
                <div class="flex items-center justify-between gap-4 p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB]">
                    <div class="flex-1">
                        <div class="text-[13.5px] font-semibold text-[#111827]">Acepta pedidos por WhatsApp</div>
                        <div class="text-[11.5px] text-[#6B7280] mt-0.5">Activa el carrito de compras en el menú público</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-none">
                        <input type="hidden" name="accepts_orders" value="0">
                        <input type="checkbox" name="accepts_orders" value="1" id="accepts_orders"
                               {{ old('accepts_orders', $restaurant->accepts_orders) ? 'checked' : '' }}
                               x-model="acceptsOrders"
                               class="sr-only peer">
                        <div class="w-[42px] h-[24px] bg-[#D1D5DB] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[18px] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-[#4F46E5]"></div>
                    </label>
                </div>

                {{-- Toggle: Aceptar delivery --}}
                <div class="flex items-center justify-between gap-4 p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB]">
                    <div class="flex-1">
                        <div class="text-[13.5px] font-semibold text-[#111827]">Ofrece delivery</div>
                        <div class="text-[11.5px] text-[#6B7280] mt-0.5">Incluye opción de delivery en el mensaje de pedido</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-none">
                        <input type="hidden" name="accepts_delivery" value="0">
                        <input type="checkbox" name="accepts_delivery" value="1" id="accepts_delivery"
                               {{ old('accepts_delivery', $restaurant->accepts_delivery) ? 'checked' : '' }}
                               x-model="acceptsDelivery"
                               class="sr-only peer">
                        <div class="w-[42px] h-[24px] bg-[#D1D5DB] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-[18px] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-[#4F46E5]"></div>
                    </label>
                </div>

                {{-- Delivery fields — only show when delivery is on --}}
                <div x-show="acceptsDelivery" x-transition class="space-y-4 pl-1">
                    {{-- Campo: Zona de delivery --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Zona de delivery</span>
                        <textarea name="delivery_zone" rows="2"
                                  placeholder="Ej. Providencia, Ñuñoa, Santiago Centro (radio 5 km)"
                                  class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF] resize-none">{{ old('delivery_zone', $restaurant->delivery_zone) }}</textarea>
                        @error('delivery_zone')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Campo: Pedido mínimo --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Pedido mínimo</span>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[13px] text-[#374151] font-semibold select-none">$</span>
                            <input type="number" name="min_order" value="{{ old('min_order', $restaurant->min_order) }}"
                                   placeholder="5000" min="0" step="100"
                                   class="w-full pl-7 pr-12 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                            <span class="absolute right-3 text-[11px] text-[#9CA3AF] font-semibold select-none">CLP</span>
                        </div>
                        @error('min_order')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Campo: Costo de delivery --}}
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Costo de delivery</span>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[13px] text-[#374151] font-semibold select-none">$</span>
                            <input type="number" name="delivery_cost" value="{{ old('delivery_cost', $restaurant->delivery_cost) }}"
                                   placeholder="2000" min="0" step="100"
                                   class="w-full pl-7 pr-12 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                            <span class="absolute right-3 text-[11px] text-[#9CA3AF] font-semibold select-none">CLP</span>
                        </div>
                        <span class="text-[11px] text-[#9CA3AF]">Se suma al total del pedido. Usa 0 para delivery gratis.</span>
                        @error('delivery_cost')
                        <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

            </div>
        </div>

        {{-- Sticky save bar --}}
        <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB] py-3 -mx-[clamp(16px,2.4vw,28px)] px-[clamp(16px,2.4vw,28px)] flex justify-end gap-2 shadow-[0_-1px_4px_rgba(16,24,40,.06)]">
            <a href="{{ route('admin.dashboard') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
</x-admin-layout>
