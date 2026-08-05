<x-admin-layout>
<div class="max-w-[820px]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between gap-3 mb-5 flex-wrap">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-[18px] font-bold text-[#111827]">Categorías</h1>
                <span class="text-[11.5px] font-bold text-[#4F46E5] bg-[#EEF2FF] rounded-full px-[9px] py-[2px]">{{ $categories->count() }}</span>
            </div>
            <p class="text-[12px] text-[#6B7280] mt-0.5">Arrastra para cambiar el orden en el menú.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors whitespace-nowrap">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva categoría
        </a>
    </div>

    <!-- Card list with SortableJS -->
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden" id="sortable-categories">
        @forelse($categories as $category)
        <div class="flex items-center gap-3 px-4 py-[13px] border-t border-[#F3F4F6] first:border-t-0 hover:bg-[#FAFBFF] transition-colors group" data-id="{{ $category->id }}">
            <!-- Drag handle -->
            <div class="drag-handle cursor-grab text-[#D1D5DB] hover:text-[#9CA3AF] flex-none active:cursor-grabbing">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="9" cy="5" r="1.7"/><circle cx="15" cy="5" r="1.7"/>
                    <circle cx="9" cy="12" r="1.7"/><circle cx="15" cy="12" r="1.7"/>
                    <circle cx="9" cy="19" r="1.7"/><circle cx="15" cy="19" r="1.7"/>
                </svg>
            </div>
            <!-- Name + badge -->
            <div class="flex-1 min-w-0 flex items-center gap-2">
                <span class="text-[14px] font-semibold {{ $category->is_active ? 'text-[#111827]' : 'text-[#9CA3AF]' }}">{{ $category->name }}</span>
                @if(!$category->is_active)
                <span class="text-[10px] font-bold text-[#6B7280] bg-[#F3F4F6] rounded-[5px] px-[5px] py-[2px] uppercase tracking-wide">Oculta</span>
                @endif
            </div>
            <!-- Item count badge -->
            <span class="text-[11.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[7px] px-[8px] py-[3px] whitespace-nowrap">
                {{ $category->menu_items_count ?? $category->menuItems()->count() }} items
            </span>
            <!-- Toggle active -->
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex-none">
                @csrf @method('PATCH')
                <input type="hidden" name="name" value="{{ $category->name }}">
                <input type="hidden" name="is_active" value="{{ $category->is_active ? '0' : '1' }}">
                <button type="submit"
                        class="relative inline-flex h-[22px] w-[40px] items-center rounded-full transition-colors {{ $category->is_active ? 'bg-[#4F46E5]' : 'bg-[#D1D5DB]' }}"
                        title="{{ $category->is_active ? 'Ocultar' : 'Mostrar' }}">
                    <span class="inline-block h-[18px] w-[18px] transform rounded-full bg-white shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform {{ $category->is_active ? 'translate-x-[20px]' : 'translate-x-[2px]' }}"></span>
                </button>
            </form>
            <!-- Edit icon btn -->
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="w-[32px] h-[32px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#EEF2FF] hover:text-[#4F46E5] transition-colors">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>
                </svg>
            </a>
            <!-- Delete form -->
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('¿Eliminar categoría?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-[32px] h-[32px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    </svg>
                </button>
            </form>
        </div>
        @empty
        <div class="px-6 py-16 text-center">
            <div class="w-[56px] h-[56px] rounded-[16px] bg-[#F3F4F6] flex items-center justify-center mx-auto mb-4">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-[#374151] mb-1">Aún no hay categorías</p>
            <p class="text-[12.5px] text-[#9CA3AF] mb-4">Crea la primera categoría para organizar tu menú.</p>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-[16px] py-[9px] text-[13px] font-semibold transition-colors">
                + Nueva categoría
            </a>
        </div>
        @endforelse
    </div>

    @if($categories->count() > 0)
    <p class="text-[11.5px] text-[#9CA3AF] mt-3 flex items-center gap-1">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $categories->count() }} categorías · el orden se aplica al instante en el menú público.
    </p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('sortable-categories');
    if (el && typeof Sortable !== 'undefined') {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                var ids = Array.from(el.querySelectorAll('[data-id]')).map(function (r) { return r.dataset.id; });
                fetch('{{ route('admin.categories.reorder') }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: ids })
                });
            }
        });
    }
});
</script>
</x-admin-layout>
