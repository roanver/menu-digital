<x-guest-layout>

    @if(session('status'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:10px;background:#ECFDF5;border:1px solid #6EE7B7;font-size:13px;font-weight:600;color:#065F46">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:10px;background:#FEF2F2;border:1px solid #FECACA;font-size:13px;font-weight:600;color:#991B1B">
        {{ session('error') }}
    </div>
    @endif

    <h1 style="margin:0;font-size:26px;font-weight:700;letter-spacing:-.028em">Hola de nuevo</h1>
    <p style="margin:9px 0 0;font-size:13.5px;line-height:1.6;color:#6B7280">Ingresa para administrar la carta de tu restaurante.</p>

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:13px;margin-top:24px">
        @csrf

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Correo</span>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="hola@elfogon.cl"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('email') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('email')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="display:flex;align-items:center;gap:8px">
                <span style="flex:1;font-size:12px;font-weight:600;color:#374151">Contraseña</span>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:11.5px;font-weight:600;color:#4F46E5">¿La olvidaste?</a>
                @endif
            </span>
            <input type="password" name="password" required autocomplete="current-password"
                placeholder="Mínimo 8 caracteres"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('password') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('password')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;align-items:center;gap:9px;cursor:pointer">
            <input type="checkbox" name="remember" style="width:16px;height:16px;border-radius:4px;accent-color:#4F46E5;cursor:pointer">
            <span style="font-size:12.5px;color:#4B5563">Mantener la sesión iniciada</span>
        </label>

        <button type="submit"
            style="width:100%;padding:11px 16px;border-radius:11px;background:#4F46E5;color:#fff;border:1px solid #4338CA;font-size:14px;font-weight:600;cursor:pointer;box-shadow:0 1px 2px rgba(79,70,229,.35);margin-top:4px"
            onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
            Ingresar
        </button>
    </form>

    <p style="margin:20px 0 0;font-size:13px;color:#6B7280;text-align:center">
        ¿Aún no tienes cuenta?
        <a href="{{ route('register') }}" style="font-weight:600;color:#4F46E5">Crear una gratis</a>
    </p>

</x-guest-layout>
