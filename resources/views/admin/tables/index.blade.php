<x-admin-layout>
@php
    $maxTables = $maxTables ?? 0;
    $canAdd = $maxTables === -1 || $tables->count() < $maxTables;
@endphp

<div class="max-w-[860px]">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 gap-3 flex-wrap">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-[18px] font-bold text-[#111827] leading-tight">Mesas</h1>
                @if($tables->count() > 0)
                <span class="text-[11px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-full px-2 py-0.5">{{ $tables->count() }}</span>
                @endif
            </div>
            <p class="text-[12.5px] text-[#6B7280] mt-0.5">QR individual por mesa. Los clientes escanean y van directo al menú.</p>
            @if($maxTables > 0)
            <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Tu plan permite hasta {{ $maxTables }} mesas · {{ $tables->count() }} creadas</p>
            @elseif($maxTables === -1)
            <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Mesas ilimitadas · {{ $tables->count() }} creadas</p>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if($tables->count() > 0)
            <a href="{{ route('admin.tables.print') }}" target="_blank"
               class="inline-flex items-center gap-2 bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-[#374151] rounded-[10px] px-4 py-2.5 text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Hoja imprimible
            </a>
            @endif
        </div>
    </div>

    @if($maxTables === 0)
    {{-- Plan upgrade prompt --}}
    <div class="bg-gradient-to-br from-[#1e1b4b] to-[#4338ca] rounded-[20px] p-8 text-white text-center mb-5">
        <div class="w-14 h-14 bg-white/10 rounded-[16px] flex items-center justify-center mx-auto mb-4">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#c7d2fe" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="7" width="18" height="4" rx="1"/><line x1="5" y1="11" x2="5" y2="17"/><line x1="19" y1="11" x2="19" y2="17"/>
            </svg>
        </div>
        <h2 class="text-[20px] font-bold mb-2">Gestión de mesas · Plan Básico o Pro</h2>
        <p class="text-[13.5px] text-indigo-200 max-w-[400px] mx-auto mb-6 leading-relaxed">
            Crea un QR único por mesa. Tus clientes escanean y van directo al menú, y puedes ver cuántos escaneos tiene cada mesa.
        </p>
        <a href="{{ route('admin.billing.show') }}"
           class="inline-flex items-center gap-2 bg-white text-[#4338CA] font-bold px-6 py-3 rounded-[12px] text-[13.5px] hover:bg-indigo-50 transition-colors">
            Ver planes
        </a>
    </div>
    @else

    {{-- Forms row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

        {{-- Crear una mesa --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
            <div class="text-[12px] font-semibold text-[#6B7280] uppercase tracking-wide mb-3">Crear mesa</div>
            <form method="POST" action="{{ route('admin.tables.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Ej: Mesa 5 · Terraza · Barra"
                       required maxlength="60"
                       class="flex-1 min-w-0 border border-[#E5E7EB] rounded-[9px] px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent placeholder-[#9CA3AF]">
                <button type="submit"
                        @if(!$canAdd) disabled title="Límite de mesas alcanzado" @endif
                        class="flex-none bg-[#4F46E5] hover:bg-[#4338CA] disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-[9px] px-4 py-2 text-[13px] font-semibold transition-colors whitespace-nowrap">
                    Crear
                </button>
            </form>
        </div>

        {{-- Creación masiva --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
            <div class="text-[12px] font-semibold text-[#6B7280] uppercase tracking-wide mb-3">Creación masiva</div>
            <form method="POST" action="{{ route('admin.tables.bulk') }}">
                @csrf
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[13px] text-[#4B5563] font-medium whitespace-nowrap">Mesa</span>
                    <div class="flex items-center gap-1">
                        <label class="text-[12px] text-[#9CA3AF]">De:</label>
                        <input type="number" name="from" value="1" min="1" max="200"
                               class="w-[60px] border border-[#E5E7EB] rounded-[9px] px-2 py-2 text-[13px] text-center focus:outline-none focus:ring-2 focus:ring-[#4F46E5]">
                    </div>
                    <div class="flex items-center gap-1">
                        <label class="text-[12px] text-[#9CA3AF]">A:</label>
                        <input type="number" name="to" value="10" min="1" max="200"
                               class="w-[60px] border border-[#E5E7EB] rounded-[9px] px-2 py-2 text-[13px] text-center focus:outline-none focus:ring-2 focus:ring-[#4F46E5]">
                    </div>
                    <button type="submit"
                            @if(!$canAdd) disabled title="Límite de mesas alcanzado" @endif
                            class="flex-none bg-[#111827] hover:bg-[#374151] disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-[9px] px-4 py-2 text-[13px] font-semibold transition-colors whitespace-nowrap">
                        Crear mesas
                    </button>
                </div>
                <p class="text-[11px] text-[#9CA3AF] mt-2">Crea "Mesa 1" hasta "Mesa N" de una sola vez.</p>
            </form>
        </div>

    </div>

    {{-- Table list --}}
    @if($tables->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-12 text-center shadow-[0_1px_3px_rgba(16,24,40,.05)]">
        <div class="w-12 h-12 bg-[#F3F4F6] rounded-[14px] flex items-center justify-center mx-auto mb-4">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="7" width="18" height="4" rx="1"/><line x1="5" y1="11" x2="5" y2="17"/><line x1="19" y1="11" x2="19" y2="17"/>
            </svg>
        </div>
        <p class="text-[14px] font-semibold text-[#374151] mb-1">Sin mesas configuradas</p>
        <p class="text-[12.5px] text-[#9CA3AF] mb-5">Usá la creación masiva para generar todas las mesas de tu local de una sola vez.</p>
    </div>

    @else

    {{-- Reorder hint --}}
    <p class="text-[11.5px] text-[#9CA3AF] mb-2">Arrastrá las filas para reordenar.</p>

    <div class="space-y-2" id="tables-sortable">
        @foreach($tables as $table)
        @php
            $qrScans  = $table->qrScansThisMonth();
            $nfcScans = $table->nfcScansThisMonth();
        @endphp
        <div x-data="{ editing: false, name: '{{ addslashes($table->name) }}' }"
             data-id="{{ $table->id }}"
             class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_3px_rgba(16,24,40,.04)] overflow-hidden">

            {{-- Main row --}}
            <div class="flex items-center gap-3 px-4 py-3 flex-wrap sm:flex-nowrap">

                {{-- Drag handle --}}
                <div class="drag-handle flex-none cursor-grab active:cursor-grabbing text-[#D1D5DB] hover:text-[#9CA3AF] transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/><circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/><circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="15" cy="19" r="1" fill="currentColor"/></svg>
                </div>

                {{-- Name / edit --}}
                <div class="flex-1 min-w-0">
                    <template x-if="!editing">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[14px] font-bold text-[#111827]">{{ $table->name }}</span>
                            @if(!$table->is_active)
                            <span class="text-[10px] font-semibold text-[#9CA3AF] bg-[#F3F4F6] border border-[#E5E7EB] rounded-[5px] px-[6px] py-[1px]">Inactiva</span>
                            @endif
                            @if($table->nfcTag)
                            <span class="text-[10px] font-semibold text-[#059669] bg-[#ECFDF5] border border-[#A7F3D0] rounded-[5px] px-[6px] py-[1px]">Chip NFC</span>
                            @endif
                        </div>
                    </template>
                    <template x-if="editing">
                        <form method="POST" action="{{ route('admin.tables.update', $table) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" x-model="name"
                                   required maxlength="60"
                                   class="flex-1 border border-[#4F46E5] rounded-[8px] px-3 py-1.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#4F46E5]">
                            <button type="submit" class="text-[12px] font-semibold text-white bg-[#4F46E5] hover:bg-[#4338CA] rounded-[8px] px-3 py-1.5 transition-colors">Guardar</button>
                            <button type="button" @click="editing = false; name = '{{ addslashes($table->name) }}'" class="text-[12px] font-semibold text-[#6B7280] hover:text-[#111827] rounded-[8px] px-3 py-1.5 transition-colors">Cancelar</button>
                        </form>
                    </template>
                    <div class="text-[11.5px] text-[#9CA3AF] mt-0.5" x-show="!editing">
                        Este mes:
                        {{ $qrScans }} por QR
                        @if($table->nfcTag)
                        · {{ $nfcScans }} por chip
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5 flex-none flex-wrap">

                    {{-- Edit button --}}
                    <button @click="editing = !editing" x-show="!editing"
                            class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#374151] bg-[#F9FAFB] hover:bg-[#F3F4F6] border border-[#E5E7EB] rounded-[8px] px-2.5 py-1.5 transition-colors">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Editar
                    </button>

                    {{-- Toggle active --}}
                    <form method="POST" action="{{ route('admin.tables.toggle', $table) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1 text-[11.5px] font-semibold rounded-[8px] px-2.5 py-1.5 transition-colors border
                                       {{ $table->is_active ? 'text-[#059669] bg-[#F0FDF4] border-[#A7F3D0] hover:bg-[#ECFDF5]' : 'text-[#9CA3AF] bg-[#F9FAFB] border-[#E5E7EB] hover:bg-[#F3F4F6]' }}">
                            {{ $table->is_active ? 'Activa' : 'Inactiva' }}
                        </button>
                    </form>

                    {{-- Delete --}}
                    @if(!$table->nfcTag)
                    <form method="POST" action="{{ route('admin.tables.destroy', $table) }}"
                          onsubmit="return confirm('¿Eliminar la mesa \"{{ addslashes($table->name) }}\"? Se borrará también su QR.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center text-[11.5px] font-semibold text-[#DC2626] hover:text-[#B91C1C] rounded-[8px] px-2.5 py-1.5 transition-colors">
                            Eliminar
                        </button>
                    </form>
                    @else
                    <span title="Desvincula el chip antes de eliminar"
                          class="inline-flex items-center text-[11.5px] font-semibold text-[#D1D5DB] rounded-[8px] px-2.5 py-1.5 cursor-not-allowed">
                        Eliminar
                    </span>
                    @endif

                </div>
            </div>

            {{-- QR row --}}
            <div class="border-t border-[#F3F4F6] bg-[#FAFAFA] px-4 py-2 flex items-center gap-3 flex-wrap">
                <span class="text-[10.5px] font-bold text-[#9CA3AF] uppercase tracking-wide w-[28px] flex-none">QR</span>
                <code class="text-[10.5px] text-[#6B7280] bg-white border border-[#E5E7EB] rounded-[5px] px-1.5 py-0.5 font-mono">{{ $table->qrTag->code }}</code>
                <span class="text-[11px] text-[#9CA3AF]">{{ $qrScans }} {{ $qrScans === 1 ? 'escaneo' : 'escaneos' }} este mes</span>
                <a href="{{ route('admin.tables.qr', $table) }}"
                   class="ml-auto inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#4F46E5] bg-[#EEF2FF] hover:bg-[#E0E7FF] rounded-[7px] px-2.5 py-1 transition-colors">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Descargar QR
                </a>
            </div>

            {{-- NFC chip row --}}
            <div class="border-t border-[#F3F4F6] bg-[#FAFAFA] px-4 py-2 flex items-center gap-3 flex-wrap">
                <span class="text-[10.5px] font-bold text-[#9CA3AF] uppercase tracking-wide w-[28px] flex-none">NFC</span>

                @if($table->nfcTag)
                {{-- Chip vinculado --}}
                <code class="text-[10.5px] text-[#6B7280] bg-white border border-[#E5E7EB] rounded-[5px] px-1.5 py-0.5 font-mono">{{ $table->nfcTag->code }}</code>
                <span class="text-[11px] text-[#9CA3AF]">{{ $nfcScans }} {{ $nfcScans === 1 ? 'escaneo' : 'escaneos' }} este mes</span>
                <form method="POST" action="{{ route('admin.tables.unlink-nfc', $table) }}"
                      class="ml-auto"
                      onsubmit="return confirm('¿Desvincular el chip NFC? El QR de la mesa no cambia.')">
                    @csrf
                    <button type="submit" class="text-[11.5px] font-semibold text-[#9CA3AF] hover:text-[#374151] transition-colors">
                        Desvincular
                    </button>
                </form>

                @else
                {{-- Sin chip --}}
                <span class="text-[12px] text-[#9CA3AF]">Sin chip asignado</span>

                @if($freeChips->count() > 0)
                <div x-data="{ open: false }" class="relative ml-auto">
                    <button @click="open = !open" type="button"
                            class="text-[11.5px] font-semibold text-[#4F46E5] hover:text-[#4338CA] transition-colors">
                        + Vincular chip
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 bottom-full mb-1 z-10 bg-white border border-[#E5E7EB] rounded-[12px] shadow-[0_8px_24px_rgba(16,24,40,.12)] py-1 min-w-[200px]"
                         style="display:none;">
                        @foreach($freeChips as $chip)
                        <form method="POST" action="{{ route('admin.tables.link-nfc', $table) }}">
                            @csrf
                            <input type="hidden" name="nfc_tag_id" value="{{ $chip->id }}">
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-[12px] text-[#374151] hover:bg-[#F9FAFB] font-medium">
                                <span class="font-mono text-[10.5px] text-[#6B7280]">{{ $chip->code }}</span>
                                @if($chip->label) · {{ $chip->label }} @endif
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
            </div>

        </div>
        @endforeach
    </div>

    @endif

    @endif {{-- maxTables !== 0 --}}

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('tables-sortable');
    if (!el || typeof Sortable === 'undefined') return;

    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            var ids = Array.from(el.querySelectorAll('[data-id]')).map(function(el) {
                return parseInt(el.getAttribute('data-id'));
            });
            fetch('{{ route('admin.tables.reorder') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ ids: ids }),
            });
        }
    });
});
</script>
</x-admin-layout>
