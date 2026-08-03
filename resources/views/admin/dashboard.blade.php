<x-admin-layout>
@php
    $__menuUrl = url('/' . $restaurant->slug);
    $__planLabel = ucfirst($restaurant->plan ?? 'trial');
    $__isActive = $restaurant->is_active ?? false;
    $__trialEnds = ($restaurant->plan === 'trial' && $restaurant->trial_ends_at)
        ? $restaurant->trial_ends_at->format('d/m/Y')
        : null;
@endphp

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-4">

    {{-- Categorías --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="flex items-center gap-[7px] mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
            </svg>
            <span class="text-[12px] font-semibold text-[#6B7280]">Categorías</span>
        </div>
        <div class="text-[28px] font-bold tracking-tight leading-none mb-[6px]">{{ $categoriesCount }}</div>
        <div class="text-[11.5px] text-[#9CA3AF]">
            {{ $categoriesCount > 0 ? $categoriesCount . ' categorías creadas' : 'Sin categorías aún' }}
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="flex items-center gap-[7px] mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                <circle cx="3.5" cy="6" r=".5" fill="#4F46E5"/><circle cx="3.5" cy="12" r=".5" fill="#4F46E5"/><circle cx="3.5" cy="18" r=".5" fill="#4F46E5"/>
            </svg>
            <span class="text-[12px] font-semibold text-[#6B7280]">Items</span>
        </div>
        <div class="text-[28px] font-bold tracking-tight leading-none mb-[6px]">{{ $itemsCount }}</div>
        <div class="text-[11.5px] text-[#9CA3AF]">
            {{ $itemsCount > 0 ? $itemsCount . ' en el menú' : 'Sin items aún' }}
        </div>
    </div>

    {{-- Plan --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="flex items-center gap-[7px] mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            <span class="text-[12px] font-semibold text-[#6B7280]">Plan</span>
        </div>
        <div class="flex items-baseline gap-2 mb-[6px]">
            <div class="text-[28px] font-bold tracking-tight leading-none">{{ $__planLabel }}</div>
            @if($__isActive)
            <span class="text-[11px] font-semibold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0] rounded-[6px] px-[6px] py-[2px]">Activo</span>
            @endif
        </div>
        <div class="text-[11.5px] text-[#9CA3AF]">
            @if($__trialEnds)
                Trial vence el {{ $__trialEnds }}
            @else
                Suscripción vigente
            @endif
        </div>
    </div>

    {{-- URL --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="flex items-center gap-[7px] mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            <span class="text-[12px] font-semibold text-[#6B7280]">URL del menú</span>
        </div>
        <div class="text-[12px] text-[#374151] truncate mb-[10px] font-medium" title="{{ $__menuUrl }}">
            {{ $__menuUrl }}
        </div>
        <div x-data="{ copied: false }">
            <button
                @click="navigator.clipboard.writeText('{{ $__menuUrl }}'); copied = true; setTimeout(() => copied = false, 1800)"
                :class="copied ? 'bg-[#047857] border-[#047857]' : 'bg-[#4F46E5] border-[#4338CA]'"
                class="w-full flex items-center justify-center gap-[5px] text-white border rounded-[10px] px-[14px] py-[8px] text-[12px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg x-show="!copied" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                <span x-text="copied ? 'Copiado ✓' : 'Copiar URL'"></span>
            </button>
        </div>
    </div>

</div>

{{-- Two-panel grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

    {{-- Completa tu menú checklist --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="text-[13px] font-bold mb-[14px]">Completa tu menú</div>
        <ul class="space-y-[10px]">

            {{-- Logo --}}
            <li class="flex items-start gap-3">
                @if($restaurant->logo)
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center flex-none">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <span class="text-[13px] text-[#047857] font-medium line-through">Subir logo</span>
                @else
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#F3F4F6] border border-[#E5E7EB] flex items-center justify-center flex-none"></span>
                <div class="flex-1">
                    <div class="text-[13px] text-[#374151] font-medium">Subir logo</div>
                    <a href="{{ route('admin.restaurant.edit') }}" class="text-[11.5px] text-[#4F46E5] hover:underline">Ir a Mi Restaurante</a>
                </div>
                @endif
            </li>

            {{-- Categorías --}}
            <li class="flex items-start gap-3">
                @if($categoriesCount > 0)
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center flex-none">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <span class="text-[13px] text-[#047857] font-medium line-through">Crear al menos una categoría</span>
                @else
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#F3F4F6] border border-[#E5E7EB] flex items-center justify-center flex-none"></span>
                <div class="flex-1">
                    <div class="text-[13px] text-[#374151] font-medium">Crear al menos una categoría</div>
                    <a href="{{ route('admin.categories.create') }}" class="text-[11.5px] text-[#4F46E5] hover:underline">Nueva categoría</a>
                </div>
                @endif
            </li>

            {{-- Items --}}
            <li class="flex items-start gap-3">
                @if($itemsCount > 0)
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#ECFDF5] border border-[#A7F3D0] flex items-center justify-center flex-none">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <span class="text-[13px] text-[#047857] font-medium line-through">Agregar items al menú</span>
                @else
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#F3F4F6] border border-[#E5E7EB] flex items-center justify-center flex-none"></span>
                <div class="flex-1">
                    <div class="text-[13px] text-[#374151] font-medium">Agregar items al menú</div>
                    <a href="{{ route('admin.items.create') }}" class="text-[11.5px] text-[#4F46E5] hover:underline">Nuevo item</a>
                </div>
                @endif
            </li>

            {{-- Apariencia --}}
            <li class="flex items-start gap-3">
                <span class="mt-[1px] w-[18px] h-[18px] rounded-full bg-[#F3F4F6] border border-[#E5E7EB] flex items-center justify-center flex-none"></span>
                <div class="flex-1">
                    <div class="text-[13px] text-[#374151] font-medium">Personalizar apariencia</div>
                    <a href="{{ route('admin.appearance.edit') }}" class="text-[11.5px] text-[#4F46E5] hover:underline">Ir a Apariencia</a>
                </div>
            </li>

        </ul>
    </div>

    {{-- Quick links --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-[15px] shadow-[0_1px_2px_rgba(16,24,40,.04)]">
        <div class="text-[13px] font-bold mb-[14px]">Accesos rápidos</div>
        <div class="flex flex-col gap-[8px]">

            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-[9px] bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nueva categoría
            </a>

            <a href="{{ route('admin.items.create') }}"
               class="flex items-center gap-[9px] bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo item
            </a>

            <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
               class="flex items-center gap-[9px] bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 4h6v6M20 4 11 13M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
                </svg>
                Ver menú público
            </a>

            <a href="{{ route('admin.qr.show') }}"
               class="flex items-center gap-[9px] bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
                    <rect x="15" y="15" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
                </svg>
                Descargar QR
            </a>

        </div>
    </div>

</div>
</x-admin-layout>
