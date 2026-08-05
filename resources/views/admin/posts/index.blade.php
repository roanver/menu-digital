<x-admin-layout>
<div class="max-w-[760px] space-y-4">

    <div>
        <h2 class="text-[22px] font-bold tracking-tight">Generador de posts</h2>
        <p class="text-[13.5px] text-[#6B7280] mt-1">Selecciona un plato para generar una imagen y copy para redes sociales.</p>
    </div>

    @foreach($categories as $category)
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] overflow-hidden">
        <div class="px-4 py-3 border-b border-[#F3F4F6] bg-[#F9FAFB]">
            <span class="text-[13px] font-bold text-[#111827]">{{ $category->name }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4">
            @foreach($category->menuItems as $item)
            <form method="POST" action="{{ route('admin.posts.generate') }}">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="w-full text-left bg-[#F9FAFB] hover:bg-[#EEF2FF] border border-[#E5E7EB] hover:border-[#C7D2FE] rounded-[12px] overflow-hidden transition-colors group">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                             class="w-full h-[80px] object-cover">
                    @else
                        <div class="w-full h-[80px] bg-[#EEF2FF] flex items-center justify-center">
                            <span class="text-[28px] text-[#C7D2FE]">{{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="p-2.5">
                        <div class="text-[12px] font-semibold text-[#111827] truncate">{{ $item->name }}</div>
                        <div class="text-[11px] text-[#6B7280] mt-0.5">${{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="mt-1.5 text-[10.5px] font-semibold text-[#4F46E5] group-hover:underline">Generar post →</div>
                    </div>
                </button>
            </form>
            @endforeach
        </div>
    </div>
    @endforeach

    @if($categories->isEmpty())
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] p-10 text-center text-[#9CA3AF] text-[14px]">
        No hay ítems para generar posts. Agrega algunos platos primero.
    </div>
    @endif
</div>
</x-admin-layout>
