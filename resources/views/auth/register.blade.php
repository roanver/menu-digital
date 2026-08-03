<x-guest-layout>

    <h1 style="margin:0;font-size:26px;font-weight:700;letter-spacing:-.028em">Crea tu menú digital</h1>
    <p style="margin:9px 0 0;font-size:13.5px;line-height:1.6;color:#6B7280">Dos minutos y tu carta queda publicada con su QR.</p>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:13px;margin-top:24px">
        @csrf

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Nombre del restaurante</span>
            <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required
                placeholder="El Fogón"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('restaurant_name') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('restaurant_name') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('restaurant_name')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Dirección de tu menú</span>
            <span style="display:flex;align-items:stretch;border:1px solid {{ $errors->has('restaurant_slug') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;overflow:hidden;background:#fff;box-shadow:0 1px 2px rgba(16,24,40,.03)" id="slug-wrap">
                <span style="display:flex;align-items:center;padding:0 10px;background:#F9FAFB;border-right:1px solid #E5E7EB;font-size:12.5px;color:#6B7280;white-space:nowrap">menudigital.cl/</span>
                <input type="text" name="restaurant_slug" value="{{ old('restaurant_slug') }}"
                    placeholder="el-fogon"
                    style="flex:1;min-width:0;padding:10px;border:none;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;background:transparent"
                    onfocus="document.getElementById('slug-wrap').style.borderColor='#4F46E5';document.getElementById('slug-wrap').style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                    onblur="document.getElementById('slug-wrap').style.borderColor='{{ $errors->has('restaurant_slug') ? '#EF4444' : '#E5E7EB' }}';document.getElementById('slug-wrap').style.boxShadow='none'">
            </span>
            <span style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:#047857">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                Se genera automáticamente si lo dejas vacío
            </span>
            @error('restaurant_slug')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Tu nombre</span>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Carolina Vera"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('name') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('name') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('name')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Correo</span>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="hola@elfogon.cl"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('email') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('email')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <label style="display:flex;flex-direction:column;gap:6px">
            <span style="font-size:12px;font-weight:600;color:#374151">Contraseña</span>
            <input type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('password') ? '#EF4444' : '#E5E7EB' }};border-radius:10px;font-size:13.5px;font-family:Inter,system-ui,sans-serif;color:#111827;outline:none;box-shadow:0 1px 2px rgba(16,24,40,.03)"
                onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.14)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#EF4444' : '#E5E7EB' }}';this.style.boxShadow='none'">
            @error('password')<span style="font-size:12px;font-weight:600;color:#EF4444">{{ $message }}</span>@enderror
        </label>

        <input type="hidden" name="password_confirmation" id="pw_confirm">

        <p style="margin:0;font-size:11.5px;line-height:1.55;color:#9CA3AF">
            Al crear la cuenta aceptas los <a href="#" style="color:#4F46E5">Términos</a> y la <a href="#" style="color:#4F46E5">Política de privacidad</a>.
        </p>

        <button type="submit"
            style="width:100%;padding:11px 16px;border-radius:11px;background:#4F46E5;color:#fff;border:1px solid #4338CA;font-size:14px;font-weight:600;cursor:pointer;box-shadow:0 1px 2px rgba(79,70,229,.35)"
            onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
            Crear cuenta gratis
        </button>
    </form>

    <p style="margin:20px 0 0;font-size:13px;color:#6B7280;text-align:center">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" style="font-weight:600;color:#4F46E5">Ingresar</a>
    </p>

    <script>
        // Mirror password to hidden confirmation field
        document.querySelector('[name="password"]').addEventListener('input', function() {
            document.getElementById('pw_confirm').value = this.value;
        });
    </script>

</x-guest-layout>
