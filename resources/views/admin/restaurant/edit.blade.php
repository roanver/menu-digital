<x-admin-layout>
<div class="max-w-[760px] space-y-4">
    <form method="POST" action="{{ route('admin.restaurant.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Card: Identidad --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[18px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
            <div class="text-[13px] font-bold mb-[14px]">Identidad</div>

            {{-- Logo upload block --}}
            <div class="flex items-center gap-4 flex-wrap pb-[18px] border-b border-[#F3F4F6] mb-[18px]">
                {{-- Logo preview --}}
                <div class="w-[88px] h-[88px] rounded-[14px] bg-[#F9FAFB] border border-dashed border-[#D1D5DB] flex flex-col items-center justify-center gap-[5px] flex-none overflow-hidden">
                    @if($restaurant->logo)
                        <img src="{{ Storage::url($restaurant->logo) }}" class="w-full h-full object-cover" alt="Logo">
                    @else
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span class="text-[10px] font-semibold text-[#9CA3AF]">Logo</span>
                    @endif
                </div>

                <div class="flex-1 min-w-[200px]">
                    <div class="text-[12.5px] font-semibold mb-1">Logo del restaurante</div>
                    <div class="text-[11.5px] text-[#6B7280] leading-relaxed mb-[10px]">PNG o JPG. Mínimo 512×512 px, hasta 2 MB.</div>
                    <div class="flex gap-2 flex-wrap">
                        <label class="flex items-center gap-[6px] cursor-pointer bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                            </svg>
                            Subir logo
                            <input type="file" name="logo" class="sr-only" accept="image/*">
                        </label>
                        @if($restaurant->logo)
                        <span class="flex items-center text-[11.5px] text-[#6B7280]">Logo actual · Sube uno nuevo para reemplazarlo</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Fields grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">

                {{-- Nombre --}}
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre del restaurante</span>
                    <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" required
                           placeholder="Ej. La Trattoria"
                           class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                    @error('name')
                    <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Slug (read-only display) --}}
                <div class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Dirección del menú</span>
                    <div class="flex border border-[#E5E7EB] rounded-[10px] overflow-hidden shadow-[0_1px_2px_rgba(16,24,40,.03)]">
                        <span class="bg-[#F9FAFB] border-r border-[#E5E7EB] px-[10px] flex items-center text-[12.5px] text-[#6B7280] whitespace-nowrap">{{ parse_url(url('/'), PHP_URL_HOST) }}/</span>
                        <span class="px-3 py-[9px] text-[13.5px] text-[#111827] flex-1 min-w-0 truncate">{{ $restaurant->slug }}</span>
                    </div>
                    <span class="text-[11px] text-[#9CA3AF]">No editable — mantiene los QR vigentes.</span>
                </div>

                {{-- Teléfono --}}
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Teléfono</span>
                    <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}"
                           placeholder="+56 9 8412 7730"
                           class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                    @error('phone')
                    <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                    @enderror
                </label>

                {{-- WhatsApp --}}
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">WhatsApp para pedidos</span>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $restaurant->whatsapp) }}"
                           placeholder="56912345678"
                           class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
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
                           class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                    @error('address')
                    <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                    @enderror
                </label>

            </div>
        </div>

        {{-- Sticky save bar --}}
        <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB] py-3 -mx-[clamp(16px,2.4vw,28px)] px-[clamp(16px,2.4vw,28px)] flex justify-end gap-2">
            <a href="{{ route('admin.dashboard') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
</x-admin-layout>
