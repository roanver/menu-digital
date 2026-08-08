<x-admin-layout>
@php $pageTitle = 'Horarios de atención'; @endphp

<div class="max-w-[560px]">

<div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
    <div class="px-5 py-4 border-b border-[#E5E7EB]">
        <h2 class="text-[13px] font-bold text-[#111827]">Horarios de atención</h2>
        <p class="text-[12px] text-[#6B7280] mt-[2px]">Configura cuándo está abierto tu local. Si un día tiene dos tramos, completa ambas filas.</p>
    </div>

    <form method="POST" action="{{ route('admin.hours.update') }}">
        @csrf
        <div class="divide-y divide-[#F3F4F6]">
            @foreach($hours as $day => $bh)
            <div class="px-5 py-4" x-data="{ closed: {{ $bh->is_closed ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[13px] font-semibold text-[#111827] w-[90px]">
                        {{ \App\Models\BusinessHour::dayName($day) }}
                    </span>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="hours[{{ $day }}][is_closed]" :value="closed ? '1' : '0'">
                        <button type="button" @click="closed = !closed"
                            :class="closed ? 'bg-[#DC2626]' : 'bg-[#E5E7EB]'"
                            class="relative inline-flex h-[22px] w-[40px] items-center rounded-full transition-colors focus:outline-none">
                            <span :class="closed ? 'translate-x-[20px]' : 'translate-x-[2px]'"
                                  class="inline-block h-[18px] w-[18px] transform rounded-full bg-white shadow transition-transform"></span>
                        </button>
                        <span :class="closed ? 'text-[#DC2626] font-semibold' : 'text-[#9CA3AF]'" class="text-[12px]">
                            <span x-show="closed">Cerrado</span>
                            <span x-show="!closed">Abierto</span>
                        </span>
                    </label>
                </div>

                <div x-show="!closed" class="space-y-2">
                    {{-- Primer tramo --}}
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-[#9CA3AF] w-[16px]">1</span>
                        <input type="time" name="hours[{{ $day }}][opens_at]"
                               value="{{ $bh->opens_at ? substr($bh->opens_at, 0, 5) : '' }}"
                               class="flex-1 px-3 py-[8px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5]">
                        <span class="text-[11px] text-[#9CA3AF]">a</span>
                        <input type="time" name="hours[{{ $day }}][closes_at]"
                               value="{{ $bh->closes_at ? substr($bh->closes_at, 0, 5) : '' }}"
                               class="flex-1 px-3 py-[8px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5]">
                    </div>
                    {{-- Segundo tramo --}}
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-[#9CA3AF] w-[16px]">2</span>
                        <input type="time" name="hours[{{ $day }}][opens_at_2]"
                               value="{{ $bh->opens_at_2 ? substr($bh->opens_at_2, 0, 5) : '' }}"
                               class="flex-1 px-3 py-[8px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5]">
                        <span class="text-[11px] text-[#9CA3AF]">a</span>
                        <input type="time" name="hours[{{ $day }}][closes_at_2]"
                               value="{{ $bh->closes_at_2 ? substr($bh->closes_at_2, 0, 5) : '' }}"
                               class="flex-1 px-3 py-[8px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[9px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5]">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-5 py-4 border-t border-[#E5E7EB]">
            <button type="submit"
                    class="w-full bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar horarios
            </button>
        </div>
    </form>
</div>

</div>
</x-admin-layout>
