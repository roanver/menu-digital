<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MenuDigital — La carta de tu local, en el celular del cliente</title>
<meta name="description" content="Carta digital con QR y chip NFC para locales de comida. Te la dejamos instalada y andando. Puente Alto y alrededores.">
<meta property="og:type" content="website">
<meta property="og:title" content="MenuDigital — La carta de tu local, en el celular del cliente">
<meta property="og:description" content="Te la dejamos instalada y andando. Tú la manejas desde tu celular.">
<meta property="og:site_name" content="MenuDigital">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..600&family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $wsp    = preg_replace('/\D/', '', config('app.sales_whatsapp', '56900000000'));
    $demo   = config('app.demo_slug', 'demo');
    $wspMsg = rawurlencode('Hola! Vi la página de MenuDigital y quiero la carta para mi local.');
@endphp

<style>
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body {
    background: #F6F3EC;
    color: #12201A;
    font-family: Archivo, system-ui, sans-serif;
    font-size: 16px;
    line-height: 1.55;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
a { color: #157347; text-decoration: none; }
a:hover { color: #0E4F31; }
img { max-width: 100%; display: block; }
html { scroll-behavior: smooth; }
@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } * { animation: none !important; transition: none !important; } }
</style>
</head>
<body>

<!-- HEADER -->
<header style="position: sticky; top: 0; z-index: 60; background: rgba(246,243,236,0.88); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(18,32,26,0.08)">
  <div style="max-width: 1180px; margin: 0 auto; padding: 14px clamp(20px,5vw,48px); display: flex; align-items: center; justify-content: space-between; gap: 16px">
    <div style="display: flex; align-items: center; gap: 10px">
      <div style="width: 30px; height: 30px; border-radius: 9px; background: #157347; display: flex; align-items: center; justify-content: center; flex: 0 0 auto">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 3h11a3 3 0 0 1 3 3v15H7a3 3 0 0 1-3-3z" /><path d="M8 8h6M8 12h6M8 16h3" /></svg>
      </div>
      <span style="font-family: Lora, Georgia, serif; font-weight: 700; font-size: 20px; letter-spacing: -0.01em">MenuDigital</span>
    </div>
    <nav style="display: flex; align-items: center; gap: clamp(12px,2vw,28px)">
      <a href="#como" style="font-size: 14.5px; font-weight: 500; color: #3E4E45; text-decoration: none">Cómo funciona</a>
      <a href="#precios" style="font-size: 14.5px; font-weight: 500; color: #3E4E45; text-decoration: none">Precios</a>
      <a href="{{ route('login') }}" style="font-size: 14.5px; font-weight: 600; color: #12201A; text-decoration: none">Ingresar</a>
      <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" style="font-size: 14.5px; font-weight: 600; color: #fff; background: #157347; padding: 10px 18px; border-radius: 10px; box-shadow: 0 1px 2px rgba(14,79,49,0.4), inset 0 1px 0 rgba(255,255,255,0.18); text-decoration: none">Escribir</a>
    </nav>
  </div>
</header>

<!-- HERO -->
<section style="max-width: 1180px; margin: 0 auto; padding: clamp(40px,6vw,76px) clamp(20px,5vw,48px) clamp(48px,6vw,84px); display: flex; flex-wrap: wrap; align-items: center; gap: clamp(36px,5vw,72px)">
  <div style="flex: 1 1 420px; min-width: 300px">
    <div style="display: inline-flex; align-items: center; gap: 8px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #6C7C71; margin-bottom: 22px">
      <span style="width: 6px; height: 6px; border-radius: 50%; background: #157347; display: inline-block"></span>
      Puente Alto y alrededores
    </div>
    <h1 style="font-family: Lora, Georgia, serif; font-weight: 600; font-size: clamp(38px,5.4vw,60px); line-height: 1.04; letter-spacing: -0.025em; margin: 0 0 22px">La carta de tu local,<br>en el celular del cliente.</h1>
    <p style="font-size: clamp(16px,1.3vw,18.5px); line-height: 1.62; color: #46564C; max-width: 46ch; margin: 0 0 30px">Vamos a tu local, dejamos el menú cargado y los códigos puestos en las mesas. Después cambias precios desde tu celular, en diez segundos.</p>
    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 18px">
      <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" style="display: inline-flex; align-items: center; gap: 10px; background: #157347; color: #fff; font-size: 16px; font-weight: 600; padding: 15px 26px; border-radius: 12px; box-shadow: 0 2px 4px rgba(14,79,49,0.25), 0 8px 24px rgba(14,79,49,0.18), inset 0 1px 0 rgba(255,255,255,0.2); text-decoration: none; transition: background 150ms ease, transform 150ms ease" onmouseover="this.style.background='#0E4F31';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#157347';this.style.transform='none'">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="#fff"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2m5.8 14.06c-.25.7-1.44 1.34-1.98 1.39-.54.05-1.05.24-3.53-.98-2.48-1.22-4.04-3.77-4.16-3.94-.12-.17-.98-1.31-.98-2.5s.62-1.77.85-2.01c.23-.25.5-.31.66-.31s.34.01.49.01c.16 0 .37-.06.57.44.2.5.7 1.73.76 1.85.06.13.1.28.01.45-.09.17-.35.5-.51.67-.16.17-.26.24-.1.5.16.25.71 1.17 1.53 1.9 1.05.94 1.79 1.14 2.04 1.27.25.12.4.1.55-.06.15-.17.63-.73.8-.98.17-.25.34-.2.57-.12.23.09 1.45.69 1.7.81.25.13.41.19.47.29.06.11.06.63-.19 1.33" /></svg>
        Pedir la mía por WhatsApp
      </a>
      <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #FFFDF9; color: #12201A; font-size: 15px; font-weight: 600; padding: 13px 22px; border-radius: 12px; border: 1px solid rgba(18,32,26,0.16); box-shadow: 0 1px 2px rgba(18,32,26,0.05); text-decoration: none; transition: border-color 150ms ease" onmouseover="this.style.borderColor='rgba(18,32,26,0.4)'" onmouseout="this.style.borderColor='rgba(18,32,26,0.16)'">Probarlo gratis</a>
    </div>
    <p style="font-size: 13.5px; color: #6C7C71; margin: 0">Sin contrato. Sin comisión por venta. <a href="/{{ $demo }}" style="color:#46564C;text-decoration:underline;text-underline-offset:2px">Ver demo</a>.</p>
  </div>

  <!-- Columna derecha: foto + teléfono + placa QR -->
  <div style="flex: 1 1 430px; min-width: 300px; position: relative; display: flex; justify-content: center">
    <!-- Panel de foto del local -->
    <div style="position: relative; width: 100%; max-width: 470px; border-radius: 26px; overflow: hidden; background: #E7E1D5; aspect-ratio: 4 / 4.6">
      <img src="{{ asset('images/landing/hero-local.svg') }}" alt="Local con placas MenuDigital en las mesas" style="width: 100%; height: 100%; object-fit: cover; display: block">
      <div style="position: absolute; inset: 0; background: linear-gradient(190deg, rgba(18,32,26,0.10) 0%, rgba(18,32,26,0) 42%, rgba(18,32,26,0.34) 100%); pointer-events: none"></div>
    </div>

    <!-- Teléfono -->
    <div style="position: absolute; right: clamp(-6px,1vw,10px); bottom: -34px; width: clamp(228px,25vw,272px); border-radius: 42px; padding: 9px; background: linear-gradient(160deg,#3a3f3c,#101312 38%,#232827); box-shadow: 0 34px 60px -18px rgba(12,22,17,0.55), 0 6px 14px rgba(12,22,17,0.25), 0 0 0 1px rgba(255,255,255,0.08) inset">
      <!-- Botones laterales -->
      <div style="position: absolute; left: -2px; top: 108px; width: 3px; height: 26px; border-radius: 2px; background: #2b302e"></div>
      <div style="position: absolute; left: -2px; top: 146px; width: 3px; height: 44px; border-radius: 2px; background: #2b302e"></div>
      <div style="position: absolute; right: -2px; top: 132px; width: 3px; height: 62px; border-radius: 2px; background: #2b302e"></div>
      <!-- Pantalla -->
      <div style="position: relative; border-radius: 34px; overflow: hidden; background: #fff; aspect-ratio: 9 / 19.3; display: flex; flex-direction: column">
        <!-- Isla dinámica -->
        <div style="position: absolute; top: 8px; left: 50%; transform: translateX(-50%); width: 62px; height: 18px; border-radius: 12px; background: #0b0d0c; z-index: 30"></div>
        <!-- Barra de estado -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 9px 16px 4px; font-size: 10.5px; font-weight: 700; color: #12201A; letter-spacing: -0.01em; position: relative; z-index: 20">
          <span>9:41</span>
          <span style="display: flex; align-items: center; gap: 4px">
            <svg width="13" height="9" viewBox="0 0 19 12"><rect x="0" y="7.5" width="3.2" height="4.5" rx="0.7" fill="#12201A" /><rect x="4.8" y="5" width="3.2" height="7" rx="0.7" fill="#12201A" /><rect x="9.6" y="2.5" width="3.2" height="9.5" rx="0.7" fill="#12201A" /><rect x="14.4" y="0" width="3.2" height="12" rx="0.7" fill="#12201A" /></svg>
            <svg width="17" height="9" viewBox="0 0 27 13"><rect x="0.5" y="0.5" width="23" height="12" rx="3.5" stroke="#12201A" stroke-opacity="0.4" fill="none" /><rect x="2" y="2" width="16" height="9" rx="2" fill="#12201A" /></svg>
          </span>
        </div>
        <!-- Carta interior -->
        <div style="flex: 1; overflow: hidden; display: flex; flex-direction: column; background: #FFFDF9">
          <!-- Portada -->
          <div style="position: relative; height: 124px; flex: 0 0 auto; background: #E7E1D5">
            <img src="{{ asset('images/landing/portada-carta.svg') }}" alt="" style="width: 100%; height: 100%; object-fit: cover; display: block">
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(12,22,17,0.05), rgba(12,22,17,0.72)); pointer-events: none"></div>
            <div style="position: absolute; left: 12px; right: 12px; bottom: 9px; pointer-events: none">
              <div style="font-size: 7.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(255,255,255,0.72)">Av. Concha y Toro 1240</div>
              <div style="font-family: Lora, Georgia, serif; font-size: 17px; font-weight: 600; color: #fff; line-height: 1.15">La Picá del Tito</div>
            </div>
          </div>
          <!-- Chips de categoría -->
          <div style="display: flex; gap: 6px; padding: 9px 12px 7px; border-bottom: 1px solid rgba(18,32,26,0.08); flex: 0 0 auto">
            <span style="font-size: 8.5px; font-weight: 700; padding: 4px 9px; border-radius: 99px; background: #157347; color: #fff; white-space: nowrap">Para picar</span>
            <span style="font-size: 8.5px; font-weight: 600; padding: 4px 9px; border-radius: 99px; background: rgba(18,32,26,0.06); color: #46564C; white-space: nowrap">Del sartén</span>
            <span style="font-size: 8.5px; font-weight: 600; padding: 4px 9px; border-radius: 99px; background: rgba(18,32,26,0.06); color: #46564C; white-space: nowrap">Bebidas</span>
          </div>
          <!-- Ítems -->
          <div style="flex: 1; overflow: hidden; padding: 11px 12px 0; display: flex; flex-direction: column; gap: 9px">
            <div style="font-size: 8px; font-weight: 700; letter-spacing: 0.13em; text-transform: uppercase; color: #157347">Para picar</div>
            <div style="display: flex; align-items: baseline; gap: 6px"><span style="font-size: 11px; font-weight: 500">Empanada de pino</span><span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span><span style="font-size: 11px; font-weight: 700">$2.500</span></div>
            <div style="display: flex; align-items: baseline; gap: 6px"><span style="font-size: 11px; font-weight: 500">Papas bravas</span><span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span><span style="font-size: 11px; font-weight: 700">$4.900</span></div>
            <div style="display: flex; align-items: baseline; gap: 6px"><span style="font-size: 11px; font-weight: 500">Chorrillana chica</span><span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span><span style="font-size: 11px; font-weight: 700">$8.900</span></div>
            <div style="font-size: 8px; font-weight: 700; letter-spacing: 0.13em; text-transform: uppercase; color: #157347; margin-top: 4px">Del sartén</div>
            <div style="display: flex; align-items: baseline; gap: 6px"><span style="font-size: 11px; font-weight: 500">Churrasco italiano</span><span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span><span style="font-size: 11px; font-weight: 700">$7.000</span></div>
            <div style="display: flex; align-items: baseline; gap: 6px; opacity: 0.42"><span style="font-size: 11px; font-weight: 500">Lomo a lo pobre</span><span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span><span style="font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em">Agotado</span></div>
          </div>
          <!-- Botón WhatsApp -->
          <div style="padding: 10px 12px 12px; background: linear-gradient(180deg, rgba(255,253,249,0), #FFFDF9 42%); flex: 0 0 auto">
            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; background: #157347; color: #fff; font-size: 10.5px; font-weight: 700; padding: 9px; border-radius: 10px">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="#fff"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2m5.8 14.06c-.25.7-1.44 1.34-1.98 1.39-.54.05-1.05.24-3.53-.98-2.48-1.22-4.04-3.77-4.16-3.94-.12-.17-.98-1.31-.98-2.5s.62-1.77.85-2.01c.23-.25.5-.31.66-.31s.34.01.49.01c.16 0 .37-.06.57.44.2.5.7 1.73.76 1.85.06.13.1.28.01.45-.09.17-.35.5-.51.67-.16.17-.26.24-.1.5.16.25.71 1.17 1.53 1.9 1.05.94 1.79 1.14 2.04 1.27.25.12.4.1.55-.06.15-.17.63-.73.8-.98.17-.25.34-.2.57-.12.23.09 1.45.69 1.7.81.25.13.41.19.47.29.06.11.06.63-.19 1.33" /></svg>
              Pedir por WhatsApp
            </div>
          </div>
        </div>
        <!-- Barra Safari -->
        <div style="flex: 0 0 auto; background: rgba(247,247,247,0.96); border-top: 1px solid rgba(0,0,0,0.09); padding: 6px 12px 9px; display: flex; flex-direction: column; gap: 5px">
          <div style="display: flex; align-items: center; justify-content: center; gap: 4px; background: rgba(0,0,0,0.06); border-radius: 8px; padding: 4px 8px">
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#6C7C71" stroke-width="2.4"><rect x="4" y="10" width="16" height="11" rx="2" /><path d="M8 10V7a4 4 0 0 1 8 0v3" /></svg>
            <span style="font-size: 8.5px; color: #46564C">carta.menudigital.cl/tito</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 6px; color: rgba(18,32,26,0.35)">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M15 5l-7 7 7 7" /></svg>
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M9 5l7 7-7 7" /></svg>
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 16V4m0 0L8 8m4-4l4 4" /><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3" /></svg>
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="4" y="4" width="7" height="7" rx="1" /><rect x="13" y="4" width="7" height="7" rx="1" /><rect x="4" y="13" width="7" height="7" rx="1" /><rect x="13" y="13" width="7" height="7" rx="1" /></svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Placa QR decorativa -->
    <div style="position: absolute; left: clamp(-4px,1vw,12px); bottom: 22px; width: 132px; background: #FFFDF9; border-radius: 12px; padding: 12px 12px 10px; box-shadow: 0 18px 34px -12px rgba(12,22,17,0.4), 0 0 0 1px rgba(18,32,26,0.07)">
      <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; margin-bottom: 9px">
        <div style="grid-column: span 2; grid-row: span 2; border: 2px solid #12201A; border-radius: 2px"></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div style="grid-column: span 2; grid-row: span 2; border: 2px solid #12201A; border-radius: 2px"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div style="grid-column: span 2; grid-row: span 2; border: 2px solid #12201A; border-radius: 2px"></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
        <div></div>
        <div style="background: #12201A; aspect-ratio: 1"></div>
      </div>
      <div style="font-size: 9.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #157347">Mesa 4</div>
      <div style="font-size: 10.5px; color: #46564C; line-height: 1.35">Escanea y mira la carta</div>
    </div>
  </div>
