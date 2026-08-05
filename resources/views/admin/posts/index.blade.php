<x-admin-layout>
<div class="max-w-[800px] space-y-5">

    {{-- Hero --}}
    <div class="rounded-[18px] p-6" style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%)">
        <div class="flex items-start gap-4">
            <div class="w-[52px] h-[52px] rounded-[14px] bg-white/15 flex items-center justify-center flex-none">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[18px] font-bold text-white">Generador de posts para redes</h2>
                <p class="text-[13px] text-indigo-200 mt-1 leading-relaxed">Selecciona un plato y la IA generará una imagen 1080×1080px lista para Instagram + copy para la descripción.</p>
            </div>
        </div>
    </div>

    @if($categories->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] p-12 text-center">
        <div class="w-[52px] h-[52px] rounded-[14px] bg-[#F3F4F6] flex items-center justify-center mx-auto mb-4">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/>
            </svg>
        </div>
        <p class="text-[14px] font-semibold text-[#374151] mb-1">No hay items con foto</p>
        <p class="text-[12.5px] text-[#9CA3AF] mb-4">Agrega fotos a los platos para poder generar posts.</p>
        <a href="{{ route('admin.items.index') }}" class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
            Ir a Items
        </a>
    </div>
    @else

    @foreach($categories as $category)
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[#F3F4F6] flex items-center gap-2">
            <span class="text-[13px] font-bold text-[#111827]">{{ $category->name }}</span>
            <span class="text-[11px] font-bold text-[#6B7280] bg-[#F3F4F6] rounded-full px-[7px] py-[1px]">{{ $category->menuItems->count() }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4">
            @foreach($category->menuItems as $item)
            <form method="POST" action="{{ route('admin.posts.generate') }}">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="w-full text-left bg-[#F9FAFB] hover:bg-[#EEF2FF] border border-[#E5E7EB] hover:border-[#C7D2FE] rounded-[14px] overflow-hidden transition-all group hover:shadow-md hover:-translate-y-[2px]">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                             class="w-full h-[90px] object-cover">
                    @else
                        <div class="w-full h-[90px] bg-gradient-to-br from-[#EEF2FF] to-[#E0E7FF] flex items-center justify-center">
                            <span class="text-[32px] font-bold text-[#C7D2FE]">{{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="p-3">
                        <div class="text-[12.5px] font-bold text-[#111827] truncate">{{ $item->name }}</div>
                        <div class="text-[11.5px] font-semibold text-[#6B7280] mt-0.5">${{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="mt-2 flex items-center gap-1 text-[10.5px] font-semibold text-[#4F46E5]">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M20.2 7.8l-7.7-1.9-1.9 7.7 7.7 1.9 1.9-7.7z"/></svg>
                            Generar post
                        </div>
                    </div>
                </button>
            </form>
            @endforeach
        </div>
    </div>
    @endforeach

    @endif
</div>
</x-admin-layout>
