<x-admin-layout>
<div class="max-w-[860px]"
     x-data="{
         variants: {{ old('variants') ? json_encode(array_values(old('variants'))) : json_encode($item->variants->map(fn($v) => ['name' => $v->name, 'price_delta' => $v->price_delta])->values()->toArray()) }},
         addVariant() { this.variants.push({ name: '', price_delta: 0 }); },
         removeVariant(index) { this.variants.splice(index, 1); },
         previewUrl: {{ $item->image ? "'" . Storage::url($item->image) . "'" : 'null' }},
         removeImage: false,
         handleFile(e) {
             var file = e.target.files[0];
             if (file) { this.previewUrl = URL.createObjectURL(file); this.removeImage = false; }
         }
     }">

    <a href="{{ route('admin.items.index') }}"
       class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-5 transition-colors">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Items
    </a>

    <form id="edit-form" method="POST" action="{{ route('admin.items.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_296px] gap-4 items-start">

            <!-- LEFT COLUMN -->
            <div class="space-y-4">

                <!-- Datos del item -->
                <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#F3F4F6]">
                        <div class="text-[13px] font-bold text-[#111827]">Datos del item</div>
                    </div>
                    <div class="px-5 py-5 space-y-4">
                        @php $vertical = $restaurant->vertical(); @endphp
                        <!-- Nombre -->
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[12px] font-semibold text-[#374151]">Nombre {{ $vertical['has_stock'] ? 'del producto' : 'del plato' }}</span>
                            <input type="text" name="name" value="{{ old('name', $item->name) }}" required autofocus
                                   class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                                   placeholder="Ej: Lomo saltado">
                            @error('name')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>

                        @if($vertical['has_sku'])
                        <!-- SKU -->
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[12px] font-semibold text-[#374151]">SKU / Código <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                            <input type="text" name="sku" value="{{ old('sku', $item->sku) }}"
                                   class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                                   placeholder="Ej: PROD-001">
                            @error('sku')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>
                        @endif

                        <!-- Precio + Categoría -->
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex flex-col gap-[6px]">
                                <span class="text-[12px] font-semibold text-[#374151]">Precio</span>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-[13.5px] text-[#374151] font-semibold select-none">$</span>
                                    <input type="number" name="price" value="{{ old('price', $item->price) }}" min="0" step="1" required
                                           class="w-full pl-7 pr-12 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                    <span class="absolute right-3 text-[11px] text-[#9CA3AF] font-semibold select-none">CLP</span>
                                </div>
                                @error('price')
                                <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="flex flex-col gap-[6px]">
                                <span class="text-[12px] font-semibold text-[#374151]">Categoría</span>
                                <select name="category_id" required
                                        class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none bg-white">
                                    <option value="">Seleccionar…</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <!-- Descripción -->
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[12px] font-semibold text-[#374151]">Descripción <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                            <textarea name="description" rows="3"
                                      class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF] resize-none"
                                      placeholder="Describe brevemente el plato…">{{ old('description', $item->description) }}</textarea>
                            @error('description')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>
                </div>

                <!-- Variantes -->
                <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#F3F4F6] flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[13px] font-bold text-[#111827]">Variantes</span>
                                <div class="group relative">
                                    <svg class="text-[#9CA3AF] cursor-help" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <div class="absolute left-0 bottom-full mb-1 hidden group-hover:block bg-[#111827] text-white text-[11px] rounded-[7px] px-3 py-2 w-[220px] z-10 leading-relaxed">
                                        Distintos tamaños o versiones del mismo plato con precios diferentes.
                                    </div>
                                </div>
                            </div>
                            <p class="text-[11px] text-[#9CA3AF] mt-0.5">Guardar sin variantes las elimina</p>
                        </div>
                        <button type="button" @click="addVariant()"
                                class="inline-flex items-center gap-1 text-[12px] font-semibold text-[#4F46E5] hover:text-[#4338CA] bg-[#EEF2FF] hover:bg-[#E0E7FF] rounded-[8px] px-3 py-1.5 transition-colors">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Agregar
                        </button>
                    </div>

                    <div class="px-5 py-4">
                        <div class="space-y-2">
                            <template x-for="(variant, index) in variants" :key="index">
                                <div class="flex items-center gap-2 p-3 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB]">
                                    <input type="text"
                                           :name="'variants[' + index + '][name]'"
                                           x-model="variant.name"
                                           placeholder="Nombre de la variante"
                                           class="flex-1 px-3 py-[8px] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                    <div class="relative w-[130px] flex-none">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12.5px] text-[#374151] font-semibold select-none">+$</span>
                                        <input type="number"
                                               :name="'variants[' + index + '][price_delta]'"
                                               x-model="variant.price_delta"
                                               placeholder="0"
                                               class="w-full pl-8 pr-3 py-[8px] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                    </div>
                                    <button type="button" @click="removeVariant(index)"
                                            class="w-[30px] h-[30px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors flex-none">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <p x-show="variants.length === 0" class="text-[12.5px] text-[#9CA3AF] italic py-2">Sin variantes — el item se venderá a precio fijo.</p>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN -->
            <div class="space-y-4">

                <!-- Foto -->
                <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#F3F4F6]">
                        <div class="text-[13px] font-bold text-[#111827]">Foto del {{ $vertical['has_stock'] ? 'producto' : 'plato' }}</div>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">JPG, PNG o WebP · máx 2 MB</p>
                    </div>

                    <div class="p-4">
                        {{-- Un solo input real: siempre en el DOM, siempre se envía --}}
                        <input type="file" name="image" accept="image/*" class="hidden"
                               x-ref="fileInput" @change="handleFile($event)">
                        <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">

                        <div x-show="!previewUrl"
                             @click="$refs.fileInput.click()"
                             class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-[#E5E7EB] rounded-[14px] p-7 cursor-pointer hover:border-[#4F46E5] hover:bg-[#EEF2FF] transition-colors">
                            <div class="w-[52px] h-[52px] rounded-[14px] bg-[#F3F4F6] flex items-center justify-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="text-[13px] font-semibold text-[#374151]">Arrastra o toca para subir</span>
                                <p class="text-[11px] text-[#9CA3AF] mt-0.5">Una foto atractiva aumenta los pedidos</p>
                            </div>
                        </div>

                        <div x-show="previewUrl" class="relative rounded-[12px] overflow-hidden border border-[#E5E7EB]">
                            <img :src="previewUrl" class="w-full h-[180px] object-cover">
                            <button type="button"
                                    @click="previewUrl = null; $refs.fileInput.value = ''; removeImage = true"
                                    class="absolute top-2 right-2 w-[28px] h-[28px] flex items-center justify-center bg-white rounded-full shadow-md text-[#6B7280] hover:text-[#DC2626] transition-colors">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                            <div @click="$refs.fileInput.click()"
                                 class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[11px] font-semibold py-2 text-center cursor-pointer hover:bg-black/65 transition-colors">
                                Cambiar foto
                            </div>
                        </div>
                    </div>

                    @error('image')
                    <p class="text-[11.5px] text-[#DC2626] px-4 pb-3">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opciones -->
                <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] p-5 space-y-3"
                     x-data="{ isPromo: {{ old('is_promo', $item->is_promo) ? 'true' : 'false' }} }">
                    <div class="text-[13px] font-bold text-[#111827]">Opciones</div>

                    <input type="hidden" name="is_available" value="0">
                    <label class="flex items-center gap-4 cursor-pointer p-3.5 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                        <div class="relative flex-none">
                            <input type="checkbox" name="is_available" value="1" class="sr-only peer"
                                   {{ old('is_available', $item->is_available) ? 'checked' : '' }}>
                            <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                            <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                        </div>
                        <div>
                            <span class="text-[13px] font-semibold text-[#111827]">Disponible</span>
                            <p class="text-[11.5px] text-[#9CA3AF]">Visible y pedible en el menú</p>
                        </div>
                    </label>

                    <input type="hidden" name="is_promo" value="0">
                    <label class="flex items-center gap-4 cursor-pointer p-3.5 bg-[#FFF7ED] rounded-[10px] border border-[#FED7AA] hover:border-[#FDBA74] transition-colors">
                        <div class="relative flex-none">
                            <input type="checkbox" name="is_promo" value="1" class="sr-only peer"
                                   x-model="isPromo" {{ old('is_promo', $item->is_promo) ? 'checked' : '' }}>
                            <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#EA580C] transition-colors"></div>
                            <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                        </div>
                        <div>
                            <span class="text-[13px] font-semibold text-[#92400E]">En promoción</span>
                            <p class="text-[11.5px] text-[#B45309]">Muestra precio tachado + precio promo</p>
                        </div>
                    </label>

                    <div x-show="isPromo" x-transition class="pt-1">
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[12px] font-semibold text-[#374151]">Precio de promoción</span>
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-[13.5px] text-[#374151] font-semibold select-none">$</span>
                                <input type="number" name="promo_price" value="{{ old('promo_price', $item->promo_price) }}" min="0" step="1"
                                       class="w-full pl-7 pr-12 py-[10px] border border-[#FED7AA] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#EA580C] focus:shadow-[0_0_0_3px_rgba(234,88,12,.12)] focus:outline-none">
                                <span class="absolute right-3 text-[11px] text-[#9CA3AF] font-semibold select-none">CLP</span>
                            </div>
                            @error('promo_price')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    @if($vertical['has_stock'])
                    <label class="flex flex-col gap-[6px] pt-1">
                        <span class="text-[12px] font-semibold text-[#374151]">Stock disponible <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                        <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" min="0" step="1"
                               class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                               placeholder="Dejar vacío = sin límite">
                        @error('stock')
                        <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                        @enderror
                    </label>
                    @endif
                </div>

            </div>
        </div>

        <!-- Bottom bar -->
        <div class="sticky bottom-0 mt-4 -mx-4 px-4 py-3 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB] flex items-center justify-between gap-2 shadow-[0_-1px_4px_rgba(16,24,40,.06)]">
            <!-- Destructive — button linked to external delete-form via HTML5 form= attribute -->
            <button type="submit" form="delete-form"
                    onclick="return confirm('¿Eliminar este item permanentemente?')"
                    class="inline-flex items-center gap-1.5 bg-white hover:bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA] rounded-[10px] px-[12px] py-[9px] text-[12.5px] font-semibold transition-colors">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                </svg>
                Eliminar
            </button>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.items.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                    Cancelar
                </a>
                <button type="submit" form="edit-form"
                        class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Guardar cambios
                </button>
            </div>
        </div>
    </form>

    {{-- Delete form sits OUTSIDE the edit form to avoid nested-form HTML5 invalidity --}}
    <form id="delete-form" method="POST" action="{{ route('admin.items.destroy', $item) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>
</x-admin-layout>
