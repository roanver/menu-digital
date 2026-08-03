<x-admin-layout>
<div class="max-w-[900px]">
    <!-- Toolbar -->
    <div class="flex items-center gap-2 mb-4 flex-wrap">
        <div class="flex-1 min-w-[200px] flex items-center gap-2 border border-[#E5E7EB] rounded-[10px] px-3 py-[9px] bg-white shadow-[0_1px_2px_rgba(16,24,40,.03)]">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
            </svg>
            <input type="text" placeholder="Buscar item…" id="searchInput"
                   class="flex-1 text-[13.5px] text-[#111827] placeholder:text-[#9CA3AF] outline-none bg-transparent">
        </div>
        <a href="{{ route('admin.items.create') }}"
           class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors whitespace-nowrap">
            + Nuevo item
        </a>
    </div>

    @if($categories->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] px-6 py-12 text-center">
        <p class="text-[#9CA3AF] text-[13px]">Primero crea al menos una categoría. <a href="{{ route('admin.categories.create') }}" class="text-[#4F46E5] font-semibold hover:text-[#4338CA]">Crear categoría</a></p>
    </div>
    @else
    <!-- Groups by category -->
    <div class="space-y-[14px]" id="itemsContainer">
        @foreach($categories as $category)
        @if($category->menuItems->isNotEmpty())
        <div class="category-group" data-category="{{ strtolower($category->name) }}">
            <!-- Category header -->
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[12px] font-bold uppercase tracking-[.02em] text-[#6B7280]">{{ $category->name }}</span>
                <span class="text-[11px] font-semibold text-[#9CA3AF] bg-[#F3F4F6] rounded-[5px] px-[5px] py-[1px]">{{ $category->menuItems->count() }}</span>
                <div class="flex-1 h-px bg-[#F3F4F6]"></div>
            </div>
            <!-- Items list -->
            <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] overflow-hidden sortable-list" data-category-id="{{ $category->id }}">
                @foreach($category->menuItems as $item)
                <div class="item-row flex items-center gap-3 px-[14px] py-3 border-t border-[#F3F4F6] first:border-t-0 {{ !$item->is_available ? 'opacity-60' : '' }}"
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
                    <div class="w-[44px] h-[44px] rounded-[10px] bg-[#F3F4F6] border border-[#E5E7EB] flex-none overflow-hidden flex items-center justify-center">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/>
                            </svg>
                        @endif
                    </div>
                    <!-- Name + desc -->
                    <div class="flex-1 min-w-0">
                        <div class="text-[13.5px] font-semibold truncate {{ !$item->is_available ? 'text-[#9CA3AF]' : 'text-[#111827]' }}">{{ $item->name }}</div>
                        @if($item->description)
                        <div class="text-[11.5px] text-[#9CA3AF] truncate">{{ $item->description }}</div>
                        @endif
                    </div>
                    <!-- Price -->
                    <span class="text-[13px] font-bold tabular-nums whitespace-nowrap {{ !$item->is_available ? 'text-[#9CA3AF]' : 'text-[#111827]' }}">${{ number_format($item->price, 0, ',', '.') }}</span>
                    <!-- Available toggle -->
                    <form method="POST" action="{{ route('admin.items.update', $item) }}" class="flex-none">
                        @csrf @method('PATCH')
                        <input type="hidden" name="name" value="{{ $item->name }}">
                        <input type="hidden" name="category_id" value="{{ $item->category_id }}">
                        <input type="hidden" name="price" value="{{ $item->price }}">
                        <input type="hidden" name="is_available" value="{{ $item->is_available ? '0' : '1' }}">
                        <button type="submit"
                                class="relative inline-flex h-[22px] w-[38px] items-center rounded-full transition-colors {{ $item->is_available ? 'bg-[#4F46E5]' : 'bg-[#D1D5DB]' }}"
                                title="{{ $item->is_available ? 'Marcar no disponible' : 'Marcar disponible' }}">
                            <span class="inline-block h-[18px] w-[18px] transform rounded-full bg-white shadow-[0_1px_2px_rgba(16,24,40,.3)] transition-transform {{ $item->is_available ? 'translate-x-[18px]' : 'translate-x-[2px]' }}"></span>
                        </button>
                    </form>
                    <!-- Edit -->
                    <a href="{{ route('admin.items.edit', $item) }}"
                       class="w-[30px] h-[30px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#F3F4F6] hover:text-[#111827] transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>
                        </svg>
                    </a>
                    <!-- Delete -->
                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('¿Eliminar item?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-[30px] h-[30px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
