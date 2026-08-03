<x-guest-layout>

    @if(session('status'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:12px;background:#FFF3CD;border:1.5px solid #201914;font-size:13px;font-weight:600;color:#201914">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:12px;background:#FEE2E2;border:1.5px solid #C2410C;font-size:13px;font-weight:600;color:#7F1D1D">
        {{ session('error') }}
    </div>
    @endif

    <h1 style="margin:0 0 6px;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:28px;font-weight:800;letter-spacing:-.03em;line-height:1.05">Hola de nuevo</h1>
    <p style="margin:0 0 24px;font-size:14px;color:#5C5245;line-height:1.5">Ingresa para administrar la carta de tu restaurante.</p>

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:14px">
        @csrf

        <div>
            <label for="email" style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="hola@mirestaurante.cl"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('email')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px">
                <label for="password" style="font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em">Contraseña</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:12px;font-weight:600;color:#E85D2F">¿La olvidaste?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Tu contraseña"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <label style="display:inline-flex;align-items:center;gap:9px;cursor:pointer;font-size:13px;font-weight:600;color:#5C5245">
            <input type="checkbox" name="remember" style="width:17px;height:17px;border-radius:5px;border:1.5px solid #201914;accent-color:#E85D2F;cursor:pointer">
            Mantener la sesión iniciada
        </label>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:999px;background:#E85D2F;color:#FFF6DE;border:1.5px solid #201914;box-shadow:4px 4px 0 #201914;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:14px;font-weight:800;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #201914';this.style.transform='translate(3px,3px)'"
            onmouseout="this.style.boxShadow='4px 4px 0 #201914';this.style.transform='none'">
            Ingresar
        </button>

        <p style="text-align:center;font-size:13px;color:#5C5245;margin:4px 0 0">
            ¿Aún no tienes cuenta?
            <a href="{{ route('register') }}" style="font-weight:700;color:#E85D2F">Crear una gratis</a>
        </p>
    </form>

</x-guest-layout>
