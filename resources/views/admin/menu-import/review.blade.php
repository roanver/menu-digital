<x-admin-layout>
<div class="max-w-[760px] space-y-5">

    {{-- Step indicator --}}
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-[24px] h-[24px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="text-[12.5px] font-semibold text-[#047857]">Fotos subidas</span>
        </div>
        <div class="flex-1 h-px bg-[#4F46E5]"></div>
        <div class="flex items-center gap-2">
            <div class="w-[24px] h-[24px] rounded-full bg-[#4F46E5] flex items-center justify-center">
                <span class="text-[11px] font-bold text-white">2</span>
            </div>
            <span class="text-[12.5px] font-bold text-[#4F46E5]">Revisar y confirmar</span>
        </div>
    </div>

    {{-- Success header --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] p-5 flex items-start gap-4">
        <div class="w-[44px] h-[44px] rounded-[12px] bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center flex-none">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <h2 class="text-[16px] font-bold text-[#111827]">IA detectó {{ count($categories) }} {{ count($categories) === 1 ? 'categoría' : 'categorías' }}</h2>
            @php $totalItems = array_sum(array_map(fn($c) => count($c['items'] ?? []), $categories)); @endphp
            <p class="text-[13px] text-[#6B7280] mt-0.5">{{ $totalItems }} {{ $totalItems === 1 ? 'plato detectado' : 'platos detectados' }} en total · Revisa y edita antes de importar</p>
        </div>
    </div>

    {{-- Warning --}}
    <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-[12px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div class="text-[12.5px] text-[#92400E] leading-relaxed">
            <strong>Revisa los precios antes de guardar.</strong> La IA puede cometer errores — especialmente en precios con puntos de miles. Desactiva los platos que no quieras importar.
        </div>
    </div>

    <form method="POST" action="{{ route('admin.import.confirm') }}">
        @csrf
        <div class="space-y-4">
            @foreach($categories as $ci => $category)
            <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
                <div class="px-5 py-3.5 bg-gradient-to-r from-[#F9FAFB] to-[#FAFBFF] border-b border-[#F3F4F6] flex items-center gap-3">
                    <div class="w-[30px] h-[30px] rounded-[8px] bg-[#EEF2FF] flex items-center justify-center flex-none">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    </div>
                    <label class="flex-1 flex flex-col gap-0.5">
                        <span class="text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wider">Categoría — editable</span>
                        <input type="text" name="categories[{{ $ci }}][name]" value="{{ $category['name'] }}" required
                               class="font-bold text-[15px] text-[#111827] bg-transparent border-none outline-none focus:underline decoration-[#4F46E5] w-full">
                    </label>
                    <span class="text-[11.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[7px] px-[8px] py-[3px]">{{ count($category['items'] ?? []) }} platos</span>
                </div>

                <div class="divide-y divide-[#F9FAFB]">
                    @foreach($category['items'] ?? [] as $ii => $item)
                    <div class="px-5 py-4 flex items-start gap-3">
                        <label class="mt-1 cursor-pointer flex-none">
                            <input type="hidden" name="categories[{{ $ci }}][items][{{ $ii }}][include]" value="0">
                            <input type="checkbox" name="categories[{{ $ci }}][items][{{ $ii }}][include]" value="1" checked
                                   class="w-4 h-4 rounded accent-[#4F46E5]">
                        </label>
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-1">
                                <label class="block text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wide mb-1">Nombre</label>
                                <input type="text" name="categories[{{ $ci }}][items][{{ $ii }}][name]" value="{{ $item['name'] }}" required
                                       class="w-full px-2.5 py-2 border border-[#E5E7EB] rounded-[8px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_2px_rgba(79,70,229,.12)]">
                            </div>
                            <div class="sm:col-span-1">
                                <label class="block text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wide mb-1">Descripción</label>
                                <input type="text" name="categories[{{ $ci }}][items][{{ $ii }}][description]" value="{{ $item['description'] ?? '' }}"
                                       class="w-full px-2.5 py-2 border border-[#E5E7EB] rounded-[8px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_2px_rgba(79,70,229,.12)]" placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wide mb-1">Precio CLP</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-2.5 text-[13px] text-[#374151] font-semibold select-none">$</span>
                                    <input type="number" name="categories[{{ $ci }}][items][{{ $ii }}][price]" value="{{ $item['price'] ?? 0 }}" min="0" required
                                           class="w-full pl-7 pr-2.5 py-2 border border-[#E5E7EB] rounded-[8px] text-[14px] font-bold text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_2px_rgba(79,70,229,.12)]">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB] py-3 -mx-[clamp(16px,2.4vw,28px)] px-[clamp(16px,2.4vw,28px)] flex justify-between gap-2 mt-5 shadow-[0_-1px_4px_rgba(16,24,40,.06)]">
            <a href="{{ route('admin.import.upload') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold no-underline shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Volver y subir más fotos
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Confirmar e importar
            </button>
        </div>
    </form>
</div>
</x-admin-layout>
