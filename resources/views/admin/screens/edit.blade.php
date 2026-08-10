<x-admin-layout>
<div class="max-w-[640px]">

    <a href="{{ route('admin.screens.index') }}"
       class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-5 transition-colors">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Pantallas
    </a>

    <form id="edit-form" method="POST" action="{{ route('admin.screens.update', $screen) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')

        {{-- Datos --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Datos de la pantalla</div>
            </div>
            <div class="px-5 py-5 space-y-4">

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre</span>
                    <input type="text" name="name" value="{{ old('name', $screen->name) }}" required autofocus
                           class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none">
                    @error('name')<p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>@enderror
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Columnas</span>
                        <select name="columns"
                                class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none">
                            @foreach([1,2,3,4] as $c)
                            <option value="{{ $c }}" {{ old('columns', $screen->columns) == $c ? 'selected' : '' }}>{{ $c }} columna{{ $c > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Orientación</span>
                        <select name="orientation"
                                class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] bg-white focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none">
                            <option value="landscape" {{ old('orientation', $screen->orientation) === 'landscape' ? 'selected' : '' }}>Horizontal (TV)</option>
                            <option value="portrait"  {{ old('orientation', $screen->orientation) === 'portrait'  ? 'selected' : '' }}>Vertical (cartel)</option>
                        </select>
                    </label>
                </div>

            </div>
        </div>

        {{-- Estilo visual --}}
        @php
        $themes = [
            'dark'  => ['label' => 'Oscuro',  'bg' => '#111115', 'text' => '#F2F2F0', 'acc' => '#6366F1'],
            'warm'  => ['label' => 'Cálido',  'bg' => '#1C0F08', 'text' => '#F5ECD7', 'acc' => '#F59E0B'],
            'slate' => ['label' => 'Pizarra', 'bg' => '#0F172A', 'text' => '#E2E8F0', 'acc' => '#38BDF8'],
            'chalk' => ['label' => 'Tiza',    'bg' => '#1E1B18', 'text' => '#F7F4F0', 'acc' => '#A3E635'],
            'neon'  => ['label' => 'Neón',    'bg' => '#080812', 'text' => '#F0F0FF', 'acc' => '#EC4899'],
        ];
        $currentTheme  = old('theme', $screen->theme ?? 'dark');
        $currentAccent = old('accent_color', $screen->accent_color ?? '');
        @endphp
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden"
             x-data="{
                theme: '{{ $currentTheme }}',
                customAccent: {{ $currentAccent ? 'true' : 'false' }},
                accentVal: '{{ $currentAccent ?: '#6366F1' }}'
             }">

            <input type="hidden" name="theme" :value="theme">
            <input type="hidden" name="accent_color" :value="customAccent ? accentVal : ''">

            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Estilo visual</div>
            </div>
            <div class="px-5 py-5 space-y-5">

                {{-- Selector de tema --}}
                <div>
                    <span class="text-[12px] font-semibold text-[#374151] block mb-3">Tema de color</span>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($themes as $key => $t)
                        <button type="button"
                                x-on:click="theme = '{{ $key }}'"
                                :class="theme === '{{ $key }}' ? 'ring-2 ring-[#4F46E5] ring-offset-2 scale-[1.03]' : 'ring-1 ring-[#E5E7EB] hover:ring-[#C7D2FE]'"
                                class="rounded-[10px] overflow-hidden transition-all cursor-pointer focus:outline-none text-left">
                            <div style="background:{{ $t['bg'] }};padding:10px 9px 8px;">
                                <div style="font-size:7.5px;font-weight:800;color:{{ $t['text'] }};opacity:.55;letter-spacing:.12em;text-transform:uppercase;margin-bottom:5px;padding-left:6px;border-left:2px solid {{ $t['acc'] }};">PLATOS</div>
                                <div style="display:flex;align-items:center;gap:3px;margin-bottom:3px;">
                                    <div style="font-size:8.5px;color:{{ $t['text'] }};flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Empanada</div>
                                    <div style="font-size:9px;font-weight:800;color:{{ $t['acc'] }};">$2.500</div>
                                </div>
                                <div style="display:flex;align-items:center;gap:3px;">
                                    <div style="font-size:8.5px;color:{{ $t['text'] }};flex:1;">Lomo</div>
                                    <div style="font-size:9px;font-weight:800;color:{{ $t['acc'] }};">$8.900</div>
                                </div>
                            </div>
                            <div style="background:#F9FAFB;padding:5px 8px;text-align:center;border-top:1px solid #E5E7EB;">
                                <span style="font-size:11px;font-weight:600;color:#374151;">{{ $t['label'] }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    @error('theme')<p class="text-[11.5px] text-[#DC2626] mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Color de acento --}}
                <div>
                    <label class="flex items-center gap-3 mb-3 cursor-pointer">
                        <div class="relative flex-none">
                            <input type="checkbox" x-model="customAccent" class="sr-only peer">
                            <div class="w-[38px] h-[22px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                            <div class="absolute top-[2px] left-[2px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.25)] transition-transform peer-checked:translate-x-[16px]"></div>
                        </div>
                        <div>
                            <span class="text-[13px] font-semibold text-[#374151]">Color de acento personalizado</span>
                            <p class="text-[11px] text-[#9CA3AF]">Para precios y etiqueta "Oferta". Por defecto usa el color del restaurante.</p>
                        </div>
                    </label>
                    <div x-show="customAccent" x-cloak class="flex items-center gap-3 ml-[52px]">
                        <input type="color" x-model="accentVal"
                               class="w-10 h-10 rounded-[8px] border border-[#E5E7EB] cursor-pointer p-1 bg-white">
                        <span class="font-mono text-[13px] text-[#6B7280]" x-text="accentVal"></span>
                    </div>
                </div>

                {{-- Imagen de fondo --}}
                <div>
                    <span class="text-[12px] font-semibold text-[#374151] block mb-2">Imagen de fondo <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>

                    @if($screen->bg_image)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB]">
                        <img src="{{ Storage::url($screen->bg_image) }}"
                             class="w-20 h-14 object-cover rounded-[8px] flex-none" alt="">
                        <div class="flex-1 min-w-0">
                            <p class="text-[12px] font-semibold text-[#374151] mb-1.5">Imagen actual</p>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_bg_image" value="1"
                                       class="w-4 h-4 rounded accent-[#DC2626]">
                                <span class="text-[12px] text-[#DC2626] font-medium">Eliminar imagen</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <input type="file" name="bg_image" accept="image/*"
                           class="block w-full text-[13px] text-[#6B7280] file:mr-3 file:px-3 file:py-1.5 file:rounded-[8px] file:border file:border-[#E5E7EB] file:text-[12px] file:font-semibold file:text-[#374151] file:bg-white file:cursor-pointer hover:file:bg-[#F9FAFB] transition-colors">
                    <p class="text-[11px] text-[#9CA3AF] mt-1.5">PNG, JPG o WEBP · Máx. 5 MB. Se aplica una capa oscura para que el texto sea legible.</p>
                    @error('bg_image')<p class="text-[11.5px] text-[#DC2626] mt-1">{{ $message }}</p>@enderror
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
                @php $checked = in_array($cat->id, old('category_ids', $screen->category_ids ?? [])); @endphp
                <label class="flex items-center gap-2.5 cursor-pointer p-2.5 rounded-[9px] hover:bg-[#F9FAFB] border border-transparent hover:border-[#E5E7EB] transition-colors">
                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                           {{ $checked ? 'checked' : '' }}
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
                ['show_images',          'Mostrar imágenes',   'Muestra la foto miniatura de cada producto'],
                ['show_promos_rotation', 'Rotación de promos', 'Banner destacado que rota los platos en promoción cada 15 s'],
                ['is_active',            'Pantalla activa',    'La URL funciona y muestra el menú'],
            ] as [$field, $label, $help])
            <label class="flex items-center gap-4 cursor-pointer p-3.5 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                <div class="relative flex-none">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" class="sr-only peer"
                           {{ old($field, $screen->{$field} ? '1' : '0') == '1' ? 'checked' : '' }}>
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

        <div class="flex items-center justify-between gap-2 pt-1">
            <button type="submit" form="delete-form"
                    onclick="return confirm('¿Eliminar la pantalla &quot;{{ addslashes($screen->name) }}&quot;?')"
                    class="inline-flex items-center gap-1.5 bg-white hover:bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA] rounded-[10px] px-3 py-[9px] text-[12.5px] font-semibold transition-colors">
                Eliminar
            </button>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.screens.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" form="edit-form"
                        class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Guardar cambios
                </button>
            </div>
        </div>
    </form>

    {{-- Form de eliminar fuera del form de edición para evitar anidamiento --}}
    <form id="delete-form" method="POST" action="{{ route('admin.screens.destroy', $screen) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>
</x-admin-layout>
