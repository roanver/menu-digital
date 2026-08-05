<x-admin-layout>
<div class="max-w-[760px] space-y-4">

    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-[22px] font-bold tracking-tight">Revisar importación</h2>
            <p class="text-[13.5px] text-[#6B7280] mt-1">Revisa y edita los datos detectados antes de importar.</p>
        </div>
        <a href="{{ route('admin.import.upload') }}"
           class="flex-none bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-3 py-2 text-[12.5px] font-semibold no-underline">
            ← Volver a subir
        </a>
    </div>

    <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-[12px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div class="text-[12.5px] text-[#92400E]">
            <strong>Revisa los precios antes de guardar.</strong> La IA puede cometer errores. Desactiva los platos que no quieras importar.
        </div>
    </div>

    <form method="POST" action="{{ route('admin.import.confirm') }}">
        @csrf
        <div class="space-y-4">
            @foreach($categories as $ci => $category)
            <div class="bg-white border border-[#E5E7EB] rounded-[14px] overflow-hidden">
                <div class="px-4 py-3 bg-[#F9FAFB] border-b border-[#F3F4F6] flex items-center gap-3">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    <label class="flex-1 flex flex-col gap-0.5">
                        <span class="text-[10.5px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Categoría</span>
                        <input type="text" name="categories[{{ $ci }}][name]" value="{{ $category['name'] }}" required
                               class="font-semibold text-[14px] text-[#111827] bg-transparent border-none outline-none focus:underline w-full">
                    </label>
                    <span class="text-[11px] text-[#9CA3AF]">{{ count($category['items'] ?? []) }} platos</span>
                </div>

                <div class="divide-y divide-[#F9FAFB]">
                    @foreach($category['items'] ?? [] as $ii => $item)
                    <div class="px-4 py-3 flex items-start gap-3">
                        <label class="mt-1 cursor-pointer">
                            <input type="hidden" name="categories[{{ $ci }}][items][{{ $ii }}][include]" value="0">
                            <input type="checkbox" name="categories[{{ $ci }}][items][{{ $ii }}][include]" value="1" checked
                                   class="w-4 h-4 rounded accent-[#4F46E5]">
                        </label>
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-1">
                                <label class="block text-[10.5px] font-semibold text-[#9CA3AF] mb-0.5">Nombre</label>
                                <input type="text" name="categories[{{ $ci }}][items][{{ $ii }}][name]" value="{{ $item['name'] }}" required
                                       class="w-full px-2 py-1.5 border border-[#E5E7EB] rounded-[7px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                            </div>
                            <div class="sm:col-span-1">
                                <label class="block text-[10.5px] font-semibold text-[#9CA3AF] mb-0.5">Descripción</label>
                                <input type="text" name="categories[{{ $ci }}][items][{{ $ii }}][description]" value="{{ $item['description'] ?? '' }}"
                                       class="w-full px-2 py-1.5 border border-[#E5E7EB] rounded-[7px] text-[13px] focus:outline-none focus:border-[#4F46E5]" placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-semibold text-[#9CA3AF] mb-0.5">Precio CLP</label>
                                <input type="number" name="categories[{{ $ci }}][items][{{ $ii }}][price]" value="{{ $item['price'] ?? 0 }}" min="0" required
                                       class="w-full px-2 py-1.5 border border-[#E5E7EB] rounded-[7px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="sticky bottom-0 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB] py-3 -mx-[clamp(16px,2.4vw,28px)] px-[clamp(16px,2.4vw,28px)] flex justify-end gap-2 mt-4">
            <a href="{{ route('admin.import.upload') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold no-underline">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold">
                Confirmar e importar
            </button>
        </div>
    </form>
</div>
</x-admin-layout>
