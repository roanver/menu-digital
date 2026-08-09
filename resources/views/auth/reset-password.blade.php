<x-guest-layout
    title="Nueva contraseña — MenuDigital"
    robots="noindex,nofollow">

    <h1 style="margin:0 0 6px;font-family:Fraunces,Georgia,serif;font-size:28px;font-weight:700;letter-spacing:-.02em;line-height:1.08">Creá una nueva contraseña</h1>
    <p style="margin:0 0 24px;font-size:14px;color:#4A4A42;line-height:1.5">Elegí una contraseña segura para tu cuenta.</p>

    <form method="POST" action="{{ route('password.store') }}" style="display:flex;flex-direction:column;gap:14px">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                placeholder="hola@mirestaurante.cl"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('email') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('email')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Nueva contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Repetí la contraseña"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password_confirmation') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('password_confirmation') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('password_confirmation')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:10px;background:#3E5A47;color:#F5F4EF;border:1.5px solid #16211C;box-shadow:3px 3px 0 #16211C;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #16211C';this.style.transform='translate(2px,2px)'"
            onmouseout="this.style.boxShadow='3px 3px 0 #16211C';this.style.transform='none'">
            Guardar nueva contraseña
        </button>
    </form>

</x-guest-layout>
