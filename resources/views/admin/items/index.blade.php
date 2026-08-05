<x-admin-layout>
<div class="max-w-[900px]">
    <!-- Toolbar -->
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <div class="flex-1 min-w-[200px] flex items-center gap-2 border border-[#E5E7EB] rounded-[10px] px-3 py-[10px] bg-white shadow-[0_1px_2px_rgba(16,24,40,.03)] focus-within:border-[#4F46E5] focus-within:shadow-[0_0_0_3px_rgba(79,70,229,.1)] transition-all">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
            </svg>
            <input type="text" placeholder="Buscar item…" id="searchInput"
                   class="flex-1 text-[13.5px] text-[#111827] placeholder:text-[#9CA3AF] outline-none bg-transparent">
        </div>
        <a href="{{ route('admin.items.create') }}"
           class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[10px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors whitespace-nowrap">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo item
        </a>
    </div>

    @if($categories->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] px-6 py-14 text-center">
        <div class="w-[52px] h-[52px] rounded-[14px] bg-[#F3F4F6] flex items-center justify-center mx-auto mb-4">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-[#374151] mb-1">Primero crea una categoría</p>
        <p class="text-[12.5px] text-[#9CA3AF] mb-4">Organiza tu menú en secciones como Entradas, Platos o Bebidas.</p>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
            + Crear categoría
        </a>
    </div>
    @else
    <!-- Groups by category -->
    <div class="space-y-4" id="itemsContainer">
        @foreach($categories as $category)
        @if($category->menuItems->isNotEmpty())
        <div class="category-group" data-category="{{ strtolower($category->name) }}">
            <!-- Category header -->
            <div class="flex items-center gap-2 mb-2.5 px-1">
                <span class="text-[11.5px] font-bold uppercase tracking-[.08em] text-[#374151]">{{ $category->name }}</span>
                <span class="text-[10.5px] font-bold text-[#4F46E5] bg-[#EEF2FF] rounded-full px-[7px] py-[1px]">{{ $category->menuItems->count() }}</span>
                <div class="flex-1 h-px bg-[#E5E7EB]"></div>
            </div>
            <!-- Items list -->
            <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden sortable-list" data-category-id="{{ $category->id }}">
                @foreach($category->menuItems as $item)
                <div class="item-row flex items-center gap-3 px-4 py-[11px] border-t border-[#F3F4F6] first:border-t-0 hover:bg-[#FAFBFF] transition-colors"
                     data-id="{{ $item->id }}"
                     data-name="{{ strtolower($item->name) }}">
                    <!-- Drag handle -->
                    <div class="drag-handle cursor-grab text-[#D1D5DB] hover:text-[#9CA3AF] flex-none">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="9" cy="5" r="1.6"/><circle cx="15" cy="5" r="1.6"/>
                            <circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/>
                            <circle cx="9" cy="19" r="1.6"/><circle cx="15" cy="19" r="1.6"/>
                        </svg>
                    </div>
                    <!-- Thumbnail -->
                    <div class="w-[52px] h-[52px] rounded-[10px] bg-[#F3F4F6] border border-[#E5E7EB] flex-none overflow-hidden flex items-center justify-center">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/>
                            </svg>
                        @endif
                    </div>
                    <!-- Name + desc -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[14px] font-semibold truncate {{ !$item->is_available ? 'text-[#9CA3AF]' : 'text-[#111827]' }}">{{ $item->name }}</span>
                            @if(!$item->is_available)
                            <span class="text-[10px] font-bold text-[#DC2626] bg-[#FEF2F2] border border-[#FECACA] rounded-[5px] px-[5px] py-[1px] whitespace-nowrap flex-none">Agotado</span>
                            @elseif($item->is_promo)
                            <span class="text-[10px] font-bold text-[#EA580C] bg-[#FFF7ED] border border-[#FED7AA] rounded-[5px] px-[5px] py-[1px] whitespace-nowrap flex-none">Promo</span>
                            @endif
                        </div>
                        @if($item->description)
                        <div class="text-[11.5px] text-[#9CA3AF] truncate mt-0.5">{{ $item->description }}</div>
                        @endif
                    </div>
                    <!-- Price -->
                    <div class="text-right whitespace-nowrap">
                        @if($item->is_promo && $item->promo_price)
                        <div class="text-[11px] text-[#9CA3AF] line-through tabular-nums">${{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="text-[14px] font-bold text-[#EA580C] tabular-nums">${{ number_format($item->promo_price, 0, ',', '.') }}</div>
                        @else
                        <span class="text-[14px] font-bold tabular-nums {{ !$item->is_available ? 'text-[#9CA3AF]' : 'text-[#111827]' }}">${{ number_format($item->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <!-- Available toggle -->
                    <form method="POST" action="{{ route('admin.items.update', $item) }}" class="flex-none">
                        @csrf @method('PATCH')
                        <input type="hidden" name="name" value="{{ $item->name }}">
                        <input type="hidden" name="category_id" value="{{ $item->category_id }}">
                        <input type="hidden" name="price" value="{{ $item->price }}">
                        <input type="hidden" name="is_available" value="{{ $item->is_available ? '0' : '1' }}">
                        <button type="submit"
                                class="relative inline-flex h-[22px] w-[40px] items-center rounded-full transition-colors {{ $item->is_available ? 'bg-[#4F46E5]' : 'bg-[#D1D5DB]' }}"
                                title="{{ $item->is_available ? 'Marcar no disponible' : 'Marcar disponible' }}">
                            <span class="inline-block h-[18px] w-[18px] transform rounded-full bg-white shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform {{ $item->is_available ? 'translate-x-[20px]' : 'translate-x-[2px]' }}"></span>
                        </button>
                    </form>
                    <!-- Edit -->
                    <a href="{{ route('admin.items.edit', $item) }}"
                       class="w-[32px] h-[32px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#EEF2FF] hover:text-[#4F46E5] transition-colors">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>
                        </svg>
                    </a>
                    <!-- Delete -->
                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('¿Eliminar item?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-[32px] h-[32px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Search
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('.item-row').forEach(function (row) {
                row.style.display = row.dataset.name.includes(q) ? '' : 'none';
            });
            document.querySelectorAll('.category-group').forEach(function (group) {
                var visible = Array.from(group.querySelectorAll('.item-row')).some(function (r) { return r.style.display !== 'none'; });
                group.style.display = visible ? '' : 'none';
            });
        });
    }

    // SortableJS per category
    document.querySelectorAll('.sortable-list').forEach(function (list) {
        if (typeof Sortable !== 'undefined') {
            Sortable.create(list, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function () {
                    var ids = Array.from(list.querySelectorAll('[data-id]')).map(function (r) { return r.dataset.id; });
                    fetch('{{ route('admin.items.reorder') }}', {
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
});
</script>
</x-admin-layout>