</section>

<!-- FRANJA DE GARANTÍAS -->
<div style="border-top: 1px solid rgba(18,32,26,0.09); border-bottom: 1px solid rgba(18,32,26,0.09); background: #FFFDF9">
  <div style="max-width: 1180px; margin: 0 auto; padding: 22px clamp(20px,5vw,48px); display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 14px clamp(18px,3vw,40px); align-items: center">
    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #46564C">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.2" stroke-linecap="round" style="flex: 0 0 auto"><path d="M20 6L9 17l-5-5" /></svg>
      El cliente no descarga nada
    </div>
    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #46564C">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.2" stroke-linecap="round" style="flex: 0 0 auto"><path d="M20 6L9 17l-5-5" /></svg>
      Cero comisión por venta
    </div>
    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #46564C">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.2" stroke-linecap="round" style="flex: 0 0 auto"><path d="M20 6L9 17l-5-5" /></svg>
      Instalamos nosotros, en tu local
    </div>
    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #46564C">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.2" stroke-linecap="round" style="flex: 0 0 auto"><path d="M20 6L9 17l-5-5" /></svg>
      Cambios en el momento
    </div>
  </div>
</div>

<!-- CÓMO ES -->
<section id="como" style="background: #F6F3EC">
  <div style="max-width: 1180px; margin: 0 auto; padding: clamp(56px,7vw,96px) clamp(20px,5vw,48px)">
    <div style="font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #6C7C71; margin-bottom: 14px">Cómo es</div>
    <h2 style="font-family: Lora, Georgia, serif; font-weight: 600; font-size: clamp(30px,3.8vw,44px); letter-spacing: -0.02em; margin: 0 0 clamp(32px,4vw,52px); line-height: 1.1">Tres pasos y listo.</h2>
    <div style="display: flex; flex-wrap: wrap; gap: clamp(28px,4vw,56px)">
      <!-- Pasos -->
      <div style="flex: 1 1 440px; min-width: 300px; display: flex; flex-direction: column">
        <div style="display: flex; gap: 20px; padding: 22px 0; border-top: 1px solid rgba(18,32,26,0.12)">
          <div style="font-family: Lora, Georgia, serif; font-size: 26px; color: #A8B3AB; line-height: 1; width: 26px; flex: 0 0 auto">1</div>
          <div>
            <h3 style="font-family: Lora, Georgia, serif; font-size: 20px; font-weight: 600; margin: 0 0 6px">Nos envías una foto de tu carta</h3>
            <p style="font-size: 15px; line-height: 1.6; color: #46564C; margin: 0">La de papel, la del pizarrón, la que tengas. Nosotros la cargamos entera, con secciones y precios.</p>
          </div>
        </div>
        <div style="display: flex; gap: 20px; padding: 22px 0; border-top: 1px solid rgba(18,32,26,0.12)">
          <div style="font-family: Lora, Georgia, serif; font-size: 26px; color: #A8B3AB; line-height: 1; width: 26px; flex: 0 0 auto">2</div>
          <div>
            <h3 style="font-family: Lora, Georgia, serif; font-size: 20px; font-weight: 600; margin: 0 0 6px">Vamos y te la dejamos puesta</h3>
            <p style="font-size: 15px; line-height: 1.6; color: #46564C; margin: 0">Un código en cada mesa y uno en la caja. Instalado y probado, contigo mirando.</p>
          </div>
        </div>
        <div style="display: flex; gap: 20px; padding: 22px 0; border-top: 1px solid rgba(18,32,26,0.12); border-bottom: 1px solid rgba(18,32,26,0.12)">
          <div style="font-family: Lora, Georgia, serif; font-size: 26px; color: #A8B3AB; line-height: 1; width: 26px; flex: 0 0 auto">3</div>
          <div>
            <h3 style="font-family: Lora, Georgia, serif; font-size: 20px; font-weight: 600; margin: 0 0 6px">La manejas desde tu celular</h3>
            <p style="font-size: 15px; line-height: 1.6; color: #46564C; margin: 0">Subió el churrasco, se acabó el lomo: lo cambias y ya está en la mesa.</p>
          </div>
        </div>
      </div>
      <!-- Foto -->
      <div style="flex: 1 1 340px; min-width: 280px; max-width: 460px; display: flex; flex-direction: column; gap: 14px">
        <div style="position: relative; border-radius: 18px; overflow: hidden; background: #E7E1D5; aspect-ratio: 4 / 3.2">
          <img src="{{ asset('images/landing/como-es.svg') }}" alt="Carta de papel sobre la mesa" style="width: 100%; height: 100%; object-fit: cover; display: block">
        </div>
        <p style="font-size: 13.5px; color: #6C7C71; margin: 0; line-height: 1.5">Placa de acrílico en cada mesa. Si se raya o se pierde, te la reponemos sin costo.</p>
      </div>
    </div>
  </div>
