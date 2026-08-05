<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Tags NFC en Lote · MenuDigital</title>
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
        <span class="text-[13px] font-semibold">Crear en lote</span>
    </div>
</header>

<div class="max-w-[640px] mx-auto px-6 py-8">
    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 mb-6 text-[13px] text-[#DC2626]">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.nfc.bulk-create.store') }}" class="space-y-4">
        @csrf
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-6 space-y-4">
            <h2 class="text-[14px] font-bold">Generar tags en lote</h2>
            <p class="text-[13px] text-[#6B7280]">Se generarán códigos únicos de 8 caracteres alfanuméricos automáticamente.</p>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Cantidad (1-50)</span>
                <input type="number" name="quantity" value="{{ old('quantity', 10) }}" min="1" max="50" required
                       class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5]">
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Tipo</span>
                <select name="type" class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5]" required>
                    <option value="menu" {{ old('type') === 'menu' ? 'selected' : '' }}>Menú</option>
                    <option value="review" {{ old('type') === 'review' ? 'selected' : '' }}>Reseña</option>
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Restaurante (opcional)</span>
                <select name="restaurant_id" class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5]">
                    <option value="">Sin asignar</option>
                    @foreach($restaurants as $r)
                        <option value="{{ $r->id }}" {{ old('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-[12px] font-semibold text-[#374151]">Prefijo de label (opcional)</span>
                <input type="text" name="label_prefix" value="{{ old('label_prefix') }}" placeholder="Ej: Mesa"
                       class="px-3 py-[9px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5]">
                <span class="text-[11px] text-[#9CA3AF]">Se generarán labels: "Mesa 1", "Mesa 2", etc.</span>
            </label>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold">Generar Tags</button>
            <a href="{{ route('superadmin.nfc.index') }}" class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold no-underline">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
