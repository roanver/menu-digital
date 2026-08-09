<x-admin-layout>
@php
    $pageTitle    = 'Asesor de carta';
    $pageSubtitle = 'Análisis con IA de tu menú';

    $priorityColors = [
        'alta'  => ['bg' => '#FEF2F2', 'border' => '#FECACA', 'text' => '#DC2626', 'badge' => '#FEE2E2', 'dot' => '#EF4444'],
        'media' => ['bg' => '#FFFBEB', 'border' => '#FDE68A', 'text' => '#D97706', 'badge' => '#FEF3C7', 'dot' => '#F59E0B'],
        'baja'  => ['bg' => '#F0FDF4', 'border' => '#BBF7D0', 'text' => '#16A34A', 'badge' => '#DCFCE7', 'dot' => '#22C55E'],
    ];

    $typeLabels = [
        'precios'        => 'Precios',
        'descripciones'  => 'Descripciones',
        'disponibilidad' => 'Disponibilidad',
        'variedad'       => 'Variedad',
        'categorias'     => 'Categorías',
        'marketing'      => 'Marketing',
    ];
@endphp

<div class="max-w-[760px] space-y-4">

    {{-- Intro card --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-5">
            <div class="flex items-start gap-4">
                <div class="w-[44px] h-[44px] rounded-[12px] bg-[#EEF2FF] flex items-center justify-center flex-none">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/>
                        <path d="M12 8v4l3 3"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[14px] font-bold text-[#111827] mb-1">Análisis inteligente de tu carta</div>
                    <p class="text-[12.5px] text-[#6B7280] leading-relaxed">La IA analiza tus precios, descripciones, variedad y oportunidades de mejora basándose en las mejores prácticas de restaurantes exitosos en Chile.</p>
                    @if($itemsCount === 0)
                    <div class="mt-3 inline-flex items-center gap-2 text-[12px] font-semibold text-[#D97706] bg-[#FEF3C7] border border-[#FDE68A] rounded-[8px] px-3 py-[6px]">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Agrega ítems a tu carta primero
                    </div>
                    @endif
                </div>
            </div>

            @if($itemsCount > 0)
            <form method="POST" action="{{ route('admin.advisor.generate') }}" class="mt-4">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-[8px] bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-[18px] py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"/>
                    </svg>
                    {{ $audit ? 'Volver a analizar' : 'Analizar mi carta' }}
                </button>
                @if($audit)
                <span class="ml-3 text-[11.5px] text-[#9CA3AF]">Último análisis: {{ $audit->created_at->diffForHumans() }}</span>
                @endif
            </form>
            @endif
        </div>
    </div>

    @if($audit)
    {{-- Score card --}}
    @php
        $score = $audit->score ?? 0;
        $scoreColor = $score >= 75 ? '#16A34A' : ($score >= 50 ? '#D97706' : '#DC2626');
        $scoreBg    = $score >= 75 ? '#F0FDF4' : ($score >= 50 ? '#FFFBEB' : '#FEF2F2');
        $scoreBorder = $score >= 75 ? '#BBF7D0' : ($score >= 50 ? '#FDE68A' : '#FECACA');
        $scoreLabel  = $score >= 75 ? 'Buena carta' : ($score >= 50 ? 'Carta mejorable' : 'Carta básica');
    @endphp
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#F3F4F6]">
            <div class="text-[13px] font-bold text-[#111827]">Puntuación general</div>
        </div>
        <div class="px-5 py-4 flex items-center gap-5">
            <div class="w-[72px] h-[72px] rounded-full flex items-center justify-center flex-none border-4"
                 style="border-color:{{ $scoreColor }};background:{{ $scoreBg }};">
                <span class="text-[22px] font-bold" style="color:{{ $scoreColor }};">{{ $score }}</span>
            </div>
            <div>
                <div class="text-[15px] font-bold" style="color:{{ $scoreColor }};">{{ $scoreLabel }}</div>
                <div class="text-[12px] text-[#6B7280] mt-1">Basado en {{ count($audit->suggestions) }} sugerencias de mejora</div>
            </div>
        </div>
    </div>

    {{-- Suggestions --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#F3F4F6]">
            <div class="text-[13px] font-bold text-[#111827]">Sugerencias de mejora</div>
        </div>
        <div class="divide-y divide-[#F3F4F6]">
            @forelse($audit->suggestions as $suggestion)
            @php
                $p = $priorityColors[$suggestion['priority'] ?? 'baja'] ?? $priorityColors['baja'];
                $typeLabel = $typeLabels[$suggestion['type'] ?? ''] ?? ucfirst($suggestion['type'] ?? '');
            @endphp
            <div class="px-5 py-4">
                <div class="flex items-start gap-3">
                    <div class="w-[8px] h-[8px] rounded-full flex-none mt-[5px]" style="background:{{ $p['dot'] }};"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-[13px] font-semibold text-[#111827]">{{ $suggestion['title'] }}</span>
                            <span class="text-[10.5px] font-semibold px-[7px] py-[2px] rounded-[5px]"
                                  style="background:{{ $p['badge'] }};color:{{ $p['text'] }};">
                                {{ ucfirst($suggestion['priority'] ?? 'baja') }}
                            </span>
                            @if($typeLabel)
                            <span class="text-[10.5px] font-medium text-[#6B7280] bg-[#F3F4F6] px-[7px] py-[2px] rounded-[5px]">{{ $typeLabel }}</span>
                            @endif
                        </div>
                        <p class="text-[12.5px] text-[#4B5563] leading-relaxed">{{ $suggestion['body'] }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-[13px] text-[#9CA3AF]">No se generaron sugerencias.</div>
            @endforelse
        </div>
    </div>
    @endif

</div>
</x-admin-layout>
