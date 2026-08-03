<x-admin-layout>

@php
    $now = \Carbon\Carbon::now();

    $badgeMap = [
        'trial'   => ['label' => 'Prueba gratuita', 'bg' => 'bg-[#EFF6FF]', 'text' => 'text-[#1D4ED8]', 'border' => 'border-[#BFDBFE]'],
        'basic'   => ['label' => 'Basic',            'bg' => 'bg-[#ECFDF5]', 'text' => 'text-[#065F46]', 'border' => 'border-[#A7F3D0]'],
        'pro'     => ['label' => 'Pro',              'bg' => 'bg-[#EEF2FF]', 'text' => 'text-[#3730A3]', 'border' => 'border-[#C7D2FE]'],
        'expired' => ['label' => 'Vencido',          'bg' => 'bg-[#FEF2F2]', 'text' => 'text-[#991B1B]', 'border' => 'border-[#FECACA]'],
    ];
    $badge = $badgeMap[$restaurant->plan] ?? ['label' => ucfirst($restaurant->plan), 'bg' => 'bg-[#F3F4F6]', 'text' => 'text-[#374151]', 'border' => 'border-[#E5E7EB]'];

    if ($restaurant->plan === 'trial' && $restaurant->trial_ends_at) {
        $daysLeft  = (int) $now->diffInDays($restaurant->trial_ends_at, false);
        $totalDays = 14;
        $elapsed   = min((int) optional($restaurant->created_at)->diffInDays($now), $totalDays);
        $endLabel  = 'Fin del trial';
        $endDate   = optional($restaurant->trial_ends_at)->format('d/m/Y');
        $barColor  = 'bg-blue-500';
    } elseif (in_array($restaurant->plan, ['basic', 'pro']) && $restaurant->subscription_ends_at) {
        $daysLeft  = (int) $now->diffInDays($restaurant->subscription_ends_at, false);
        $totalDays = 30;
        $elapsed   = max(0, $totalDays - $daysLeft);
        $endLabel  = 'Renovación';
        $endDate   = optional($restaurant->subscription_ends_at)->format('d/m/Y');
        $barColor  = $restaurant->plan === 'pro' ? 'bg-[#4F46E5]' : 'bg-emerald-500';
    } else {
        $daysLeft  = 0;
        $totalDays = 1;
        $elapsed   = 1;
        $endLabel  = '';
        $endDate   = '';
        $barColor  = 'bg-[#D1D5DB]';
    }
    $progressPercent = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 100;

    $waNumber  = '56912345678';
    $waMessage = urlencode('Hola, quiero renovar/activar mi plan en MenuDigital. Mi restaurante es: ' . $restaurant->name);
    $waUrl     = "https://wa.me/{$waNumber}?text={$waMessage}";
@endphp

