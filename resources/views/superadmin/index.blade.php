<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

@php
function relativeDate(\Carbon\Carbon $date): string {
    $diff = now()->diffInDays($date, false);
    if ($diff < -1)  return 'venció hace ' . abs((int)$diff) . ' días';
    if ($diff === -1) return 'venció ayer';
    if ($diff === 0)  return 'vence hoy';
    if ($diff === 1)  return 'mañana';
    return 'en ' . (int)$diff . ' días';
}
function relativeLogin(?\Carbon\Carbon $date): string {
    if (!$date) return 'Nunca';
    $diff = now()->diffInDays($date, false);
    if ($diff >= 0)  return 'Hoy';
    $days = abs((int)$diff);
    if ($days === 1) return 'Ayer';
    if ($days < 7)  return "Hace {$days} días";
    if ($days < 30) return 'Hace ' . floor($days/7) . ' sem.';
    return 'Hace ' . floor($days/30) . ' meses';
}
$planLabels = ['free' => 'Gratis', 'basico' => 'Básico', 'pro' => 'Pro'];
$planColors = [
    'free'   => 'bg-[#F3F4F6] text-[#6B7280]',
    'basico' => 'bg-[#EEF2FF] text-[#4F46E5]',
    'pro'    => 'bg-[#F5F3FF] text-[#7C3AED]',
];
@endphp

