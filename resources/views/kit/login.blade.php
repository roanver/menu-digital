<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar · MenuDigital</title>
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
        p.sub{font-size:14px;color:#6B7280;line-height:1.55;margin-bottom:24px}
        .field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px}
        label{font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#374151}
        input[type=email],input[type=password]{width:100%;padding:11px 13px;border:1.5px solid #D1D5DB;border-radius:10px;font-family:Inter,system-ui,sans-serif;font-size:14px;color:#111827;background:#fff;outline:none;transition:border-color .15s}
        input:focus{border-color:#4F46E5;box-shadow:0 0 0 3px rgba(79,70,229,.1)}
        .error-field{border-color:#EF4444 !important}
        .field-error{font-size:12px;font-weight:600;color:#B91C1C}
        .btn-primary{display:block;width:100%;padding:13px;border-radius:12px;border:none;background:#4F46E5;color:#fff;font-family:Inter,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;margin-top:8px;transition:opacity .15s}
        .btn-primary:hover{opacity:.88}
        .back-link{display:block;text-align:center;margin-top:14px;font-size:13px;color:#6B7280}
        .back-link a{color:#4F46E5;font-weight:600;text-decoration:none}
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

    <h1>Ingresa a tu cuenta</h1>
    <p class="sub">Después de ingresar, el kit se asignará a tu negocio.</p>

    <form method="POST" action="{{ route('kit.login', $token) }}">
        @csrf

        <div class="field">
            <label for="email">Correo electrónico</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="hola@minegocio.cl"
                required
                autofocus
                autocomplete="username"
                class="{{ $errors->has('email') ? 'error-field' : '' }}"
            >
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Contraseña</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Tu contraseña"
                required
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'error-field' : '' }}"
            >
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Ingresar y continuar</button>
    </form>

    <p class="back-link">
        ¿No tienes cuenta? <a href="{{ route('kit.register.form', $token) }}">Crear una gratis</a>
    </p>
</div>

<footer>menudigital.cl</footer>

</body>
</html>
