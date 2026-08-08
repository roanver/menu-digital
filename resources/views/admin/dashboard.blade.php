<x-admin-layout>
@php
    $__menuUrl = url('/' . $restaurant->slug);
    $__planLabels = ['carta' => 'Carta', 'pedidos' => 'Pedidos', 'full' => 'Full'];
    $__planLabel = $__planLabels[$restaurant->plan] ?? ucfirst($restaurant->plan ?? 'Carta');
    $__inTrial = $restaurant->trial_ends_at && now()->lt($restaurant->trial_ends_at);
    $__inPaid  = $restaurant->subscription_ends_at && now()->lt($restaurant->subscription_ends_at);
    $__isActive = $__inTrial || $__inPaid;
    $__trialDays = $__inTrial ? (int) now()->diffInDays($restaurant->trial_ends_at) : null;
    $__paidEnds  = $__inPaid ? $restaurant->subscription_ends_at->format('d/m/Y') : null;
    $availableCount = $restaurant->menuItems()->where('is_available', true)->count();

    // Checklist progress
    $steps = [
        (bool)$restaurant->logo,
        $categoriesCount > 0,
        $itemsCount > 0,
        false, // apariencia — siempre pendiente como sugerencia
    ];
    $completedSteps = count(array_filter($steps));
@endphp

