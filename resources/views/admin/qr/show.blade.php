<x-admin-layout>
@php $__hasKit = $restaurant->kits()->where('status', 'activado')->exists(); @endphp
<div class="max-w-[780px]">

    <div class="grid grid-cols-1 md:grid-cols-[340px_1fr] gap-5 items-start">

        <!-- QR Hero Card -->
        <div class="bg-white border border-[#E5E7EB] rounded-[20px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <!-- Header -->
            <div class="px-5 py-4 border-b border-[#F3F4F6] text-center">
                <p class="text-[12px] font-semibold uppercase tracking-[.1em] text-[#9CA3AF]">Código QR del menú</p>
                <p class="text-[15px] font-bold text-[#111827] mt-0.5">{{ $restaurant->name ?? '' }}</p>
            </div>

            <!-- QR Container -->
            <div class="p-6 flex flex-col items-center">
                <div class="p-4 rounded-[16px] bg-white border-2 border-[#E5E7EB] shadow-[0_4px_16px_rgba(16,24,40,.1)] mb-4" style="min-width:240px;min-height:240px;display:flex;align-items:center;justify-content:center;">
                    {!! $qr !!}
                </div>

                <!-- URL -->
                <p class="text-[11px] text-[#9CA3AF] mb-1">Tu menú está disponible en:</p>
                <a href="{{ $url }}" target="_blank"
                   class="text-[12.5px] font-semibold text-[#4F46E5] hover:text-[#4338CA] break-all transition-colors text-center">
                    {{ $url }}
                </a>

                <p class="text-[11px] text-[#9CA3AF] mt-4 text-center leading-relaxed">
                    Coloca este QR en las mesas o en la entrada
                </p>
            </div>

            <!-- Actions -->
            <div class="px-5 pb-5 flex flex-col gap-2">
                <a href="{{ route('admin.qr.download') }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Descargar PNG
                </a>

                <div x-data="{ copied: false }" class="w-full">
                    <button
                        @click="navigator.clipboard.writeText('{{ $url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        :class="copied ? 'bg-[#ECFDF5] border-[#6EE7B7] text-[#047857]' : 'bg-white border-[#E5E7EB] text-[#374151] hover:bg-[#F9FAFB]'"
                        class="w-full inline-flex items-center justify-center gap-2 border rounded-[10px] px-[14px] py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                        <svg x-show="!copied" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        <span x-text="copied ? 'URL copiada' : 'Copiar URL del menú'"></span>
                    </button>
                </div>

                <a href="{{ $url }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                    </svg>
                    Ver menú público
                </a>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="space-y-4">
            <!-- How to use -->
            <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] p-5">
                <div class="text-[13px] font-bold text-[#111827] mb-4">Cómo usar tu QR</div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-[28px] h-[28px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">1</span>
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-[#111827]">Descarga el PNG</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Archivo de alta resolución listo para imprimir.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-[28px] h-[28px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">2</span>
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-[#111827]">Imprime y coloca en las mesas</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Papel fotográfico o plastificado para mayor durabilidad.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-[28px] h-[28px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none mt-0.5">
                            <span class="text-[11px] font-bold text-[#4F46E5]">3</span>
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-[#111827]">Los clientes escanean con su cámara</p>
                            <p class="text-[12px] text-[#6B7280] mt-0.5">Sin necesidad de app. El menú se abre al instante.</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($__hasKit)
            <!-- NFC Tip (kit activo — instrucciones técnicas) -->
            <div class="rounded-[16px] p-5" style="background:linear-gradient(135deg,#1e1b4b,#312e81)">
                <div class="flex items-start gap-3">
                    <div class="w-[36px] h-[36px] rounded-[10px] bg-white/15 flex items-center justify-center flex-none">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58M12.91 4.1a15.91 15.91 0 0 1 .01 15.8M16.37 2a20.16 20.16 0 0 1 0 20"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-white">Chips NFC del kit</p>
                        <p class="text-[12px] text-indigo-200 mt-1">Los chips de tu kit ya vienen grabados con el link de cada mesa. Puedes ver el link de cada chip en <a href="{{ route('admin.tables.index') }}" class="underline text-indigo-100">Mesas</a>.</p>
                    </div>
                </div>
            </div>
            @else
            <!-- Kit upsell (sin kit) -->
            <div class="rounded-[16px] p-5" style="background:linear-gradient(135deg,#1e1b4b,#312e81)">
                <div class="flex items-start gap-3">
                    <div class="w-[36px] h-[36px] rounded-[10px] bg-white/15 flex items-center justify-center flex-none">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58M12.91 4.1a15.91 15.91 0 0 1 .01 15.8M16.37 2a20.16 20.16 0 0 1 0 20"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-white">¿Quieres que abra solo al acercar el celular?</p>
                        <p class="text-[12px] text-indigo-200 mt-1 leading-relaxed">Con el kit físico, cada mesa tiene un chip NFC pregrabado. Tus clientes no necesitan escanear nada — solo acercan el celular y el menú se abre.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
</x-admin-layout>
