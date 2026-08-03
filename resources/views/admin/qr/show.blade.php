<x-admin-layout>
<div class="max-w-[740px]">
    <p class="text-[12.5px] text-[#6B7280] mb-5">Descarga e imprime el QR para que tus clientes accedan al menú desde la mesa.</p>

    <div class="grid grid-cols-1 md:grid-cols-[320px_1fr] gap-4 items-start">

        <!-- QR Card -->
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-5 flex flex-col items-center text-center">
            <!-- QR -->
            <div class="p-3 bg-white border border-[#E5E7EB] rounded-[12px] shadow-[0_1px_3px_rgba(16,24,40,.06)] mb-4">
                {!! $qr !!}
            </div>

            <!-- URL -->
            <p class="text-[11px] text-[#9CA3AF] mb-1">Tu menú está en:</p>
            <a href="{{ $url }}" target="_blank"
               class="text-[12.5px] font-semibold text-[#4F46E5] hover:text-[#4338CA] break-all transition-colors mb-5">
                {{ $url }}
            </a>

            <!-- Actions -->
            <div class="w-full flex flex-col gap-2">
                <a href="{{ route('admin.qr.download') }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Descargar PNG
                </a>
                <a href="{{ $url }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                    </svg>
                    Ver menú público
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="space-y-3">
            <!-- How to use -->
            <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                <div class="text-[13px] font-bold text-[#111827] mb-3">Cómo usar tu QR</div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-[26px] h-[26px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">1</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-[#111827]">Descarga el PNG</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Haz clic en "Descargar PNG" para obtener el archivo de alta resolución.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-[26px] h-[26px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">2</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-[#111827]">Imprime y coloca en las mesas</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Imprime en papel fotográfico o plastificado para mayor durabilidad.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-[26px] h-[26px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">3</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-[#111827]">Los clientes escanean</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Con la cámara del celular sin necesidad de app. El menú se abre al instante.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NFC Tip -->
            <div class="bg-[#EEF2FF] border border-[#E0E7FF] rounded-[14px] p-4">
                <div class="flex items-start gap-3">
                    <div class="w-[32px] h-[32px] rounded-[8px] bg-[#4F46E5] flex items-center justify-center flex-none">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58M12.91 4.1a15.91 15.91 0 0 1 .01 15.8M16.37 2a20.16 20.16 0 0 1 0 20"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-[#3730A3]">Tip: Chip NFC</p>
                        <p class="text-[12px] text-[#4F46E5] mt-0.5">Si configuras un chip NFC en tu mesa, apúntalo a:</p>
                        <p class="text-[12px] font-semibold text-[#3730A3] mt-1 break-all">{{ $url }}</p>
                    </div>
                </div>
            </div>

            <!-- URL copy helper -->
            <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
                <div class="text-[12px] font-semibold text-[#374151] mb-2">URL del menú</div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 min-w-0 px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px]">
                        <span class="text-[12.5px] text-[#6B7280] truncate block">{{ $url }}</span>
                    </div>
                    <button type="button"
                            onclick="navigator.clipboard.writeText('{{ $url }}').then(function(){ this.textContent='¡Copiado!'; setTimeout(function(b){ b.textContent='Copiar'; }.bind(null, this), 2000); }.bind(this))"
                            class="inline-flex items-center gap-1.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-3 py-[9px] text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors whitespace-nowrap">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copiar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
</x-admin-layout>
