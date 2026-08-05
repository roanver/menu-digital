<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Tag NFC · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-10 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
    <div class="max-w-[640px] mx-auto px-6 h-[54px] flex items-center gap-3">
        <a href="{{ route('superadmin.nfc.index') }}"
           class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] transition-colors no-underline">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Tags NFC
        </a>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[13px] font-semibold text-[#111827]">Editar tag</span>
        <code class="font-mono text-[12px] font-bold text-[#4F46E5] bg-[#EEF2FF] rounded-[6px] px-[8px] py-[2px]">{{ $tag->code }}</code>
    </div>
</header>

<div class="max-w-[640px] mx-auto px-6 py-8">
    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 mb-6 text-[13px] text-[#DC2626] flex gap-3">
        <svg class="flex-none mt-0.5" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Stats bar --}}
    <div class="bg-[#EEF2FF] border border-[#E0E7FF] rounded-[12px] p-4 mb-5 flex items-center gap-4 flex-wrap">
        <div class="flex items-center gap-2">
            <div class="w-[30px] h-[30px] rounded-[8px] bg-[#4F46E5] flex items-center justify-center">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58"/></svg>
            </div>
            <div>
                <p class="text-[10.5px] font-bold text-[#4338CA] uppercase tracking-wide">Código</p>
                <p class="text-[13px] font-mono font-bold text-[#3730A3]">{{ $tag->code }}</p>
            </div>
        </div>
        <div class="h-[32px] w-px bg-[#C7D2FE]"></div>
        <div>
            <p class="text-[10.5px] font-bold text-[#4338CA] uppercase tracking-wide">Escaneos</p>
            <p class="text-[18px] font-bold text-[#3730A3]">{{ number_format($tag->scans_count) }}</p>
        </div>
        @if($tag->last_scanned_at)
        <div class="h-[32px] w-px bg-[#C7D2FE]"></div>
        <div>
            <p class="text-[10.5px] font-bold text-[#4338CA] uppercase tracking-wide">Último scan</p>
            <p class="text-[13px] font-semibold text-[#3730A3]">{{ $tag->last_scanned_at->format('d/m/Y H:i') }}</p>
        </div>
        @endif
    </div>

    <form method="POST" action="{{ route('superadmin.nfc.update', $tag) }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <h2 class="text-[14px] font-bold text-[#111827]">Editar Tag</h2>
            </div>

            <div class="px-5 py-5 space-y-4">
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Tipo</span>
                    <select name="type" class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]" required>
                        <option value="menu" {{ old('type', $tag->type) === 'menu' ? 'selected' : '' }}>Menú</option>
                        <option value="review" {{ old('type', $tag->type) === 'review' ? 'selected' : '' }}>Reseña</option>
                    </select>
                </label>

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Restaurante</span>
                    <select name="restaurant_id" class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <option value="">Sin asignar</option>
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}" {{ old('restaurant_id', $tag->restaurant_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Label / descripción</span>
                    <input type="text" name="label" value="{{ old('label', $tag->label) }}" placeholder="Ej: Mesa 3, Caja, Entrada"
                           class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)] placeholder:text-[#9CA3AF]">
                </label>

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">URL destino <span class="font-normal text-[#9CA3AF]">(solo para tipo Reseña)</span></span>
                    <input type="url" name="target_url" value="{{ old('target_url', $tag->target_url) }}" placeholder="https://g.page/r/..."
                           class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)] placeholder:text-[#9CA3AF]">
                </label>

                <label class="flex items-center gap-4 cursor-pointer p-4 bg-[#F9FAFB] rounded-[12px] border border-[#E5E7EB] hover:border-[#C7D2FE] transition-colors">
                    <div class="relative flex-none">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tag->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-[40px] h-[23px] bg-[#D1D5DB] rounded-full peer-checked:bg-[#4F46E5] transition-colors"></div>
                        <div class="absolute top-[2.5px] left-[2.5px] w-[18px] h-[18px] bg-white rounded-full shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform peer-checked:translate-x-[17px]"></div>
                    </div>
                    <span class="text-[13px] font-semibold text-[#111827]">Tag activo</span>
                </label>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                Guardar cambios
            </button>
            <a href="{{ route('superadmin.nfc.index') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold no-underline shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
</body>
</html>
