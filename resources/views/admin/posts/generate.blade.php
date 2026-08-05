<x-admin-layout>
<div class="max-w-[640px] space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.posts.index') }}" class="text-[#6B7280] hover:text-[#111827] no-underline text-[13px]">← Volver</a>
    </div>

    <h2 class="text-[22px] font-bold tracking-tight">Post: {{ $item->name }}</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Image preview --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] overflow-hidden">
            <div class="px-4 py-3 border-b border-[#F3F4F6]">
                <span class="text-[13px] font-bold">Imagen generada</span>
            </div>
            <div class="p-4">
                <img src="data:image/png;base64,{{ $imageBase64 }}" alt="{{ $item->name }}"
                     class="w-full rounded-[10px] border border-[#E5E7EB]" style="aspect-ratio:1/1;object-fit:cover;">

                <form method="POST" action="{{ route('admin.posts.download') }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#111827] hover:bg-[#1F2937] text-white rounded-[10px] px-4 py-[10px] text-[13px] font-semibold">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Descargar PNG
                    </button>
                </form>
            </div>
        </div>

        {{-- Copy --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-[#F3F4F6] flex items-center justify-between">
                <span class="text-[13px] font-bold">Copy para redes</span>
                <span class="text-[11px] text-[#9CA3AF]">Generado por IA</span>
            </div>
            <div class="p-4 flex-1 flex flex-col gap-3">
                <textarea id="copy-text"
                          class="flex-1 w-full px-3 py-3 border border-[#E5E7EB] rounded-[10px] text-[13.5px] text-[#111827] resize-none focus:outline-none focus:border-[#4F46E5]"
                          rows="7">{{ $copy }}</textarea>

                <button onclick="copyToClipboard()"
                        class="w-full flex items-center justify-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copiar al portapapeles
                </button>

                {{-- Regenerate copy --}}
                <form method="POST" action="{{ route('admin.posts.generate') }}">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#EEF2FF] hover:bg-[#E0E7FF] text-[#4F46E5] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold border border-[#E0E7FF]">
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
        btn.textContent = '¡Copiado!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}
</script>
</x-admin-layout>
