<x-admin-layout>
@php
$pageTitle   = 'Agregar negocio';
$verticalsJs = collect(config('verticals'))->map(fn($v) => ['label' => $v['label'], 'items_label' => $v['items_label']])->toArray();
@endphp

<div class="max-w-[560px] space-y-4"
     x-data="{ selectedType: 'restaurant', verticals: @js($verticalsJs) }">

    <form method="POST" action="{{ route('admin.restaurants.store') }}">
        @csrf
        <input type="hidden" name="type" :value="selectedType">

        {{-- Tipo de negocio --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Tipo de negocio</div>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">¿Qué tipo de negocio vas a agregar?</p>
            </div>
            <div class="px-5 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(config('verticals') as $key => $v)
                    @if($key === 'services' || $key === 'store')
                        @continue {{-- tienda: temporalmente deshabilitada --}}
                    @endif
                    <div class="relative flex flex-col gap-2 p-4 rounded-[12px] border-2 cursor-pointer transition-colors"
                         :class="selectedType === '{{ $key }}' ? 'border-[#4F46E5] bg-[#EEF2FF]' : 'border-[#E5E7EB] bg-white hover:border-[#C7D2FE]'"
                         @click="selectedType = '{{ $key }}'">
                        <svg class="w-[20px] h-[20px]"
                             :class="selectedType === '{{ $key }}' ? 'text-[#4F46E5]' : 'text-[#9CA3AF]'"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $v['icon'] }}"/>
                        </svg>
                        <div>
                            <div class="text-[13px] font-bold"
                                 :class="selectedType === '{{ $key }}' ? 'text-[#4F46E5]' : 'text-[#111827]'">{{ $v['label'] }}</div>
                            <div class="text-[11px] text-[#9CA3AF] mt-[2px]">{{ $v['items_label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Nombre --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <div class="text-[13px] font-bold text-[#111827]">Información básica</div>
            </div>
            <div class="px-5 py-5">
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre del negocio</span>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Ej. La Trattoria"
                           class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]">
                    <span class="text-[11px] text-[#9CA3AF]">Podrás editar el logo, dirección y demás detalles después.</span>
                    @error('name')
                    <span class="text-[11.5px] text-[#DC2626]">{{ $message }}</span>
                    @enderror
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.restaurant.edit') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Crear negocio
            </button>
        </div>

    </form>
</div>
</x-admin-layout>
