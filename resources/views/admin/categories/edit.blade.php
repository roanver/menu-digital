<x-admin-layout>
<div class="max-w-[480px]">
    <a href="{{ route('admin.categories.index') }}"
       class="inline-flex items-center gap-1 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] mb-4 transition-colors">
        ← Volver a categorías
    </a>

    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[18px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="text-[13px] font-bold text-[#111827] mb-4">Editar categoría</div>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="is_active" value="0">

            <div class="space-y-4">
                <!-- Nombre -->
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre</span>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required autofocus
                           class="w-full px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                           placeholder="Ej: Platos de fondo">
                    @error('name')
                    <p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </label>

                <!-- Visible toggle -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative flex-none">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <div class="w-[38px] h-[22px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2px] left-[2px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <div>
                        <span class="text-[13px] font-semibold text-[#111827]">Visible en el menú</span>
                        <p class="text-[11.5px] text-[#9CA3AF]">Los comensales verán esta categoría</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
