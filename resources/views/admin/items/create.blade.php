<x-admin-layout>
<div class="max-w-[860px]"
     x-data="{
         variants: {{ old('variants') ? json_encode(array_values(old('variants'))) : '[]' }},
         addVariant() { this.variants.push({ name: '', price_delta: 0 }); },
         removeVariant(index) { this.variants.splice(index, 1); },
         previewUrl: null,
         handleFile(e) {
             var file = e.target.files[0];
             if (file) this.previewUrl = URL.createObjectURL(file);
         }
     }">

    <a href="{{ route('admin.items.index') }}"
       class="inline-flex items-center gap-1 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-4 transition-colors">
        ← Volver a items
    </a>

    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4 items-start">

            <!-- LEFT COLUMN -->
            <div class="space-y-4">

                <!-- Datos del item -->
                <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                    <div class="text-[13px] font-bold text-[#111827] mb-4">Datos del item</div>

                    <div class="space-y-4">
                        <!-- Nombre -->
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[12px] font-semibold text-[#374151]">Nombre</span>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                                   placeholder="Ej: Lomo saltado">
                            @error('name')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>

                        <!-- Precio + Categoría -->
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex flex-col gap-[6px]">
                                <span class="text-[12px] font-semibold text-[#374151]">Precio</span>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-[13px] text-[#6B7280] font-medium select-none">$</span>
                                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1" required
                                           class="w-full pl-7 pr-10 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                    <span class="absolute right-3 text-[11px] text-[#9CA3AF] font-medium select-none">CLP</span>
                                </div>
                                @error('price')
                                <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="flex flex-col gap-[6px]">
                                <span class="text-[12px] font-semibold text-[#374151]">Categoría</span>
                                <select name="category_id" required
                                        class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none bg-white">
                                    <option value="">Seleccionar…</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                      class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF] resize-none"
                                      placeholder="Describe brevemente el plato…">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>
                </div>

                <!-- Variantes -->
                <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                    <div class="flex items-center justify-between mb-1">
                        <div class="text-[13px] font-bold text-[#111827]">Variantes</div>
                        <button type="button" @click="addVariant()"
                                class="inline-flex items-center gap-1 text-[12px] font-semibold text-[#4F46E5] hover:text-[#4338CA] transition-colors">
                            + Agregar
                        </button>
                    </div>
                    <p class="text-[11.5px] text-[#9CA3AF] mb-3">Ej: "Porción grande" con precio adicional de +1500</p>

                    <div class="space-y-2">
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text"
                                       :name="'variants[' + index + '][name]'"
                                       x-model="variant.name"
                                       placeholder="Nombre de la variante"
                                       class="flex-1 px-3 py-[8px] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                <div class="relative w-[120px] flex-none">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-[#6B7280] select-none">+$</span>
                                    <input type="number"
                                           :name="'variants[' + index + '][price_delta]'"
                                           x-model="variant.price_delta"
                                           placeholder="0"
                                           class="w-full pl-7 pr-3 py-[8px] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                                </div>
                                <button type="button" @click="removeVariant(index)"
                                        class="w-[30px] h-[30px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors flex-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <p x-show="variants.length === 0" class="text-[12px] text-[#9CA3AF] italic">Sin variantes — el item se venderá a precio fijo.</p>
                </div>

            </div>

            <!-- RIGHT COLUMN -->
            <div class="space-y-4">

                <!-- Foto -->
                <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                    <div class="text-[13px] font-bold text-[#111827] mb-3">Foto</div>

                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-[#E5E7EB] rounded-[12px] p-5 cursor-pointer hover:border-[#4F46E5] hover:bg-[#EEF2FF] transition-colors relative"
                           x-show="!previewUrl">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/><circle cx="8.5" cy="8.5" r="1.5"/>
                        </svg>
                        <span class="text-[12.5px] font-semibold text-[#6B7280]">Subir foto</span>
                        <span class="text-[11px] text-[#9CA3AF]">JPG, PNG o WebP · máx 2 MB</span>
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                               @change="handleFile($event)">
                    </label>

                    <div x-show="previewUrl" class="relative rounded-[12px] overflow-hidden border border-[#E5E7EB]">
                        <img :src="previewUrl" class="w-full h-[160px] object-cover">
                        <button type="button"
                                @click="previewUrl = null; $el.closest('.relative').previousElementSibling && null"
                                class="absolute top-2 right-2 w-[26px] h-[26px] flex items-center justify-center bg-white rounded-full shadow text-[#6B7280] hover:text-[#DC2626] transition-colors">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                        <label class="absolute bottom-0 inset-x-0 bg-black/40 text-white text-[11px] font-semibold py-1.5 text-center cursor-pointer hover:bg-black/60 transition-colors">
                            Cambiar foto
                            <input type="file" name="image" accept="image/*" class="sr-only" @change="handleFile($event)">
                        </label>
                    </div>

                    @error('image')
                    <p class="text-[11.5px] text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opciones -->
                <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                    <div class="text-[13px] font-bold text-[#111827] mb-3">Opciones</div>

                    <input type="hidden" name="is_available" value="0">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative flex-none">
                            <input type="checkbox" name="is_available" value="1" class="sr-only peer"
                                   {{ old('is_available', '1') ? 'checked' : '' }}>
                            <div class="w-[38px] h-[22px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                            <div class="absolute top-[2px] left-[2px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <div>
                            <span class="text-[13px] font-semibold text-[#111827]">Disponible</span>
                            <p class="text-[11.5px] text-[#9CA3AF]">Visible y pedible en el menú</p>
                        </div>
                    </label>
                </div>

            </div>
        </div>

        <!-- Bottom bar -->
        <div class="sticky bottom-0 mt-4 -mx-4 px-4 py-3 bg-white border-t border-[#E5E7EB] flex items-center justify-end gap-2">
            <a href="{{ route('admin.items.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Crear item
            </button>
        </div>
    </form>
</div>
</x-admin-layout>