<div class="max-w-[720px] space-y-4">

    <!-- Plan Status Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
        <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
            <div>
                <div class="text-[13px] font-bold text-[#111827] mb-1">Tu plan actual</div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                    {{ $badge['label'] }}
                </span>
            </div>
            @if($restaurant->plan !== 'expired')
            <div class="text-right">
                @if($daysLeft > 0)
                <div class="text-[28px] font-bold text-[#111827] leading-none">{{ $daysLeft }}</div>
                <div class="text-[12px] text-[#6B7280] mt-0.5">días restantes</div>
                @else
                <div class="text-[13px] font-semibold text-[#DC2626]">Vencido</div>
                @endif
            </div>
            @else
            <div class="text-[13px] font-semibold text-[#DC2626]">Plan vencido</div>
            @endif
        </div>

        @if($restaurant->plan !== 'expired')
        <div>
            <div class="flex justify-between text-[11px] text-[#9CA3AF] mb-1.5">
                <span>Inicio</span>
                @if($endDate)
                <span>{{ $endLabel }} · {{ $endDate }}</span>
                @endif
            </div>
            <div class="w-full h-[6px] bg-[#F3F4F6] rounded-full overflow-hidden">
                <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
        @endif
    </div>

    <!-- Plans Comparison -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
        <div class="text-[13px] font-bold text-[#111827] mb-3">Planes disponibles</div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            <!-- Trial / Free -->
            <div class="border rounded-[12px] p-4 relative {{ $restaurant->plan === 'trial' ? 'border-[#4F46E5] bg-[#EEF2FF]' : 'border-[#E5E7EB]' }}">
                @if($restaurant->plan === 'trial')
                <div class="absolute top-3 right-3">
                    <span class="text-[10px] font-bold text-[#4F46E5] bg-[#E0E7FF] rounded-[5px] px-[5px] py-[2px]">Actual</span>
                </div>
                @endif
                <div class="text-[11px] font-bold uppercase tracking-[.04em] text-[#6B7280] mb-2">Free</div>
                <div class="text-[24px] font-bold text-[#111827] leading-none">Gratis</div>
                <div class="text-[12px] text-[#9CA3AF] mt-0.5 mb-3">14 días de prueba</div>
                <ul class="space-y-1.5">
                    @foreach(['Menú digital', 'QR personalizado', 'Categorías e ítems ilimitados'] as $feat)
                    <li class="flex items-center gap-2 text-[12px] text-[#374151]">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="#10B981"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Basic -->
            <div class="border rounded-[12px] p-4 relative {{ $restaurant->plan === 'basic' ? 'border-[#4F46E5] bg-[#EEF2FF]' : 'border-[#E5E7EB]' }}">
                @if($restaurant->plan === 'basic')
                <div class="absolute top-3 right-3">
                    <span class="text-[10px] font-bold text-[#4F46E5] bg-[#E0E7FF] rounded-[5px] px-[5px] py-[2px]">Actual</span>
                </div>
                @endif
                <div class="text-[11px] font-bold uppercase tracking-[.04em] text-[#6B7280] mb-2">Basic</div>
                <div class="text-[24px] font-bold text-[#111827] leading-none">$9.990</div>
                <div class="text-[12px] text-[#9CA3AF] mt-0.5 mb-3">CLP / mes</div>
                <ul class="space-y-1.5">
                    @foreach(['Todo lo del Free', 'Apariencia personalizada', 'Sin límite de ítems', 'Soporte por email'] as $feat)
                    <li class="flex items-center gap-2 text-[12px] text-[#374151]">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="#10B981"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @if($restaurant->plan !== 'basic')
                <a href="{{ $waUrl }}" target="_blank"
                   class="mt-4 flex items-center justify-center gap-1.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-3 py-2 text-[12px] font-semibold transition-colors">
                    Contratar
                </a>
                @endif
            </div>

            <!-- Pro -->
            <div class="border rounded-[12px] p-4 relative {{ $restaurant->plan === 'pro' ? 'border-[#4F46E5] bg-[#EEF2FF]' : 'border-[#4F46E5]' }}">
                @if($restaurant->plan === 'pro')
                <div class="absolute top-3 right-3">
                    <span class="text-[10px] font-bold text-[#4F46E5] bg-[#E0E7FF] rounded-[5px] px-[5px] py-[2px]">Actual</span>
                </div>
                @else
                <div class="absolute top-3 right-3">
                    <span class="text-[10px] font-bold text-white bg-[#4F46E5] rounded-[5px] px-[5px] py-[2px]">Popular</span>
                </div>
                @endif
                <div class="text-[11px] font-bold uppercase tracking-[.04em] text-[#4F46E5] mb-2">Pro</div>
                <div class="text-[24px] font-bold text-[#111827] leading-none">$19.990</div>
                <div class="text-[12px] text-[#9CA3AF] mt-0.5 mb-3">CLP / mes</div>
                <ul class="space-y-1.5">
                    @foreach(['Todo lo del Basic', 'Soporte prioritario WhatsApp', 'Actualizaciones anticipadas', 'Variantes de platos'] as $feat)
                    <li class="flex items-center gap-2 text-[12px] text-[#374151]">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="#4F46E5"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @if($restaurant->plan !== 'pro')
                <a href="{{ $waUrl }}" target="_blank"
                   class="mt-4 flex items-center justify-center gap-1.5 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-3 py-2 text-[12px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Contratar Pro
                </a>
                @endif
            </div>

        </div>
    </div>

    <!-- Bank Transfer Info -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
        <div class="text-[13px] font-bold text-[#111827] mb-1">Datos para transferencia bancaria</div>
        <p class="text-[12px] text-[#6B7280] mb-4">Realiza la transferencia y envíanos el comprobante por WhatsApp para activar tu plan en minutos.</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @php
            $bankData = [
                'Banco'                => 'Banco Estado',
                'Tipo de cuenta'       => 'Cuenta Corriente',
                'N° de cuenta'         => '123456789',
                'Titular'              => 'MenuDigital SpA',
                'RUT'                  => '76.xxx.xxx-x',
                'Email transferencia'  => 'pagos@menudigital.cl',
            ];
            @endphp
            @foreach($bankData as $key => $value)
            <div class="bg-[#F9FAFB] border border-[#F3F4F6] rounded-[10px] px-3 py-2.5">
                <div class="text-[11px] text-[#9CA3AF] font-medium mb-0.5">{{ $key }}</div>
                <div class="text-[13px] font-semibold text-[#111827]">{{ $value }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- WhatsApp CTA -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <div class="text-[13px] font-bold text-[#111827]">¿Listo para renovar o cambiar de plan?</div>
            <p class="text-[12px] text-[#6B7280] mt-0.5">Escríbenos con tu comprobante y activamos tu plan en minutos.</p>
        </div>
        <a href="{{ $waUrl }}" target="_blank"
           class="inline-flex items-center gap-2 bg-[#16A34A] hover:bg-[#15803D] text-white rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(22,163,74,.35)] transition-colors whitespace-nowrap">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contactar por WhatsApp
        </a>
    </div>

</div>

</x-admin-layout>
