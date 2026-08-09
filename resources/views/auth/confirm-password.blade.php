<x-guest-layout
    title="Confirmá tu contraseña — MenuDigital"
    robots="noindex,nofollow">

    <h1 style="margin:0 0 6px;font-family:Fraunces,Georgia,serif;font-size:28px;font-weight:700;letter-spacing:-.02em;line-height:1.08">Área segura</h1>
    <p style="margin:0 0 24px;font-size:14px;color:#4A4A42;line-height:1.5">Confirmá tu contraseña para continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}" style="display:flex;flex-direction:column;gap:14px">
        @csrf

        <div>
            <label for="password" style="display:block;font-size:12px;font-weight:700;color:#16211C;letter-spacing:.03em;text-transform:uppercase;margin-bottom:5px">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Tu contraseña"
                style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? '#C8452F' : '#16211C' }};border-radius:10px;font-size:14px;font-family:Archivo,system-ui,sans-serif;color:#16211C;background:#fff;outline:none"
                onfocus="this.style.borderColor='#C8452F';this.style.boxShadow='0 0 0 3px rgba(200,69,47,.1)'"
                onblur="this.style.borderColor='{{ $errors->has('password') ? '#C8452F' : '#16211C' }}';this.style.boxShadow='none'">
            @error('password')<p style="margin-top:5px;font-size:12px;font-weight:600;color:#C8452F">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
            style="width:100%;margin-top:4px;padding:13px;border-radius:10px;background:#3E5A47;color:#F5F4EF;border:1.5px solid #16211C;box-shadow:3px 3px 0 #16211C;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:box-shadow .1s,transform .1s"
            onmouseover="this.style.boxShadow='1px 1px 0 #16211C';this.style.transform='translate(2px,2px)'"
            onmouseout="this.style.boxShadow='3px 3px 0 #16211C';this.style.transform='none'">
            Confirmar
        </button>
    </form>

</x-guest-layout>