</section>

<!-- DEMO INTERACTIVA -->
<section id="carta" style="background: #12201A; color: #F6F3EC"
  x-data="{
    precio: 7000,
    agotado: true,
    guardado: false,
    _t: null,
    fmt(n) { return '\$' + n.toLocaleString('es-CL'); },
    touch(patch) {
      Object.assign(this, patch);
      this.guardado = true;
      clearTimeout(this._t);
      this._t = setTimeout(() => { this.guardado = false; }, 1800);
    },
    bajar() { this.touch({ precio: Math.max(this.precio - 500, 4000) }); },
    subir() { this.touch({ precio: Math.min(this.precio + 500, 12000) }); },
    toggleAgotado() { this.touch({ agotado: !this.agotado }); }
  }">
  <div style="max-width: 1180px; margin: 0 auto; padding: clamp(56px,7vw,96px) clamp(20px,5vw,48px); display: flex; flex-wrap: wrap; gap: clamp(32px,5vw,72px); align-items: center">

    <!-- Panel izquierdo -->
    <div style="flex: 1 1 400px; min-width: 300px">
      <div style="font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #7FA791; margin-bottom: 14px">Pruébalo aquí</div>
      <h2 style="font-family: Lora, Georgia, serif; font-weight: 600; font-size: clamp(30px,3.8vw,44px); letter-spacing: -0.02em; line-height: 1.1; margin: 0 0 18px; color: #FFFDF9">Cambia el precio y mira la mesa.</h2>
      <p style="font-size: 16.5px; line-height: 1.62; color: rgba(246,243,236,0.72); max-width: 44ch; margin: 0 0 28px">A la izquierda, el panel que usas tú. A la derecha, el celular del cliente sentado en la mesa 4. Muévelo y fíjate en lo que pasa.</p>

      <!-- Panel de administración mock -->
      <div style="background: rgba(255,255,255,0.055); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 18px 18px 16px; max-width: 420px">
        <!-- Cabecera del panel -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1)">
          <span style="font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #7FA791">Panel · La Picá del Tito</span>
          <span style="font-size: 11.5px; font-weight: 600; white-space: nowrap; transition: color 200ms ease"
            :style="'color: ' + (guardado ? '#7FE0A8' : 'rgba(246,243,236,0.5)')"
            x-text="guardado ? 'Guardado ✓' : 'Todo al día'"></span>
        </div>

        <!-- Fila churrasco con stepper -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px">
          <span style="font-size: 15px; font-weight: 500; color: #FFFDF9">Churrasco italiano</span>
          <div style="display: flex; align-items: center; gap: 10px">
            <button @click="bajar()" style="width: 34px; height: 34px; border-radius: 9px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.06); color: #FFFDF9; font-size: 18px; font-family: Archivo, sans-serif; cursor: pointer; line-height: 1; transition: background 150ms ease" onmouseover="this.style.background='rgba(255,255,255,0.14)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">−</button>
            <span style="font-size: 16px; font-weight: 700; min-width: 74px; text-align: center; color: #FFFDF9; font-variant-numeric: tabular-nums" x-text="fmt(precio)"></span>
            <button @click="subir()" style="width: 34px; height: 34px; border-radius: 9px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.06); color: #FFFDF9; font-size: 18px; font-family: Archivo, sans-serif; cursor: pointer; line-height: 1; transition: background 150ms ease" onmouseover="this.style.background='rgba(255,255,255,0.14)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">+</button>
          </div>
        </div>

        <!-- Fila lomo con toggle -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px">
          <span style="font-size: 15px; font-weight: 500; color: #FFFDF9">Lomo a lo pobre</span>
          <button @click="toggleAgotado()" style="display: flex; align-items: center; gap: 9px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.06); border-radius: 99px; padding: 6px 12px 6px 8px; cursor: pointer; color: #FFFDF9; font-family: Archivo, sans-serif; transition: background 150ms ease" onmouseover="this.style.background='rgba(255,255,255,0.14)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
            <span style="width: 34px; height: 20px; border-radius: 99px; position: relative; display: block; transition: background 180ms ease"
              :style="'background: ' + (agotado ? '#C2402B' : '#3FA46A')">
              <span style="position: absolute; top: 2px; width: 16px; height: 16px; border-radius: 50%; background: #fff; transition: left 180ms ease"
                :style="'left: ' + (agotado ? '16px' : '2px')"></span>
            </span>
            <span style="font-size: 13px; font-weight: 600" x-text="agotado ? 'Agotado' : 'Disponible'"></span>
          </button>
        </div>
      </div>
    </div>

    <!-- Teléfono de la demo -->
    <div style="flex: 0 1 300px; min-width: 260px; display: flex; justify-content: center">
      <div style="width: 268px; border-radius: 44px; padding: 9px; background: linear-gradient(160deg,#3a3f3c,#101312 38%,#232827); box-shadow: 0 30px 60px -18px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.09) inset">
        <div style="border-radius: 36px; overflow: hidden; background: #FFFDF9; color: #12201A; aspect-ratio: 9 / 17.4; display: flex; flex-direction: column; position: relative">
          <!-- Isla dinámica -->
          <div style="position: absolute; top: 9px; left: 50%; transform: translateX(-50%); width: 68px; height: 20px; border-radius: 12px; background: #0b0d0c; z-index: 30"></div>
          <!-- Barra de estado -->
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 11px 18px 6px; font-size: 11px; font-weight: 700; color: #12201A; position: relative; z-index: 20">
            <span>9:41</span>
            <svg width="18" height="10" viewBox="0 0 27 13"><rect x="0.5" y="0.5" width="23" height="12" rx="3.5" stroke="#12201A" stroke-opacity="0.4" fill="none" /><rect x="2" y="2" width="16" height="9" rx="2" fill="#12201A" /></svg>
          </div>
          <!-- Portada -->
          <div style="position: relative; height: 118px; flex: 0 0 auto; background: #E7E1D5">
            <img src="{{ asset('images/landing/demo-portada.svg') }}" alt="" style="width: 100%; height: 100%; object-fit: cover; display: block">
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(12,22,17,0.05), rgba(12,22,17,0.74)); pointer-events: none"></div>
            <div style="position: absolute; left: 14px; right: 14px; bottom: 10px; pointer-events: none">
              <div style="font-size: 8px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(255,255,255,0.72)">Mesa 4 · Av. Concha y Toro 1240</div>
              <div style="font-family: Lora, Georgia, serif; font-size: 19px; font-weight: 600; color: #fff; line-height: 1.15">La Picá del Tito</div>
            </div>
          </div>
          <!-- Chips de categoría -->
          <div style="display: flex; gap: 6px; padding: 10px 14px 9px; border-bottom: 1px solid rgba(18,32,26,0.08); flex: 0 0 auto">
            <span style="font-size: 9px; font-weight: 600; padding: 4px 10px; border-radius: 99px; background: rgba(18,32,26,0.06); color: #46564C; white-space: nowrap">Para picar</span>
            <span style="font-size: 9px; font-weight: 700; padding: 4px 10px; border-radius: 99px; background: #157347; color: #fff; white-space: nowrap">Del sartén</span>
            <span style="font-size: 9px; font-weight: 600; padding: 4px 10px; border-radius: 99px; background: rgba(18,32,26,0.06); color: #46564C; white-space: nowrap">Bebidas</span>
          </div>
          <!-- Ítems reactivos -->
          <div style="flex: 1; padding: 13px 14px 0; display: flex; flex-direction: column; gap: 12px">
            <div style="font-size: 8px; font-weight: 700; letter-spacing: 0.13em; text-transform: uppercase; color: #157347">Del sartén</div>
            <div style="display: flex; align-items: baseline; gap: 7px">
              <span style="font-size: 12.5px; font-weight: 500">Churrasco italiano</span>
              <span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span>
              <span style="font-size: 12.5px; font-weight: 700; color: #12201A; font-variant-numeric: tabular-nums" x-text="fmt(precio)"></span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 7px; transition: opacity 180ms ease" :style="'opacity: ' + (agotado ? 0.42 : 1)">
              <span style="font-size: 12.5px; font-weight: 500">Lomo a lo pobre</span>
              <span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span>
              <span style="font-size: 12.5px; font-weight: 700; color: #12201A; font-variant-numeric: tabular-nums" x-text="agotado ? 'Agotado' : '$9.800'"></span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 7px">
              <span style="font-size: 12.5px; font-weight: 500">Pollo asado</span>
              <span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span>
              <span style="font-size: 12.5px; font-weight: 700; font-variant-numeric: tabular-nums">$6.500</span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 7px">
              <span style="font-size: 12.5px; font-weight: 500">Chuleta con arroz</span>
              <span style="flex: 1; border-bottom: 1px dotted rgba(18,32,26,0.22)"></span>
              <span style="font-size: 12.5px; font-weight: 700; font-variant-numeric: tabular-nums">$6.900</span>
            </div>
            <!-- Estado de actualización -->
            <div style="display: flex; align-items: center; gap: 5px; font-size: 9.5px; color: #6C7C71; margin-top: 2px">
              <span style="width: 5px; height: 5px; border-radius: 50%; background: #3FA46A; flex: 0 0 auto"></span>
              <span x-text="guardado ? 'Actualizado recién' : 'Actualizado hace 2 minutos'"></span>
            </div>
          </div>
          <!-- Botón WhatsApp -->
          <div style="padding: 10px 14px 12px; flex: 0 0 auto">
            <div style="display: flex; align-items: center; justify-content: center; gap: 7px; background: #157347; color: #fff; font-size: 11.5px; font-weight: 700; padding: 11px; border-radius: 11px">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="#fff"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2m5.8 14.06c-.25.7-1.44 1.34-1.98 1.39-.54.05-1.05.24-3.53-.98-2.48-1.22-4.04-3.77-4.16-3.94-.12-.17-.98-1.31-.98-2.5s.62-1.77.85-2.01c.23-.25.5-.31.66-.31s.34.01.49.01c.16 0 .37-.06.57.44.2.5.7 1.73.76 1.85.06.13.1.28.01.45-.09.17-.35.5-.51.67-.16.17-.26.24-.1.5.16.25.71 1.17 1.53 1.9 1.05.94 1.79 1.14 2.04 1.27.25.12.4.1.55-.06.15-.17.63-.73.8-.98.17-.25.34-.2.57-.12.23.09 1.45.69 1.7.81.25.13.41.19.47.29.06.11.06.63-.19 1.33" /></svg>
              Pedir por WhatsApp
            </div>
          </div>
          <!-- Barra de inicio (demo usa home indicator en lugar de nav Safari) -->
          <div style="flex: 0 0 auto; background: rgba(247,247,247,0.96); border-top: 1px solid rgba(0,0,0,0.09); padding: 7px 14px 10px; display: flex; flex-direction: column; gap: 6px">
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; background: rgba(0,0,0,0.06); border-radius: 8px; padding: 5px 8px">
              <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6C7C71" stroke-width="2.4"><rect x="4" y="10" width="16" height="11" rx="2" /><path d="M8 10V7a4 4 0 0 1 8 0v3" /></svg>
              <span style="font-size: 9.5px; color: #46564C">carta.menudigital.cl/tito</span>
            </div>
            <div style="display: flex; align-items: center; justify-content: center; margin-top: 1px">
              <span style="width: 92px; height: 4px; border-radius: 99px; background: rgba(18,32,26,0.22)"></span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- PRECIOS -->
