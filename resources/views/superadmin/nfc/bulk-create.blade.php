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

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-10 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
    <div class="max-w-[640px] mx-auto px-6 h-[54px] flex items-center gap-3">
        <a href="{{ route('superadmin.nfc.index') }}"
           class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] transition-colors no-underline">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Tags NFC
        </a>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[13px] font-semibold text-[#111827]">Crear en lote</span>
    </div>
</header>

<div class="max-w-[640px] mx-auto px-6 py-8" x-data="{
    quantity: {{ old('quantity', 10) }},
    labelPrefix: '{{ old('label_prefix', '') }}',
    get preview() {
        if (!this.labelPrefix || this.quantity < 1) return [];
        return Array.from({length: Math.min(this.quantity, 5)}, (_, i) => this.labelPrefix + ' ' + (i + 1));
    }
}">

    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 mb-6 text-[13px] text-[#DC2626] flex gap-3">
        <svg class="flex-none mt-0.5" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.nfc.bulk-create.store') }}" class="space-y-4">
        @csrf
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <h2 class="text-[14px] font-bold text-[#111827]">Generar tags en lote</h2>
                <p class="text-[12px] text-[#9CA3AF] mt-0.5">Se generarán códigos únicos de 8 caracteres alfanuméricos automáticamente.</p>
            </div>

            <div class="px-5 py-5 space-y-5">

                <!-- Cantidad con slider -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[12px] font-semibold text-[#374151]">Cantidad</span>
                        <span class="text-[16px] font-bold text-[#4F46E5]" x-text="quantity"></span>
                    </div>
                    <input type="range" name="quantity" min="1" max="50" x-model="quantity"
                           class="w-full h-[4px] bg-[#E5E7EB] rounded-full appearance-none cursor-pointer accent-[#4F46E5]">
                    <div class="flex justify-between text-[10.5px] text-[#9CA3AF] mt-1">
                        <span>1</span>
                        <span>25</span>
                        <span>50</span>
                    </div>
                </div>

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Tipo</span>
                    <select name="type" class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]" required>
                        <option value="menu" {{ old('type') === 'menu' ? 'selected' : '' }}>Menú</option>
                        <option value="review" {{ old('type') === 'review' ? 'selected' : '' }}>Reseña</option>
                    </select>
                </label>

                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Restaurante <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                    <select name="restaurant_id" class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] bg-white focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                        <option value="">Sin asignar</option>
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}" {{ old('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </label>

                <div>
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[12px] font-semibold text-[#374151]">Prefijo de label <span class="font-normal text-[#9CA3AF]">(opcional)</span></span>
                        <input type="text" name="label_prefix" x-model="labelPrefix" value="{{ old('label_prefix') }}" placeholder="Ej: Mesa"
                               class="px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13.5px] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)] placeholder:text-[#9CA3AF]">
                        <span class="text-[11px] text-[#9CA3AF]">Se generarán labels: "Mesa 1", "Mesa 2", etc.</span>
                    </label>

                    <!-- Preview labels -->
                    <div x-show="preview.length > 0" class="mt-3 p-3 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB]">
                        <p class="text-[10.5px] font-bold text-[#6B7280] uppercase tracking-wide mb-2">Vista previa de labels:</p>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="label in preview" :key="label">
                                <span class="text-[11.5px] font-semibold text-[#374151] bg-white border border-[#E5E7EB] rounded-[6px] px-2.5 py-1" x-text="label"></span>
                            </template>
                            <span x-show="quantity > 5" class="text-[11.5px] text-[#9CA3AF] px-2.5 py-1" x-text="'+ ' + (quantity - 5) + ' más'"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <span x-text="'Generar ' + quantity + ' tags'"></span>
            </button>
            <a href="{{ route('superadmin.nfc.index') }}"
               class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold no-underline shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
