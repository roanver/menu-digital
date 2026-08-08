<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()->restaurant->name ?? 'Admin' }} · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-white text-[#111827] antialiased">

<div class="min-h-screen flex">

    {{-- ============================================================
         DESKTOP SIDEBAR
    ============================================================ --}}
    @php
        $_catCount  = auth()->user()->restaurant?->categories()->count() ?? 0;
        $_itemCount = auth()->user()->restaurant?->menuItems()->count() ?? 0;
        $_restName  = auth()->user()->restaurant?->name ?? 'Mi Restaurante';
        $_userName  = auth()->user()->name ?? 'Admin';
        $_userInit  = mb_strtoupper(mb_substr($_userName, 0, 2));
        $_plan      = auth()->user()->restaurant?->plan ?? 'trial';
    @endphp

    <aside class="hidden lg:flex flex-col w-[252px] flex-none bg-[#F9FAFB] border-r border-[#E5E7EB] h-screen sticky top-0 overflow-y-auto">

        {{-- Brand --}}
        <div class="flex items-center gap-[10px] px-[8px] pt-[18px] pb-[18px]">
            <div class="w-[30px] h-[30px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center shadow-[0_1px_2px_rgba(79,70,229,.4)] flex-none">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <div class="flex flex-col leading-tight min-w-0">
                <span class="text-[14px] font-bold tracking-tight truncate">MenuDigital</span>
                <span class="text-[11px] text-[#9CA3AF] font-medium truncate">{{ $_restName }}</span>
            </div>
        </div>

        {{-- Nav section label --}}
        <div class="text-[10.5px] font-bold text-[#9CA3AF] tracking-[.07em] uppercase px-[10px] pb-[7px]">GESTIÓN</div>

        {{-- Navigation --}}
        <nav class="flex flex-col gap-[2px] px-[6px]">

            {{-- Dashboard --}}
            @php $__active = request()->routeIs('admin.dashboard'); @endphp
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Dashboard
            </a>

            @if(auth()->user()->isOwner())
            {{-- Mi Restaurante --}}
            @php $__active = request()->routeIs('admin.restaurant.*'); @endphp
            <a href="{{ route('admin.restaurant.edit') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><path d="M9 22V12h6v10"/>
                </svg>
                Mi Restaurante
            </a>
            @endif

            {{-- Categorías --}}
            @php $__active = request()->routeIs('admin.categories.*'); @endphp
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
                </svg>
                <span class="flex-1">Categorías</span>
                <span class="text-[10.5px] font-semibold text-[#6B7280] bg-white border border-[#E5E7EB] rounded-[6px] px-[5px] py-[1px]">{{ $_catCount }}</span>
            </a>

            {{-- Items --}}
            @php $__active = request()->routeIs('admin.items.*'); @endphp
            <a href="{{ route('admin.items.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                    <circle cx="3.5" cy="6" r=".5" fill="currentColor"/><circle cx="3.5" cy="12" r=".5" fill="currentColor"/><circle cx="3.5" cy="18" r=".5" fill="currentColor"/>
                </svg>
                <span class="flex-1">Items</span>
                <span class="text-[10.5px] font-semibold text-[#6B7280] bg-white border border-[#E5E7EB] rounded-[6px] px-[5px] py-[1px]">{{ $_itemCount }}</span>
            </a>

            {{-- Horarios --}}
            @php $__active = request()->routeIs('admin.hours.*'); @endphp
            <a href="{{ route('admin.hours.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Horarios
            </a>

            @if(auth()->user()->isOwner())
            {{-- Apariencia --}}
            @php $__active = request()->routeIs('admin.appearance.*'); @endphp
            <a href="{{ route('admin.appearance.edit') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/><path d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5-.7.7m-9.6 9.6-.7.7m0-10.3-.7-.7m10.3 10.3-.7-.7"/>
                </svg>
                Apariencia
            </a>

            {{-- QR --}}
            @php $__active = request()->routeIs('admin.qr.*'); @endphp
            <a href="{{ route('admin.qr.show') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
                    <rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
                </svg>
                QR
            </a>

            {{-- Billing --}}
            @php $__active = request()->routeIs('admin.billing.*'); @endphp
            <a href="{{ route('admin.billing.show') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Billing
            </a>

            {{-- Equipo --}}
            @php $__active = request()->routeIs('admin.staff.*'); @endphp
            <a href="{{ route('admin.staff.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Equipo
            </a>

            {{-- Importar menú --}}
            @php $__active = request()->routeIs('admin.import.*'); @endphp
            <a href="{{ route('admin.import.upload') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 16l4-4 4 4M12 12V3"/><path d="M20 16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2"/>
                    <circle cx="12" cy="20" r=".5" fill="currentColor"/>
                </svg>
                Importar menú
            </a>

            {{-- Posts --}}
            @php $__active = request()->routeIs('admin.posts.*'); @endphp
            <a href="{{ route('admin.posts.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-2 rounded-[9px] border text-[13px] transition-colors no-underline
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-semibold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[17px] h-[17px] flex-none {{ $__active ? 'opacity-100' : 'opacity-75' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                Posts
            </a>
            @endif

        </nav>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Plan card --}}
        <div class="mx-[6px] mb-[10px]">
            <div class="bg-[#EEF2FF] border border-[#E0E7FF] rounded-[12px] p-3">
                <div class="text-[12px] font-bold text-[#3730A3] mb-[2px]">Plan {{ ['carta' => 'Carta', 'pedidos' => 'Pedidos', 'full' => 'Full'][$_plan] ?? ucfirst($_plan) }}</div>
                <div class="text-[11.5px] text-[#4F46E5] mb-[10px]">Gestiona tu suscripción</div>
                <a href="{{ route('admin.billing.show') }}"
                   class="block w-full py-[7px] rounded-[8px] bg-[#4F46E5] text-white text-[12px] font-semibold text-center transition-colors hover:bg-[#4338CA]">
                    Ver plan
                </a>
            </div>
        </div>

        {{-- User row --}}
        <div class="mx-[6px] mb-[10px]">
            <div class="flex items-center gap-[9px] p-[7px_8px] rounded-[10px] border border-[#E5E7EB] bg-white">
                <div class="w-[26px] h-[26px] rounded-full bg-[#111827] text-white text-[11px] font-bold flex items-center justify-center flex-none select-none">
                    {{ $_userInit }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12px] font-semibold truncate">{{ $_userName }}</div>
                    <div class="text-[10.5px] text-[#9CA3AF]">Administrador</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-[11.5px] text-[#6B7280] hover:text-[#111827] font-medium transition-colors">
                        Salir
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ============================================================
         RIGHT COLUMN
    ============================================================ --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Mobile header --}}
        <header class="lg:hidden sticky top-0 z-20 bg-white/95 backdrop-blur-sm border-b border-[#E5E7EB]">
            <div class="flex items-center gap-3 px-4 py-[14px]">
                <div class="w-[28px] h-[28px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center shadow-[0_1px_2px_rgba(79,70,229,.4)] flex-none">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round">
                        <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-[17px] font-bold tracking-tight truncate">{{ $pageTitle ?? auth()->user()->restaurant?->name ?? 'Admin' }}</h1>
                </div>
                @if(auth()->user()->restaurant?->slug)
                <a href="{{ url('/' . auth()->user()->restaurant->slug) }}" target="_blank"
                   class="flex-none bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[12px] py-[7px] text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors whitespace-nowrap">
                    Ver menú
                </a>
                @endif
            </div>
        </header>

        {{-- Desktop header --}}
        <header class="hidden lg:flex items-center gap-3 px-[clamp(16px,2.4vw,28px)] py-[14px] border-b border-[#E5E7EB] bg-white/95 backdrop-blur-sm sticky top-0 z-20">
            <div class="flex-1 min-w-0">
                <h1 class="text-[17px] font-bold tracking-[-0.015em] truncate">{{ $pageTitle ?? auth()->user()->restaurant?->name ?? 'Admin' }}</h1>
                @if(isset($pageSubtitle))
                <p class="text-[12.5px] text-[#6B7280] truncate mt-[1px]">{{ $pageSubtitle }}</p>
                @endif
            </div>
            @if(auth()->user()->restaurant?->slug)
            <a href="{{ url('/' . auth()->user()->restaurant->slug) }}" target="_blank"
               class="flex-none flex items-center gap-[6px] bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors whitespace-nowrap">
                Ver menú
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 4h6v6M20 4 11 13M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
                </svg>
            </a>
            @endif
        </header>

        {{-- Flash messages --}}
        <div class="px-[clamp(16px,2.4vw,28px)] pt-4 space-y-2">
            @if(session('success'))
            <div class="bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] px-4 py-3 rounded-[10px] text-[13px]">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-[#FEF2F2] border border-[#FECACA] text-[#DC2626] px-4 py-3 rounded-[10px] text-[13px]">
                {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-[#FEF2F2] border border-[#FECACA] text-[#DC2626] px-4 py-3 rounded-[10px] text-[13px]">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
            @if(session('billing_warning'))
            <div class="bg-[#FFFBEB] border border-[#FDE68A] text-[#B45309] px-4 py-3 rounded-[10px] text-[13px]">
                ⚠️ {{ session('billing_warning') }}
                <a href="{{ route('admin.billing.show') }}" class="font-semibold underline ml-1">Ver plan</a>
            </div>
            @endif
        </div>

        {{-- Main slot --}}
        <main class="flex-1 p-[clamp(16px,2.4vw,28px)] pb-24 lg:pb-6">
            {{ $slot }}
        </main>

    </div>

</div>

{{-- ============================================================
     MOBILE BOTTOM TAB BAR
============================================================ --}}
<div x-data="{ moreOpen: false }"
     @keydown.escape.window="moreOpen = false"
     class="lg:hidden">

    {{-- Bottom nav --}}
    <nav class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur-sm border-t border-[#E5E7EB]">
        <div class="flex">

            {{-- Inicio --}}
            @php $__t = request()->routeIs('admin.dashboard'); @endphp
            <a href="{{ route('admin.dashboard') }}"
               class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] {{ $__t ? 'text-[#4F46E5]' : 'text-[#9CA3AF]' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="text-[10px] leading-none {{ $__t ? 'font-semibold' : 'font-medium' }}">Inicio</span>
            </a>

            {{-- Ítems --}}
            @php $__t = request()->routeIs('admin.items.*'); @endphp
            <a href="{{ route('admin.items.index') }}"
               class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] {{ $__t ? 'text-[#4F46E5]' : 'text-[#9CA3AF]' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                    <circle cx="3.5" cy="6" r=".5" fill="currentColor"/><circle cx="3.5" cy="12" r=".5" fill="currentColor"/><circle cx="3.5" cy="18" r=".5" fill="currentColor"/>
                </svg>
                <span class="text-[10px] leading-none {{ $__t ? 'font-semibold' : 'font-medium' }}">Ítems</span>
            </a>

            {{-- Categorías --}}
            @php $__t = request()->routeIs('admin.categories.*'); @endphp
            <a href="{{ route('admin.categories.index') }}"
               class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] {{ $__t ? 'text-[#4F46E5]' : 'text-[#9CA3AF]' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
                </svg>
                <span class="text-[10px] leading-none {{ $__t ? 'font-semibold' : 'font-medium' }}">Categorías</span>
            </a>

            {{-- Ver carta --}}
            @php $__t = false; @endphp
            @if(auth()->user()->restaurant?->slug)
            <a href="{{ url('/' . auth()->user()->restaurant->slug) }}" target="_blank"
               class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] text-[#9CA3AF]">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                </svg>
                <span class="text-[10px] leading-none font-medium">Ver carta</span>
            </a>
            @endif

            {{-- Más --}}
            @php
                $__inMore = request()->routeIs('admin.import.*') || request()->routeIs('admin.posts.*') || request()->routeIs('admin.qr.*') || request()->routeIs('admin.appearance.*') || request()->routeIs('admin.restaurant.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.billing.*') || request()->routeIs('admin.hours.*');
            @endphp
            <button @click="moreOpen = true"
                    class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] {{ $__inMore ? 'text-[#4F46E5]' : 'text-[#9CA3AF]' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/>
                </svg>
                <span class="text-[10px] leading-none {{ $__inMore ? 'font-semibold' : 'font-medium' }}">Más</span>
            </button>

        </div>
    </nav>

    {{-- Bottom Sheet overlay --}}
    <div x-show="moreOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="moreOpen = false"
         class="fixed inset-0 z-40 bg-black/40"
         style="display:none;">
    </div>

    {{-- Bottom Sheet panel --}}
    <div x-show="moreOpen"
         x-transition:enter="transition-transform duration-250 ease-out"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition-transform duration-200 ease-in"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-[20px] shadow-[0_-8px_32px_rgba(16,24,40,.14)]"
         style="display:none;">

        {{-- Handle + header --}}
        <div class="flex items-center justify-between px-5 pt-4 pb-2">
            <div class="w-[36px] h-[4px] rounded-full bg-[#E5E7EB] mx-auto absolute left-0 right-0 top-[10px]"></div>
            <span class="text-[13px] font-bold text-[#111827] mt-2">Más opciones</span>
            <button @click="moreOpen = false" class="w-[32px] h-[32px] flex items-center justify-center rounded-full bg-[#F3F4F6] text-[#6B7280] mt-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="px-4 pb-8 pt-2 space-y-1" style="padding-bottom: max(2rem, env(safe-area-inset-bottom, 2rem));">

            @if(auth()->user()->isOwner())
            {{-- Importar menú --}}
            @php $__a = request()->routeIs('admin.import.*'); @endphp
            <a href="{{ route('admin.import.upload') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16l4-4 4 4M12 12V3"/><path d="M20 16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Importar menú</span>
            </a>

            {{-- Posts --}}
            @php $__a = request()->routeIs('admin.posts.*'); @endphp
            <a href="{{ route('admin.posts.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Posts</span>
            </a>

            {{-- Códigos QR --}}
            @php $__a = request()->routeIs('admin.qr.*'); @endphp
            <a href="{{ route('admin.qr.show') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Códigos QR</span>
            </a>

            {{-- Apariencia --}}
            @php $__a = request()->routeIs('admin.appearance.*'); @endphp
            <a href="{{ route('admin.appearance.edit') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5-.7.7m-9.6 9.6-.7.7m0-10.3-.7-.7m10.3 10.3-.7-.7"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Apariencia</span>
            </a>

            {{-- Horarios --}}
            @php $__a = request()->routeIs('admin.hours.*'); @endphp
            <a href="{{ route('admin.hours.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Horarios</span>
            </a>

            {{-- Restaurante --}}
            @php $__a = request()->routeIs('admin.restaurant.*'); @endphp
            <a href="{{ route('admin.restaurant.edit') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><path d="M9 22V12h6v10"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Restaurante</span>
            </a>

            {{-- Equipo --}}
            @php $__a = request()->routeIs('admin.staff.*'); @endphp
            <a href="{{ route('admin.staff.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Equipo</span>
            </a>

            {{-- Planes --}}
            @php $__a = request()->routeIs('admin.billing.*'); @endphp
            <a href="{{ route('admin.billing.show') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Planes</span>
            </a>
            @endif

            {{-- Cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-[11px] rounded-[12px] text-[#DC2626] hover:bg-[#FEF2F2] min-h-[44px]">
                    <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    <span class="text-[13px] font-medium">Cerrar sesión</span>
                </button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
