<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }} · Super Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

@php
$expDate  = $restaurant->subscription_ends_at ?? $restaurant->trial_ends_at;
$daysLeft = $expDate ? now()->diffInDays($expDate, false) : null;
$isExpired = $restaurant->plan !== 'free' && $expDate && $daysLeft < 0;
$isAlert   = $restaurant->plan !== 'free' && $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
$planLabels = ['free' => 'Gratis', 'basico' => 'Básico', 'pro' => 'Pro'];
$planColors = ['free' => 'bg-[#F3F4F6] text-[#6B7280]', 'basico' => 'bg-[#EEF2FF] text-[#4F46E5]', 'pro' => 'bg-[#F5F3FF] text-[#7C3AED]'];
$methodLabels = ['transferencia' => 'Transferencia', 'efectivo' => 'Efectivo', 'otro' => 'Otro'];
@endphp

{{-- Header --}}
<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-20">
    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 h-[56px] flex items-center gap-3">
        <a href="{{ route('superadmin.index') }}" class="text-[#9CA3AF] hover:text-[#374151] transition-colors no-underline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <div class="w-[30px] h-[30px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center flex-none">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
        </div>
        <span class="text-[13px] font-semibold text-[#4F46E5] bg-[#EEF2FF] rounded-[7px] px-[8px] py-[3px]">Super Admin</span>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[14px] font-bold truncate">{{ $restaurant->name }}</span>
        <div class="flex-1"></div>
        <form method="POST" action="/logout">@csrf
            <button type="submit" class="text-[12.5px] font-semibold text-[#6B7280] hover:text-[#111827] transition-colors">Salir</button>
        </form>
    </div>
</header>

<div class="max-w-[1100px] mx-auto px-4 sm:px-6 py-6 space-y-5">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[12px] p-4 flex items-center gap-3">
        <svg class="flex-none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 flex items-center gap-3">
        <svg class="flex-none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-[13px] font-semibold text-[#DC2626]">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Info header card --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5">
        <div class="flex flex-wrap items-start gap-4 justify-between">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-[20px] font-bold">{{ $restaurant->name }}</h1>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $planColors[$restaurant->plan] ?? 'bg-[#F3F4F6] text-[#6B7280]' }}">
                        {{ $planLabels[$restaurant->plan] ?? $restaurant->plan }}
                    </span>
                    @if($restaurant->type && $restaurant->type !== 'restaurant')
                    <span class="text-[10.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-[5px] px-[6px] py-[2px]">{{ ucfirst($restaurant->type) }}</span>
                    @endif
                    @if(!$restaurant->is_active)
                    <span class="text-[10.5px] font-bold text-[#DC2626] bg-[#FEF2F2] rounded-[5px] px-[6px] py-[2px]">Inactivo</span>
                    @endif
                </div>
                <div class="text-[12.5px] text-[#9CA3AF] font-mono mt-1">/{{ $restaurant->slug }}</div>
            </div>
            <div class="flex items-center gap-2">
                @if($restaurant->slug)
                <a href="{{ url('/' . $restaurant->slug) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 bg-white border border-[#E5E7EB] text-[#374151] rounded-[9px] px-3 py-2 text-[12.5px] font-semibold no-underline hover:bg-[#F9FAFB] transition-colors">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                    Ver carta
                </a>
                @endif
                <form method="POST" action="{{ route('superadmin.enter', $restaurant) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-[#EEF2FF] text-[#4F46E5] rounded-[9px] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#E0E7FF] transition-colors">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                        Entrar como negocio
                    </button>
                </form>
                <button onclick="document.getElementById('pay-modal').style.display='flex'"
                    class="inline-flex items-center gap-1.5 bg-[#4F46E5] text-white rounded-[9px] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#4338CA] transition-colors">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Registrar pago
                </button>
            </div>
        </div>

        {{-- Details grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 pt-5 border-t border-[#F3F4F6]">
            <div>
                <div class="text-[10.5px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-1">Suscripción</div>
                @if($restaurant->plan === 'free')
                <div class="text-[13px] font-semibold text-[#6B7280]">Sin vencimiento</div>
                @elseif($expDate)
                <div class="text-[13px] font-semibold {{ $isExpired ? 'text-[#DC2626]' : ($isAlert ? 'text-[#B45309]' : 'text-[#111827]') }}"
                     title="{{ $expDate->format('d/m/Y') }}">
                    @if($isExpired)
                    Vencido hace {{ abs((int)$daysLeft) }} días
                    @elseif($daysLeft === 0)
                    Vence hoy
                    @elseif($daysLeft <= 7)
                    Vence en {{ (int)$daysLeft }} días
                    @else
                    {{ $expDate->format('d/m/Y') }}
                    @endif
                </div>
                @else
                <div class="text-[13px] text-[#9CA3AF]">—</div>
                @endif
            </div>
            <div>
                <div class="text-[10.5px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-1">Fin de prueba</div>
                <div class="text-[13px] text-[#374151]">{{ $restaurant->trial_ends_at?->format('d/m/Y') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-[10.5px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-1">WhatsApp</div>
                @if($restaurant->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $restaurant->whatsapp) }}" target="_blank"
                   class="text-[13px] font-semibold text-[#059669] no-underline hover:text-[#047857]">
                    {{ $restaurant->whatsapp }}
                </a>
                @else
                <div class="text-[13px] text-[#9CA3AF]">—</div>
                @endif
            </div>
            <div>
                <div class="text-[10.5px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-1">Tags NFC</div>
                <div class="text-[13px] text-[#374151]">{{ $restaurant->nfcTags->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Dueño + señales de fuga --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5">
            <h2 class="text-[12px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-4">Dueño</h2>
            @if($owner)
            <div class="text-[14px] font-semibold mb-1">{{ $owner->name }}</div>
            <div class="text-[12.5px] text-[#6B7280] mb-2">{{ $owner->email }}</div>
            @if($restaurant->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $restaurant->whatsapp) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] rounded-[9px] px-3 py-2 text-[12.5px] font-semibold no-underline hover:bg-[#D1FAE5] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                Abrir WhatsApp
            </a>
            @endif
            @else
            <div class="text-[13px] text-[#9CA3AF]">Sin dueño asignado</div>
            @endif
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5">
            <h2 class="text-[12px] font-bold uppercase tracking-[.07em] text-[#9CA3AF] mb-4">Señales de actividad</h2>
            @php
                $lastLogin = $owner?->last_login_at;
                $loginDays = $lastLogin ? now()->diffInDays($lastLogin) : null;
                $loginOld  = $loginDays !== null && $loginDays > 21;
            @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[12.5px] text-[#6B7280]">Último acceso del dueño</span>
                    <span class="text-[12.5px] font-semibold {{ $loginOld ? 'text-[#DC2626]' : 'text-[#111827]' }}"
                          title="{{ $lastLogin?->format('d/m/Y H:i') ?? '' }}">
                        @if(!$lastLogin) Nunca
                        @elseif($loginDays === 0) Hoy
                        @elseif($loginDays === 1) Ayer
                        @elseif($loginDays < 7) Hace {{ $loginDays }} días
                        @elseif($loginDays < 30) Hace {{ floor($loginDays/7) }} sem.
                        @else Hace {{ floor($loginDays/30) }} meses
                        @endif
                    </span>
                </div>
                @foreach($scansData as $row)
                <div class="flex items-center justify-between">
                    <span class="text-[12.5px] text-[#6B7280]">Escaneos {{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->locale('es')->isoFormat('MMMM YYYY') }}</span>
                    <span class="text-[12.5px] font-semibold text-[#111827]">{{ number_format($row->total) }}</span>
                </div>
                @endforeach
                @if($scansData->isEmpty())
                <div class="text-[12.5px] text-[#9CA3AF]">Sin escaneos registrados</div>
                @endif
                @if($loginOld)
                <div class="mt-2 p-3 bg-[#FEF2F2] border border-[#FEE2E2] rounded-[10px]">
                    <p class="text-[12px] font-semibold text-[#DC2626]">Sin actividad hace más de 21 días — posible fuga.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Historial de pagos --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-[13px] font-bold">Historial de pagos</h2>
            <button onclick="document.getElementById('pay-modal').style.display='flex'"
                class="inline-flex items-center gap-1.5 bg-[#4F46E5] text-white rounded-[8px] px-3 py-1.5 text-[12px] font-semibold hover:bg-[#4338CA] transition-colors">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Registrar pago
            </button>
        </div>

        @if($restaurant->payments->isEmpty())
        <div class="px-5 py-10 text-center text-[13px] text-[#9CA3AF]">Sin pagos registrados.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#F3F4F6]">
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Fecha</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Monto</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Método</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Meses</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Notas</th>
                        <th class="text-left px-4 py-3 text-[10.5px] font-bold uppercase tracking-[.06em] text-[#9CA3AF]">Registrado por</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restaurant->payments as $payment)
                    <tr class="border-b border-[#F9FAFB] {{ $payment->cancelled_at ? 'opacity-40' : '' }}">
                        <td class="px-4 py-3 font-medium text-[#111827]">{{ $payment->paid_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-semibold text-[#059669]">${{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-[#6B7280]">{{ $methodLabels[$payment->method] ?? $payment->method }}</td>
                        <td class="px-4 py-3 text-[#6B7280]">{{ $payment->months }} {{ $payment->months === 1 ? 'mes' : 'meses' }}</td>
                        <td class="px-4 py-3 text-[#6B7280] max-w-[200px] truncate">{{ $payment->notes ?? '—' }}</td>
                        <td class="px-4 py-3 text-[#6B7280]">{{ $payment->creator?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($payment->cancelled_at)
                            <span class="text-[11px] font-semibold text-[#9CA3AF]">Anulado</span>
                            @else
                            <form method="POST" action="{{ route('superadmin.payments.destroy', $payment) }}"
                                  onsubmit="return confirm('¿Anular este pago? Se restará {{ $payment->months }} {{ $payment->months === 1 ? 'mes' : 'meses' }} de la suscripción.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-[11.5px] font-semibold text-[#DC2626] hover:text-[#B91C1C] transition-colors">
                                    Anular
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Asignar usuario --}}
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5">
        <h2 class="text-[13px] font-bold mb-4">Asignar usuario</h2>
        @if($errors->any())
        <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] p-3 mb-4">
            <p class="text-[12.5px] font-semibold text-[#DC2626]">{{ $errors->first() }}</p>
        </div>
        @endif
        <form method="POST" action="{{ route('superadmin.assign-user', $restaurant) }}" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Correo del usuario</label>
                <input type="email" name="email" required placeholder="usuario@ejemplo.com"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Rol</label>
                <select name="role" class="px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                    <option value="owner">Dueño</option>
                    <option value="staff">Equipo</option>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-[10px] bg-white border border-[#E5E7EB] text-[#374151] rounded-[10px] text-[13px] font-semibold hover:bg-[#F9FAFB] transition-colors">
                Asignar
            </button>
        </form>
    </div>
</div>

{{-- Modal: Registrar pago --}}
<div id="pay-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(17,24,39,.5);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-[18px] shadow-[0_20px_60px_rgba(16,24,40,.2)] w-full max-w-[420px] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#F3F4F6] flex items-center justify-between">
            <div>
                <h3 class="text-[14px] font-bold text-[#111827]">Registrar pago</h3>
                <p class="text-[12px] text-[#6B7280] mt-0.5">{{ $restaurant->name }}</p>
            </div>
            <button onclick="document.getElementById('pay-modal').style.display='none'"
                class="w-[28px] h-[28px] flex items-center justify-center rounded-[8px] text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('superadmin.payments.store', $restaurant) }}" class="px-6 py-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Monto (CLP)</label>
                    <input type="number" name="amount" min="1" placeholder="9990" required
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                </div>
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Fecha de pago</label>
                    <input type="date" name="paid_at" value="{{ now()->format('Y-m-d') }}" required
                        class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
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
                <button type="button" onclick="document.getElementById('pay-modal').style.display='none'"
                    class="bg-white border border-[#E5E7EB] text-[#374151] rounded-[10px] px-4 py-[10px] text-[13px] font-semibold hover:bg-[#F9FAFB] transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('pay-modal').addEventListener('click', e => {
    if (e.target === e.currentTarget) e.currentTarget.style.display = 'none';
});
</script>

</body>
</html>
