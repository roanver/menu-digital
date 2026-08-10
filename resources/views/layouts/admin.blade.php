<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $_activeRestaurant = session('active_restaurant_id')
            ? \App\Models\Restaurant::find(session('active_restaurant_id'))
            : auth()->user()->restaurant;
    @endphp
    <title>{{ $_activeRestaurant?->name ?? 'Admin' }} · MenuDigital</title>
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
        $_catCount       = $_activeRestaurant?->categories()->count() ?? 0;
        $_itemCount      = $_activeRestaurant?->menuItems()->count() ?? 0;
        $_restName       = $_activeRestaurant?->name ?? 'Mi negocio';
        $_userName       = auth()->user()->name ?? 'Admin';
        $_userInit       = mb_strtoupper(mb_substr($_userName, 0, 2));
        $_plan           = $_activeRestaurant?->plan ?? 'free';
        $_userRestaurants = auth()->user()->restaurants()->orderBy('name')->get();
        $_multiNegocio   = $_userRestaurants->count() > 1;
        $_impersonating  = session()->has('superadmin_entry_restaurant_id');
        $_vertical       = $_activeRestaurant?->vertical() ?? config('verticals.restaurant');
        $_itemsLabel     = $_vertical['items_label'] ?? 'Platos';
        $_negocioLabel   = 'Mi ' . strtolower($_vertical['label'] ?? 'negocio');
        $_cartaLabel     = in_array($_vertical['label'] ?? '', ['Tienda', 'Servicios']) ? 'MI CATÁLOGO' : 'MI CARTA';
        $_planLabel      = match($_plan) { 'free' => 'Gratis', 'basico' => 'Básico', 'pro' => 'Pro', default => ucfirst($_plan) };
        $_planExpiresAt  = $_activeRestaurant?->trial_ends_at ?? $_activeRestaurant?->subscription_ends_at;
        $_planDaysLeft   = $_planExpiresAt ? now()->diffInDays($_planExpiresAt, false) : null;
        $_planExpired    = $_plan !== 'free' && $_activeRestaurant && !$_activeRestaurant->planIsActive();
        $_planAlert      = !$_planExpired && $_planDaysLeft !== null && $_planDaysLeft <= 7 && $_plan !== 'free';
        $_userRole       = match(auth()->user()->role) { 'owner' => 'Dueño', 'staff' => 'Equipo', 'super_admin' => 'Administrador', default => 'Usuario' };
    @endphp

    <aside class="hidden lg:flex flex-col w-[252px] flex-none bg-[#F9FAFB] border-r border-[#E5E7EB] h-screen sticky top-0 overflow-y-auto">

        {{-- Brand + selector de negocio --}}
        <div class="px-[8px] pt-[16px] pb-[12px]">
            <div class="flex items-center gap-[10px]">
                <div class="w-[30px] h-[30px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center shadow-[0_1px_2px_rgba(79,70,229,.4)] flex-none">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round">
                        <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                    </svg>
                </div>
                <span class="text-[14px] font-bold tracking-tight">MenuDigital</span>
            </div>

            {{-- Selector de negocio (dropdown) --}}
            <div class="mt-[10px] relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                        class="flex items-center gap-[8px] w-full px-[10px] py-[8px] rounded-[9px] border border-[#E5E7EB] bg-white text-[12px] font-medium text-[#4B5563] hover:border-[#4F46E5] hover:text-[#4F46E5] transition-colors">
                    <span class="flex-1 truncate text-left">{{ $_restName }}</span>
                    <svg class="w-[12px] h-[12px] flex-none opacity-50 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 right-0 top-full mt-[4px] z-50 bg-white border border-[#E5E7EB] rounded-[12px] shadow-[0_8px_24px_rgba(16,24,40,.12)] py-[6px] overflow-hidden"
                     style="display:none;">

                    {{-- Lista de negocios --}}
                    @foreach($_userRestaurants as $_r)
                    <form method="POST" action="{{ route('admin.restaurants.switch') }}">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $_r->id }}">
                        <button type="submit"
                                class="w-full flex items-center gap-[8px] px-[10px] py-[8px] text-left text-[12px] hover:bg-[#F9FAFB] transition-colors
                                       {{ $_r->id === $_activeRestaurant?->id ? 'text-[#4F46E5] font-semibold' : 'text-[#374151] font-medium' }}">
                            <span class="flex-1 truncate">{{ $_r->name }}</span>
                            @if($_r->id === $_activeRestaurant?->id)
                            <svg class="w-[13px] h-[13px] flex-none text-[#4F46E5]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            @endif
                        </button>
                    </form>
                    @endforeach

                    {{-- Separador + Agregar --}}
                    <div class="border-t border-[#F3F4F6] mt-[4px] pt-[4px]">
                        <a href="{{ route('admin.restaurants.create') }}" @click="open = false"
                           class="flex items-center gap-[8px] px-[10px] py-[8px] text-[12px] font-medium text-[#4F46E5] hover:bg-[#EEF2FF] transition-colors">
                            <svg class="w-[13px] h-[13px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Agregar negocio
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner de impersonación (superadmin) --}}
        @if($_impersonating)
        <div class="mx-[6px] mb-[8px] bg-[#FEF3C7] border border-[#FDE68A] rounded-[10px] px-3 py-2">
            <div class="text-[11px] font-semibold text-[#92400E] mb-[4px]">Entrando como negocio</div>
            <form method="POST" action="{{ route('admin.restaurants.exit-impersonation') }}">
                @csrf
                <button type="submit" class="text-[11px] font-medium text-[#B45309] underline">Salir y volver a superadmin</button>
            </form>
        </div>
        @endif

        {{-- Navigation --}}
        <nav class="flex flex-col px-[6px] pb-3">

            {{-- Dashboard (sin título de grupo) --}}
            @php $__active = request()->routeIs('admin.dashboard'); @endphp
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Dashboard
            </a>

            {{-- ── MI CARTA / MI CATÁLOGO ── --}}
            <div class="text-[9.5px] font-semibold text-[#C4C9D4] tracking-[.08em] uppercase px-[10px] pt-[14px] pb-[5px]">{{ $_cartaLabel }}</div>

            {{-- Items (primero — es la acción más frecuente) --}}
            @php $__active = request()->routeIs('admin.items.*'); @endphp
            <a href="{{ route('admin.items.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                    <circle cx="3.5" cy="6" r=".5" fill="currentColor"/><circle cx="3.5" cy="12" r=".5" fill="currentColor"/><circle cx="3.5" cy="18" r=".5" fill="currentColor"/>
                </svg>
                <span class="flex-1">{{ $_itemsLabel }}</span>
                @if($_itemCount > 0)
                <span class="text-[10px] font-semibold text-[#9CA3AF] tabular-nums">{{ $_itemCount }}</span>
                @endif
            </a>

            {{-- Categorías --}}
            @php $__active = request()->routeIs('admin.categories.*'); @endphp
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
                </svg>
                <span class="flex-1">Categorías</span>
                @if($_catCount > 0)
                <span class="text-[10px] font-semibold text-[#9CA3AF] tabular-nums">{{ $_catCount }}</span>
                @endif
            </a>

            @if(auth()->user()->isOwner())

            {{-- ── CONFIGURACIÓN ── --}}
            <div class="text-[9.5px] font-semibold text-[#C4C9D4] tracking-[.08em] uppercase px-[10px] pt-[14px] pb-[5px]">CONFIGURACIÓN</div>

            {{-- Mi negocio --}}
            @php $__active = request()->routeIs('admin.restaurant.*'); @endphp
            <a href="{{ route('admin.restaurant.edit') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><path d="M9 22V12h6v10"/>
                </svg>
                {{ ucfirst($_negocioLabel) }}
            </a>

            {{-- Horarios --}}
            @php $__active = request()->routeIs('admin.hours.*'); @endphp
            <a href="{{ route('admin.hours.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Horarios
            </a>

            {{-- Apariencia --}}
            @php $__active = request()->routeIs('admin.appearance.*'); @endphp
            <a href="{{ route('admin.appearance.edit') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/><path d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5-.7.7m-9.6 9.6-.7.7m0-10.3-.7-.7m10.3 10.3-.7-.7"/>
                </svg>
                Apariencia
            </a>

            {{-- Mesas --}}
            @php
                $__maxT = config('plans.plans.'.$_plan.'.max_tables', 0);
                $__active = request()->routeIs('admin.tables.*');
            @endphp
            @if(($_activeRestaurant?->type === 'restaurant' || !$_activeRestaurant?->type) && $__maxT !== 0)
            <a href="{{ route('admin.tables.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="7" width="18" height="4" rx="1"/><line x1="5" y1="11" x2="5" y2="17"/><line x1="19" y1="11" x2="19" y2="17"/>
                </svg>
                Mesas
            </a>
            @endif

            {{-- ── DIFUSIÓN ── --}}
            <div class="text-[9.5px] font-semibold text-[#C4C9D4] tracking-[.08em] uppercase px-[10px] pt-[14px] pb-[5px]">DIFUSIÓN</div>

            {{-- QR --}}
            @php $__active = request()->routeIs('admin.qr.*'); @endphp
            <a href="{{ route('admin.qr.show') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/>
                    <rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/>
                </svg>
                QR
            </a>

            {{-- Pantallas --}}
            @php $__active = request()->routeIs('admin.screens.*'); @endphp
            <a href="{{ route('admin.screens.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                <span class="flex-1">Pantallas</span>
                @if(!$_activeRestaurant?->planCan('screens'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px] leading-none">PRO</span>
                @endif
            </a>

            {{-- Posts --}}
            @php $__active = request()->routeIs('admin.posts.*'); @endphp
            <a href="{{ route('admin.posts.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                <span class="flex-1">Posts</span>
                @if(!$_activeRestaurant?->planCan('ai_posts'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px] leading-none">PRO</span>
                @endif
            </a>

            {{-- ── HERRAMIENTAS ── --}}
            <div class="text-[9.5px] font-semibold text-[#C4C9D4] tracking-[.08em] uppercase px-[10px] pt-[14px] pb-[5px]">HERRAMIENTAS</div>

            {{-- Importar menú --}}
            @php $__active = request()->routeIs('admin.import.*'); @endphp
            <a href="{{ route('admin.import.upload') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Importar menú
            </a>

            {{-- Asesor de carta --}}
            @php $__active = request()->routeIs('admin.advisor.*'); @endphp
            <a href="{{ route('admin.advisor.show') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 8v4l2 2"/><circle cx="18" cy="6" r="4" fill="currentColor" stroke="none"/><path d="M18 4v4M16 6h4" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
                <span class="flex-1">Asesor de carta</span>
                @if(!$_activeRestaurant?->planCan('ai_advisor'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px] leading-none">PRO</span>
                @endif
            </a>

            {{-- ── CUENTA ── --}}
            <div class="text-[9.5px] font-semibold text-[#C4C9D4] tracking-[.08em] uppercase px-[10px] pt-[14px] pb-[5px]">CUENTA</div>

            {{-- Equipo --}}
            @php $__active = request()->routeIs('admin.staff.*'); @endphp
            <a href="{{ route('admin.staff.index') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="flex-1">Equipo</span>
                @if(!$_activeRestaurant?->planCan('staff'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px] leading-none">PRO</span>
                @endif
            </a>

            {{-- Mi plan --}}
            @php $__active = request()->routeIs('admin.billing.*'); @endphp
            <a href="{{ route('admin.billing.show') }}"
               class="flex items-center gap-[10px] w-full px-[10px] py-[8px] rounded-[9px] border text-[13px] transition-colors no-underline mb-[2px]
                      {{ $__active ? 'bg-white border-[#E5E7EB] text-[#4F46E5] font-bold' : 'border-transparent text-[#4B5563] font-medium hover:bg-white/60' }}">
                <svg class="w-[16px] h-[16px] flex-none {{ $__active ? 'opacity-100' : 'opacity-60' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Mi plan
            </a>

            @endif {{-- isOwner --}}

        </nav>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Plan chip --}}
        @if($_planExpired)
        <div class="mx-[6px] mb-[8px]">
            <a href="{{ route('admin.billing.show') }}"
               class="flex items-center gap-[7px] px-[10px] py-[8px] rounded-[9px] bg-[#FEF3C7] border border-[#FDE68A] text-[12px] font-semibold text-[#92400E] hover:bg-[#FDE68A] transition-colors no-underline">
                <svg class="w-[13px] h-[13px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Plan vencido · Renovar
            </a>
        </div>
        @elseif($_planAlert)
        <div class="mx-[6px] mb-[8px]">
            <a href="{{ route('admin.billing.show') }}"
               class="flex items-center gap-[7px] px-[10px] py-[8px] rounded-[9px] bg-[#FEF3C7] border border-[#FDE68A] text-[12px] font-semibold text-[#92400E] hover:bg-[#FDE68A] transition-colors no-underline">
                <svg class="w-[13px] h-[13px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Vence en {{ (int) $_planDaysLeft }} {{ (int) $_planDaysLeft === 1 ? 'día' : 'días' }} · Renovar
            </a>
        </div>
        @elseif($_plan !== 'free')
        <div class="mx-[6px] mb-[8px]">
            <div class="flex items-center justify-between px-[10px] py-[7px] rounded-[9px]">
                <span class="text-[11.5px] text-[#9CA3AF] font-medium">Plan {{ $_planLabel }}</span>
                <a href="{{ route('admin.billing.show') }}" class="text-[11px] text-[#6B7280] hover:text-[#4F46E5] font-medium transition-colors no-underline">Ver →</a>
            </div>
        </div>
        @endif

        {{-- User row --}}
        <div class="mx-[6px] mb-[10px]">
            <div class="flex items-center gap-[9px] p-[7px_8px] rounded-[10px] border border-[#E5E7EB] bg-white">
                <div class="w-[26px] h-[26px] rounded-full bg-[#111827] text-white text-[11px] font-bold flex items-center justify-center flex-none select-none">
                    {{ $_userInit }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12px] font-semibold truncate">{{ $_userName }}</div>
                    <div class="text-[10.5px] text-[#9CA3AF]">{{ $_userRole }}</div>
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
                    <h1 class="text-[17px] font-bold tracking-tight truncate">{{ $pageTitle ?? $_activeRestaurant?->name ?? 'Admin' }}</h1>
                </div>
                @if($_activeRestaurant?->slug)
                <a href="{{ url('/' . $_activeRestaurant->slug) }}" target="_blank"
                   class="flex-none bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-[12px] py-[7px] text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors whitespace-nowrap">
                    Ver menú
                </a>
                @endif
            </div>
        </header>

        {{-- Desktop header --}}
        <header class="hidden lg:flex items-center gap-3 px-[clamp(16px,2.4vw,28px)] py-[14px] border-b border-[#E5E7EB] bg-white/95 backdrop-blur-sm sticky top-0 z-20">
            <div class="flex-1 min-w-0">
                <h1 class="text-[17px] font-bold tracking-[-0.015em] truncate">{{ $pageTitle ?? $_activeRestaurant?->name ?? 'Admin' }}</h1>
                @if(isset($pageSubtitle))
                <p class="text-[12.5px] text-[#6B7280] truncate mt-[1px]">{{ $pageSubtitle }}</p>
                @endif
            </div>
            @if($_activeRestaurant?->slug)
            <a href="{{ url('/' . $_activeRestaurant->slug) }}" target="_blank"
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
            @if($_activeRestaurant?->slug)
            <a href="{{ url('/' . $_activeRestaurant->slug) }}" target="_blank"
               class="flex-1 flex flex-col items-center justify-center gap-[3px] min-h-[56px] text-[#9CA3AF]">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                </svg>
                <span class="text-[10px] leading-none font-medium">Ver carta</span>
            </a>
            @endif

            {{-- Más --}}
            @php
                $__inMore = request()->routeIs('admin.import.*') || request()->routeIs('admin.posts.*') || request()->routeIs('admin.qr.*') || request()->routeIs('admin.appearance.*') || request()->routeIs('admin.restaurant.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.billing.*') || request()->routeIs('admin.hours.*') || request()->routeIs('admin.advisor.*') || request()->routeIs('admin.screens.*') || request()->routeIs('admin.tables.*');
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

        <div class="px-4 pt-2 overflow-y-auto" style="padding-bottom: max(2rem, env(safe-area-inset-bottom, 2rem)); max-height: 80vh;">

            @if(auth()->user()->isOwner())

            {{-- CONFIGURACIÓN --}}
            <div class="text-[9.5px] font-semibold text-[#9CA3AF] tracking-[.08em] uppercase px-2 pt-3 pb-2">CONFIGURACIÓN</div>

            {{-- Mi negocio --}}
            @php $__a = request()->routeIs('admin.restaurant.*'); @endphp
            <a href="{{ route('admin.restaurant.edit') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><path d="M9 22V12h6v10"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">{{ ucfirst($_negocioLabel) }}</span>
            </a>

            {{-- Horarios --}}
            @php $__a = request()->routeIs('admin.hours.*'); @endphp
            <a href="{{ route('admin.hours.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Horarios</span>
            </a>

            {{-- Apariencia --}}
            @php $__a = request()->routeIs('admin.appearance.*'); @endphp
            <a href="{{ route('admin.appearance.edit') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v1m0 16v1m8-9h1M3 12H2m14.5-6.5-.7.7m-9.6 9.6-.7.7m0-10.3-.7-.7m10.3 10.3-.7-.7"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Apariencia</span>
            </a>

            {{-- Mesas --}}
            @php $__maxTm = config('plans.plans.'.$_plan.'.max_tables', 0); @endphp
            @if(($_activeRestaurant?->type === 'restaurant' || !$_activeRestaurant?->type) && $__maxTm !== 0)
            @php $__a = request()->routeIs('admin.tables.*'); @endphp
            <a href="{{ route('admin.tables.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="4" rx="1"/><line x1="5" y1="11" x2="5" y2="17"/><line x1="19" y1="11" x2="19" y2="17"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Mesas</span>
            </a>
            @endif

            {{-- DIFUSIÓN --}}
            <div class="text-[9.5px] font-semibold text-[#9CA3AF] tracking-[.08em] uppercase px-2 pt-4 pb-2">DIFUSIÓN</div>

            {{-- QR --}}
            @php $__a = request()->routeIs('admin.qr.*'); @endphp
            <a href="{{ route('admin.qr.show') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><rect x="15" y="15" width="2" height="2"/><rect x="19" y="15" width="2" height="2"/><rect x="15" y="19" width="2" height="2"/><rect x="19" y="19" width="2" height="2"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">QR</span>
            </a>

            {{-- Pantallas --}}
            @php $__a = request()->routeIs('admin.screens.*'); @endphp
            <a href="{{ route('admin.screens.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <span class="flex-1 text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Pantallas</span>
                @if(!$_activeRestaurant?->planCan('screens'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px]">PRO</span>
                @endif
            </a>

            {{-- Posts --}}
            @php $__a = request()->routeIs('admin.posts.*'); @endphp
            <a href="{{ route('admin.posts.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span class="flex-1 text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Posts</span>
                @if(!$_activeRestaurant?->planCan('ai_posts'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px]">PRO</span>
                @endif
            </a>

            {{-- HERRAMIENTAS --}}
            <div class="text-[9.5px] font-semibold text-[#9CA3AF] tracking-[.08em] uppercase px-2 pt-4 pb-2">HERRAMIENTAS</div>

            {{-- Importar menú --}}
            @php $__a = request()->routeIs('admin.import.*'); @endphp
            <a href="{{ route('admin.import.upload') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Importar menú</span>
            </a>

            {{-- Asesor de carta --}}
            @php $__a = request()->routeIs('admin.advisor.*'); @endphp
            <a href="{{ route('admin.advisor.show') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span class="flex-1 text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Asesor de carta</span>
                @if(!$_activeRestaurant?->planCan('ai_advisor'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px]">PRO</span>
                @endif
            </a>

            {{-- CUENTA --}}
            <div class="text-[9.5px] font-semibold text-[#9CA3AF] tracking-[.08em] uppercase px-2 pt-4 pb-2">CUENTA</div>

            {{-- Equipo --}}
            @php $__a = request()->routeIs('admin.staff.*'); @endphp
            <a href="{{ route('admin.staff.index') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="flex-1 text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Equipo</span>
                @if(!$_activeRestaurant?->planCan('staff'))
                <span class="text-[9px] font-bold text-[#7C3AED] bg-[#F3E8FF] rounded-[5px] px-[5px] py-[1px]">PRO</span>
                @endif
            </a>

            {{-- Mi plan --}}
            @php $__a = request()->routeIs('admin.billing.*'); @endphp
            <a href="{{ route('admin.billing.show') }}" @click="moreOpen = false"
               class="flex items-center gap-3 px-3 py-[11px] rounded-[12px] {{ $__a ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-[#374151] hover:bg-[#F9FAFB]' }} min-h-[44px]">
                <svg class="w-[18px] h-[18px] flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span class="text-[13px] {{ $__a ? 'font-semibold' : 'font-medium' }}">Mi plan</span>
            </a>

            @endif {{-- isOwner --}}

            {{-- Cerrar sesión --}}
            <div class="border-t border-[#F3F4F6] mt-3 pt-2">
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

</div>

</body>
</html>
