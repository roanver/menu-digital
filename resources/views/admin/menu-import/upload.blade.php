<x-admin-layout>
<div class="max-w-[640px] space-y-5">

    {{-- Step indicator --}}
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-[24px] h-[24px] rounded-full bg-[#4F46E5] flex items-center justify-center">
                <span class="text-[11px] font-bold text-white">1</span>
            </div>
            <span class="text-[12.5px] font-bold text-[#4F46E5]">Subir fotos</span>
        </div>
        <div class="flex-1 h-px bg-[#E5E7EB]"></div>
        <div class="flex items-center gap-2">
            <div class="w-[24px] h-[24px] rounded-full bg-[#E5E7EB] flex items-center justify-center">
                <span class="text-[11px] font-semibold text-[#9CA3AF]">2</span>
            </div>
            <span class="text-[12.5px] font-semibold text-[#9CA3AF]">Revisar y confirmar</span>
        </div>
    </div>

    {{-- Hero --}}
    <div class="rounded-[18px] p-6 text-center" style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%)">
        <div class="w-[60px] h-[60px] rounded-[16px] bg-white/15 flex items-center justify-center mx-auto mb-4">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/><path d="M20.2 7.8l-7.7-1.9-1.9 7.7 7.7 1.9 1.9-7.7z"/>
            </svg>
        </div>
        <h2 class="text-[20px] font-bold text-white mb-2">Importa tu carta con IA</h2>
        <p class="text-[13px] text-indigo-200 leading-relaxed max-w-[420px] mx-auto">Sube fotos de tu carta física y la IA extrae las categorías y platos automáticamente. Solo revisa y confirma.</p>
    </div>

    <form method="POST" action="{{ route('admin.import.process') }}" enctype="multipart/form-data" id="upload-form">
        @csrf

        {{-- Drop zone --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div id="drop-zone"
                 class="border-2 border-dashed border-[#E5E7EB] rounded-[14px] m-4 p-10 text-center cursor-pointer hover:border-[#4F46E5] hover:bg-[#FAFBFF] transition-colors"
                 onclick="document.getElementById('images-input').click()">
                <div class="w-[56px] h-[56px] rounded-[16px] bg-[#F3F4F6] flex items-center justify-center mx-auto mb-4">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <div class="text-[14px] font-bold text-[#374151] mb-1">Arrastra fotos de tu carta aquí</div>
                <div class="text-[13px] text-[#6B7280] mb-1">o toca para seleccionar archivos</div>
                <div class="text-[11.5px] text-[#9CA3AF]">Hasta 5 fotos · JPG, PNG, WebP · máx. 5 MB c/u</div>
                <input type="file" id="images-input" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
            </div>

            {{-- Preview grid --}}
            <div id="preview-grid" class="grid grid-cols-3 gap-3 px-4 pb-4" style="display:none!important;"></div>
        </div>

        @if($errors->any())
        <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 text-[13px] text-[#DC2626]">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 text-[13px] text-[#DC2626]">{{ session('error') }}</div>
        @endif

        <div class="bg-[#EEF2FF] border border-[#E0E7FF] rounded-[12px] p-4 flex gap-3">
            <svg class="flex-none mt-0.5" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div class="text-[12.5px] text-[#3730A3]">
                <strong>Tip:</strong> Las mejores fotos son de cartas bien iluminadas, sin sombras ni reflejos. Puedes subir varias páginas a la vez.
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" id="submit-btn" disabled
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] disabled:opacity-40 disabled:cursor-not-allowed text-white border border-[#4338CA] rounded-[10px] px-4 py-[11px] text-[13.5px] font-semibold flex items-center justify-center gap-2 shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M20.2 7.8l-7.7-1.9-1.9 7.7 7.7 1.9 1.9-7.7z"/></svg>
                Analizar con IA
            </button>
            <a href="{{ route('admin.items.index') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[11px] text-[13px] font-semibold no-underline shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
const input = document.getElementById('images-input');
const dropZone = document.getElementById('drop-zone');
const previewGrid = document.getElementById('preview-grid');
const submitBtn = document.getElementById('submit-btn');

input.addEventListener('change', handleFiles);

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor='#4F46E5'; dropZone.style.background='#EEF2FF'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor='#E5E7EB'; dropZone.style.background=''; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.borderColor='#E5E7EB';
    dropZone.style.background='';
    if (e.dataTransfer.files.length) {
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).slice(0, 5).forEach(f => dt.items.add(f));
        input.files = dt.files;
        handleFiles();
    }
});

function handleFiles() {
    const files = Array.from(input.files).slice(0, 5);
    previewGrid.innerHTML = '';
    if (files.length === 0) {
        previewGrid.style.display = 'none';
        submitBtn.disabled = true;
        return;
    }
    previewGrid.style.display = 'grid';
    files.forEach(file => {
        const div = document.createElement('div');
        div.style.cssText = 'position:relative;border-radius:10px;overflow:hidden;border:1px solid #E5E7EB;aspect-ratio:4/3;background:#F9FAFB;';
        const img = document.createElement('img');
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
        img.src = URL.createObjectURL(file);
        const label = document.createElement('div');
        label.style.cssText = 'position:absolute;bottom:0;left:0;right:0;background:rgba(17,24,39,.6);color:#fff;font-size:10px;padding:3px 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
        label.textContent = file.name;
        div.appendChild(img);
        div.appendChild(label);
        previewGrid.appendChild(div);
    });
    submitBtn.disabled = false;

    // Loading state on submit
    document.getElementById('upload-form').addEventListener('submit', () => {
        submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Analizando…';
        submitBtn.disabled = true;
        const style = document.createElement('style');
        style.textContent = '@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}';
        document.head.appendChild(style);
    }, { once: true });
}
</script>
</x-admin-layout>
