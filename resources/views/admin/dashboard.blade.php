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
    $__totalScans = array_sum(array_column($days, 'total'));

    // Checklist diferenciado por path
    if ($hasKit) {
        // Path kit: mesas configuradas (siempre done), logo, cats, items
        $steps = [true, (bool)$restaurant->logo, $categoriesCount > 0, $itemsCount > 0];
    } else {
        // Path sin kit: logo, cats, items, imprimir QR
        $steps = [(bool)$restaurant->logo, $categoriesCount > 0, $itemsCount > 0, false];
    }
    $completedSteps = count(array_filter($steps));
@endphp

{{-- Banner de bienvenida post-activación de kit --}}
@if(session('kit_activated'))
@php
    $__kr = session('kit_result', []);
    $__created = $__kr['tables_created'] ?? 0;
    $__matched = $__kr['tables_matched'] ?? 0;
    $__hasRev  = $__kr['has_review'] ?? false;
    $__totalTables = $__created + $__matched;
@endphp
<div class="rounded-[14px] mb-4 px-5 py-4 flex items-start gap-4" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac">
    <div class="w-[40px] h-[40px] rounded-[11px] bg-emerald-500 flex items-center justify-center flex-none">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="flex-1 min-w-0">
        <div class="text-[14px] font-bold text-emerald-900 mb-1">¡Kit activado! Bienvenido a MenuDigital</div>
        <div class="text-[13px] text-emerald-800 space-y-0.5">
            @if($__totalTables > 0)
            <div>
                @if($__matched > 0 && $__created > 0)
                    Se vincularon {{ $__matched }} {{ $__matched === 1 ? 'mesa existente' : 'mesas existentes' }} y se crearon {{ $__created }} nuevas.
                @elseif($__matched > 0)
                    Se vincularon {{ $__matched }} {{ $__matched === 1 ? 'mesa existente' : 'mesas existentes' }} con los chips del kit.
                @else
                    Se configuraron {{ $__created }} {{ $__created === 1 ? 'mesa' : 'mesas' }} listas para usar.
                @endif
                @if($__hasRev)
                    El chip de reseñas también quedó activo.
                @endif
            </div>
            @endif
            <div>Tu próximo paso: <a href="{{ route('admin.categories.create') }}" class="font-semibold underline">carga tu carta</a> para que tus clientes puedan verla desde las mesas.</div>
        </div>
    </div>
</div>
@endif

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
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-3 gap-3 mb-5">

    {{-- Escaneos esta semana --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#EEF2FF] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
                <rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ number_format($last7) }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Escaneos (7 días)</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $last7 > 0 ? 'este período' : 'Sin datos aún' }}</div>
    </div>

    {{-- WhatsApp iniciados --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#F0FDF4] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ number_format($waClicksMonth) }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Pedidos WhatsApp</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $waClicksMonth > 0 ? 'iniciados este mes' : 'Sin datos aún' }}</div>
    </div>

    {{-- Items en carta --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)] hover:shadow-[0_4px_12px_rgba(16,24,40,.08)] hover:-translate-y-[2px] transition-all">
        <div class="w-[38px] h-[38px] rounded-[10px] bg-[#FFF7ED] flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                <circle cx="3.5" cy="6" r=".5" fill="#EA580C"/><circle cx="3.5" cy="12" r=".5" fill="#EA580C"/><circle cx="3.5" cy="18" r=".5" fill="#EA580C"/>
            </svg>
        </div>
        <div class="text-[30px] font-bold tracking-tight leading-none text-[#111827] mb-1">{{ $itemsCount }}</div>
        <div class="text-[12px] font-semibold text-[#6B7280]">Ítems en carta</div>
        <div class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $categoriesCount }} {{ $categoriesCount === 1 ? 'categoría' : 'categorías' }}</div>
    </div>

</div>

