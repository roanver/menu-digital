<x-admin-layout>
<x-slot name="head">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@400;600&family=Playfair+Display:wght@400;600&family=Pacifico&family=Oswald:wght@400;600&display=swap" rel="stylesheet">
</x-slot>

<div class="max-w-[740px]">
    <p class="text-[12.5px] text-[#6B7280] mb-6">Elige el diseño y el color principal de tu menú público.</p>

    <form method="POST" action="{{ route('admin.appearance.update') }}">
        @csrf
        @method('PATCH')

        <!-- Plantilla -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Plantilla</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">El diseño que verán tus clientes al abrir el menú</p>
            </div>

            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">

                {{-- Minimal --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="minimal" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'minimal' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#FBFAF7] rounded-[10px] mb-3 overflow-hidden border border-[#E5E7EB] flex flex-col items-center justify-center gap-1.5 p-2">
                            <div class="h-2.5 bg-[#1C1917] rounded-sm w-20" style="font-family:serif"></div>
                            <div class="flex items-center gap-1 w-full justify-center">
                                <div class="h-px bg-[#D6D3D1] w-6"></div>
                                <div class="w-1 h-1 bg-[#A8A29E] rotate-45"></div>
                                <div class="h-px bg-[#D6D3D1] w-6"></div>
                            </div>
                            <div class="flex justify-between items-center w-full px-2">
                                <div class="h-1 bg-[#D6D3D1] rounded w-14"></div>
                                <div class="h-1 bg-[#9CA3AF] rounded w-6"></div>
                            </div>
                            <div class="flex justify-between items-center w-full px-2">
                                <div class="h-1 bg-[#E7E5E4] rounded w-16"></div>
                                <div class="h-1 bg-[#D6D3D1] rounded w-5"></div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Minimal</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Editorial serif, crema</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Dark --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="dark" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'dark' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#09090E] rounded-[10px] mb-3 overflow-hidden border border-[#26262F]">
                            <div class="p-1.5">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <div class="w-5 h-5 rounded-md bg-[#312E81]"></div>
                                    <div class="h-1.5 bg-[#F4F4F5] rounded w-14"></div>
                                </div>
                                <div class="bg-[#15151C] border border-[#23232D] rounded-lg p-1 flex gap-1">
                                    <div class="w-8 h-8 rounded bg-[#2E2620] flex-shrink-0"></div>
                                    <div class="flex flex-col gap-0.5 justify-center">
                                        <div class="h-1 bg-[#F4F4F5] rounded w-12"></div>
                                        <div class="h-1 bg-[#C7D2FE] rounded w-8"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Dark</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Modo oscuro, índigo</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Cards --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="cards" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'cards' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#F7F8FA] rounded-[10px] mb-3 overflow-hidden border border-[#E5E7EB]">
                            <div class="h-[30px] bg-gradient-to-r from-[#4F46E5] to-[#312E81] flex items-center px-2">
                                <div class="h-1.5 bg-white/80 rounded w-14"></div>
                            </div>
                            <div class="p-1.5 grid grid-cols-2 gap-1">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-[#EAECF0]">
                                    <div class="h-4 bg-[#EFE6D4]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#D1D5DB] rounded w-full mb-0.5"></div>
                                        <div class="h-1 bg-[#4F46E5] rounded w-5"></div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-[#EAECF0]">
                                    <div class="h-4 bg-[#E9EBDD]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#D1D5DB] rounded w-full mb-0.5"></div>
                                        <div class="h-1 bg-[#4F46E5] rounded w-5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Cards</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Cuadrícula, hero índigo</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Carta --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="carta" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'carta' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#F6F1E7] rounded-[10px] mb-3 overflow-hidden border border-[#211A12]">
                            <div style="border:1px solid rgba(33,26,18,.2);margin:5px;height:calc(100% - 10px);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;">
                                <div class="h-1.5 bg-[#8A7B66] rounded-sm w-12"></div>
                                <div class="h-3 bg-[#211A12] rounded-sm w-20" style="font-family:serif"></div>
                                <div class="flex items-center gap-1">
                                    <div class="h-px bg-[#211A12] w-5"></div>
                                    <div class="text-[8px] text-[#211A12]">❦</div>
                                    <div class="h-px bg-[#211A12] w-5"></div>
                                </div>
                                <div class="flex justify-between w-full px-2">
                                    <div class="h-1 bg-[#D6D3D1] rounded w-12"></div>
                                    <div class="h-1 bg-[#8A7B66] rounded w-6"></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Carta</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Papel e imprenta</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Brasa --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="brasa" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'brasa' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#0C0A09] rounded-[10px] mb-3 overflow-hidden border border-[#292524]">
                            <div class="p-2">
                                <div class="h-3 w-24 rounded-sm mb-2" style="background:linear-gradient(90deg,#FAFAF9,#F59E0B)"></div>
                                <div class="rounded-lg overflow-hidden border border-[#292524] flex gap-1.5 p-1">
                                    <div class="w-8 h-8 rounded-md flex-shrink-0" style="background:radial-gradient(circle at 30% 20%,rgba(245,158,11,.3),transparent 60%),#2E2018"></div>
                                    <div class="flex flex-col gap-0.5 justify-center">
                                        <div class="h-1 bg-[#FAFAF9] rounded w-12"></div>
                                        <div class="h-1 bg-[#F59E0B] rounded w-8"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Brasa</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Parrilla nocturna</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

                {{-- Feria --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="template" value="feria" class="sr-only peer"
                           {{ old('template', $restaurant->template ?? 'minimal') === 'feria' ? 'checked' : '' }}>
                    <div class="border-2 border-[#E5E7EB] rounded-[14px] p-3 transition-all hover:border-[#C7D2FE] hover:shadow-md peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <div class="w-full h-[88px] bg-[#FFF6DE] rounded-[10px] mb-3 overflow-hidden border-[1.5px] border-[#191410]">
                            <div class="bg-[#191410] h-[10px] w-full"></div>
                            <div class="p-1.5 grid grid-cols-2 gap-1">
                                <div class="bg-white rounded-lg overflow-hidden" style="border:1.5px solid #191410;box-shadow:2px 2px 0 #191410">
                                    <div class="h-4 bg-[#FFE08A]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#191410] rounded w-full mb-0.5"></div>
                                        <div class="inline-block h-1.5 bg-[#FFD84D] rounded w-5" style="border:1px solid #191410"></div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg overflow-hidden" style="border:1.5px solid #191410;box-shadow:2px 2px 0 #191410">
                                    <div class="h-4 bg-[#FFC7B3]"></div>
                                    <div class="p-0.5">
                                        <div class="h-1 bg-[#191410] rounded w-full mb-0.5"></div>
                                        <div class="inline-block h-1.5 bg-[#FFD84D] rounded w-5" style="border:1px solid #191410"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[13px] font-bold text-[#111827]">Feria</p>
                        <p class="text-[10.5px] text-[#9CA3AF] mt-0.5">Pop, stickers, delivery</p>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-2.5 right-2.5 w-[18px] h-[18px] bg-[#4F46E5] rounded-full items-center justify-center shadow-md">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>

            </div>
        </div>

        <!-- Colores -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Colores</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Personaliza los colores del menú público</p>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Color principal -->
                <div class="flex items-center gap-4 p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB]">
                    <input type="color" name="primary_color" id="primary_color"
                           value="{{ old('primary_color', $restaurant->primary_color ?? '#16a34a') }}"
                           class="w-[48px] h-[48px] rounded-[10px] border border-[#E5E7EB] cursor-pointer p-1 shadow-[0_1px_2px_rgba(16,24,40,.04)]">
                    <div>
                        <label for="primary_color" class="block text-[13px] font-semibold text-[#111827]">Color principal</label>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Encabezado, precios, botón WhatsApp</p>
                    </div>
                </div>
                <!-- Color de fondo -->
                <div class="flex items-center gap-4 p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB]">
                    <input type="color" name="bg_color" id="bg_color"
                           value="{{ old('bg_color', $restaurant->bg_color ?? '#f9fafb') }}"
                           class="w-[48px] h-[48px] rounded-[10px] border border-[#E5E7EB] cursor-pointer p-1 shadow-[0_1px_2px_rgba(16,24,40,.04)]">
                    <div>
                        <label for="bg_color" class="block text-[13px] font-semibold text-[#111827]">Color de fondo</label>
                        <p class="text-[11px] text-[#9CA3AF] mt-0.5">Fondo de la página del menú</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fuente -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Fuente tipográfica</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Afecta los textos del menú público</p>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach(['Inter', 'Poppins', 'Playfair Display', 'Pacifico', 'Oswald'] as $font)
                <label class="relative cursor-pointer">
                    <input type="radio" name="font" value="{{ $font }}" class="sr-only peer"
                           {{ old('font', $restaurant->font ?? 'Inter') === $font ? 'checked' : '' }}>
                    <div class="border border-[#E5E7EB] rounded-[10px] px-3 py-3 text-center transition-all hover:border-[#C7D2FE] hover:bg-[#F9FAFB] peer-checked:border-[#4F46E5] peer-checked:bg-[#EEF2FF] peer-checked:shadow-[0_0_0_2px_rgba(79,70,229,.15)]">
                        <span class="text-[15px] font-medium text-[#374151] block leading-tight" style="font-family: '{{ $font }}', sans-serif;">{{ $font }}</span>
                        <span class="text-[10px] text-[#9CA3AF] mt-1 block" style="font-family: '{{ $font }}', sans-serif;">Aa Bb Cc</span>
                    </div>
                    <div class="hidden peer-checked:flex absolute top-1.5 right-1.5 w-[14px] h-[14px] bg-[#4F46E5] rounded-full items-center justify-center">
                        <svg width="8" height="8" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Opciones adicionales -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden mb-5">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Opciones adicionales</div>
            </div>
            <div class="p-5 space-y-4">
                <!-- Mostrar precio -->
                <label class="flex items-center gap-4 cursor-pointer p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                    <div class="relative flex-none">
                        <input type="hidden" name="show_price" value="0">
                        <input type="checkbox" name="show_price" value="1" id="show_price" class="sr-only peer"
                               {{ old('show_price', $restaurant->show_price ?? true) ? 'checked' : '' }}>
                        <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                    </div>
                    <div>
                        <span class="text-[13.5px] font-semibold text-[#111827]">Mostrar precios</span>
                        <p class="text-[11.5px] text-[#9CA3AF]">Los precios serán visibles en el menú</p>
                    </div>
                </label>

                <!-- Mostrar descripción -->
                <label class="flex items-center gap-4 cursor-pointer p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                    <div class="relative flex-none">
                        <input type="hidden" name="show_description" value="0">
                        <input type="checkbox" name="show_description" value="1" id="show_description" class="sr-only peer"
                               {{ old('show_description', $restaurant->show_description ?? true) ? 'checked' : '' }}>
                        <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                    </div>
                    <div>
                        <span class="text-[13.5px] font-semibold text-[#111827]">Mostrar descripciones</span>
                        <p class="text-[11.5px] text-[#9CA3AF]">Las descripciones de los platos serán visibles</p>
                    </div>
                </label>

                <!-- Mensaje de bienvenida -->
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Mensaje de bienvenida <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                    <textarea name="welcome_message" id="welcome_message" rows="2" maxlength="255"
                              placeholder="Ej: Bienvenidos, gracias por elegirnos."
                              class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF] resize-none">{{ old('welcome_message', $restaurant->welcome_message) }}</textarea>
                    <p class="text-[11px] text-[#9CA3AF]">Se muestra debajo del encabezado en el menú público.</p>
                </label>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex items-center justify-between">
            <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#4F46E5] hover:text-[#4338CA] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                Ver menú público
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar apariencia
            </button>
        </div>

    </form>
</div>
</x-admin-layout>
