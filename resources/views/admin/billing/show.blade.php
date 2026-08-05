<x-admin-layout>

@php
    $now = \Carbon\Carbon::now();

    $inTrial  = $restaurant->trial_ends_at && $now->lt($restaurant->trial_ends_at);
    $inPaid   = $restaurant->subscription_ends_at && $now->lt($restaurant->subscription_ends_at);
    $isActive = $inTrial || $inPaid;

    if ($inTrial) {
        $daysLeft        = (int) $now->diffInDays($restaurant->trial_ends_at, false);
        $totalDays       = 14;
        $elapsed         = min((int) optional($restaurant->created_at)->diffInDays($now), $totalDays);
        $endDate         = $restaurant->trial_ends_at->format('d \d\e F Y');
        $statusLabel     = 'Prueba gratuita';
        $statusSub       = 'Vence el ' . $endDate;
        $progressPercent = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 100;
    } elseif ($inPaid) {
        $daysLeft        = (int) $now->diffInDays($restaurant->subscription_ends_at, false);
        $totalDays       = 30;
        $elapsed         = max(0, $totalDays - $daysLeft);
        $endDate         = $restaurant->subscription_ends_at->format('d \d\e F Y');
        $statusLabel     = 'Plan activo';
        $statusSub       = 'Renueva el ' . $endDate;
        $progressPercent = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 100;
    } else {
        $daysLeft        = 0;
        $totalDays       = 1;
        $elapsed         = 1;
        $statusLabel     = 'Plan vencido';
        $statusSub       = 'Renueva para seguir usando tu carta';
        $progressPercent = 100;
    }

    $planNames  = ['carta' => 'Carta', 'pedidos' => 'Pedidos', 'full' => 'Full'];
    $planName   = $planNames[$restaurant->plan] ?? ucfirst($restaurant->plan);

    $waNumber  = '56912345678';
    $waMessage = urlencode('Hola, quiero renovar/activar mi plan en MenuDigital. Mi restaurante es: ' . $restaurant->name);
    $waUrl     = "https://wa.me/{$waNumber}?text={$waMessage}";

    $plans = [
        'carta' => [
            'name'     => 'Carta',
            'price'    => '$8.000',
            'color'    => '#10B981',
            'features' => ['Carta digital', 'Chip NFC y QR', 'Pedidos por WhatsApp', 'Importación por foto IA'],
        ],
        'pedidos' => [
            'name'     => 'Pedidos',
            'price'    => '$15.000',
            'color'    => '#4F46E5',
            'features' => ['Todo lo de Carta', 'Panel de pedidos', 'Generador de posts redes', 'Estadísticas básicas'],
        ],
        'full' => [
            'name'     => 'Full',
            'price'    => '$25.000',
            'color'    => '#7C3AED',
            'features' => ['Todo lo de Pedidos', 'Reportes de ventas', 'Soporte prioritario WhatsApp', 'Acceso anticipado a funciones'],
        ],
    ];

    $bankData = [
        ['label' => 'Banco',       'value' => 'Banco Estado',        'icon' => 'M3 6h18M3 10h18M3 14h18M3 18h18'],
        ['label' => 'Cuenta',      'value' => 'Cuenta Corriente',    'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['label' => 'N° cuenta',   'value' => '123456789',           'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
        ['label' => 'Titular',     'value' => 'MenuDigital SpA',     'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label' => 'RUT',         'value' => '76.xxx.xxx-x',        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'Email',       'value' => 'pagos@menudigital.cl','icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ];
@endphp

<style>
.billing-hero{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 40%,#4338ca 100%);border-radius:20px;padding:32px;position:relative;overflow:hidden;}
.billing-hero::before{content:'';position:absolute;top:-60px;right:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none;}
.billing-hero::after{content:'';position:absolute;bottom:-80px;left:40px;width:160px;height:160px;border-radius:50%;background:rgba(99,102,241,.18);pointer-events:none;}
.ring-wrap{position:relative;width:88px;height:88px;flex:none;}
.ring-svg{transform:rotate(-90deg);}
.ring-bg{fill:none;stroke:rgba(255,255,255,.12);stroke-width:7;}
.ring-fg{fill:none;stroke:#a5b4fc;stroke-width:7;stroke-linecap:round;transition:stroke-dashoffset .6s ease;}
.days-num{font-size:28px;font-weight:800;color:#fff;line-height:1;}
.days-lbl{font-size:10px;color:#a5b4fc;font-weight:600;letter-spacing:.06em;text-transform:uppercase;}
.plan-card{border-radius:16px;padding:24px;position:relative;transition:transform .15s,box-shadow .15s;}
.plan-card:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,0,0,.12);}
.plan-card-popular{background:linear-gradient(145deg,#4F46E5,#7C3AED);color:#fff;}
.plan-card-popular .feat-check{color:#c4b5fd;}
.plan-card-popular .plan-price{color:#fff;}
.plan-card-popular .plan-sub{color:#c4b5fd;}
.bank-item{background:#F9FAFB;border:1px solid #F3F4F6;border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;cursor:pointer;transition:background .12s,border-color .12s;position:relative;}
.bank-item:hover{background:#F3F4F6;border-color:#E5E7EB;}
.bank-item .copy-hint{position:absolute;top:8px;right:10px;font-size:10px;color:#9CA3AF;opacity:0;transition:opacity .15s;}
.bank-item:hover .copy-hint{opacity:1;}
.copy-toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);background:#111827;color:#fff;font-size:12px;font-weight:600;padding:8px 18px;border-radius:999px;opacity:0;pointer-events:none;transition:opacity .2s,transform .2s;z-index:999;}
.copy-toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
.expired-banner{background:linear-gradient(135deg,#7f1d1d,#991b1b);border-radius:20px;padding:32px;position:relative;overflow:hidden;}
.expired-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
</style>

<div class="max-w-[760px] space-y-5">

    {{-- ── HERO STATUS ──────────────────────────────────────────── --}}
    @if($isActive)
    <div class="billing-hero">
        <div style="position:relative;z-index:1;">

            {{-- Top row --}}
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:4px 12px;margin-bottom:14px;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#34D399;box-shadow:0 0 8px #34D399;flex:none;"></span>
                        <span style="font-size:11px;font-weight:700;color:#a5b4fc;letter-spacing:.06em;text-transform:uppercase;">{{ $statusLabel }}</span>
                    </div>
                    <div style="font-size:30px;font-weight:800;color:#fff;line-height:1.1;letter-spacing:-.02em;">Plan {{ $planName }}</div>
                    <div style="font-size:13px;color:#c7d2fe;margin-top:6px;">{{ $statusSub }}</div>
                </div>

                {{-- Ring --}}
                <div class="ring-wrap">
                    @php
                        $r = 36; $circ = 2 * M_PI * $r;
                        $offset = $circ - ($progressPercent / 100) * $circ;
                    @endphp
                    <svg class="ring-svg" width="88" height="88" viewBox="0 0 88 88">
                        <circle class="ring-bg" cx="44" cy="44" r="{{ $r }}"/>
                        <circle class="ring-fg" cx="44" cy="44" r="{{ $r }}"
                            stroke-dasharray="{{ round($circ, 2) }}"
                            stroke-dashoffset="{{ round($offset, 2) }}"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1px;">
                        <span class="days-num">{{ $daysLeft }}</span>
                        <span class="days-lbl">días</span>
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div style="margin-top:24px;">
                <div style="display:flex;justify-content:space-between;font-size:11px;color:#a5b4fc;margin-bottom:8px;font-weight:600;">
                    <span>{{ $inTrial ? 'Inicio del trial' : 'Inicio del período' }}</span>
                    <span>{{ $inTrial ? 'Fin del trial' : 'Renovación' }} · {{ $endDate }}</span>
                </div>
                <div style="width:100%;height:5px;background:rgba(255,255,255,.12);border-radius:999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $progressPercent }}%;background:linear-gradient(90deg,#818cf8,#a5b4fc);border-radius:999px;"></div>
                </div>
            </div>

        </div>
    </div>
    @else
    {{-- Expired banner --}}
    <div class="expired-banner">
        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div>
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);border-radius:999px;padding:4px 12px;margin-bottom:12px;">
                    <span style="font-size:11px;font-weight:700;color:#fca5a5;letter-spacing:.06em;text-transform:uppercase;">Plan vencido</span>
                </div>
                <div style="font-size:24px;font-weight:800;color:#fff;line-height:1.2;">Tu plan está inactivo</div>
                <div style="font-size:13px;color:#fca5a5;margin-top:6px;">Renueva ahora para seguir usando tu carta digital.</div>
            </div>
            <a href="{{ $waUrl }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#991b1b;padding:12px 20px;border-radius:12px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;box-shadow:0 4px 16px rgba(0,0,0,.25);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Renovar ahora
            </a>
        </div>
    </div>
    @endif

    {{-- ── PLANES ────────────────────────────────────────────────── --}}
    <div>
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9CA3AF;margin-bottom:14px;">Planes disponibles</div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            @foreach($plans as $key => $plan)
            @php
                $isCurrent = $restaurant->plan === $key;
                $isPopular = $key === 'full';
            @endphp

            <div class="plan-card {{ $isPopular && !$isCurrent ? 'plan-card-popular' : 'bg-white border border-[#E5E7EB]' }}
                        {{ $isCurrent ? 'ring-2 ring-[#4F46E5] ring-offset-2' : '' }}">

                {{-- Badge --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                    <span style="font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:{{ $isPopular && !$isCurrent ? '#c4b5fd' : $plan['color'] }};">
                        {{ $plan['name'] }}
                    </span>
                    @if($isCurrent)
                    <span style="font-size:10px;font-weight:700;background:#EEF2FF;color:#4F46E5;border-radius:6px;padding:3px 8px;">Actual</span>
                    @elseif($isPopular)
                    <span style="font-size:10px;font-weight:700;background:rgba(255,255,255,.2);color:#fff;border-radius:6px;padding:3px 8px;">Popular</span>
                    @endif
                </div>

                {{-- Price --}}
                <div style="margin-bottom:20px;">
                    <div class="plan-price" style="font-size:34px;font-weight:800;letter-spacing:-.03em;line-height:1;color:{{ $isPopular && !$isCurrent ? '#fff' : '#111827' }};">{{ $plan['price'] }}</div>
                    <div class="plan-sub" style="font-size:12px;color:{{ $isPopular && !$isCurrent ? '#c4b5fd' : '#9CA3AF' }};margin-top:3px;">CLP / mes</div>
                </div>

                {{-- Features --}}
                <ul style="display:flex;flex-direction:column;gap:9px;margin-bottom:22px;">
                    @foreach($plan['features'] as $feat)
                    <li style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:{{ $isPopular && !$isCurrent ? 'rgba(255,255,255,.85)' : '#374151' }};">
                        <svg class="feat-check" width="15" height="15" viewBox="0 0 20 20" fill="{{ $isPopular && !$isCurrent ? '#a5b4fc' : $plan['color'] }}" style="flex:none;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                @if($isCurrent)
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#4F46E5;">
                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Plan actual
                </div>
                @else
                <a href="{{ $waUrl }}" target="_blank"
                   style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;transition:opacity .15s;
                          background:{{ $isPopular ? 'rgba(255,255,255,.18)' : '#F3F4F6' }};
                          color:{{ $isPopular ? '#fff' : '#111827' }};
                          border:1px solid {{ $isPopular ? 'rgba(255,255,255,.25)' : '#E5E7EB' }};">
                    Contratar {{ $plan['name'] }}
                </a>
                @endif

            </div>
            @endforeach

        </div>
    </div>

    {{-- ── CÓMO PAGAR ───────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid #E5E7EB;border-radius:20px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,.05);">

        {{-- Header --}}
        <div style="padding:20px 24px 16px;border-bottom:1px solid #F3F4F6;">
            <div style="font-size:15px;font-weight:700;color:#111827;">Cómo pagar</div>
            <div style="font-size:12.5px;color:#6B7280;margin-top:3px;">Transfiere y envíanos el comprobante por WhatsApp. Activamos en minutos.</div>
        </div>

        {{-- Steps --}}
        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;border-bottom:1px solid #F3F4F6;">
            @foreach([
                ['num'=>'1','text'=>'Elige tu plan arriba y haz clic en "Contratar"'],
                ['num'=>'2','text'=>'Transfiere el monto mensual a los datos de abajo'],
                ['num'=>'3','text'=>'Envíanos el comprobante por WhatsApp y listo'],
            ] as $step)
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:28px;height:28px;border-radius:50%;background:#EEF2FF;color:#4F46E5;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex:none;">{{ $step['num'] }}</div>
                <span style="font-size:13px;color:#374151;">{{ $step['text'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Bank data --}}
        <div style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9CA3AF;margin-bottom:14px;">Datos bancarios <span style="font-weight:400;text-transform:none;letter-spacing:0;">(toca para copiar)</span></div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;">
                @foreach($bankData as $item)
                <div class="bank-item" onclick="copyText('{{ $item['value'] }}', this)">
                    <div style="width:34px;height:34px;border-radius:10px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;flex:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:10.5px;color:#9CA3AF;font-weight:600;margin-bottom:1px;">{{ $item['label'] }}</div>
                        <div style="font-size:13px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['value'] }}</div>
                    </div>
                    <span class="copy-hint">copiar</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- WhatsApp CTA --}}
        <div style="padding:16px 24px 20px;background:#F9FAFB;border-top:1px solid #F3F4F6;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <div style="font-size:13px;font-weight:700;color:#111827;">¿Ya hiciste la transferencia?</div>
                <div style="font-size:12px;color:#6B7280;margin-top:2px;">Envíanos el comprobante y activamos tu plan al tiro.</div>
            </div>
            <a href="{{ $waUrl }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;background:#16A34A;color:#fff;padding:11px 18px;border-radius:12px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;box-shadow:0 2px 8px rgba(22,163,74,.35);transition:background .15s;"
               onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Enviar comprobante
            </a>
        </div>

    </div>

</div>

{{-- Toast copy --}}
<div class="copy-toast" id="copyToast">¡Copiado!</div>

<script>
function copyText(text, el) {
    navigator.clipboard.writeText(text).then(function () {
        var toast = document.getElementById('copyToast');
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 1800);
    });
}
</script>

</x-admin-layout>