{{-- Scan Stats --}}
<div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] mb-5 overflow-hidden">
    <div class="px-5 pt-5 pb-4">

        @if($__totalScans === 0)
        {{-- Estado vacío --}}
        <div class="text-center py-6">
            <div class="w-[52px] h-[52px] rounded-[14px] bg-[#EEF2FF] flex items-center justify-center mx-auto mb-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
                    <rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
                </svg>
            </div>
            <div class="text-[15px] font-bold text-[#111827] mb-1">Todavía no hay escaneos</div>
            <div class="text-[13px] text-[#6B7280] mb-4">Poné el QR en las mesas para que tus clientes puedan ver el menú.</div>
            <a href="{{ route('admin.qr.show') }}"
               class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Descargar QR
            </a>
        </div>
        @else
        {{-- Gráfico con datos --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-[13px] font-bold text-[#111827]">Escaneos de QR / NFC</div>
                <div class="text-[11.5px] text-[#9CA3AF] mt-[1px]">Últimos 14 días · Total mes: <span class="font-semibold text-[#111827]">{{ number_format($monthTotal) }}</span></div>
            </div>
            <div class="text-right">
                <div class="text-[22px] font-bold text-[#111827] leading-none">{{ number_format($last7) }}</div>
                @if($prev7 > 0)
                <div class="text-[11px] font-semibold mt-[2px] {{ $scanDelta >= 0 ? 'text-[#059669]' : 'text-[#DC2626]' }}">
                    {{ $scanDelta >= 0 ? '↑' : '↓' }} {{ abs($scanDelta) }}% vs semana ant.
                </div>
                @elseif($last7 > 0)
                <div class="text-[11px] text-[#6B7280] mt-[2px]">Primera semana con datos</div>
                @endif
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
        @endif

    </div>
</div>

{{-- Escaneos por mesa este mes --}}
@if(isset($tableScans) && $tableScans->isNotEmpty())
<div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] mb-5 overflow-hidden">
    <div class="px-5 pt-4 pb-4">
        <div class="flex items-center justify-between mb-3">
            <div class="text-[13px] font-bold text-[#111827]">Escaneos por mesa</div>
            <a href="{{ route('admin.tables.index') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Ver mesas →</a>
        </div>
        @php $maxTableScans = max(1, $tableScans->max('scans')); @endphp
        <div class="space-y-1.5">
            @foreach($tableScans->take(6) as $ts)
            <div class="flex items-center gap-2 text-[12px]">
                <span class="flex-1 text-[#4B5563] truncate">{{ $ts['name'] }}</span>
                <span class="font-semibold text-[#111827] tabular-nums w-[28px] text-right">{{ $ts['scans'] }}</span>
                <div class="w-[80px] h-[5px] bg-[#F3F4F6] rounded-full overflow-hidden flex-none">
                    <div class="h-full bg-[#4F46E5] rounded-full" style="width:{{ $maxTableScans > 0 ? min(100, round($ts['scans'] / $maxTableScans * 100)) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

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

                @if($hasKit)
                {{-- PASO 1 KIT: Mesas configuradas (siempre done) --}}
                <li class="flex items-start gap-3">
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <div class="flex-1">
                        <span class="text-[13px] text-[#047857] font-semibold line-through decoration-[#6EE7B7]">Mesas con QR/NFC configuradas</span>
                        <div class="text-[11.5px] text-[#6B7280]">{{ $tableCount }} {{ $tableCount === 1 ? 'mesa activa' : 'mesas activas' }} desde el kit</div>
                    </div>
                </li>
                @endif

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
                        <a href="{{ route('admin.restaurant.edit') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Ir a Mi Negocio →</a>
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

                @if($hasKit)
                {{-- PASO 4 KIT: Apariencia --}}
                <li class="flex items-start gap-3">
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Personalizar apariencia</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Elige plantilla, colores y fuente</div>
                        <a href="{{ route('admin.appearance.edit') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Ir a Apariencia →</a>
                    </div>
                </li>
                @else
                {{-- PASO 4 SIN KIT: Imprimir QR --}}
                <li class="flex items-start gap-3">
                    <span class="mt-[1px] flex-none w-[20px] h-[20px] rounded-full border-2 border-dashed border-[#D1D5DB] flex items-center justify-center"></span>
                    <div class="flex-1">
                        <div class="text-[13px] text-[#111827] font-semibold">Imprimir QR para tus mesas</div>
                        <div class="text-[11.5px] text-[#9CA3AF]">Descarga y pega el QR en cada mesa</div>
                        <a href="{{ route('admin.qr.show') }}" class="text-[11.5px] font-semibold text-[#4F46E5] hover:underline">Descargar QR →</a>
                    </div>
                </li>
                @endif

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
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group min-h-[56px]">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#EEF2FF] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Nueva categoría</div>
                    <div class="text-[11px] text-[#9CA3AF]">Organiza el menú</div>
                </div>
            </a>

            <a href="{{ route('admin.qr.show') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group min-h-[56px]">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#FFF7ED] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><rect x="15" y="15" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Descargar QR</div>
                    <div class="text-[11px] text-[#9CA3AF]">Para mesas y entrada</div>
                </div>
            </a>

            <a href="{{ route('admin.appearance.edit') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group min-h-[56px]">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#F5F3FF] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5-.7.7m-9.6 9.6-.7.7m0-10.3-.7-.7m10.3 10.3-.7-.7"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Apariencia</div>
                    <div class="text-[11px] text-[#9CA3AF]">Plantilla y colores</div>
                </div>
            </a>

            <a href="{{ route('admin.import.upload') }}"
               class="flex items-center gap-3 bg-white border border-[#E5E7EB] hover:border-[#C7D2FE] hover:bg-[#FAFBFF] rounded-[14px] px-4 py-3.5 shadow-[0_1px_3px_rgba(16,24,40,.05)] transition-all group min-h-[56px]">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-[#F0FDF4] flex items-center justify-center flex-none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16l4-4 4 4M12 12V3"/><path d="M20 16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-bold text-[#111827]">Importar menú</div>
                    <div class="text-[11px] text-[#9CA3AF]">Por foto con IA</div>
                </div>
            </a>
        </div>
    </div>

</div>
</x-admin-layout>