{{-- Hero Banner --}}
<div class="rounded-[18px] mb-5 overflow-hidden" style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%)">
    <div class="px-5 py-6 sm:px-7 sm:py-7">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[.12em] text-indigo-300 mb-1">Panel de administración</p>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-white leading-tight truncate">{{ $restaurant->name }}</h1>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                    <span class="text-[12px] text-indigo-200 truncate max-w-[240px]">{{ $__menuUrl }}</span>
                    <div x-data="{ copied: false }">
                        <button
                            @click="navigator.clipboard.writeText('{{ $__menuUrl }}'); copied = true; setTimeout(() => copied = false, 1800)"
                            :class="copied ? 'bg-emerald-500/20 border-emerald-400/40 text-emerald-300' : 'bg-white/10 border-white/20 text-white hover:bg-white/20'"
                            class="inline-flex items-center gap-1 border rounded-[7px] px-[8px] py-[3px] text-[11px] font-semibold transition-colors">
                            <svg x-show="!copied" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span x-text="copied ? 'Copiado' : 'Copiar URL'"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                @if(!$__isActive)
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-300 bg-red-400/15 border border-red-400/30 rounded-[7px] px-[8px] py-[3px]">
                    Plan vencido
                </span>
                @elseif($__inTrial)
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-300 bg-amber-400/15 border border-amber-400/30 rounded-[7px] px-[8px] py-[3px]">
                    Trial · {{ $__trialDays }} días restantes
                </span>
                @else
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-300 bg-emerald-400/15 border border-emerald-400/30 rounded-[7px] px-[8px] py-[3px]">
                    <span class="w-[5px] h-[5px] rounded-full bg-emerald-400"></span> {{ $__planLabel }} · vence {{ $__paidEnds }}
                </span>
                @endif
                <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
                   class="inline-flex items-center gap-[6px] bg-white text-[#312e81] rounded-[10px] px-[14px] py-[9px] text-[12.5px] font-bold shadow-lg hover:bg-indigo-50 transition-colors whitespace-nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                    Ver menú
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-5">

    {{-- Categorías --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#EEF2FF] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ $categoriesCount }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Categorías</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $categoriesCount > 0 ? 'en el menú' : 'Sin categorías' }}</div>
    </div>

    {{-- Items --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#F0FDF4] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                <circle cx="3.5" cy="6" r=".5" fill="#16a34a"/><circle cx="3.5" cy="12" r=".5" fill="#16a34a"/><circle cx="3.5" cy="18" r=".5" fill="#16a34a"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ $itemsCount }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Items totales</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $itemsCount > 0 ? 'en el menú' : 'Sin items' }}</div>
    </div>

    {{-- Disponibles --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#FFF7ED] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ $availableCount }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Disponibles</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">pedibles ahora</div>
    </div>

    {{-- Plan --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#F5F3FF] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
        <div class="text-[22px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ $__planLabel }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Plan activo</div>
        <div class="text-[11px] mt-0.5 @if($__isActive) text-[#9CA3AF] @else text-[#DC2626] font-semibold @endif">
            @if(!$__isActive) Vencido
            @elseif($__inTrial) Trial · {{ $__trialDays }} días
            @else Vence {{ $__paidEnds }} @endif
        </div>
    </div>

</div>

{{-- Scan Stats --}}
<div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] mb-5 overflow-hidden">
    <div class="px-5 pt-5 pb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-[13px] font-bold text-[#111827]">Escaneos de QR / NFC</div>
                <div class="text-[11.5px] text-[#9CA3AF] mt-[1px]">Últimos 14 días · Total mes: <span class="font-semibold text-[#111827]">{{ number_format($monthTotal) }}</span></div>
            </div>
            <div class="text-right">
                <div class="text-[22px] font-bold text-[#111827] leading-none">{{ number_format($last7) }}</div>
                <div class="text-[11px] font-semibold mt-[2px] {{ $scanDelta >= 0 ? 'text-[#059669]' : 'text-[#DC2626]' }}">
                    {{ $scanDelta >= 0 ? '↑' : '↓' }} {{ abs($scanDelta) }}% vs semana ant.
                </div>
            </div>
        </div>

        {{-- Bar chart --}}
        @php $maxVal = max(1, collect($days)->max('total')); @endphp
        <div class="flex items-end gap-[3px] h-[56px]">
            @foreach($days as $i => $day)
                @php
                    $pct  = round($day['total'] / $maxVal * 100);
                    $isRecent = $i >= 7;
                    $isToday = $day['date'] === now()->toDateString();
                @endphp
                <div class="flex-1 flex flex-col items-center gap-[2px]" title="{{ $day['date'] }}: {{ $day['total'] }} escaneos">
                    <div class="w-full rounded-t-[3px] transition-all"
                         style="height:{{ max(2, (int)round($pct * 48 / 100)) }}px;background:{{ $isToday ? '#4F46E5' : ($isRecent ? '#A5B4FC' : '#E5E7EB') }};"></div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-1">
            <span class="text-[9px] text-[#9CA3AF]">{{ now()->subDays(13)->format('d/m') }}</span>
            <span class="text-[9px] text-[#9CA3AF]">Hoy</span>
        </div>

        {{-- Tag breakdown --}}
        @if($tagBreakdown->isNotEmpty())
        <div class="mt-4 space-y-1">
            @foreach($tagBreakdown->take(4) as $tag)
            <div class="flex items-center gap-2 text-[12px]">
                <span class="flex-1 text-[#4B5563] truncate">{{ $tag->label ?? ($tag->type === 'menu' ? 'Menú' : 'Reseña') }}</span>
                <span class="font-semibold text-[#111827]">{{ number_format($tag->month_scans ?? 0) }}</span>
                <div class="w-[60px] h-[5px] bg-[#F3F4F6] rounded-full overflow-hidden">
                    <div class="h-full bg-[#4F46E5] rounded-full" style="width:{{ $monthTotal > 0 ? min(100, round(($tag->month_scans ?? 0) / $monthTotal * 100)) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Two-panel grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Checklist card --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 pt-5 pb-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[13px] font-bold text-[#111827]">Configura tu carta</span>
                <span class="text-[11px] font-semibold text-[#6B7280]">{{ $completedSteps }}/4</span>
            </div>
            {{-- Progress bar --}}
            <div class="h-[5px] bg-[#F3F4F6] rounded-full overflow-hidden mb-5">
                <div class="h-full bg-[#4F46E5] rounded-full transition-all" style="width:{{ ($completedSteps/4)*100 }}%"></div>
            </div>

            <ul class="space-y-[13px]">

                {{-- Logo --}}
                <li class="flex items-start gap-3">
                    @if($restaurant->logo)
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <div class="flex-1">
                        <span class="text-[13px] text-[#047857] font-semibold line-through decoration-[#6EE7B7]">Subir logo</span>
                    </div>
                    @else
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Subir logo</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Aparecerá en la cabecera del menú</div>
                        <a href="{{ route('admin.restaurant.edit') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Ir a Mi Restaurante →</a>
                    </div>
                    @endif
                </li>

                {{-- Categorías --}}
                <li class="flex items-start gap-3">
                    @if($categoriesCount > 0)
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <div class="flex-1">
                        <span class="text-[13px] text-[#047857] font-semibold line-through decoration-[#6EE7B7]">Crear al menos una categoría</span>
                    </div>
                    @else
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Crear al menos una categoría</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Ej: Entradas, Platos de fondo, Bebidas</div>
                        <a href="{{ route('admin.categories.create') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Nueva categoría →</a>
                    </div>
                    @endif
                </li>

                {{-- Items --}}
                <li class="flex items-start gap-3">
                    @if($itemsCount > 0)
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <div class="flex-1">
                        <span class="text-[13px] text-[#047857] font-semibold line-through decoration-[#6EE7B7]">Agregar platos al menú</span>
                    </div>
                    @else
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Agregar platos al menú</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Nombre, precio y foto de cada plato</div>
                        <a href="{{ route('admin.items.create') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Nuevo item →</a>
                    </div>
                    @endif
                </li>

                {{-- Apariencia --}}
                <li class="flex items-start gap-3">
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Personalizar apariencia</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Elige plantilla, colores y fuente</div>
                        <a href="{{ route('admin.appearance.edit') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Ir a Apariencia →</a>
                    </div>
                </li>

            </ul>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="space-y-3">

        {{-- CTA principal --}}
        <a href="{{ route('admin.items.create') }}"
           class="flex items-center gap-4 bg-[#4F46E5] hover:bg-[#4338CA] rounded-[16px] px-5 py-4 shadow-[0_2px_8px_rgba(79,70,229,.4)] transition-all hover:-translate-y-[1px] group">
            <div class="w-[42px] h-[42px] rounded-[12px] bg-white/15 flex items-center justify-center flex-none">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div class="flex-1">
                <div class="text-[14px] font-bold text-white">Nuevo item</div>
                <div class="text-[11.5px] text-indigo-200">Agrega un plato, bebida o postre</div>
            </div>
            <svg class="text-white/60 group-hover:translate-x-1 transition-transform" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>

        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#EEF2FF] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Nueva categoría</div>
                    <div class="text-[11px] text-[#9CA3AF]">Organiza el menú</div>
                </div>
            </a>

            <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#F0FDF4] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Ver menú público</div>
                    <div class="text-[11px] text-[#9CA3AF]">Abre en nueva pestaña</div>
                </div>
            </a>

            <a href="{{ route('admin.qr.show') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#FFF7ED] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><rect x="15" y="15" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Descargar QR</div>
                    <div class="text-[11px] text-[#9CA3AF]">Para mesas y entrada</div>
                </div>
            </a>

            <a href="{{ route('admin.appearance.edit') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#F5F3FF] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Apariencia</div>
                    <div class="text-[11px] text-[#9CA3AF]">Plantilla y colores</div>
                </div>
            </a>
        </div>
    </div>

</div>
</x-admin-layout>
