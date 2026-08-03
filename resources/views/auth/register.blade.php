<x-guest-layout>

    <h1 style="margin:0 0 6px;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:28px;font-weight:800;letter-spacing:-.03em;line-height:1.05">Crea tu menú digital</h1>
    <p style="margin:0 0 20px;font-size:14px;color:#5C5245;line-height:1.5">Dos minutos y tu carta queda publicada con su QR.</p>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:13px">
        @csrf

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Nombre del restaurante</label>
            <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required
                placeholder="El Fogón"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('restaurant_name') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('restaurant_name') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('restaurant_name')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Dirección del menú <span style="font-weight:500;color:#8A7B62">(opcional)</span></label>
            <div style="display:flex;align-items:stretch;border:1.5px solid {{ $errors->has('restaurant_slug') ? '#E85D2F' : '#201914' }};border-radius:12px;overflow:hidden;background:#fff" id="slug-container">
                <span style="display:flex;align-items:center;padding:0 12px;background:#FBF3E4;border-right:1.5px solid #201914;font-size:12.5px;color:#8A7B62;white-space:nowrap;flex-shrink:0">menudigital.cl/</span>
                <input type="text" name="restaurant_slug" value="{{ old('restaurant_slug') }}"
                    placeholder="mi-restaurante"
                    style="flex:1;padding:11px 12px;border:none;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none;min-width:0"
                    onfocus="document.getElementById('slug-container').style.borderColor='#E85D2F';document.getElementById('slug-container').style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                    onblur="document.getElementById('slug-container').style.borderColor='{{ $errors->has('restaurant_slug') ? '#E85D2F' : '#201914' }}';document.getElementById('slug-container').style.boxShadow='none'">
            </div>
            <p style="margin-top:5px;font-size:11.5px;color:#8A7B62">Se genera automáticamente si lo dejas vacío.</p>
            @error('restaurant_slug')<p style="margin-top:3px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Tu nombre</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Carolina Vera"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('name') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('name') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('name')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="hola@mirestaurante.cl"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('email')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#201914;letter-spacing:.02em;margin-bottom:5px">Contraseña</label>
            <input type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#E85D2F' : '#201914' }};border-radius:12px;font-size:14px;font-family:Inter,system-ui,sans-serif;color:#201914;background:#fff;outline:none"
                onfocus="this.style.borderColor='#E85D2F';this.style.boxShadow='0 0 0 3px rgba(232,93,47,.12)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#E85D2F' : '#201914' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#E85D2F">{{ $message }}</p>@enderror
        </div>

        <input type="hidden" name="password_confirmation" id="pw_confirm">

        <p style="margin:2px 0 0;font-size:11.5px;line-height:1.55;color:#8A7B62">
            Al crear la cuenta aceptas los <a href="#" style="color:#E85D2F">Términos</a> y la <a href="#" style="color:#E85D2F">Política de privacidad</a>.
        </p>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:999px;background:#E85D2F;color:#FFF6DE;border:1.5px solid #201914;box-shadow:4px 4px 0 #201914;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:14px;font-weight:800;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #201914';this.style.transform='translate(3px,3px)'"
            onmouseout="this.style.boxShadow='4px 4px 0 #201914';this.style.transform='none'">
            Crear cuenta gratis
        </button>

        <p style="text-align:center;font-size:13px;color:#5C5245;margin:4px 0 0">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" style="font-weight:700;color:#E85D2F">Ingresar</a>
        </p>
    </form>

    <script>
        document.querySelector('[name="password"]').addEventListener('input', function() {
            document.getElementById('pw_confirm').value = this.value;
        });
    </script>

</x-guest-layout>
