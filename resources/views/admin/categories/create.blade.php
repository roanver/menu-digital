<x-admin-layout>
<div class="max-w-[520px]">
    <a href="{{ route('admin.categories.index') }}"
       class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-5 transition-colors">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Categorías
    </a>

    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#F3F4F6]">
            <h2 class="text-[14px] font-bold text-[#111827]">Nueva categoría</h2>
            <p class="text-[12px] text-[#9CA3AF] mt-0.5">Agrupa los platos de tu menú por sección.</p>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <input type="hidden" name="is_active" value="0">

            <div class="px-5 py-5 space-y-5">
                <!-- Nombre -->
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre de la categoría</span>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-3 py-[11px] border border-[#E5E7EB] rounded-[10px] text-[14px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                           placeholder="Ej: Platos de fondo, Bebidas, Postres…">
                    @error('name')
                    <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </label>

                <!-- Visible toggle -->
                <label class="flex items-center gap-4 cursor-pointer p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                    <div class="relative flex-none">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                    </div>
                    <div>
                        <span class="text-[13.5px] font-semibold text-[#111827]">Visible en el menú</span>
                        <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Los comensales verán esta categoría al abrir el menú</p>
                    </div>
                </label>
            </div>

            <div class="px-5 py-4 bg-[#F9FAFB] border-t border-[#F3F4F6] flex items-center justify-end gap-2">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Crear categoría
                </button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
