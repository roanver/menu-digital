<x-admin-layout>
<div class="max-w-[760px] space-y-5">

    <a href="{{ route('admin.posts.index') }}"
       class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] transition-colors no-underline">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Elegir otro plato
    </a>

    {{-- Hero --}}
    <div class="rounded-[18px] overflow-hidden" style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%)">
        <div class="px-6 py-5 flex items-center gap-4">
            @if($item->image)
            <div class="w-[60px] h-[60px] rounded-[14px] overflow-hidden flex-none border-2 border-white/20 shadow-[0_4px_12px_rgba(0,0,0,.3)]">
                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-[60px] h-[60px] rounded-[14px] bg-white/15 flex items-center justify-center flex-none">
                <span class="text-[24px] font-bold text-white/70">{{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}</span>
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[.1em] text-indigo-300 mb-0.5">Post generado · IA</p>
                <h2 class="text-[20px] font-bold text-white leading-tight truncate">{{ $item->name }}</h2>
                <p class="text-[12.5px] text-indigo-200 mt-0.5">${{ number_format($item->price, 0, ',', '.') }} · Imagen 1080×1080px para Instagram</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Image preview --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden flex flex-col">
            <div class="px-4 py-3.5 border-b border-[#F3F4F6] flex items-center justify-between">
                <span class="text-[13px] font-bold text-[#111827]">Imagen generada</span>
                <span class="text-[10.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[6px] px-[7px] py-[2px]">1080×1080</span>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <div class="rounded-[12px] overflow-hidden border border-[#E5E7EB] shadow-[0_2px_8px_rgba(16,24,40,.08)]">
                    <img src="data:image/png;base64,{{ $imageBase64 }}" alt="{{ $item->name }}"
                         class="w-full" style="aspect-ratio:1/1;object-fit:cover;display:block;">
                </div>

                <form method="POST" action="{{ route('admin.posts.download') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#111827] hover:bg-[#1F2937] text-white rounded-[10px] px-4 py-[10px] text-[13px] font-semibold transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Descargar PNG
                    </button>
                </form>
            </div>
        </div>

        {{-- Copy --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden flex flex-col">
            <div class="px-4 py-3.5 border-b border-[#F3F4F6] flex items-center justify-between">
                <span class="text-[13px] font-bold text-[#111827]">Copy para redes</span>
                <div class="flex items-center gap-1 text-[10.5px] font-semibold text-[#4F46E5] bg-[#EEF2FF] rounded-[6px] px-[7px] py-[2px]">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M20.2 7.8l-7.7-1.9-1.9 7.7 7.7 1.9 1.9-7.7z"/></svg>
                    Generado por IA
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col gap-3">
                <textarea id="copy-text"
                          class="flex-1 w-full px-3 py-3 border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] leading-relaxed resize-none focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]"
                          rows="9">{{ $copy }}</textarea>

                <button onclick="copyToClipboard()"
                        class="w-full flex items-center justify-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors shadow-[0_1px_2px_rgba(16,24,40,.04)]">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copiar al portapapeles
                </button>

                {{-- Regenerate copy --}}
                <form method="POST" action="{{ route('admin.posts.generate') }}">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#EEF2FF] hover:bg-[#E0E7FF] text-[#4F46E5] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold border border-[#E0E7FF] transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.36"/></svg>
                        Regenerar copy
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function copyToClipboard() {
    const text = document.getElementById('copy-text').value;
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const orig = btn.innerHTML;
        btn.textContent = 'Copiado!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}
</script>
</x-admin-layout>
