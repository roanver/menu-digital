<x-guest-layout
    title="Crear cuenta gratis — MenuDigital"
    description="Publica la carta digital de tu restaurante en 2 minutos. QR y NFC incluidos, sin comisión por venta."
    robots="noindex,nofollow">

    <h1 style="margin:0 0 6px;font-family:Fraunces,Georgia,serif;font-size:28px;font-weight:700;letter-spacing:-.02em;line-height:1.08">Crea tu carta digital</h1>
    <p style="margin:0 0 20px;font-size:14px;color:#4A4A42;line-height:1.5">Dos minutos y tu menú queda publicado con QR incluido.</p>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:13px">
        @csrf

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Nombre de tu negocio</label>
            <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required
                placeholder="El Fogón, Mi Tienda…"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('restaurant_name') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('restaurant_name') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('restaurant_name')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Dirección del menú <span style="font-weight:400;color:#6B6B60;text-transform:none">(opcional)</span></label>
            <div style="display:flex;align-items:stretch;border:1.5px solid {{ $errors->has('restaurant_slug') ? '#C8452F' : '#16211C' }};border-radius:10px;overflow:hidden;background:#fff" id="slug-container">
                <span style="display:flex;align-items:center;padding:0 12px;background:#EDEBE3;border-right:1.5px solid #16211C;font-size:12.5px;color:#6B6B60;white-space:nowrap;flex-shrink:0">menudigital.cl/</span>
                <input type="text" name="restaurant_slug" value="{{ old('restaurant_slug') }}"
                    placeholder="mi-negocio"
                    style="flex:1;padding:11px 12px;border:none;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none;min-width:0"
                    onfocus="document.getElementById('slug-container').style.borderColor='#C8452F';document.getElementById('slug-container').style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                    onblur="document.getElementById('slug-container').style.borderColor='{{ $errors->has('restaurant_slug') ? '#C8452F' : '#16211C' }}';document.getElementById('slug-container').style.boxShadow='none'">
            </div>
            <p style="margin-top:5px;font-size:11.5px;color:#6B6B60">Se genera automáticamente si lo dejas vacío.</p>
            @error('restaurant_slug')<p style="margin-top:3px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Tu nombre</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Carolina Vera"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('name') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('name') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('name')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="hola@minegocio.cl"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('email')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Contraseña</label>
            <input type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <input type="hidden" name="password_confirmation" id="pw_confirm">

        <p style="margin:2px 0 0;font-size:11.5px;line-height:1.55;color:#6B6B60">
            Al crear la cuenta aceptas los <a href="#" style="color:#C8452F">Términos</a> y la <a href="#" style="color:#C8452F">Política de privacidad</a>.
        </p>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:10px;background:#3E5A47;color:#F5F4EF;border:1.5px solid #16211C;box-shadow:3px 3px 0 #16211C;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #16211C';this.style.transform='translate(2px,2px)'"
            onmouseout="this.style.boxShadow='3px 3px 0 #16211C';this.style.transform='none'">
            Crear cuenta gratis
        </button>

        <p style="text-align:center;font-size:13px;color:#6B6B60;margin:4px 0 0">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" style="font-weight:700;color:#C8452F">Ingresar</a>
        </p>
    </form>

    <script>
        document.querySelector('[name="password"]').addEventListener('input', function() {
            document.getElementById('pw_confirm').value = this.value;
        });
    </script>

</x-guest-layout>
