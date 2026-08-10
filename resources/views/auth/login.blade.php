<x-guest-layout
    title="Ingresar — MenuDigital"
    description="Accede a tu cuenta para actualizar la carta digital de tu restaurante."
    robots="noindex,nofollow">

    @if(session('status'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:10px;background:#EEF5EE;border:1.5px solid #3E5A47;font-size:13px;font-weight:600;color:#16211C">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom:16px;padding:11px 14px;border-radius:10px;background:#FDECEA;border:1.5px solid #C8452F;font-size:13px;font-weight:600;color:#7F1D1D">
        {{ session('error') }}
    </div>
    @endif

    <h1 style="margin:0 0 6px;font-family:Fraunces,Georgia,serif;font-size:28px;font-weight:700;letter-spacing:-.02em;line-height:1.08">Hola de nuevo</h1>
    <p style="margin:0 0 24px;font-size:14px;color:#4A4A42;line-height:1.5">Ingresa para administrar tu carta digital.</p>

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:14px">
        @csrf

        <div>
            <label for="email" style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="hola@minegocio.cl"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('email')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px">
                <label for="password" style="font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase">Contraseña</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:12px;font-weight:600;color:#C8452F">¿La olvidaste?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Tu contraseña"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <label style="display:inline-flex;align-items:center;gap:9px;cursor:pointer;font-size:13px;font-weight:500;color:#4A4A42">
            <input type="checkbox" name="remember" style="width:17px;height:17px;border-radius:4px;border:1.5px solid #16211C;accent-color:#3E5A47;cursor:pointer">
            Mantener la sesión iniciada
        </label>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:10px;background:#3E5A47;color:#F5F4EF;border:1.5px solid #16211C;box-shadow:3px 3px 0 #16211C;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #16211C';this.style.transform='translate(2px,2px)'"
            onmouseout="this.style.boxShadow='3px 3px 0 #16211C';this.style.transform='none'">
            Ingresar
        </button>

        <p style="text-align:center;font-size:13px;color:#6B6B60;margin:4px 0 0">
            ¿Aún no tienes cuenta?
            <a href="{{ route('register') }}" style="font-weight:700;color:#C8452F">Crear una gratis</a>
        </p>
    </form>

</x-guest-layout>
