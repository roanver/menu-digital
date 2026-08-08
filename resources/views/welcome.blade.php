<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MenuDigital — La carta de tu local, en el celular del cliente</title>
<meta name="description" content="Carta digital con QR y chip NFC para locales de comida. Te la dejamos instalada y andando. Puente Alto y alrededores.">
<meta property="og:type" content="website">
<meta property="og:title" content="MenuDigital — La carta de tu local, en el celular del cliente">
<meta property="og:description" content="Te la dejamos instalada y andando. Vos la cambiás desde tu celular.">
<meta property="og:site_name" content="MenuDigital">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">

@php
    // Ajustá estos dos en config/app.php o .env
    $wsp  = preg_replace('/\D/', '', config('app.sales_whatsapp', '56900000000'));
    $demo = config('app.demo_slug', 'demo');
    $wspMsg = rawurlencode('Hola! Vi la página de MenuDigital y quiero la carta para mi local.');
@endphp

<style>
:root{
  --tinta:#16211C;      /* verde-negro, casi tinta */
  --papel:#F5F4EF;      /* papel de carta */
  --papel-2:#EDEBE3;
  --oliva:#3E5A47;      /* verde de toldo */
  --oliva-cl:#6E8A76;
  --aji:#C8452F;        /* solo para precios y destacados */
  --wsp:#128C4A;        /* el canal es el CTA */
  --linea:#D6D2C6;
}
*{box-sizing:border-box}
html,body{margin:0;padding:0}
body{
  background:var(--papel);color:var(--tinta);
  font-family:Archivo,system-ui,sans-serif;font-size:16px;line-height:1.55;
  -webkit-font-smoothing:antialiased;
}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3{font-family:Fraunces,Georgia,serif;font-weight:600;letter-spacing:-.015em;margin:0;line-height:1.08}
.wrap{max-width:600px;margin:0 auto;padding:0 22px}
.eyebrow{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--oliva)}
section{padding:56px 0}
.rule{height:1px;background:var(--linea);border:0;margin:0}

/* botones */
.btn{display:flex;align-items:center;justify-content:center;gap:9px;
  min-height:52px;padding:0 22px;border-radius:12px;font-weight:600;font-size:16px;
  border:1.5px solid var(--tinta);transition:transform .12s ease,box-shadow .12s ease}
