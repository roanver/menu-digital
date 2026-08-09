<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selecciona un negocio · MenuDigital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="flex items-center justify-center gap-2 mb-8">
        <div class="w-[36px] h-[36px] rounded-[11px] bg-[#4F46E5] flex items-center justify-center shadow-[0_1px_3px_rgba(79,70,229,.4)]">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round">
                <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
            </svg>
        </div>
        <span class="text-[18px] font-bold tracking-tight">MenuDigital</span>
    </div>

    <div class="bg-white rounded-[16px] shadow-[0_2px_12px_rgba(16,24,40,.08)] p-6">
        <h1 class="text-[18px] font-bold text-[#111827] mb-1">Selecciona un negocio</h1>
        <p class="text-[13px] text-[#6B7280] mb-6">Tenés acceso a varios negocios. ¿Cuál querés administrar ahora?</p>

        <div class="space-y-2">
            @foreach($restaurants as $rest)
            <form method="POST" action="{{ route('admin.restaurants.switch') }}">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ $rest->id }}">
                <button type="submit"
                        class="w-full flex items-center gap-3 p-3 rounded-[12px] border border-[#E5E7EB] hover:border-[#4F46E5] hover:bg-[#EEF2FF] transition-colors text-left group min-h-[56px]">
                    <div class="w-[38px] h-[38px] rounded-[10px] bg-[#F3F4F6] flex items-center justify-center flex-none group-hover:bg-[#E0E7FF] transition-colors">
                        @if($rest->logo)
                        <img src="{{ Storage::url($rest->logo) }}" alt="" class="w-full h-full object-cover rounded-[10px]">
                        @else
                        <span class="text-[14px] font-bold text-[#6B7280] group-hover:text-[#4F46E5]">
                            {{ mb_strtoupper(mb_substr($rest->name, 0, 2)) }}
                        </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[14px] font-semibold text-[#111827] truncate">{{ $rest->name }}</div>
                        <div class="text-[12px] text-[#6B7280] truncate">
                            {{ ucfirst($rest->pivot->role) }} · {{ $rest->plan }}
                        </div>
                    </div>
                    <svg class="w-[16px] h-[16px] text-[#9CA3AF] group-hover:text-[#4F46E5] flex-none transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </form>
            @endforeach
        </div>
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-[13px] text-[#6B7280] hover:text-[#111827] font-medium transition-colors">
                Cerrar sesión
            </button>
        </form>
    </div>

</div>

</body>
</html>
