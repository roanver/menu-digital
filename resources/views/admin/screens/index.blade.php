<x-admin-layout>
<div class="max-w-[860px]">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-[18px] font-bold text-[#111827] leading-tight">Pantallas</h1>
            <p class="text-[12.5px] text-[#6B7280] mt-0.5">Carta digital para TV o monitor en el local.</p>
        </div>
        @if($restaurant->planCan('screens'))
        @php $atLimit = $screens->count() >= $restaurant->maxScreens(); @endphp
        @if(!$atLimit)
        <a href="{{ route('admin.screens.create') }}"
           class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-2.5 text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.3)] transition-colors">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva pantalla
        </a>
        @else
        <span class="text-[12px] text-[#9CA3AF] bg-[#F3F4F6] rounded-[10px] px-4 py-2.5 font-medium">
            Límite alcanzado ({{ $restaurant->maxScreens() }}/{{ $restaurant->maxScreens() }})
        </span>
        @endif
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-[#ECFDF5] border border-[#6EE7B7] rounded-[10px] text-[13px] font-semibold text-[#065F46]">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] text-[13px] font-semibold text-[#991B1B]">
        {{ session('error') }}
    </div>
    @endif

    @if(!$restaurant->planCan('screens'))
    {{-- Upgrade prompt --}}
    <div class="bg-gradient-to-br from-[#1e1b4b] to-[#4338ca] rounded-[20px] p-8 text-white text-center">
        <div class="w-14 h-14 bg-white/10 rounded-[16px] flex items-center justify-center mx-auto mb-4">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#c7d2fe" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <h2 class="text-[20px] font-bold mb-2">Pantallas digitales · Plan Pro</h2>
        <p class="text-[13.5px] text-indigo-200 max-w-[400px] mx-auto mb-6 leading-relaxed">
            Mostrá tu carta en TVs o monitores del local. Se actualiza sola en menos de 30 segundos cuando cambiás un precio o marcás algo como agotado.
        </p>
        <a href="{{ route('admin.billing.show') }}"
           class="inline-flex items-center gap-2 bg-white text-[#4338CA] font-bold px-6 py-3 rounded-[12px] text-[13.5px] hover:bg-indigo-50 transition-colors">
            Ver planes
        </a>
    </div>

    @elseif($screens->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-12 text-center shadow-[0_1px_3px_rgba(16,24,40,.05)]">
        <div class="w-12 h-12 bg-[#F3F4F6] rounded-[14px] flex items-center justify-center mx-auto mb-4">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <p class="text-[14px] font-semibold text-[#374151] mb-1">Sin pantallas configuradas</p>
        <p class="text-[12.5px] text-[#9CA3AF] mb-5">Creá una pantalla y abrí la URL en tu TV Box.</p>
        <a href="{{ route('admin.screens.create') }}"
           class="inline-flex items-center gap-2 bg-[#4F46E5] text-white rounded-[10px] px-5 py-2.5 text-[13px] font-semibold hover:bg-[#4338CA] transition-colors">
            Crear primera pantalla
        </a>
    </div>

    @else
    <div class="space-y-3">
        @foreach($screens as $screen)
        @php $boardUrl = url('/board/' . $screen->token); @endphp
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-4">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-[10px] bg-[#EEF2FF] flex items-center justify-center flex-none">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[14px] font-bold text-[#111827]">{{ $screen->name }}</span>
                        <span class="text-[10.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[6px] px-2 py-0.5">{{ $screen->columns }} col</span>
                        <span class="text-[10.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[6px] px-2 py-0.5">{{ $screen->orientation }}</span>
                        @if(!$screen->is_active)
                        <span class="text-[10.5px] font-semibold text-[#9CA3AF] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[6px] px-2 py-0.5">Inactiva</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <code class="text-[11.5px] text-[#6B7280] bg-[#F9FAFB] border border-[#F3F4F6] rounded-[6px] px-2 py-0.5 font-mono truncate max-w-[280px]">{{ $boardUrl }}</code>
                        <button onclick="copyUrl('{{ $boardUrl }}')"
                                title="Copiar URL"
                                class="text-[#9CA3AF] hover:text-[#4F46E5] transition-colors flex-none">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-none">
                    <a href="{{ $boardUrl }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#4F46E5] bg-[#EEF2FF] hover:bg-[#E0E7FF] rounded-[8px] px-3 py-1.5 transition-colors">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Abrir
                    </a>
                    <a href="{{ route('admin.screens.edit', $screen) }}"
                       class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#374151] bg-[#F9FAFB] hover:bg-[#F3F4F6] border border-[#E5E7EB] rounded-[8px] px-3 py-1.5 transition-colors">
                        Editar
                    </a>
                </div>
            </div>

            {{-- QR --}}
            <div class="px-5 py-4 border-t border-[#F9FAFB] bg-[#FAFAFA] flex items-center gap-5">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($boardUrl) }}"
                     width="64" height="64" class="rounded-[6px] border border-[#E5E7EB]"
                     alt="QR {{ $screen->name }}">
                <div>
                    <p class="text-[12px] font-semibold text-[#374151] mb-0.5">Escaneá para abrir en el TV Box</p>
                    <p class="text-[11.5px] text-[#9CA3AF] leading-relaxed">
                        En el TV Box: abrí Chrome → escaneá el QR o tipeá la URL → poné la pestaña en pantalla completa (F11).
                    </p>
                </div>
                <div class="ml-auto">
                    <form method="POST" action="{{ route('admin.screens.destroy', $screen) }}"
                          onsubmit="return confirm('¿Eliminar la pantalla "{{ addslashes($screen->name) }}"?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-[12px] font-semibold text-[#DC2626] hover:text-[#B91C1C] transition-colors">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

<div id="copy-toast"
     style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);background:#111827;color:#fff;font-size:12px;font-weight:600;padding:8px 18px;border-radius:999px;opacity:0;pointer-events:none;transition:opacity .2s,transform .2s;z-index:999;">
    URL copiada
</div>

<script>
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        var t = document.getElementById('copy-toast');
        t.style.opacity = '1';
        t.style.transform = 'translateX(-50%) translateY(0)';
        setTimeout(function() {
            t.style.opacity = '0';
            t.style.transform = 'translateX(-50%) translateY(10px)';
        }, 1800);
    });
}
</script>
</x-admin-layout>