.btn:active{transform:translateY(1px)}
.btn-wsp{background:var(--wsp);border-color:var(--wsp);color:#fff}
.btn-ghost{background:transparent;color:var(--tinta)}
a:focus-visible,button:focus-visible{outline:2.5px solid var(--aji);outline-offset:3px;border-radius:12px}

/* la firma: renglón de carta con puntos suspensivos */
.renglon{display:flex;align-items:baseline;gap:8px;padding:11px 0}
.renglon .nom{font-weight:500}
.renglon .pts{flex:1;border-bottom:1.5px dotted var(--linea);transform:translateY(-4px)}
.renglon .val{font-variant-numeric:tabular-nums;font-weight:700;color:var(--aji);white-space:nowrap}
.sub{font-size:13.5px;color:#6B6B60;margin-top:2px}

/* celular del hero */
.fono{width:250px;margin:0 auto;border:9px solid var(--tinta);border-radius:34px;
  background:#fff;overflow:hidden;box-shadow:0 18px 40px -18px rgba(22,33,28,.5)}
.fono-top{background:var(--oliva);color:#fff;padding:16px 16px 14px;text-align:center}
.fono-body{padding:6px 16px 18px}
.tag{display:inline-block;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  background:var(--papel-2);color:#6B6B60;padding:3px 8px;border-radius:5px}

@media (min-width:760px){
  .wrap{max-width:1040px}
  section{padding:76px 0}
  .dos{display:grid;grid-template-columns:1fr 300px;gap:56px;align-items:center}
  .cols{display:grid;grid-template-columns:1fr 1fr;gap:44px}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>

<!-- NAV -->
<header style="position:sticky;top:0;z-index:40;background:rgba(245,244,239,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--linea)">
  <div class="wrap" style="display:flex;align-items:center;gap:14px;padding-top:13px;padding-bottom:13px">
    <span style="font-family:Fraunces,serif;font-size:19px;font-weight:700;flex:1">MenuDigital</span>
    <a href="{{ route('login') }}" style="font-size:15px;font-weight:600;padding:10px 6px">Ingresar</a>
    <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" class="btn btn-wsp" style="min-height:42px;font-size:14.5px;padding:0 15px">Escribir</a>
  </div>
</header>

<!-- HERO -->
<section style="padding-top:40px">
  <div class="wrap dos">
    <div>
      <p class="eyebrow" style="margin:0 0 16px">Puente Alto y alrededores</p>
      <h1 style="font-size:clamp(34px,8vw,52px)">La carta de tu local,<br>en el celular del cliente.</h1>
      <p style="font-size:17.5px;color:#4A4A42;margin:20px 0 0;max-width:38ch">
        Vamos a tu local, te dejamos el menú cargado y los códigos puestos en las mesas.
        Después vos cambiás precios desde tu celular, en 10 segundos.
      </p>
      <div style="display:flex;flex-direction:column;gap:11px;margin-top:28px;max-width:340px">
        <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" class="btn btn-wsp">Pedir la mía por WhatsApp</a>
        <a href="/{{ $demo }}" class="btn btn-ghost">Ver una carta de verdad</a>
      </div>
      <p style="font-size:13.5px;color:#6B6B60;margin-top:14px">Sin contrato. Sin comisión por venta.</p>
    </div>

    <!-- SIGNATURE: la carta dentro del celular, con los renglones punteados -->
    <div style="margin-top:44px">
      <div class="fono">
        <div class="fono-top">
          <div style="font-size:9.5px;letter-spacing:.2em;text-transform:uppercase;opacity:.65">Av. Concha y Toro</div>
          <div style="font-family:Fraunces,serif;font-size:23px;margin-top:5px">La Picá del Tito</div>
        </div>
        <div class="fono-body">
          <p class="eyebrow" style="margin:16px 0 2px">Para picar</p>
          <div class="renglon"><span class="nom">Empanada de pino</span><span class="pts"></span><span class="val">$2.500</span></div>
          <div class="renglon"><span class="nom">Papas bravas</span><span class="pts"></span><span class="val">$4.900</span></div>
          <p class="eyebrow" style="margin:16px 0 2px">Del sartén</p>
          <div class="renglon"><span class="nom">Churrasco italiano</span><span class="pts"></span><span class="val">$7.000</span></div>
          <div class="renglon" style="opacity:.42">
            <span class="nom">Lomo a lo pobre <span class="tag">Agotado</span></span><span class="pts"></span><span class="val" style="color:#6B6B60">$9.800</span>
          </div>
          <div style="margin-top:16px;background:var(--wsp);color:#fff;text-align:center;font-size:13px;font-weight:600;padding:11px;border-radius:10px">
            Pedir por WhatsApp
          </div>
        </div>
      </div>
      <p style="text-align:center;font-size:12.5px;color:#6B6B60;margin-top:12px">Así se ve para tu cliente</p>
    </div>
  </div>
</section>

<hr class="rule">

<!-- CÓMO FUNCIONA -->
<section>
  <div class="wrap">
    <p class="eyebrow">Cómo es</p>
    <h2 style="font-size:clamp(26px,6vw,36px);margin-top:10px">Tres pasos y quedaste.</h2>

    <div class="cols" style="margin-top:34px">
      <div>
        <div style="display:flex;gap:16px;padding:18px 0;border-top:1px solid var(--linea)">
          <span style="font-family:Fraunces,serif;font-size:26px;color:var(--oliva-cl);line-height:1">1</span>
          <div>
            <h3 style="font-size:19px">Nos mandás una foto de tu carta</h3>
            <p class="sub">La de papel, la del pizarrón, la que tengas. Nosotros la cargamos entera.</p>
          </div>
        </div>
        <div style="display:flex;gap:16px;padding:18px 0;border-top:1px solid var(--linea)">
          <span style="font-family:Fraunces,serif;font-size:26px;color:var(--oliva-cl);line-height:1">2</span>
          <div>
            <h3 style="font-size:19px">Vamos y te la dejamos puesta</h3>
            <p class="sub">Un código en cada mesa y uno en la caja. Instalado y probado, con vos mirando.</p>
          </div>
        </div>
        <div style="display:flex;gap:16px;padding:18px 0;border-top:1px solid var(--linea);border-bottom:1px solid var(--linea)">
          <span style="font-family:Fraunces,serif;font-size:26px;color:var(--oliva-cl);line-height:1">3</span>
          <div>
            <h3 style="font-size:19px">La manejás desde tu celular</h3>
            <p class="sub">Subiste el precio del churrasco, se te acabó el lomo: lo cambiás y ya está en la mesa.</p>
          </div>
        </div>
      </div>

      <div style="background:var(--papel-2);border-radius:16px;padding:26px">
        <p class="eyebrow">Qué te queda</p>
        <div style="margin-top:10px">
          <div class="renglon" style="border-bottom:1px solid var(--linea)"><span class="nom">Carta digital</span><span class="pts"></span><span class="val" style="color:var(--oliva)">✓</span></div>
          <div class="renglon" style="border-bottom:1px solid var(--linea)"><span class="nom">Códigos en las mesas</span><span class="pts"></span><span class="val" style="color:var(--oliva)">✓</span></div>
          <div class="renglon" style="border-bottom:1px solid var(--linea)"><span class="nom">Letrero de reseñas Google</span><span class="pts"></span><span class="val" style="color:var(--oliva)">✓</span></div>
          <div class="renglon"><span class="nom">Pedidos a tu WhatsApp</span><span class="pts"></span><span class="val" style="color:var(--oliva)">✓</span></div>
        </div>
        <p class="sub" style="margin-top:14px">Tu cliente no descarga nada. Acerca el celular y aparece.</p>
      </div>
    </div>
  </div>
</section>

<hr class="rule">

<!-- PLANES, servidos como carta -->
<section>
  <div class="wrap" style="max-width:620px">
    <p class="eyebrow">Precios</p>
    <h2 style="font-size:clamp(26px,6vw,36px);margin-top:10px">Nuestra carta.</h2>
    <p class="sub" style="margin-top:12px;font-size:15px">Instalación aparte, según cuántas mesas tengas. Se paga una sola vez.</p>

    <div style="margin-top:30px">
      <div style="border-top:1px solid var(--linea);padding-top:6px">
        <div class="renglon"><span class="nom" style="font-size:18px">Carta</span><span class="pts"></span><span class="val">$8.000<span style="font-weight:500;color:#6B6B60;font-size:13px"> /mes</span></span></div>
        <p class="sub" style="margin:0 0 14px">Tu menú, los códigos y el botón de pedidos por WhatsApp.</p>
      </div>
      <div style="border-top:1px solid var(--linea);padding-top:6px">
        <div class="renglon"><span class="nom" style="font-size:18px">Pedidos</span><span class="pts"></span><span class="val">$15.000<span style="font-weight:500;color:#6B6B60;font-size:13px"> /mes</span></span></div>
        <p class="sub" style="margin:0 0 14px">Los pedidos te llegan ordenados, con su estado. Más las publicaciones para tus redes.</p>
      </div>
      <div style="border-top:1px solid var(--linea);border-bottom:1px solid var(--linea);padding-top:6px">
        <div class="renglon"><span class="nom" style="font-size:18px">Full</span><span class="pts"></span><span class="val">$25.000<span style="font-weight:500;color:#6B6B60;font-size:13px"> /mes</span></span></div>
        <p class="sub" style="margin:0 0 14px">Todo lo anterior más los reportes de lo que más se vende.</p>
      </div>
    </div>

    <p style="font-size:14px;color:#6B6B60;margin-top:20px">
      No cobramos comisión por lo que vendas. No hay permanencia: si no te sirve, avisás y listo.
    </p>
  </div>
</section>

<hr class="rule">

<!-- QUIÉNES SOMOS / OBJECIONES -->
<section>
  <div class="wrap">
    <div class="cols">
      <div>
        <p class="eyebrow">Quiénes somos</p>
        <h2 style="font-size:clamp(24px,5.5vw,30px);margin-top:10px">Somos de acá.</h2>
        <p style="color:#4A4A42;margin-top:14px">
          No somos una empresa de Santiago centro que te atiende por formulario.
          Vamos a tu local, te lo instalamos y te dejamos un WhatsApp donde contesta una persona.
        </p>
        <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" class="btn btn-wsp" style="margin-top:22px;max-width:320px">Escribinos</a>
      </div>

      <div>
        <p class="eyebrow">Dudas de siempre</p>
        <div style="margin-top:12px">
          <div style="border-top:1px solid var(--linea);padding:16px 0">
            <h3 style="font-size:16.5px">¿Mi cliente tiene que bajar una app?</h3>
            <p class="sub">No. Acerca el celular o escanea el código y se abre solo.</p>
          </div>
          <div style="border-top:1px solid var(--linea);padding:16px 0">
            <h3 style="font-size:16.5px">No soy bueno con la tecnología.</h3>
            <p class="sub">Se cambia un precio en dos toques. Te enseñamos ahí mismo y quedás con nuestro WhatsApp.</p>
          </div>
          <div style="border-top:1px solid var(--linea);padding:16px 0">
            <h3 style="font-size:16.5px">¿Se cobra comisión por pedido?</h3>
            <p class="sub">No. Los pedidos llegan a tu WhatsApp y la plata es tuya completa.</p>
          </div>
          <div style="border-top:1px solid var(--linea);border-bottom:1px solid var(--linea);padding:16px 0">
            <h3 style="font-size:16.5px">¿Y si me cambio de local?</h3>
            <p class="sub">Los códigos siguen sirviendo. Cambiamos la dirección y listo, no hay que imprimir nada.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CIERRE -->
<section style="background:var(--tinta);color:var(--papel);text-align:center">
  <div class="wrap" style="max-width:520px">
    <h2 style="font-size:clamp(27px,6.5vw,38px);color:var(--papel)">¿Lo vemos en tu local?</h2>
    <p style="color:#A9B2AB;margin-top:14px">Escribinos y coordinamos. Vamos, lo instalamos y lo probás antes de decidir.</p>
    <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" class="btn btn-wsp" style="margin:26px auto 0;max-width:330px">Escribir por WhatsApp</a>
    <a href="/{{ $demo }}" style="display:inline-block;margin-top:18px;font-size:15px;color:#A9B2AB;border-bottom:1px solid #4A5A50;padding-bottom:2px">Ver una carta de verdad</a>
  </div>
</section>

<footer style="background:var(--tinta);color:#7E8A83;border-top:1px solid #2A3830">
  <div class="wrap" style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;padding-top:22px;padding-bottom:30px;font-size:13.5px">
    <span style="flex:1;min-width:180px">MenuDigital · Puente Alto, Chile</span>
    <a href="{{ route('login') }}" style="color:var(--papel)">Ingresar</a>
    <a href="{{ route('register') }}" style="color:#7E8A83">Crear cuenta</a>
  </div>
</footer>

</body>
</html>