<section id="precios" style="background: #FFFDF9">
  <div style="max-width: 1180px; margin: 0 auto; padding: clamp(56px,7vw,96px) clamp(20px,5vw,48px)">
    <div style="font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #6C7C71; margin-bottom: 14px">Precios</div>
    <h2 style="font-family: Lora, Georgia, serif; font-weight: 600; font-size: clamp(30px,3.8vw,44px); letter-spacing: -0.02em; line-height: 1.1; margin: 0 0 clamp(30px,4vw,48px)">Se paga una vez y después es poco.</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 20px">
      <!-- Tarjeta clara: Puesta en marcha -->
      <div style="flex: 1 1 300px; min-width: 270px; background: #F6F3EC; border: 1px solid rgba(18,32,26,0.1); border-radius: 18px; padding: 26px">
        <div style="font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #6C7C71; margin-bottom: 12px">Puesta en marcha</div>
        <div style="font-family: Lora, Georgia, serif; font-size: 38px; font-weight: 600; letter-spacing: -0.02em; margin-bottom: 4px">$25.000</div>
        <div style="font-size: 13.5px; color: #6C7C71; margin-bottom: 20px">una vez, y queda funcionando</div>
        <div style="display: flex; flex-direction: column; gap: 11px">
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: #33453B">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Carta completa cargada por nosotros
          </div>
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: #33453B">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Placas de acrílico para las mesas
          </div>
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: #33453B">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#157347" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Visita e instalación en el local
          </div>
        </div>
      </div>
      <!-- Tarjeta oscura: Mensual -->
      <div style="flex: 1 1 300px; min-width: 270px; background: #12201A; color: #FFFDF9; border-radius: 18px; padding: 26px; position: relative; box-shadow: 0 20px 40px -20px rgba(12,22,17,0.5)">
        <div style="font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #7FA791; margin-bottom: 12px">Mensual</div>
        <div style="font-family: Lora, Georgia, serif; font-size: 38px; font-weight: 600; letter-spacing: -0.02em; margin-bottom: 4px">$9.900</div>
        <div style="font-size: 13.5px; color: rgba(246,243,236,0.6); margin-bottom: 20px">al mes, lo cancelas cuando quieras</div>
        <div style="display: flex; flex-direction: column; gap: 11px; margin-bottom: 22px">
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: rgba(246,243,236,0.86)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7FA791" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Cambios ilimitados desde el celular
          </div>
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: rgba(246,243,236,0.86)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7FA791" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Pedidos derivados a tu WhatsApp
          </div>
          <div style="display: flex; gap: 10px; font-size: 14.5px; color: rgba(246,243,236,0.86)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7FA791" stroke-width="2.4" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 3px"><path d="M20 6L9 17l-5-5" /></svg>
            Letrero de reseñas de Google incluido
          </div>
        </div>
        <div style="font-size: 13px; color: rgba(246,243,236,0.55); padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.12)">Nunca cobramos comisión por lo que vendes.</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section id="contacto" style="background: #157347; color: #fff">
  <div style="max-width: 1180px; margin: 0 auto; padding: clamp(52px,6vw,84px) clamp(20px,5vw,48px); display: flex; flex-wrap: wrap; gap: 32px; align-items: center; justify-content: space-between">
    <div style="flex: 1 1 380px">
      <h2 style="font-family: Lora, Georgia, serif; font-weight: 600; font-size: clamp(28px,3.4vw,40px); letter-spacing: -0.02em; line-height: 1.12; margin: 0 0 12px; color: #fff">Envíanos la foto de tu carta y te decimos cuándo pasamos.</h2>
      <p style="font-size: 16px; line-height: 1.6; color: rgba(255,255,255,0.82); margin: 0; max-width: 46ch">Contestamos el mismo día. Si no te sirve, no pasa nada.</p>
    </div>
    <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start">
      <a href="https://wa.me/{{ $wsp }}?text={{ $wspMsg }}" style="display: inline-flex; align-items: center; gap: 10px; background: #FFFDF9; color: #0E4F31; font-size: 16.5px; font-weight: 700; padding: 16px 28px; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.14); text-decoration: none; transition: background 150ms ease, transform 150ms ease" onmouseover="this.style.background='#fff';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#FFFDF9';this.style.transform='none'">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="#0E4F31"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2m5.8 14.06c-.25.7-1.44 1.34-1.98 1.39-.54.05-1.05.24-3.53-.98-2.48-1.22-4.04-3.77-4.16-3.94-.12-.17-.98-1.31-.98-2.5s.62-1.77.85-2.01c.23-.25.5-.31.66-.31s.34.01.49.01c.16 0 .37-.06.57.44.2.5.7 1.73.76 1.85.06.13.1.28.01.45-.09.17-.35.5-.51.67-.16.17-.26.24-.1.5.16.25.71 1.17 1.53 1.9 1.05.94 1.79 1.14 2.04 1.27.25.12.4.1.55-.06.15-.17.63-.73.8-.98.17-.25.34-.2.57-.12.23.09 1.45.69 1.7.81.25.13.41.19.47.29.06.11.06.63-.19 1.33" /></svg>
        Escribir por WhatsApp
      </a>
      <span style="font-size: 13.5px; color: rgba(255,255,255,0.7)">o a hola@menudigital.cl</span>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer style="background: #12201A; color: rgba(246,243,236,0.55)">
  <div style="max-width: 1180px; margin: 0 auto; padding: 28px clamp(20px,5vw,48px); display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between; font-size: 13px">
    <span style="font-family: Lora, Georgia, serif; font-size: 16px; font-weight: 700; color: #F6F3EC">MenuDigital</span>
    <span>Puente Alto, Santiago · hola@menudigital.cl</span>
  </div>
</footer>

</body>
</html>
