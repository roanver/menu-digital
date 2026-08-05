<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tags NFC · Super Admin · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-10">
    <div class="max-w-[1200px] mx-auto px-6 h-[54px] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.index') }}" class="flex items-center gap-2 no-underline">
                <div class="w-[28px] h-[28px] rounded-[8px] bg-[#4F46E5] flex items-center justify-center">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
                </div>
                <span class="text-[14px] font-bold text-[#111827]">MenuDigital</span>
            </a>
            <span class="text-[#D1D5DB]">/</span>
            <span class="text-[13px] font-semibold text-[#6B7280]">Tags NFC</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.nfc.create') }}" class="bg-[#4F46E5] hover:bg-[#4338CA] text-white text-[12.5px] font-semibold px-4 py-2 rounded-[9px] no-underline">+ Nuevo tag</a>
            <a href="{{ route('superadmin.nfc.bulk-create') }}" class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] text-[12.5px] font-semibold px-4 py-2 rounded-[9px] no-underline">Crear en lote</a>
            <form method="POST" action="/logout">@csrf<button type="submit" class="text-[12.5px] font-semibold text-[#6B7280]">Salir</button></form>
        </div>
    </div>
</header>

<div class="max-w-[1200px] mx-auto px-6 py-8">

    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[12px] p-4 mb-6 text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="bg-white border border-[#E5E7EB] rounded-[12px] p-4 mb-4 flex gap-3 flex-wrap items-end">
        <div>
            <label class="block text-[11.5px] font-semibold text-[#6B7280] mb-1">Restaurante</label>
            <select name="restaurant_id" class="px-3 py-2 border border-[#E5E7EB] rounded-[8px] text-[13px] bg-white">
                <option value="">Todos</option>
                @foreach($restaurants as $r)
                    <option value="{{ $r->id }}" {{ request('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11.5px] font-semibold text-[#6B7280] mb-1">Tipo</label>
            <select name="type" class="px-3 py-2 border border-[#E5E7EB] rounded-[8px] text-[13px] bg-white">
                <option value="">Todos</option>
                <option value="menu" {{ request('type') === 'menu' ? 'selected' : '' }}>Menú</option>
                <option value="review" {{ request('type') === 'review' ? 'selected' : '' }}>Reseña</option>
            </select>
        </div>
        <div>
            <label class="block text-[11.5px] font-semibold text-[#6B7280] mb-1">Estado</label>
            <select name="active" class="px-3 py-2 border border-[#E5E7EB] rounded-[8px] text-[13px] bg-white">
                <option value="">Todos</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <button type="submit" class="bg-[#111827] text-white text-[13px] font-semibold px-4 py-2 rounded-[8px]">Filtrar</button>
        <a href="{{ route('superadmin.nfc.index') }}" class="text-[13px] font-medium text-[#6B7280] py-2 no-underline">Limpiar</a>
    </form>

    <div class="bg-white border border-[#E5E7EB] rounded-[14px] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB]">
            <h2 class="text-[13px] font-bold">Tags NFC ({{ $tags->total() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#F3F4F6]">
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Código</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Tipo</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Restaurante</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Label</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Escaneos</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Último scan</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Activo</th>
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280]">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                    <tr class="border-b border-[#F9FAFB] hover:bg-[#FAFAFA]">
                        <td class="px-4 py-3">
                            <code class="font-mono text-[12px] font-bold text-[#4F46E5] bg-[#EEF2FF] px-2 py-0.5 rounded">{{ $tag->code }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $tag->type === 'menu' ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'bg-[#FEF3C7] text-[#B45309]' }}">
                                {{ $tag->type === 'menu' ? 'Menú' : 'Reseña' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[#4B5563]">{{ $tag->restaurant?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-[#4B5563]">{{ $tag->label ?? '—' }}</td>
                        <td class="px-4 py-3 text-[#111827] font-semibold">{{ number_format($tag->scans_count) }}</td>
                        <td class="px-4 py-3 text-[#6B7280]">{{ $tag->last_scanned_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($tag->is_active)
                                <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#059669]"><span class="w-[5px] h-[5px] rounded-full bg-[#059669]"></span> Sí</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#DC2626]"><span class="w-[5px] h-[5px] rounded-full bg-[#DC2626]"></span> No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('superadmin.nfc.edit', $tag) }}" class="text-[12px] font-semibold text-[#4F46E5] no-underline hover:underline">Editar</a>
                                <form method="POST" action="{{ route('superadmin.nfc.destroy', $tag) }}" onsubmit="return confirm('¿Eliminar este tag?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[12px] font-semibold text-[#DC2626]">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-[13px] text-[#9CA3AF]">No hay tags NFC.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tags->hasPages())
        <div class="px-5 py-4 border-t border-[#F3F4F6]">{{ $tags->links() }}</div>
        @endif
    </div>
</div>
</body>
</html>
