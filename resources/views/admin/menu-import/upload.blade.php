<x-admin-layout>
<div class="max-w-[640px] space-y-4">

    {{-- Header --}}
    <div>
        <h2 class="text-[22px] font-bold tracking-tight">Importar menú con IA</h2>
        <p class="text-[13.5px] text-[#6B7280] mt-1">Sube fotos de tu carta física y la IA extrae las categorías y platos automáticamente.</p>
    </div>

    <form method="POST" action="{{ route('admin.import.process') }}" enctype="multipart/form-data" id="upload-form">
        @csrf

        {{-- Drop zone --}}
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-6 space-y-4">
            <div id="drop-zone"
                 class="border-2 border-dashed border-[#E5E7EB] rounded-[12px] p-8 text-center cursor-pointer hover:border-[#4F46E5] hover:bg-[#FAFBFF] transition-colors"
                 onclick="document.getElementById('images-input').click()">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                <div class="text-[14px] font-semibold text-[#374151] mb-1">Arrastra fotos aquí o haz clic para seleccionar</div>
                <div class="text-[12px] text-[#9CA3AF]">Hasta 5 imágenes · JPG, PNG o WebP · máx. 5 MB c/u</div>
                <input type="file" id="images-input" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
            </div>

            {{-- Preview grid --}}
            <div id="preview-grid" class="grid grid-cols-3 gap-3" style="display:none!important;"></div>
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
                <strong>Tip:</strong> Las mejores fotos son de cartas bien iluminadas, sin sombras ni reflejos. Puedes subir varias páginas de la carta a la vez.
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" id="submit-btn" disabled
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] disabled:opacity-50 disabled:cursor-not-allowed text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold flex items-center justify-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M20.2 7.8l-7.7-1.9-1.9 7.7 7.7 1.9 1.9-7.7z"/></svg>
                Analizar con IA
            </button>
            <a href="{{ route('admin.items.index') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold no-underline">
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

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor='#4F46E5'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor='#E5E7EB'; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.borderColor='#E5E7EB';
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
        div.style.cssText = 'border-radius:10px;overflow:hidden;border:1px solid #E5E7EB;aspect-ratio:4/3;background:#F9FAFB;';
        const img = document.createElement('img');
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
        img.src = URL.createObjectURL(file);
        div.appendChild(img);
        previewGrid.appendChild(div);
    });
    submitBtn.disabled = false;

    // Loading state on submit
    document.getElementById('upload-form').addEventListener('submit', () => {
        submitBtn.textContent = 'Analizando...';
        submitBtn.disabled = true;
    }, { once: true });
}
</script>
</x-admin-layout>
