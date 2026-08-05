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

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-10">
    <div class="max-w-[640px] mx-auto px-6 h-[54px] flex items-center gap-3">
        <a href="{{ route('superadmin.nfc.index') }}" class="text-[#6B7280] hover:text-[#111827] no-underline text-[13px]">← Tags NFC</a>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[13px] font-semibold">Editar tag {{ $tag->code }}</span>
    </div>
</header>

<div class="max-w-[640px] mx-auto px-6 py-8">
    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 mb-6 text-[13px] text-[#DC2626]">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="bg-[#EEF2FF] border border-[#E0E7FF] rounded-[12px] p-4 mb-4 flex items-center gap-3">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span class="text-[13px] text-[#3730A3] font-medium">Código NFC: <strong class="font-mono">{{ $tag->code }}</strong> · Escaneos: <strong>{{ $tag->scans_count }}</strong></span>
    </div>

    <form method="POST" action="{{ route('superadmin.nfc.update', $tag) }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-6 space-y-4">
            <h2 class="text-[14px] font-bold">Editar Tag</h2>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Tipo</span>
                <select name="type" class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5]" required>
                    <option value="menu" {{ old('type', $tag->type) === 'menu' ? 'selected' : '' }}>Menú</option>
                    <option value="review" {{ old('type', $tag->type) === 'review' ? 'selected' : '' }}>Reseña</option>
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Restaurante</span>
                <select name="restaurant_id" class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5]">
                    <option value="">Sin asignar</option>
                    @foreach($restaurants as $r)
                        <option value="{{ $r->id }}" {{ old('restaurant_id', $tag->restaurant_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Label / descripción</span>
                <input type="text" name="label" value="{{ old('label', $tag->label) }}" placeholder="Ej: Mesa 3, Caja, Entrada"
                       class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">URL destino (solo para tipo Reseña)</span>
                <input type="url" name="target_url" value="{{ old('target_url', $tag->target_url) }}" placeholder="https://g.page/r/..."
                       class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5]">
            </label>

            <label class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tag->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded accent-[#4F46E5]">
                <span class="text-[13px] font-semibold">Activo</span>
            </label>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold">Guardar cambios</button>
            <a href="{{ route('superadmin.nfc.index') }}" class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold no-underline">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
