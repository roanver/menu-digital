<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MenuDigital — Tu carta digital con QR en minutos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html,body{margin:0;padding:0;background:#fff;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        body{font-family:Inter,system-ui,sans-serif;color:#111827}
        a{text-decoration:none}
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header style="position:sticky;top:0;z-index:30;background:rgba(255,255,255,.86);backdrop-filter:blur(10px);border-bottom:1px solid #F3F4F6">
    <div style="max-width:1160px;margin:0 auto;padding:13px clamp(18px,3vw,28px);display:flex;align-items:center;gap:14px">
        <div style="display:flex;align-items:center;gap:9px;flex:0 0 auto">
            <div style="width:29px;height:29px;border-radius:9px;background:#4F46E5;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 2px rgba(79,70,229,.4)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span style="font-size:15px;font-weight:700;letter-spacing:-.015em;color:#111827">MenuDigital</span>
        </div>
        <nav style="flex:1;display:flex;justify-content:center;gap:22px">
            <a href="#como-funciona" style="font-size:13.5px;font-weight:500;color:#4B5563" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Cómo funciona</a>
            <a href="#plantillas" style="font-size:13.5px;font-weight:500;color:#4B5563" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Plantillas</a>
            <a href="#precios" style="font-size:13.5px;font-weight:500;color:#4B5563" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Precios</a>
        </nav>
        <div style="display:flex;align-items:center;gap:8px;flex:0 0 auto">
            <a href="{{ route('login') }}" style="padding:8px 13px;border-radius:10px;border:1px solid transparent;font-size:13px;font-weight:600;color:#374151" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">Ingresar</a>
            <a href="{{ route('register') }}" style="padding:9px 15px;border-radius:10px;background:#4F46E5;color:#fff;border:1px solid #4338CA;font-size:13px;font-weight:600;white-space:nowrap;box-shadow:0 1px 2px rgba(79,70,229,.35)" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">Crear menú gratis</a>
        </div>
    </div>
</header>

<!-- ===== HERO ===== -->
<section style="max-width:1160px;margin:0 auto;padding:clamp(40px,6vw,84px) clamp(18px,3vw,28px) clamp(30px,4vw,56px);display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:clamp(28px,4vw,60px);align-items:center">
    <div>
        <span style="display:inline-flex;align-items:center;gap:7px;font-size:11.5px;font-weight:600;color:#3730A3;background:#EEF2FF;border:1px solid #E0E7FF;border-radius:999px;padding:5px 11px">
            <span style="width:5px;height:5px;border-radius:50%;background:#4F46E5"></span>
            Usado por 1.200 restaurantes en Chile
        </span>
        <h1 style="margin:18px 0 0;font-size:clamp(34px,4.4vw,52px);line-height:1.06;font-weight:700;letter-spacing:-.035em">Tu carta, en un QR<br>que actualizas en 30 segundos</h1>
        <p style="margin:18px 0 0;max-width:490px;font-size:15px;line-height:1.65;color:#4B5563">Se acabó reimprimir la carta cada vez que sube un precio. Edita desde el celular, elige una plantilla y tus comensales ven el cambio al instante — con pedidos directos por WhatsApp.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:26px">
            <a href="{{ route('register') }}" style="padding:12px 20px;border-radius:11px;background:#4F46E5;color:#fff;border:1px solid #4338CA;font-size:14px;font-weight:600;box-shadow:0 1px 2px rgba(79,70,229,.35)" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">Crear mi menú gratis</a>
            <a href="/la-buena-mesa" style="display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:11px;background:#fff;color:#374151;border:1px solid #E5E7EB;font-size:14px;font-weight:600;box-shadow:0 1px 2px rgba(16,24,40,.04)" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='#fff'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2.5v2.5H14zM17.5 17.5H20V20h-2.5z"/></svg>
                Ver un menú de ejemplo
            </a>
        </div>
        <div style="display:flex;align-items:center;gap:18px;margin-top:22px;flex-wrap:wrap">
            @foreach(['Gratis hasta 20 platos','Sin tarjeta de crédito','Listo en 2 minutos'] as $p)
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:#6B7280">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                {{ $p }}
            </span>
            @endforeach
        </div>
    </div>

    <!-- Phone mockup -->
    <div style="display:flex;justify-content:center;position:relative">
        <div style="position:absolute;inset:6% 8%;background:radial-gradient(60% 60% at 50% 30%,rgba(79,70,229,.18),transparent 70%);filter:blur(28px)"></div>
        <div style="position:relative;width:290px;height:584px;border-radius:34px;background:#111827;padding:8px;box-shadow:0 26px 60px rgba(16,24,40,.28)">
            <div style="width:100%;height:100%;border-radius:27px;background:#F8FAFC;overflow:hidden;display:flex;flex-direction:column">
                <div style="background:#fff;padding:16px 15px 13px;border-bottom:1px solid #EEF0F4">
                    <div style="display:flex;align-items:center;gap:11px">
                        <div style="width:42px;height:42px;border-radius:12px;background:#EEF2FF;border:1px solid #E0E7FF;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#4F46E5;flex-shrink:0">EF</div>
                        <div>
                            <div style="font-size:17px;font-weight:700;letter-spacing:-.02em">El Fogón</div>
                            <div style="font-size:11px;color:#047857;font-weight:600;margin-top:3px">Abierto · Retiro y delivery</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:6px;padding:11px 15px;overflow:hidden">
                    <span style="font-size:10.5px;font-weight:600;padding:5px 11px;border-radius:999px;background:#4F46E5;color:#fff;white-space:nowrap">Todo</span>
                    <span style="font-size:10.5px;font-weight:600;padding:5px 11px;border-radius:999px;background:#fff;border:1px solid #E5E7EB;color:#4B5563;white-space:nowrap">Entradas</span>
                    <span style="font-size:10.5px;font-weight:600;padding:5px 11px;border-radius:999px;background:#fff;border:1px solid #E5E7EB;color:#4B5563;white-space:nowrap">Fondos</span>
                </div>
                <div style="flex:1;min-height:0;overflow:hidden;padding:2px 15px 15px;display:grid;grid-template-columns:1fr 1fr;gap:10px;align-content:start">
                    @foreach([['Lomo a lo pobre','$15.900'],['Pastel de choclo','$12.900'],['Ceviche de reineta','$9.500'],['Cazuela de vacuno','$11.500'],['Empanadas de pino','$6.900'],['Leche asada','$4.200']] as $c)
                    <div style="background:#fff;border:1px solid #EAECF0;border-radius:14px;overflow:hidden;box-shadow:0 1px 2px rgba(16,24,40,.05)">
                        <div style="height:66px;background:#F1F3F7;display:flex;align-items:center;justify-content:center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C3C8D2" stroke-width="1.6" stroke-linejoin="round"><path d="M3 16V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2ZM3 16l5-5 4 4 3-3 6 6"/></svg>
                        </div>
                        <div style="padding:8px 9px 9px">
                            <div style="font-size:11px;font-weight:600;line-height:1.3">{{ $c[0] }}</div>
                            <div style="display:flex;align-items:center;gap:6px;margin-top:6px">
                                <span style="flex:1;font-size:11.5px;font-weight:700;color:#4F46E5">{{ $c[1] }}</span>
                                <span style="width:20px;height:20px;border-radius:6px;background:#4F46E5;display:flex;align-items:center;justify-content:center">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:flex-end;padding:0 14px 16px">
                    <div style="width:44px;height:44px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(37,211,102,.4)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/><path d="M8.6 9.2c.4 2.4 2.4 4.4 4.8 4.9l1-1.4 2 1c-.4 1.4-2 1.9-3.3 1.5a8 8 0 0 1-5.2-5.2c-.4-1.3.1-2.9 1.5-3.3l1 2-1.8.5Z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CÓMO FUNCIONA ===== -->
<section id="como-funciona" style="border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;background:#F9FAFB">
    <div style="max-width:1160px;margin:0 auto;padding:clamp(34px,4.5vw,58px) clamp(18px,3vw,28px)">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:clamp(16px,2vw,26px)">
            @foreach([
                ['1','Carga tu carta','Crea categorías y platos con precio, foto y variantes. Si ya tienes la carta en PDF, la importamos por ti.'],
                ['2','Elige cómo se ve','Tres plantillas, tu color de marca y tu tipografía. El cambio se ve al instante, sin volver a imprimir el QR.'],
                ['3','Imprime y publica','Descarga el QR en PNG o SVG, o usa las plantillas listas para mesa, vitrina y adhesivo.'],
            ] as [$n,$title,$body])
            <div>
                <div style="width:32px;height:32px;border-radius:10px;background:#fff;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;font-size:12.5px;font-weight:700;color:#4F46E5;margin-bottom:13px;box-shadow:0 1px 2px rgba(16,24,40,.04)">{{ $n }}</div>
                <div style="font-size:15px;font-weight:700;letter-spacing:-.015em">{{ $title }}</div>
                <p style="margin:7px 0 0;font-size:13.5px;line-height:1.6;color:#6B7280">{{ $body }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section style="max-width:1160px;margin:0 auto;padding:clamp(40px,5vw,72px) clamp(18px,3vw,28px)">
    <div style="max-width:560px">
        <h2 style="margin:0;font-size:clamp(24px,2.8vw,32px);font-weight:700;letter-spacing:-.028em">Todo lo que necesita una carta digital</h2>
        <p style="margin:11px 0 0;font-size:14.5px;line-height:1.65;color:#6B7280">Sin apps que descargar, sin comisiones por pedido y sin depender de tu agencia para cambiar un precio.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px;margin-top:30px">
        @foreach([
            ['Cambios al instante','Subiste el precio del lomo a las 12:40; a las 12:41 ya está en todas las mesas.','M13 2 4.5 13H11l-1 9 8.5-11H12l1-9Z'],
            ['Pedidos por WhatsApp','El comensal arma su pedido y te llega al chat, sin comisiones por venta.','M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z'],
            ['Un QR para siempre','Cambia de plantilla o de carta completa: el código impreso sigue funcionando.','M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2.5v2.5H14zM17.5 17.5H20V20h-2.5z'],
            ['Sin plato agotado','Apaga un plato con un toque y desaparece del menú hasta que vuelva.','M6 6h12v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2ZM9 3h6M10 11v5M14 11v5'],
            ['Analítica de escaneos','Cuántos escanean, a qué hora y qué platos miran más.','M4 20V10M10 20V4M16 20v-7M22 20H2'],
            ['Varios locales','Administra las cartas de todas tus sucursales desde una sola cuenta.','M4 9h16v11H4zM3 9l2-5h14l2 5M10 20v-6h4v6'],
        ] as [$title,$body,$d])
        <div style="background:#fff;border:1px solid #E5E7EB;border-radius:14px;padding:18px;box-shadow:0 1px 2px rgba(16,24,40,.04)">
            <div style="width:34px;height:34px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;margin-bottom:13px">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $d }}"/></svg>
            </div>
            <div style="font-size:14px;font-weight:700;letter-spacing:-.01em">{{ $title }}</div>
            <p style="margin:6px 0 0;font-size:13px;line-height:1.6;color:#6B7280">{{ $body }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- ===== PLANTILLAS ===== -->
<section id="plantillas" style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(40px,5vw,72px)">
    <div style="background:#0F1020;border-radius:22px;padding:clamp(26px,3.4vw,44px);display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:clamp(22px,3vw,42px);align-items:center">
        <div>
            <h2 style="margin:0;color:#fff;font-size:clamp(22px,2.6vw,30px);font-weight:700;letter-spacing:-.028em">Tres plantillas. La misma carta.</h2>
            <p style="margin:12px 0 0;font-size:14px;line-height:1.65;color:#9CA3AF">Cambia de plantilla cuando quieras: la carta sigue siendo la misma y el QR impreso nunca cambia.</p>
            <div style="display:flex;flex-direction:column;gap:10px;margin-top:22px">
                @foreach([['Minimal','lista limpia, sin fotos','#fff'],['Dark','fondo oscuro, tarjetas','#818CF8'],['Cards','grilla con fotos','#34D399']] as [$name,$hint,$dot])
                <div style="display:flex;align-items:center;gap:11px">
                    <span style="width:9px;height:9px;border-radius:3px;flex-shrink:0;background:{{ $dot }}"></span>
                    <span style="font-size:13.5px;font-weight:600;color:#fff">{{ $name }}</span>
                    <span style="font-size:12.5px;color:#8A8A99">{{ $hint }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            <!-- Minimal preview -->
            <div style="width:132px;height:196px;border-radius:16px;background:#fff;padding:12px;display:flex;flex-direction:column;gap:7px">
                <div style="width:52%;height:7px;border-radius:3px;background:#111827"></div>
                <div style="width:32%;height:5px;border-radius:3px;background:#D1D5DB;margin-bottom:4px"></div>
                <div style="display:flex;gap:6px"><span style="flex:1;height:5px;border-radius:3px;background:#E5E7EB"></span><span style="width:20px;height:5px;border-radius:3px;background:#9CA3AF"></span></div>
                <div style="display:flex;gap:6px"><span style="flex:1;height:5px;border-radius:3px;background:#E5E7EB"></span><span style="width:20px;height:5px;border-radius:3px;background:#9CA3AF"></span></div>
                <div style="display:flex;gap:6px"><span style="flex:1;height:5px;border-radius:3px;background:#E5E7EB"></span><span style="width:20px;height:5px;border-radius:3px;background:#9CA3AF"></span></div>
                <div style="display:flex;gap:6px"><span style="flex:1;height:5px;border-radius:3px;background:#F3F4F6"></span><span style="width:20px;height:5px;border-radius:3px;background:#D1D5DB"></span></div>
            </div>
            <!-- Dark preview -->
            <div style="width:132px;height:196px;border-radius:16px;background:#0B0B0F;border:1px solid #26262F;padding:12px;display:flex;flex-direction:column;gap:7px">
                <div style="width:52%;height:7px;border-radius:3px;background:#fff"></div>
                <div style="width:34%;height:5px;border-radius:3px;background:#4B5563;margin-bottom:4px"></div>
                <div style="background:#17171F;border:1px solid #26262F;border-radius:7px;padding:7px;display:flex;gap:7px;align-items:center"><span style="width:18px;height:18px;border-radius:5px;background:#2A2A35;flex-shrink:0"></span><span style="flex:1;height:5px;border-radius:3px;background:#33333F"></span><span style="width:18px;height:5px;border-radius:3px;background:#818CF8"></span></div>
                <div style="background:#17171F;border:1px solid #26262F;border-radius:7px;padding:7px;display:flex;gap:7px;align-items:center"><span style="width:18px;height:18px;border-radius:5px;background:#2A2A35;flex-shrink:0"></span><span style="flex:1;height:5px;border-radius:3px;background:#33333F"></span><span style="width:18px;height:5px;border-radius:3px;background:#818CF8"></span></div>
                <div style="background:#17171F;border:1px solid #26262F;border-radius:7px;padding:7px;display:flex;gap:7px;align-items:center"><span style="width:18px;height:18px;border-radius:5px;background:#2A2A35;flex-shrink:0"></span><span style="flex:1;height:5px;border-radius:3px;background:#33333F"></span><span style="width:18px;height:5px;border-radius:3px;background:#818CF8"></span></div>
            </div>
            <!-- Cards preview -->
            <div style="width:132px;height:196px;border-radius:16px;background:#F8FAFC;padding:12px;display:grid;grid-template-columns:1fr 1fr;gap:8px;align-content:start">
                <div style="grid-column:1/-1;width:52%;height:7px;border-radius:3px;background:#111827"></div>
                <div style="background:#fff;border:1px solid #E5E7EB;border-radius:8px;padding:6px;display:flex;flex-direction:column;gap:5px"><span style="height:30px;border-radius:5px;background:#E5E7EB"></span><span style="width:80%;height:4px;border-radius:2px;background:#D1D5DB"></span><span style="width:44%;height:4px;border-radius:2px;background:#4F46E5"></span></div>
                <div style="background:#fff;border:1px solid #E5E7EB;border-radius:8px;padding:6px;display:flex;flex-direction:column;gap:5px"><span style="height:30px;border-radius:5px;background:#E5E7EB"></span><span style="width:68%;height:4px;border-radius:2px;background:#D1D5DB"></span><span style="width:44%;height:4px;border-radius:2px;background:#4F46E5"></span></div>
                <div style="background:#fff;border:1px solid #E5E7EB;border-radius:8px;padding:6px;display:flex;flex-direction:column;gap:5px"><span style="height:30px;border-radius:5px;background:#E5E7EB"></span><span style="width:74%;height:4px;border-radius:2px;background:#D1D5DB"></span><span style="width:44%;height:4px;border-radius:2px;background:#4F46E5"></span></div>
                <div style="background:#fff;border:1px solid #E5E7EB;border-radius:8px;padding:6px;display:flex;flex-direction:column;gap:5px"><span style="height:30px;border-radius:5px;background:#E5E7EB"></span><span style="width:60%;height:4px;border-radius:2px;background:#D1D5DB"></span><span style="width:44%;height:4px;border-radius:2px;background:#4F46E5"></span></div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRECIOS ===== -->
<section id="precios" style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(40px,5vw,72px)">
    <div style="max-width:560px;margin-bottom:26px">
        <h2 style="margin:0;font-size:clamp(24px,2.8vw,32px);font-weight:700;letter-spacing:-.028em">Precios claros, en pesos</h2>
        <p style="margin:11px 0 0;font-size:14.5px;line-height:1.65;color:#6B7280">Empieza gratis. Sube de plan cuando tu carta crezca; cancela cuando quieras.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px">
        @php
        $plans = [
            ['Free','$0','/mes',false,['Hasta 20 platos','1 plantilla','QR con marca MenuDigital'],'Empezar gratis','#E5E7EB','0 1px 2px rgba(16,24,40,.04)','#fff','#374151','#E5E7EB'],
            ['Pro','$14.900','/mes','Más elegido',['Platos y categorías ilimitados','3 plantillas + tu color','QR sin marca','Pedidos por WhatsApp'],'Probar Pro 14 días','#4F46E5','0 0 0 3px rgba(79,70,229,.13)','#4F46E5','#fff','#4338CA'],
            ['Business','$34.900','/mes',false,['Hasta 5 locales','Dominio propio','Analítica de escaneos','Soporte prioritario'],'Hablar con ventas','#E5E7EB','0 1px 2px rgba(16,24,40,.04)','#fff','#374151','#E5E7EB'],
        ];
        @endphp
        @foreach($plans as [$name,$price,$per,$tag,$features,$cta,$border,$shadow,$btnBg,$btnFg,$btnBorder])
        <div style="background:#fff;border-radius:16px;padding:20px;display:flex;flex-direction:column;gap:12px;border:1px solid {{ $border }};box-shadow:{{ $shadow }}">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:14px;font-weight:700">{{ $name }}</span>
                @if($tag)
                <span style="font-size:10px;font-weight:700;color:#4338CA;background:#EEF2FF;border-radius:5px;padding:2px 6px">{{ $tag }}</span>
                @endif
            </div>
            <div style="display:flex;align-items:baseline;gap:5px">
                <span style="font-size:30px;font-weight:700;letter-spacing:-.035em">{{ $price }}</span>
                <span style="font-size:12.5px;color:#9CA3AF">{{ $per }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:7px">
                @foreach($features as $ft)
                <div style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#374151;line-height:1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px"><path d="m5 13 4 4L19 7"/></svg>
                    <span>{{ $ft }}</span>
                </div>
                @endforeach
            </div>
            <div style="flex:1"></div>
            <a href="{{ route('register') }}" style="display:block;padding:10px 14px;border-radius:11px;font-size:13.5px;font-weight:600;text-align:center;background:{{ $btnBg }};color:{{ $btnFg }};border:1px solid {{ $btnBorder }}" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">{{ $cta }}</a>
        </div>
        @endforeach
    </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(44px,5vw,80px)">
    <div style="border:1px solid #E0E7FF;background:#EEF2FF;border-radius:20px;padding:clamp(26px,3.2vw,40px);display:flex;flex-wrap:wrap;gap:20px;align-items:center">
        <div style="flex:1;min-width:250px">
            <h2 style="margin:0;font-size:clamp(20px,2.4vw,27px);font-weight:700;letter-spacing:-.025em;color:#1E1B4B">Publica tu carta esta misma tarde</h2>
            <p style="margin:9px 0 0;font-size:14px;line-height:1.6;color:#4338CA">Crear la cuenta toma dos minutos y el QR queda listo para imprimir.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('register') }}" style="padding:12px 20px;border-radius:11px;background:#4F46E5;color:#fff;border:1px solid #4338CA;font-size:14px;font-weight:600;box-shadow:0 1px 2px rgba(79,70,229,.35)" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">Crear cuenta gratis</a>
            <a href="{{ route('login') }}" style="padding:12px 18px;border-radius:11px;background:#fff;color:#4338CA;border:1px solid #C7D2FE;font-size:14px;font-weight:600" onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='#fff'">Ya tengo cuenta</a>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer style="border-top:1px solid #F3F4F6">
    <div style="max-width:1160px;margin:0 auto;padding:22px clamp(18px,3vw,28px);display:flex;align-items:center;gap:14px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:180px">
            <div style="width:22px;height:22px;border-radius:7px;background:#4F46E5;display:flex;align-items:center;justify-content:center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span style="font-size:12.5px;color:#9CA3AF">© 2026 MenuDigital SpA · Santiago, Chile</span>
        </div>
        <div style="display:flex;gap:18px;flex-wrap:wrap">
            <a href="#" style="font-size:12.5px;color:#6B7280" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Términos</a>
            <a href="#" style="font-size:12.5px;color:#6B7280" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Privacidad</a>
            <a href="#" style="font-size:12.5px;color:#6B7280" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Soporte</a>
        </div>
    </div>
</footer>

</body>
</html>
