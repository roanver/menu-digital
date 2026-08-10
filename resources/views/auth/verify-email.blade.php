<x-guest-layout
    title="Verifica tu correo — MenuDigital"
    robots="noindex,nofollow">

    <h1 style="margin:0 0 6px;font-family:Fraunces,Georgia,serif;font-size:28px;font-weight:700;letter-spacing:-.02em;line-height:1.08">Verifica tu correo</h1>
    <p style="margin:0 0 24px;font-size:14px;color:#4A4A42;line-height:1.6">Te enviamos un link de verificación. Revisa tu bandeja de entrada y haz clic en el link para activar tu cuenta.</p>

    @if(session('status') == 'verification-link-sent')
    <div style="margin-bottom:20px;padding:11px 14px;border-radius:10px;background:#EEF5EE;border:1.5px solid #3E5A47;font-size:13px;font-weight:600;color:#16211C;">
        Te enviamos un nuevo link de verificación a tu correo.
    </div>
    @endif

    <div style="display:flex;flex-direction:column;gap:11px">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                style="width:100%;padding:13px;border-radius:10px;background:#3E5A47;color:#F5F4EF;border:1.5px solid #16211C;box-shadow:3px 3px 0 #16211C;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:box-shadow .1s,transform .1s"
                onmouseover="this.style.boxShadow='1px 1px 0 #16211C';this.style.transform='translate(2px,2px)'"
                onmouseout="this.style.boxShadow='3px 3px 0 #16211C';this.style.transform='none'">
                Reenviar link de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;padding:13px;border-radius:10px;background:transparent;color:#6B6B60;border:1.5px solid #D6D2C6;font-family:Archivo,system-ui,sans-serif;font-size:14px;font-weight:600;cursor:pointer;">
                Cerrar sesión
            </button>
        </form>
    </div>

</x-guest-layout>
