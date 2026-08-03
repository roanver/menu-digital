<x-admin-layout>
<x-slot name="head">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@400;600&family=Playfair+Display:wght@400;600&family=Pacifico&family=Oswald:wght@400;600&display=swap" rel="stylesheet">
</x-slot>

<div class="max-w-[700px]">
    <p class="text-[12.5px] text-[#6B7280] mb-5">Elige el diseño y el color principal de tu menú público.</p>

    <form method="POST" action="{{ route('admin.appearance.update') }}">
        @csrf
        @method('PATCH')

        <!-- Plantilla -->
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4 mb-4">
            <div class="text-[13px] font-bold text-[#111827] mb-3">Plantilla</div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                {{-- Minimal --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="minimal" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'minimal' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[12px] p-3 transition-colors hover:border-[#C7D2FE] peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF]">
                        <div class="w-full h-[80px] bg-[#F9FAFB] rounded-[8px] mb-2.5 overflow-hidden border border-[#E5E7EB]">
                            <div class="bg-white h-7 flex items-center px-2 border-b border-[#F3F4F6]">
                                <div class="w-4 h-4 rounded-full bg-[#E5E7EB] mr-1.5"></div>
                                <div class="h-1.5 bg-[#D1D5DB] rounded w-14"></div>
                            </div>
                            <div class="p-1.5 space-y-1">
                                <div class="flex justify-between items-center">
                                    <div class="h-1.5 bg-[#D1D5DB] rounded w-12"></div>
                                    <div class="h-1.5 bg-[#9CA3AF] rounded w-8"></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="h-1.5 bg-[#E5E7EB] rounded w-16"></div>
                                    <div class="h-1.5 bg-[#D1D5DB] rounded w-6"></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-semibold text-[#111827]">Minimal</p>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Blanco y limpio, foco en el contenido</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Dark --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="dark" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'dark' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[12px] p-3 transition-colors hover:border-[#C7D2FE] peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF]">
                        <div class="w-full h-[80px] bg-gray-900 rounded-[8px] mb-2.5 overflow-hidden border border-gray-700">
                            <div class="bg-gray-800 h-7 flex items-center justify-center border-b border-gray-700">
                                <div class="h-1.5 bg-gray-400 rounded w-14"></div>
                            </div>
                            <div class="p-1.5 space-y-1">
                                <div class="h-1.5 bg-green-500 rounded w-10 mb-1.5"></div>
                                <div class="bg-gray-800 rounded p-1">
                                    <div class="flex justify-between">
                                        <div class="h-1.5 bg-gray-400 rounded w-12"></div>
                                        <div class="h-1.5 bg-green-400 rounded w-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-semibold text-[#111827]">Dark</p>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Modo oscuro elegante</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Cards --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="cards" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'cards' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[12px] p-3 transition-colors hover:border-[#C7D2FE] peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF]">
                        <div class="w-full h-[80px] bg-[#F3F4F6] rounded-[8px] mb-2.5 overflow-hidden border border-[#E5E7EB]">
                            <div class="bg-green-600 h-7 flex items-center justify-center">
                                <div class="h-1.5 bg-white/80 rounded w-14"></div>
                            </div>
                            <div class="p-1.5 grid grid-cols-2 gap-1">
                                <div class="bg-white rounded shadow-sm overflow-hidden">
                                    <div class="h-4 bg-[#E5E7EB]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#D1D5DB] rounded w-full mb-0.5"></div>
                                        <div class="h-1 bg-green-400 rounded w-8"></div>
                                    </div>
                                </div>
                                <div class="bg-white rounded shadow-sm overflow-hidden">
                                    <div class="h-4 bg-[#E5E7EB]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#D1D5DB] rounded w-full mb-0.5"></div>
                                        <div class="h-1 bg-green-400 rounded w-8"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-semibold text-[#111827]">Cards</p>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Estilo app de delivery, cuadrícula</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

            </div>
        </div>

        <!-- Colores -->
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4 mb-4">
            <div class="text-[13px] font-bold text-[#111827] mb-3">Colores</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Color principal -->
                <div class="flex items-center gap-3">
                    <input type="color" name="primary_color" id="primary_color"
                           value="{{ old('primary_color', $restaurant->primary_color ?? '#16a34a') }}"
                           class="w-12 h-12 rounded-[10px] border border-[#E5E7EB] cursor-pointer p-1 shadow-[0_1px_2px_rgba(16,24,40,.04)]">
                    <div>
                        <label for="primary_color" class="block text-[12.5px] font-semibold text-[#374151]">Color principal</label>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Encabezado, precios y botón WhatsApp</p>
                    </div>
                </div>
                <!-- Color de fondo -->
                <div class="flex items-center gap-3">
                    <input type="color" name="bg_color" id="bg_color"
                           value="{{ old('bg_color', $restaurant->bg_color ?? '#f9fafb') }}"
                           class="w-12 h-12 rounded-[10px] border border-[#E5E7EB] cursor-pointer p-1 shadow-[0_1px_2px_rgba(16,24,40,.04)]">
                    <div>
                        <label for="bg_color" class="block text-[12.5px] font-semibold text-[#374151]">Color de fondo</label>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Fondo de la página del menú</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fuente -->
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4 mb-4">
            <div class="text-[13px] font-bold text-[#111827] mb-3">Fuente tipográfica</div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach(['Inter', 'Poppins', 'Playfair Display', 'Pacifico', 'Oswald'] as $font)
                <label class="relative cursor-pointer">
                    <input type="radio" name="font" value="{{ $font }}" class="sr-only peer"
                           {{ old('font', $restaurant->font ?? 'Inter') === $font ? 'checked' : '' }}>
                    <div class="border border-[#E5E7EB] rounded-[10px] px-3 py-2.5 text-center transition-colors hover:border-[#C7D2FE] hover:bg-[#F9FAFB] peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF]">
                        <span class="text-[13px] font-medium text-[#374151]" style="font-family: '{{ $font }}', sans-serif;">{{ $font }}</span>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-1.5 right-1.5 w-[14px] h-[14px] bg-[#4F46E5] rounded-full items-center justify-center">
                        <svg width="8" height="8" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Opciones adicionales -->
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4 mb-5">
            <div class="text-[13px] font-bold text-[#111827] mb-3">Opciones adicionales</div>

            <div class="space-y-4">
                <!-- Mostrar precio -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative flex-none">
                        <input type="hidden" name="show_price" value="0">
                        <input type="checkbox" name="show_price" value="1" id="show_price" class="sr-only peer"
                               {{ old('show_price', $restaurant->show_price ?? true) ? 'checked' : '' }}>
                        <div class="w-[38px] h-[22px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2px] left-[2px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <div>
                        <span class="text-[13px] font-semibold text-[#111827]">Mostrar precio</span>
                        <p class="text-[11.5px] text-[#9CA3AF]">Los precios serán visibles en el menú</p>
                    </div>
                </label>

                <!-- Mostrar descripción -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative flex-none">
                        <input type="hidden" name="show_description" value="0">
                        <input type="checkbox" name="show_description" value="1" id="show_description" class="sr-only peer"
                               {{ old('show_description', $restaurant->show_description ?? true) ? 'checked' : '' }}>
                        <div class="w-[38px] h-[22px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2px] left-[2px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <div>
                        <span class="text-[13px] font-semibold text-[#111827]">Mostrar descripción</span>
                        <p class="text-[11.5px] text-[#9CA3AF]">Las descripciones de los platos serán visibles</p>
                    </div>
                </label>

                <!-- Mensaje de bienvenida -->
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Mensaje de bienvenida <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                    <textarea name="welcome_message" id="welcome_message" rows="2" maxlength="255"
                              placeholder="Ej: Bienvenidos, gracias por elegirnos."
                              class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF] resize-none">{{ old('welcome_message', $restaurant->welcome_message) }}</textarea>
                    <p class="text-[11px] text-[#9CA3AF]">Se muestra debajo del encabezado en el menú público.</p>
                </label>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex items-center justify-between">
            <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#4F46E5] hover:text-[#4338CA] transition-colors">
                Ver menú público →
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar apariencia
            </button>
        </div>

    </form>
</div>
</x-admin-layout>