{{-- Header --}}
<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-20 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 h-[56px] flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-[30px] h-[30px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center flex-none">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span class="text-[15px] font-bold hidden sm:block">MenuDigital</span>
            <span class="text-[#D1D5DB] font-light hidden sm:block">/</span>
            <span class="text-[13px] font-semibold text-[#4F46E5] bg-[#EEF2FF] rounded-[7px] px-[8px] py-[3px]">Super Admin</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.create') }}"
               class="inline-flex items-center gap-1.5 bg-[#4F46E5] hover:bg-[#4338CA] text-white text-[12.5px] font-semibold px-3 py-1.5 rounded-[8px] no-underline transition-colors">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span class="hidden sm:inline">Nuevo negocio</span>
            </a>
            <a href="{{ route('superadmin.nfc.index') }}"
               class="inline-flex items-center gap-1.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] text-[12.5px] font-semibold px-3 py-1.5 rounded-[8px] no-underline transition-colors">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58M12.91 4.1a15.91 15.91 0 0 1 .01 15.8"/></svg>
                <span class="hidden sm:inline">Tags NFC</span>
            </a>
            <form method="POST" action="/logout">@csrf
                <button type="submit" class="text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] transition-colors">Salir</button>
            </form>
        </div>
    </div>
</header>

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[12px] p-4 flex items-center gap-3 mb-5">
        <svg class="flex-none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 flex items-center gap-3 mb-5">
        <svg class="flex-none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-[13px] font-semibold text-[#DC2626]">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4">
            <p class="text-[10.5px] font-bold uppercase tracking-[.08em] text-[#9CA3AF] mb-2">Negocios</p>
            <p class="text-[32px] font-bold leading-none text-[#111827]">{{ $stats['total'] }}</p>
            <p class="text-[11.5px] text-[#9CA3AF] mt-1">en total</p>
        </div>
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4">
            <p class="text-[10.5px] font-bold uppercase tracking-[.08em] text-[#059669] mb-2">MRR</p>
            <p class="text-[32px] font-bold leading-none text-[#059669]">${{ number_format($stats['mrr'] / 1000, 0) }}K</p>
            <p class="text-[11.5px] text-[#9CA3AF] mt-1">recurrente mensual</p>
        </div>
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-4">
            <p class="text-[10.5px] font-bold uppercase tracking-[.08em] text-[#4F46E5] mb-2">Facturado</p>
            <p class="text-[32px] font-bold leading-none text-[#4F46E5]">${{ number_format($stats['billed'] / 1000, 0) }}K</p>
            <p class="text-[11.5px] text-[#9CA3AF] mt-1">este mes</p>
        </div>
        <div class="bg-white border border-{{ $stats['pending'] > 0 ? '[#FEE2E2]' : '[#E5E7EB]' }} rounded-[16px] p-4">
            <p class="text-[10.5px] font-bold uppercase tracking-[.08em] text-{{ $stats['pending'] > 0 ? '[#DC2626]' : '[#9CA3AF]' }} mb-2">Pendiente</p>
            <p class="text-[32px] font-bold leading-none text-{{ $stats['pending'] > 0 ? '[#DC2626]' : '[#111827]' }}">{{ $stats['pending'] }}</p>
            <p class="text-[11.5px] text-[#9CA3AF] mt-1">vencidos o por vencer</p>
        </div>
    </div>

    {{-- Lista de cobranza --}}
    @if($expired->isNotEmpty() || $expiring7->isNotEmpty() || $expiring30->isNotEmpty())
    <div class="mb-6 space-y-3">

        @if($expired->isNotEmpty())
        <div class="bg-white border border-[#FEE2E2] rounded-[16px] overflow-hidden">
            <div class="px-4 py-3 bg-[#FEF2F2] border-b border-[#FEE2E2] flex items-center justify-between">
                <span class="text-[13px] font-bold text-[#DC2626]">Vencidos</span>
                <span class="text-[11px] font-bold text-white bg-[#DC2626] rounded-full px-[9px] py-[2px]">{{ $expired->count() }}</span>
            </div>
            <div class="divide-y divide-[#FEF2F2]">
                @foreach($expired as $r)
                @php
                    $rOwner = $r->users->first() ?? $r->members->first();
                    $expDate = $r->subscription_ends_at ?? $r->trial_ends_at;
                    $daysAgo = $expDate ? now()->diffInDays($expDate) : null;
                @endphp
                <div class="px-4 py-3 flex items-center gap-3 flex-wrap sm:flex-nowrap">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('superadmin.show', $r) }}" class="text-[13px] font-semibold text-[#111827] hover:text-[#4F46E5] no-underline">{{ $r->name }}</a>
                        <div class="text-[11.5px] text-[#DC2626] font-semibold mt-[1px]">
                            Vencido hace {{ $daysAgo }} {{ $daysAgo === 1 ? 'día' : 'días' }}
                            <span class="text-[#9CA3AF] font-normal ml-1">({{ $expDate?->format('d/m/Y') }})</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-none">
                        @if($r->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $r->whatsapp) }}" target="_blank"
                           class="inline-flex items-center gap-1 bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold no-underline hover:bg-[#D1FAE5] transition-colors">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                            WA
                        </a>
                        @endif
                        <button onclick="openPayModal({{ $r->id }}, '{{ addslashes($r->name) }}')"
                            class="inline-flex items-center gap-1 bg-[#4F46E5] text-white rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold hover:bg-[#4338CA] transition-colors">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Pago
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($expiring7->isNotEmpty())
        <div class="bg-white border border-[#FDE68A] rounded-[16px] overflow-hidden">
            <div class="px-4 py-3 bg-[#FFFBEB] border-b border-[#FDE68A] flex items-center justify-between">
                <span class="text-[13px] font-bold text-[#B45309]">Vencen en 7 días</span>
                <span class="text-[11px] font-bold text-white bg-[#B45309] rounded-full px-[9px] py-[2px]">{{ $expiring7->count() }}</span>
            </div>
            <div class="divide-y divide-[#FFFBEB]">
                @foreach($expiring7 as $r)
                @php $rOwner = $r->users->first() ?? $r->members->first(); @endphp
                <div class="px-4 py-3 flex items-center gap-3 flex-wrap sm:flex-nowrap">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('superadmin.show', $r) }}" class="text-[13px] font-semibold text-[#111827] hover:text-[#4F46E5] no-underline">{{ $r->name }}</a>
                        <div class="text-[11.5px] text-[#B45309] font-semibold mt-[1px]" title="{{ $r->subscription_ends_at?->format('d/m/Y') }}">
                            {{ relativeDate($r->subscription_ends_at) }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-none">
                        @if($r->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $r->whatsapp) }}" target="_blank"
                           class="inline-flex items-center gap-1 bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold no-underline hover:bg-[#D1FAE5] transition-colors">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                            WA
                        </a>
                        @endif
                        <button onclick="openPayModal({{ $r->id }}, '{{ addslashes($r->name) }}')"
                            class="inline-flex items-center gap-1 bg-[#4F46E5] text-white rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold hover:bg-[#4338CA] transition-colors">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Pago
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($expiring30->isNotEmpty())
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] overflow-hidden">
            <div class="px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between">
                <span class="text-[13px] font-bold text-[#6B7280]">Vencen en 30 días</span>
                <span class="text-[11px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-full px-[9px] py-[2px]">{{ $expiring30->count() }}</span>
            </div>
            <div class="divide-y divide-[#F9FAFB]">
                @foreach($expiring30 as $r)
                @php $rOwner = $r->users->first() ?? $r->members->first(); @endphp
                <div class="px-4 py-3 flex items-center gap-3 flex-wrap sm:flex-nowrap">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('superadmin.show', $r) }}" class="text-[13px] font-semibold text-[#111827] hover:text-[#4F46E5] no-underline">{{ $r->name }}</a>
                        <div class="text-[11.5px] text-[#6B7280] mt-[1px]" title="{{ $r->subscription_ends_at?->format('d/m/Y') }}">
                            {{ relativeDate($r->subscription_ends_at) }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-none">
                        @if($r->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $r->whatsapp) }}" target="_blank"
                           class="inline-flex items-center gap-1 bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold no-underline hover:bg-[#D1FAE5] transition-colors">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                            WA
                        </a>
                        @endif
                        <button onclick="openPayModal({{ $r->id }}, '{{ addslashes($r->name) }}')"
                            class="inline-flex items-center gap-1 bg-white text-[#374151] border border-[#E5E7EB] rounded-[8px] px-2.5 py-1.5 text-[12px] font-semibold hover:bg-[#F9FAFB] transition-colors">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Pago
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Filtros --}}
    <form method="GET" action="{{ route('superadmin.index') }}" class="mb-4">
        <div class="flex flex-wrap gap-2 items-center">
            <input type="text" name="q" value="{{ $search }}" placeholder="Buscar nombre, slug, email, teléfono…"
                class="flex-1 min-w-[200px] px-3 py-[8px] bg-white border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] placeholder-[#9CA3AF] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.1)]">
            <select name="status" class="px-3 py-[8px] bg-white border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#374151] focus:outline-none focus:border-[#4F46E5]">
                <option value="">Todos los estados</option>
                <option value="active" @selected($status==='active')>Activo</option>
                <option value="trial" @selected($status==='trial')>En prueba</option>
                <option value="expired" @selected($status==='expired')>Vencido</option>
                <option value="free" @selected($status==='free')>Gratis</option>
            </select>
            <select name="plan" class="px-3 py-[8px] bg-white border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#374151] focus:outline-none focus:border-[#4F46E5]">
                <option value="">Todos los planes</option>
                <option value="free" @selected($plan==='free')>Gratis</option>
                <option value="basico" @selected($plan==='basico')>Básico</option>
                <option value="pro" @selected($plan==='pro')>Pro</option>
            </select>
            <select name="type" class="px-3 py-[8px] bg-white border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#374151] focus:outline-none focus:border-[#4F46E5]">
                <option value="">Todos los tipos</option>
                <option value="restaurant" @selected($type==='restaurant')>Restaurante</option>
                <option value="store" @selected($type==='store')>Tienda</option>
                <option value="services" @selected($type==='services')>Servicios</option>
            </select>
            <select name="sort" class="px-3 py-[8px] bg-white border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#374151] focus:outline-none focus:border-[#4F46E5]">
                <option value="recent" @selected($sort==='recent')>Más reciente</option>
                <option value="expiry" @selected($sort==='expiry')>Por vencimiento</option>
                <option value="login" @selected($sort==='login')>Último acceso</option>
                <option value="scans" @selected($sort==='scans')>Escaneos mes</option>
            </select>
            <button type="submit" class="px-4 py-[8px] bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] text-[13px] font-semibold transition-colors">
                Filtrar
            </button>
            @if($search || $status || $plan || $type || $sort !== 'recent')
            <a href="{{ route('superadmin.index') }}" class="px-3 py-[8px] bg-white border border-[#E5E7EB] text-[#6B7280] rounded-[10px] text-[13px] font-medium hover:bg-[#F9FAFB] no-underline transition-colors">
                Limpiar
            </a>
            @endif
        </div>
    </form>

    {{-- Tabla desktop --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-[13px] font-bold text-[#111827]">Negocios</h2>
            <span class="text-[11.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-full px-[9px] py-[2px]">{{ $restaurants->total() }}</span>
        </div>

        {{-- Desktop table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#F3F4F6]">
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Negocio</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Dueño</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Plan</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Vence</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Último acceso</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Scans</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">NFC</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                    @php
                        $owner    = $restaurant->users->first() ?? $restaurant->members->first();
                        $expDate  = $restaurant->subscription_ends_at ?? $restaurant->trial_ends_at;
                        $isTrial  = $restaurant->trial_ends_at && (!$restaurant->subscription_ends_at || $restaurant->subscription_ends_at < $restaurant->trial_ends_at);
                        $daysLeft = $expDate ? now()->diffInDays($expDate, false) : null;
                        $isExpired = $restaurant->plan !== 'free' && $expDate && $daysLeft < 0;
                        $isAlert   = $restaurant->plan !== 'free' && $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
                        $lastLogin = $owner?->last_login_at;
                        $loginDaysAgo = $lastLogin ? now()->diffInDays($lastLogin) : null;
                        $loginOld = $loginDaysAgo !== null && $loginDaysAgo > 21;
                    @endphp
                    <tr class="border-b border-[#F9FAFB] hover:bg-[#FAFAFA] transition-colors {{ $restaurant->deleted_at ? 'opacity-40' : '' }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('superadmin.show', $restaurant) }}" class="font-semibold text-[#111827] hover:text-[#4F46E5] no-underline block">{{ $restaurant->name }}</a>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[10.5px] text-[#9CA3AF] font-mono">/{{ $restaurant->slug }}</span>
                                @if($restaurant->type && $restaurant->type !== 'restaurant')
                                <span class="text-[9.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[4px] px-[5px] py-[1px]">{{ ucfirst($restaurant->type) }}</span>
                                @endif
                                @if($restaurant->deleted_at)
                                <span class="text-[9.5px] text-[#DC2626] font-semibold">eliminado</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($owner)
                            <div class="text-[12px] font-medium text-[#374151]">{{ $owner->name }}</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] text-[#9CA3AF]">{{ $owner->email }}</span>
                                @if($restaurant->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $restaurant->whatsapp) }}" target="_blank"
                                   class="text-[#059669] hover:text-[#047857] no-underline" title="{{ $restaurant->whatsapp }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-[#9CA3AF]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold {{ $planColors[$restaurant->plan] ?? 'bg-[#F3F4F6] text-[#6B7280]' }}">
                                {{ $planLabels[$restaurant->plan] ?? $restaurant->plan }}
                            </span>
                            @if($isTrial)
                            <div class="text-[10px] text-[#B45309] font-semibold mt-0.5">prueba</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($restaurant->plan === 'free')
                            <span class="text-[12px] text-[#9CA3AF]">—</span>
                            @elseif($expDate)
                            <span class="text-[12px] font-semibold {{ $isExpired ? 'text-[#DC2626]' : ($isAlert ? 'text-[#B45309]' : 'text-[#374151]') }}"
                                  title="{{ $expDate->format('d/m/Y') }}">
                                {{ relativeDate($expDate) }}
                            </span>
                            @else
                            <span class="text-[12px] text-[#9CA3AF]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-[12px] {{ $loginOld ? 'text-[#DC2626] font-semibold' : 'text-[#6B7280]' }}"
                                  title="{{ $lastLogin?->format('d/m/Y H:i') ?? '' }}">
                                {{ relativeLogin($lastLogin) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[12px] text-[#6B7280]">{{ number_format($restaurant->monthly_scans) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('superadmin.nfc.index') }}?restaurant={{ $restaurant->id }}"
                               class="text-[12px] text-[#6B7280] hover:text-[#4F46E5] no-underline font-medium">
                                {{ $restaurant->nfc_tags_count }} tags
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                @if($restaurant->slug)
                                <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
                                   class="p-1.5 text-[#9CA3AF] hover:text-[#4F46E5] rounded-[6px] hover:bg-[#EEF2FF] transition-colors" title="Ver carta">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                                </a>
                                @endif
                                <button onclick="openPayModal({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')"
                                    class="p-1.5 text-[#9CA3AF] hover:text-[#059669] rounded-[6px] hover:bg-[#ECFDF5] transition-colors" title="Registrar pago">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                </button>
                                <button onclick="openPlanModal({{ $restaurant->id }}, '{{ $restaurant->plan }}', '{{ $restaurant->trial_ends_at?->format('Y-m-d') ?? '' }}', '{{ $restaurant->subscription_ends_at?->format('Y-m-d') ?? '' }}', {{ $restaurant->is_active ? 'true' : 'false' }})"
                                    class="p-1.5 text-[#9CA3AF] hover:text-[#374151] rounded-[6px] hover:bg-[#F3F4F6] transition-colors" title="Editar plan">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('superadmin.enter', $restaurant) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-[#9CA3AF] hover:text-[#4F46E5] rounded-[6px] hover:bg-[#EEF2FF] transition-colors" title="Entrar como negocio">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-[13px] text-[#9CA3AF]">No hay negocios que coincidan con los filtros.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-[#F3F4F6]">
            @forelse($restaurants as $restaurant)
            @php
                $owner    = $restaurant->users->first() ?? $restaurant->members->first();
                $expDate  = $restaurant->subscription_ends_at ?? $restaurant->trial_ends_at;
                $daysLeft = $expDate ? now()->diffInDays($expDate, false) : null;
                $isExpired = $restaurant->plan !== 'free' && $expDate && $daysLeft < 0;
                $isAlert   = $restaurant->plan !== 'free' && $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
            @endphp
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="min-w-0">
                        <a href="{{ route('superadmin.show', $restaurant) }}" class="text-[14px] font-bold text-[#111827] hover:text-[#4F46E5] no-underline block truncate">{{ $restaurant->name }}</a>
                        <span class="text-[11px] text-[#9CA3AF] font-mono">/{{ $restaurant->slug }}</span>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold flex-none {{ $planColors[$restaurant->plan] ?? 'bg-[#F3F4F6] text-[#6B7280]' }}">
                        {{ $planLabels[$restaurant->plan] ?? $restaurant->plan }}
                    </span>
                </div>
                <div class="flex items-center gap-3 text-[11.5px] text-[#6B7280] mb-3">
                    @if($restaurant->plan !== 'free' && $expDate)
                    <span class="{{ $isExpired ? 'text-[#DC2626] font-semibold' : ($isAlert ? 'text-[#B45309] font-semibold' : '') }}" title="{{ $expDate->format('d/m/Y') }}">
                        {{ relativeDate($expDate) }}
                    </span>
                    @endif
                    @if($owner?->email)
                    <span class="truncate">{{ $owner->email }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if($restaurant->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $restaurant->whatsapp) }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-1.5 bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] rounded-[9px] py-2 text-[12.5px] font-semibold no-underline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    @endif
                    <form method="POST" action="{{ route('superadmin.enter', $restaurant) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 bg-[#EEF2FF] text-[#4F46E5] rounded-[9px] py-2 text-[12.5px] font-semibold">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                            Entrar
                        </button>
                    </form>
                    <button onclick="openPayModal({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')"
                        class="flex-1 flex items-center justify-center gap-1.5 bg-[#4F46E5] text-white rounded-[9px] py-2 text-[12.5px] font-semibold">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Pago
                    </button>
                </div>
            </div>
            @empty
            <div class="px-4 py-10 text-center text-[13px] text-[#9CA3AF]">No hay resultados.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($restaurants->hasPages())
        <div class="px-4 py-4 border-t border-[#F3F4F6]">
            {{ $restaurants->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal: Registrar pago --}}
<div id="pay-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(17,24,39,.5);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-[18px] shadow-[0_20px_60px_rgba(16,24,40,.2)] w-full max-w-[420px] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#F3F4F6] flex items-center justify-between">
            <div>
                <h3 class="text-[14px] font-bold text-[#111827]">Registrar pago</h3>
                <p id="pay-modal-name" class="text-[12px] text-[#6B7280] mt-0.5"></p>
            </div>
            <button onclick="closePayModal()" class="w-[28px] h-[28px] flex items-center justify-center rounded-[8px] text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="pay-modal-form" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Monto (CLP)</label>
                    <input type="number" name="amount" min="1" placeholder="9990" required
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                </div>
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Fecha de pago</label>
                    <input type="date" name="paid_at" required
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Método</label>
                    <select name="method"
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                        <option value="transferencia">Transferencia</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Meses</label>
                    <select name="months"
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ $m }} {{ $m === 1 ? 'mes' : 'meses' }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Notas (opcional)</label>
                <input type="text" name="notes" maxlength="200" placeholder="Transferencia ref #…"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
            </div>
            <div class="flex gap-2 pt-1">
                <button type="submit"
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[10px] px-4 py-[10px] text-[13px] font-semibold transition-colors">
                    Guardar pago
                </button>
                <button type="button" onclick="closePayModal()"
                    class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar plan --}}
<div id="plan-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(17,24,39,.5);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-[18px] shadow-[0_20px_60px_rgba(16,24,40,.2)] w-full max-w-[420px] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#F3F4F6] flex items-center justify-between">
            <h3 class="text-[14px] font-bold text-[#111827]">Editar plan</h3>
            <button onclick="closePlanModal()" class="w-[28px] h-[28px] flex items-center justify-center rounded-[8px] text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="plan-modal-form" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Plan</label>
                <select id="plan-modal-plan" name="plan"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                    <option value="free">Gratis</option>
                    <option value="basico">Básico ($9.990/mes)</option>
                    <option value="pro">Pro ($19.990/mes)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Fin de prueba</label>
                    <input id="plan-modal-trial" type="date" name="trial_ends_at"
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                </div>
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Suscripción vence</label>
                    <input id="plan-modal-sub" type="date" name="subscription_ends_at"
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB]">
                <div>
                    <label class="text-[13px] font-semibold text-[#111827]">Activo</label>
                    <p class="text-[11px] text-[#9CA3AF] mt-0.5">Puede acceder al panel</p>
                </div>
                <input id="plan-modal-active" type="hidden" name="is_active" value="0">
                <button type="button" id="plan-toggle-active" onclick="togglePlanActive()"
                    class="relative inline-flex h-[24px] w-[42px] items-center rounded-full transition-colors focus:outline-none">
                    <span id="plan-toggle-knob" class="inline-block h-[20px] w-[20px] transform rounded-full bg-white shadow-md transition-transform"></span>
                </button>
            </div>
            <div class="flex gap-2 pt-1">
                <button type="submit"
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold transition-colors">
                    Guardar
                </button>
                <button type="button" onclick="closePlanModal()"
                    class="bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Pay modal
function openPayModal(id, name) {
    document.getElementById('pay-modal-name').textContent = name;
    document.getElementById('pay-modal-form').action = '/superadmin/restaurants/' + id + '/payments';
    document.querySelector('#pay-modal input[name="paid_at"]').value = new Date().toISOString().slice(0,10);
    document.getElementById('pay-modal').style.display = 'flex';
}
function closePayModal() { document.getElementById('pay-modal').style.display = 'none'; }
document.getElementById('pay-modal').addEventListener('click', e => { if (e.target === e.currentTarget) closePayModal(); });

// Plan modal
let planActive = false;
function openPlanModal(id, plan, trial, sub, active) {
    document.getElementById('plan-modal-form').action = '/superadmin/restaurants/' + id + '/plan';
    document.getElementById('plan-modal-plan').value = plan;
    document.getElementById('plan-modal-trial').value = trial;
    document.getElementById('plan-modal-sub').value = sub;
    planActive = active;
    updatePlanToggle();
    document.getElementById('plan-modal').style.display = 'flex';
}
function closePlanModal() { document.getElementById('plan-modal').style.display = 'none'; }
function togglePlanActive() { planActive = !planActive; updatePlanToggle(); }
function updatePlanToggle() {
    document.getElementById('plan-modal-active').value = planActive ? '1' : '0';
    document.getElementById('plan-toggle-active').style.backgroundColor = planActive ? '#4F46E5' : '#D1D5DB';
    document.getElementById('plan-toggle-knob').style.transform = planActive ? 'translateX(20px)' : 'translateX(2px)';
}
document.getElementById('plan-modal').addEventListener('click', e => { if (e.target === e.currentTarget) closePlanModal(); });
</script>

</body>
</html>
