<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activar kit · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,sans-serif;background:#F9FAFB;color:#111827;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:32px 16px}
        .card{background:#fff;border:1px solid #E5E7EB;border-radius:20px;box-shadow:0 1px 4px rgba(0,0,0,.06);max-width:420px;width:100%;padding:32px}
        .brand{display:flex;align-items:center;gap:9px;margin-bottom:28px}
        .brand-icon{width:32px;height:32px;border-radius:10px;background:#4F46E5;display:flex;align-items:center;justify-content:center;flex-none}
        .brand-name{font-size:16px;font-weight:700;letter-spacing:-.02em;color:#111827}
        h1{font-size:22px;font-weight:700;letter-spacing:-.02em;margin-bottom:6px}
        p.sub{font-size:14px;color:#6B7280;line-height:1.55;margin-bottom:22px}
        .kit-card{background:#F0F0FF;border:1px solid #C7D2FE;border-radius:14px;padding:16px 18px;margin-bottom:24px}
        .kit-row{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:500;color:#1E1B4B;padding:4px 0}
        .kit-row svg{flex-none}
        .divider{height:1px;background:#E5E7EB;margin:20px 0}
        .label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9CA3AF;margin-bottom:12px}
        .btn{display:block;width:100%;padding:13px;border-radius:12px;border:none;font-family:Inter,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;text-align:center;text-decoration:none;transition:opacity .15s}
        .btn-primary{background:#4F46E5;color:#fff}
        .btn-primary:hover{opacity:.88}
        .btn-outline{background:#fff;color:#111827;border:1.5px solid #D1D5DB;margin-top:10px}
        .btn-outline:hover{border-color:#9CA3AF}
        .radio-list{display:flex;flex-direction:column;gap:8px;margin-bottom:20px}
        .radio-item{display:flex;align-items:center;gap:10px;padding:11px 14px;border:1.5px solid #E5E7EB;border-radius:10px;cursor:pointer;transition:border-color .1s}
        .radio-item:has(input:checked){border-color:#4F46E5;background:#F5F3FF}
        .radio-item input{accent-color:#4F46E5;width:16px;height:16px;flex-none}
        .radio-item span{font-size:14px;font-weight:500}
        .flash-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:11px 14px;font-size:13px;font-weight:600;color:#B91C1C;margin-bottom:18px}
        .big-icon{width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px}
        footer{margin-top:24px;font-size:11px;color:#D1D5DB;text-align:center}
    </style>
</head>
<body>

<div class="card">
    <div class="brand">
        <div class="brand-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
        </div>
        <span class="brand-name">MenuDigital</span>
    </div>

    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    {{-- ======================== NOT FOUND ======================== --}}
    @if($state === 'not_found')
        <div class="big-icon" style="background:#FEE2E2">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <h1 style="text-align:center">Código no encontrado</h1>
        <p class="sub" style="text-align:center;margin-top:8px">
            Este enlace de activación no existe o ya fue procesado.<br>
            Revisa la tarjeta que venía en la caja.
        </p>
        <a href="https://menudigital.cl" class="btn btn-primary" style="margin-top:8px">Ir a menudigital.cl</a>

    {{-- ======================== ALREADY ACTIVATED ======================== --}}
    @elseif($state === 'already_activated')
        <div class="big-icon" style="background:#FEF3C7">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h1 style="text-align:center">Este kit ya fue activado</h1>
        <p class="sub" style="text-align:center;margin-top:8px">
            Si eres el dueño del negocio, entra a tu cuenta para administrar tu carta.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary" style="margin-top:8px">Entrar a mi cuenta</a>

    {{-- ======================== AVAILABLE ======================== --}}
    @elseif($state === 'available')

        <div style="margin-bottom:6px">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#6366F1;margin-bottom:6px">Tu kit está listo</div>
            <h1>Activar MenuDigital</h1>
        </div>
        <p class="sub">En menos de 2 minutos tu carta digital estará funcionando en las mesas.</p>

        {{-- Contenido del kit --}}
        <div class="kit-card">
            <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#6366F1;margin-bottom:10px">Contenido del kit</div>
            <div class="kit-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="3" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><rect x="15" y="15" width="2" height="2"/></svg>
                {{ $tableCount }} {{ $tableCount === 1 ? 'mesa' : 'mesas' }} (QR + chip NFC)
            </div>
            @if($hasReview)
            <div class="kit-row" style="margin-top:4px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Letrero de reseñas Google
            </div>
            @endif
            <div class="kit-row" style="margin-top:4px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Plan Básico incluido por 2 meses
            </div>
        </div>

        <div class="divider"></div>

        {{-- ---- USUARIO NO AUTENTICADO ---- --}}
        @if(! $user)
            <div class="label">¿Cómo quieres continuar?</div>
            <a href="{{ route('kit.register.form', $token) }}" class="btn btn-primary">Crear mi cuenta gratis</a>
            <a href="{{ route('kit.login.form', $token) }}" class="btn btn-outline">Ya tengo cuenta</a>

        {{-- ---- USUARIO AUTENTICADO, 1 NEGOCIO ---- --}}
        @elseif($restaurants->count() <= 1)
            @php $restaurant = $restaurants->first() @endphp
            <div class="label">Activar para</div>
            <div style="font-size:15px;font-weight:600;color:#111827;margin-bottom:16px">
                {{ $restaurant?->name ?? 'Tu negocio' }}
            </div>
            <form method="POST" action="{{ route('kit.activate', $token) }}">
                @csrf
                @if($restaurant)
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                @endif
                <button type="submit" class="btn btn-primary">Activar kit</button>
            </form>
            <div style="margin-top:14px;font-size:12px;color:#9CA3AF;text-align:center">
                Sesión iniciada como <strong>{{ $user->email }}</strong>
            </div>

        {{-- ---- USUARIO AUTENTICADO, MÚLTIPLES NEGOCIOS ---- --}}
        @else
            <div class="label">¿A cuál negocio lo asignas?</div>
            <form method="POST" action="{{ route('kit.activate', $token) }}">
                @csrf
                <div class="radio-list">
                    @foreach($restaurants as $restaurant)
                    <label class="radio-item">
                        <input type="radio" name="restaurant_id" value="{{ $restaurant->id }}" required>
                        <span>{{ $restaurant->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('restaurant_id')
                    <p style="font-size:12px;font-weight:600;color:#B91C1C;margin-bottom:12px">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary">Activar kit</button>
            </form>
            <div style="margin-top:14px;font-size:12px;color:#9CA3AF;text-align:center">
                Sesión iniciada como <strong>{{ $user->email }}</strong>
            </div>
        @endif

    @endif
</div>

<footer>menudigital.cl</footer>

</body>
</html>
