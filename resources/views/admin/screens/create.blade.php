<x-admin-layout>
<div class="max-w-[640px]">

    <a href="{{ route('admin.screens.index') }}"
       class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-5 transition-colors">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Pantallas
    </a>

    <form method="POST" action="{{ route('admin.screens.store') }}" class="space-y-4">
        @csrf

        {{-- Datos --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Datos de la pantalla</div>
            </div>
            <div class="px-5 py-5 space-y-4">

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre <span class="font-normal text-[#9CA3AF]">(ej: Platos, Bebidas, Promos)</span></span>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                           placeholder="Platos principales">
                    @error('name')<p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>@enderror
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Columnas</span>
                        <select name="columns"
                                class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none">
                            @foreach([1,2,3,4] as $c)
                            <option value="{{ $c }}" {{ old('columns', 2) == $c ? 'selected' : '' }}>{{ $c }} columna{{ $c > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Orientación</span>
                        <select name="orientation"
                                class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none">
                            <option value="landscape" {{ old('orientation','landscape') === 'landscape' ? 'selected' : '' }}>Horizontal (TV)</option>
                            <option value="portrait"  {{ old('orientation') === 'portrait'  ? 'selected' : '' }}>Vertical (cartel)</option>
                        </select>
                    </label>
                </div>

            </div>
        </div>

        {{-- Categorías --}}
        @if($categories->isNotEmpty())
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Categorías a mostrar</div>
                <p class="text-[11px] text-[#9CA3AF] mt-0.5">Sin selección = muestra todas</p>
            </div>
            <div class="px-5 py-4 grid grid-cols-2 gap-2">
                @foreach($categories as $cat)
                <label class="flex items-center gap-2.5 cursor-pointer p-2.5 rounded-[9px] hover:bg-[#F9FAFB] border border-transparent hover:border-[#E5E7EB] transition-colors">
                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                           {{ in_array($cat->id, old('category_ids', [])) ? 'checked' : '' }}
                           class="w-4 h-4 rounded accent-[#4F46E5]">
                    <span class="text-[13px] text-[#374151]">{{ $cat->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Opciones --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] p-5 space-y-3">
            <div class="text-[13px] font-bold text-[#111827]">Opciones</div>

            @foreach([
                ['show_images',          'Mostrar imágenes',       'Muestra la foto miniatura de cada producto'],
                ['show_promos_rotation', 'Rotación de promos',     'Banner destacado que rota los platos en promoción cada 15 s'],
                ['is_active',            'Pantalla activa',        'La URL funciona y muestra el menú'],
            ] as [$field, $label, $help])
            <label class="flex items-center gap-4 cursor-pointer p-3.5 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                <div class="relative flex-none">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" class="sr-only peer"
                           {{ old($field, $field === 'is_active' ? '1' : '0') == '1' ? 'checked' : '' }}>
                    <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                    <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                </div>
                <div>
                    <span class="text-[13px] font-semibold text-[#111827]">{{ $label }}</span>
                    <p class="text-[11.5px] text-[#9CA3AF]">{{ $help }}</p>
                </div>
            </label>
            @endforeach
        </div>

        <div class="flex items-center justify-end gap-2 pt-1">
            <a href="{{ route('admin.screens.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Crear pantalla
            </button>
        </div>
    </form>
</div>
</x-admin-layout>
