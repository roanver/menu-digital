<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MenuDigital — Tu carta digital con QR en minutos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Bricolage+Grotesque:wght@500;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html,body{margin:0;padding:0;background:#FBF3E4;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        body{font-family:Inter,system-ui,sans-serif;color:#201914}
        a{text-decoration:none}
        @keyframes md-marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        @keyframes md-float{0%,100%{transform:translateY(0) rotate(var(--r,0deg))}50%{transform:translateY(-12px) rotate(var(--r,0deg))}}
        @keyframes md-steam{0%{transform:translateY(14px) scaleX(1);opacity:0}35%{opacity:.75}100%{transform:translateY(-84px) scaleX(1.5);opacity:0}}
        @keyframes md-spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
        @keyframes md-sizzle{0%,100%{transform:scale(1)}50%{transform:scale(1.04)}}
    </style>
</head>
<body>

<!-- ═══ NAV ═══ -->
<header style="position:sticky;top:0;z-index:50;background:rgba(251,243,228,.88);backdrop-filter:blur(12px);border-bottom:1.5px solid #201914">
    <div style="max-width:1180px;margin:0 auto;padding:13px clamp(18px,3vw,28px);display:flex;align-items:center;gap:14px">
        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
            <div style="width:34px;height:34px;border-radius:11px;background:#E85D2F;border:1.5px solid #201914;box-shadow:2.5px 2.5px 0 #201914;display:flex;align-items:center;justify-content:center;transform:rotate(-5deg);flex-shrink:0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:17px;font-weight:800;letter-spacing:-.025em">MenuDigital</span>
        </div>
        <nav class="hidden md:flex" style="gap:2px">
            <a href="#como-funciona" style="font-size:13px;font-weight:600;color:#201914;padding:8px 12px;border-radius:999px" onmouseover="this.style.background='#F1E4CB'" onmouseout="this.style.background='transparent'">Cómo funciona</a>
            <a href="#plantillas" style="font-size:13px;font-weight:600;color:#201914;padding:8px 12px;border-radius:999px" onmouseover="this.style.background='#F1E4CB'" onmouseout="this.style.background='transparent'">Plantillas</a>
            <a href="#precios" style="font-size:13px;font-weight:600;color:#201914;padding:8px 12px;border-radius:999px" onmouseover="this.style.background='#F1E4CB'" onmouseout="this.style.background='transparent'">Precios</a>
        </nav>
        <div style="display:flex;align-items:center;gap:9px">
            <a href="{{ route('login') }}" style="font-size:13px;font-weight:700;color:#201914;padding:9px 13px;border-radius:999px" onmouseover="this.style.background='#F1E4CB'" onmouseout="this.style.background='transparent'">Ingresar</a>
            <a href="{{ route('register') }}" style="font-size:13px;font-weight:800;color:#FFD84D;background:#201914;border:1.5px solid #201914;box-shadow:3px 3px 0 rgba(32,25,20,.28);padding:9px 16px;border-radius:999px;white-space:nowrap" onmouseover="this.style.boxShadow='1px 1px 0 #201914';this.style.transform='translate(2px,2px)';this.style.color='#fff'" onmouseout="this.style.boxShadow='3px 3px 0 rgba(32,25,20,.28)';this.style.transform='none';this.style.color='#FFD84D'">Crear menú gratis</a>
        </div>
    </div>
</header>

<!-- ═══ HERO ═══ -->
<section style="position:relative;max-width:1180px;margin:0 auto;padding:clamp(36px,5vw,72px) clamp(18px,3vw,28px) clamp(30px,4vw,60px);display:grid;grid-template-columns:repeat(auto-fit,minmax(330px,1fr));gap:clamp(24px,3vw,40px);align-items:center">
    <div style="position:relative;z-index:2">
        <div style="display:inline-flex;align-items:center;gap:8px;font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#201914;background:#FFD84D;border:1.5px solid #201914;border-radius:999px;padding:6px 13px;transform:rotate(-1.5deg);box-shadow:2px 2px 0 #201914">
            <span style="width:6px;height:6px;border-radius:50%;background:#201914"></span>
            1.200 restaurantes en Chile
        </div>
        <h1 style="margin:22px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:clamp(40px,5.6vw,68px);line-height:.98;font-weight:800;letter-spacing:-.035em">Se acabó<br>reimprimir<br>la carta<span style="color:#E85D2F">.</span></h1>
        <p style="margin:20px 0 0;max-width:440px;font-size:16px;line-height:1.6;color:#5C5245">Subió la reineta, se acabó el congrio, llegó el menú de invierno. <span style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:17.5px;color:#201914">Cámbialo desde la caja</span> y en la mesa se ve al tiro — el QR impreso es el mismo para siempre.</p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:28px;align-items:center">
            <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:9px;font-size:14.5px;font-weight:700;color:#FFF6DE;background:#E85D2F;border:1.5px solid #201914;box-shadow:4px 4px 0 #201914;padding:14px 22px;border-radius:999px" onmouseover="this.style.boxShadow='1px 1px 0 #201914';this.style.transform='translate(3px,3px)'" onmouseout="this.style.boxShadow='4px 4px 0 #201914';this.style.transform='none'">
                Crear mi menú gratis
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </a>
            <a href="/la-buena-mesa" style="display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;color:#201914;border-bottom:2px solid #201914;padding-bottom:2px" onmouseover="this.style.color='#E85D2F';this.style.borderColor='#E85D2F'" onmouseout="this.style.color='#201914';this.style.borderColor='#201914'">Ver un menú real</a>
        </div>
        <div style="display:flex;gap:16px;margin-top:24px;flex-wrap:wrap">
            <span style="font-size:12.5px;font-weight:600;color:#8A7B62">✶ Gratis hasta 20 platos</span>
            <span style="font-size:12.5px;font-weight:600;color:#8A7B62">✶ Sin tarjeta</span>
            <span style="font-size:12.5px;font-weight:600;color:#8A7B62">✶ Listo en 2 minutos</span>
        </div>
    </div>

    <!-- Composición: sartén + stickers -->
    <div style="position:relative;min-height:480px;display:flex;align-items:center;justify-content:center">
        <div style="position:absolute;width:430px;height:430px;border-radius:50%;background:radial-gradient(circle,rgba(232,93,47,.16),transparent 65%)"></div>

        <!-- Sartén -->
        <div style="position:relative;width:330px;height:330px;animation:md-sizzle 5s ease-in-out infinite">
            <div style="position:absolute;right:-86px;top:142px;width:120px;height:36px;border-radius:12px 20px 20px 12px;background:linear-gradient(180deg,#2B2622,#171310);border:1.5px solid #201914;box-shadow:0 10px 18px rgba(32,25,20,.25)"></div>
            <div style="position:absolute;inset:0;border-radius:50%;background:radial-gradient(circle at 34% 28%,#3C3630,#1B1714 72%);border:1.5px solid #201914;box-shadow:0 24px 44px rgba(32,25,20,.35),inset 0 -6px 18px rgba(0,0,0,.5)"></div>
            <div style="position:absolute;inset:26px;border-radius:50%;background:radial-gradient(circle at 40% 32%,#2E2823,#211C18 70%);box-shadow:inset 0 6px 16px rgba(0,0,0,.55)"></div>
            <!-- papas fritas -->
            <div style="position:absolute;left:52px;top:176px;width:86px;height:17px;border-radius:8px;background:linear-gradient(180deg,#F8CE62,#E9A93B);border:1px solid rgba(120,70,10,.4);transform:rotate(-18deg)"></div>
            <div style="position:absolute;left:66px;top:196px;width:96px;height:17px;border-radius:8px;background:linear-gradient(180deg,#FAD97E,#EFB23F);border:1px solid rgba(120,70,10,.4);transform:rotate(-6deg)"></div>
            <div style="position:absolute;left:50px;top:216px;width:78px;height:16px;border-radius:8px;background:linear-gradient(180deg,#F6C453,#E39F2F);border:1px solid rgba(120,70,10,.4);transform:rotate(9deg)"></div>
            <div style="position:absolute;left:120px;top:212px;width:70px;height:15px;border-radius:8px;background:linear-gradient(180deg,#F8CE62,#E9A93B);border:1px solid rgba(120,70,10,.4);transform:rotate(24deg)"></div>
            <!-- huevos fritos -->
            <div style="position:absolute;left:132px;top:64px;width:128px;height:112px;background:#FFF9EF;border-radius:52% 48% 58% 42%/55% 50% 50% 45%;box-shadow:inset 0 -4px 10px rgba(200,160,90,.28),0 4px 10px rgba(0,0,0,.3)">
                <div style="position:absolute;left:38px;top:30px;width:48px;height:48px;border-radius:50%;background:radial-gradient(circle at 34% 30%,#FFCF57,#F59E0B 68%,#D97706);box-shadow:inset 0 -3px 6px rgba(160,80,0,.4)">
                    <div style="position:absolute;left:9px;top:7px;width:14px;height:9px;border-radius:50%;background:rgba(255,255,255,.55);transform:rotate(-24deg)"></div>
                </div>
            </div>
            <div style="position:absolute;left:74px;top:96px;width:104px;height:92px;background:#FFF6E8;border-radius:46% 54% 44% 56%/52% 46% 54% 48%;box-shadow:inset 0 -4px 10px rgba(200,160,90,.28),0 4px 10px rgba(0,0,0,.3)">
                <div style="position:absolute;left:30px;top:24px;width:40px;height:40px;border-radius:50%;background:radial-gradient(circle at 34% 30%,#FFCF57,#F59E0B 68%,#D97706);box-shadow:inset 0 -3px 6px rgba(160,80,0,.4)">
                    <div style="position:absolute;left:7px;top:6px;width:12px;height:8px;border-radius:50%;background:rgba(255,255,255,.55);transform:rotate(-24deg)"></div>
                </div>
            </div>
            <!-- cebolla -->
            <div style="position:absolute;left:196px;top:180px;width:58px;height:12px;border-radius:999px;background:transparent;border:3.5px solid rgba(245,222,179,.85);border-bottom-color:transparent;transform:rotate(14deg)"></div>
            <div style="position:absolute;left:186px;top:200px;width:48px;height:11px;border-radius:999px;border:3px solid rgba(245,222,179,.75);border-bottom-color:transparent;transform:rotate(-10deg)"></div>
            <!-- vapor -->
            <div style="position:absolute;left:118px;top:-38px;width:9px;height:84px;border-radius:999px;background:linear-gradient(180deg,transparent,rgba(255,250,240,.85),transparent);filter:blur(5px);animation:md-steam 3.2s ease-in-out infinite"></div>
            <div style="position:absolute;left:168px;top:-46px;width:11px;height:96px;border-radius:999px;background:linear-gradient(180deg,transparent,rgba(255,250,240,.8),transparent);filter:blur(6px);animation:md-steam 3.8s ease-in-out .8s infinite"></div>
            <div style="position:absolute;left:212px;top:-34px;width:8px;height:78px;border-radius:999px;background:linear-gradient(180deg,transparent,rgba(255,250,240,.75),transparent);filter:blur(5px);animation:md-steam 3.5s ease-in-out 1.6s infinite"></div>
        </div>

        <!-- Sticker: precio editado -->
        <div style="position:absolute;left:clamp(0px,2vw,18px);top:44px;--r:-5deg;animation:md-float 5.5s ease-in-out infinite;background:#fff;border:1.5px solid #201914;border-radius:14px;box-shadow:4px 4px 0 #201914;padding:11px 14px;z-index:3">
            <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8A7B62">Lomo a lo pobre</div>
            <div style="display:flex;align-items:baseline;gap:8px;margin-top:3px">
                <span style="font-size:12.5px;font-weight:600;color:#B8AA92;text-decoration:line-through">$14.500</span>
                <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:19px;font-weight:800;color:#E85D2F">$15.900</span>
            </div>
            <div style="font-size:10px;font-weight:600;color:#25A85A;margin-top:3px">● Publicado hace 30 s</div>
        </div>

        <!-- Sticker: pedido WhatsApp -->
        <div style="position:absolute;right:clamp(0px,1vw,10px);top:96px;--r:4deg;animation:md-float 6.2s ease-in-out .9s infinite;background:#fff;border:1.5px solid #201914;border-radius:14px;box-shadow:4px 4px 0 #201914;padding:10px 13px;display:flex;align-items:center;gap:10px;z-index:3">
            <span style="width:30px;height:30px;border-radius:50%;background:#25D366;border:1.5px solid #201914;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.4L3.5 20.5l1.7-5A8.4 8.4 0 1 1 21 11.5Z"/></svg>
            </span>
            <span>
                <span style="display:block;font-size:11.5px;font-weight:700">Nuevo pedido · Mesa 4</span>
                <span style="display:block;font-size:10.5px;color:#8A7B62;margin-top:1px">2× empanadas, 1× limonada</span>
            </span>
        </div>

        <!-- Sticker: agotado -->
        <div style="position:absolute;right:clamp(4px,2vw,26px);bottom:72px;--r:-3deg;animation:md-float 5.8s ease-in-out 1.7s infinite;background:#fff;border:1.5px solid #201914;border-radius:14px;box-shadow:4px 4px 0 #201914;padding:10px 13px;display:flex;align-items:center;gap:10px;z-index:3">
            <span>
                <span style="display:block;font-size:11.5px;font-weight:700">Congrio frito</span>
                <span style="display:block;font-size:10.5px;color:#C2410C;font-weight:600;margin-top:1px">Agotado por hoy</span>
            </span>
            <span style="width:34px;height:20px;border-radius:999px;background:#E8DCC4;border:1.5px solid #201914;display:flex;align-items:center;padding:1.5px;flex-shrink:0">
                <span style="width:14px;height:14px;border-radius:50%;background:#fff;border:1.5px solid #201914"></span>
            </span>
        </div>

        <!-- Sello giratorio -->
        <div style="position:absolute;left:clamp(6px,3vw,34px);bottom:24px;width:104px;height:104px;z-index:3">
            <svg viewBox="0 0 104 104" style="width:100%;height:100%;animation:md-spin 14s linear infinite">
                <defs><path id="circ" d="M52,52 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"/></defs>
                <text style="font-size:10.6px;font-weight:700;letter-spacing:.24em;fill:#201914;font-family:Inter,sans-serif">
                    <textPath href="#circ">ESCANEA · PIDE · AL TIRO · ESCANEA · PIDE ·</textPath>
                </text>
            </svg>
            <div style="position:absolute;inset:30px;border-radius:50%;background:#201914;display:flex;align-items:center;justify-content:center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFD84D" stroke-width="1.8" stroke-linejoin="round"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2.5v2.5H14zM17.5 17.5H20V20h-2.5z"/></svg>
            </div>
        </div>
    </div>
</section>

<!-- ═══ MARQUESINA ═══ -->
<div style="background:#201914;border-top:1.5px solid #201914;border-bottom:1.5px solid #201914;padding:11px 0;overflow:hidden;transform:rotate(-1deg) scale(1.02);margin:6px -10px">
    <div style="display:inline-flex;white-space:nowrap;animation:md-marquee 26s linear infinite">
        <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:14px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#FBF3E4">&nbsp;Empanadas de pino $6.900 <span style="color:#E85D2F">✶</span> Lomo a lo pobre $15.900 <span style="color:#FFD84D">✶</span> Ceviche de reineta $9.500 <span style="color:#E85D2F">✶</span> Pastel de choclo $12.900 <span style="color:#FFD84D">✶</span> Cazuela de vacuno $11.500 <span style="color:#E85D2F">✶</span> Limonada de menta $3.900 <span style="color:#FFD84D">✶</span></span>
        <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:14px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#FBF3E4">&nbsp;Empanadas de pino $6.900 <span style="color:#E85D2F">✶</span> Lomo a lo pobre $15.900 <span style="color:#FFD84D">✶</span> Ceviche de reineta $9.500 <span style="color:#E85D2F">✶</span> Pastel de choclo $12.900 <span style="color:#FFD84D">✶</span> Cazuela de vacuno $11.500 <span style="color:#E85D2F">✶</span> Limonada de menta $3.900 <span style="color:#FFD84D">✶</span></span>
    </div>
</div>

<!-- ═══ CÓMO FUNCIONA ═══ -->
<section id="como-funciona" style="max-width:1180px;margin:0 auto;padding:clamp(48px,6vw,88px) clamp(18px,3vw,28px) clamp(30px,4vw,52px)">
    <div style="text-align:center;max-width:640px;margin:0 auto">
        <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:19px;color:#E85D2F">de la cocina a la mesa</div>
        <h2 style="margin:8px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:clamp(30px,3.6vw,44px);font-weight:800;letter-spacing:-.03em;line-height:1.05">Tres pasos y estás sirviendo</h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:clamp(16px,2vw,24px);margin-top:clamp(30px,4vw,48px)">
        <!-- Paso 1 -->
        <div style="background:#fff;border:1.5px solid #201914;border-radius:20px;box-shadow:5px 5px 0 #201914;padding:24px;transform:rotate(-1.2deg)" onmouseover="this.style.transform='rotate(0deg) translateY(-4px)'" onmouseout="this.style.transform='rotate(-1.2deg)'">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:44px;line-height:1;color:#E85D2F">1</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#8A7B62;border:1.5px solid #E8DCC4;border-radius:999px;padding:4px 10px">2 minutos</span>
            </div>
            <h3 style="margin:14px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:20px;font-weight:800;letter-spacing:-.02em">Carga tu carta</h3>
            <p style="margin:8px 0 0;font-size:13.5px;line-height:1.6;color:#5C5245">Platos, precios, fotos y variantes. ¿La tienes en PDF o papel? Se la mandas a soporte y la subimos por ti.</p>
            <div style="margin-top:18px;background:#FBF3E4;border:1.5px solid #201914;border-radius:13px;padding:11px 13px;display:flex;align-items:center;gap:10px">
                <span style="width:34px;height:34px;border-radius:9px;background:linear-gradient(140deg,#FFE08A,#FFC24D);border:1.5px solid #201914;display:flex;align-items:center;justify-content:center;font-family:'Instrument Serif',Georgia,serif;font-size:17px;flex-shrink:0">E</span>
                <span style="flex:1;min-width:0">
                    <span style="display:block;font-size:12px;font-weight:700">Empanadas de pino</span>
                    <span style="display:block;font-size:10.5px;color:#8A7B62">Entradas · 2 unidades</span>
                </span>
                <span style="font-size:13px;font-weight:800;font-family:'Bricolage Grotesque',Inter,sans-serif">$6.900</span>
            </div>
        </div>

        <!-- Paso 2 -->
        <div style="background:#fff;border:1.5px solid #201914;border-radius:20px;box-shadow:5px 5px 0 #201914;padding:24px;transform:rotate(.8deg)" onmouseover="this.style.transform='rotate(0deg) translateY(-4px)'" onmouseout="this.style.transform='rotate(.8deg)'">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:44px;line-height:1;color:#E85D2F">2</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#8A7B62;border:1.5px solid #E8DCC4;border-radius:999px;padding:4px 10px">A tu pinta</span>
            </div>
            <h3 style="margin:14px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:20px;font-weight:800;letter-spacing:-.02em">Elige cómo se ve</h3>
            <p style="margin:8px 0 0;font-size:13.5px;line-height:1.6;color:#5C5245">Carta de autor, parrilla nocturna o feria pop. Tu color, tu tipografía — sin diseñador de por medio.</p>
            <div style="margin-top:18px;display:flex;gap:8px">
                <span style="flex:1;height:52px;border-radius:11px;background:#F6F1E7;border:1.5px solid #201914;display:flex;align-items:center;justify-content:center;font-family:'Instrument Serif',Georgia,serif;font-size:13px">Carta</span>
                <span style="flex:1;height:52px;border-radius:11px;background:#201914;border:1.5px solid #201914;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;letter-spacing:.06em;color:#F59E0B;text-transform:uppercase">Brasa</span>
                <span style="flex:1;height:52px;border-radius:11px;background:#FFD84D;border:1.5px solid #201914;display:flex;align-items:center;justify-content:center;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:12.5px;font-weight:800">Feria</span>
            </div>
        </div>

        <!-- Paso 3 -->
        <div style="background:#fff;border:1.5px solid #201914;border-radius:20px;box-shadow:5px 5px 0 #201914;padding:24px;transform:rotate(-.6deg)" onmouseover="this.style.transform='rotate(0deg) translateY(-4px)'" onmouseout="this.style.transform='rotate(-.6deg)'">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span style="font-family:'Instrument Serif',Georgia,serif;font-size:44px;line-height:1;color:#E85D2F">3</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#8A7B62;border:1.5px solid #E8DCC4;border-radius:999px;padding:4px 10px">Para siempre</span>
            </div>
            <h3 style="margin:14px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:20px;font-weight:800;letter-spacing:-.02em">Imprime el QR una vez</h3>
            <p style="margin:8px 0 0;font-size:13.5px;line-height:1.6;color:#5C5245">Tarjetas de mesa, adhesivos y cartel de vitrina listos para imprimir. El código nunca cambia, aunque cambies todo.</p>
            <div style="margin-top:18px;display:flex;align-items:center;gap:12px;background:#FBF3E4;border:1.5px solid #201914;border-radius:13px;padding:11px 13px">
                <span style="width:40px;height:40px;border-radius:8px;background:#fff;border:1.5px solid #201914;flex-shrink:0;background-image:repeating-linear-gradient(0deg,#201914 0 3.5px,transparent 3.5px 7px),repeating-linear-gradient(90deg,rgba(32,25,20,.85) 0 3.5px,transparent 3.5px 7px);background-size:7px 7px;background-position:5px 5px"></span>
                <span style="flex:1;font-size:11.5px;font-weight:600;color:#5C5245;line-height:1.45">menudigital.cl/<span style="font-weight:800;color:#201914">el-fogon</span></span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PLANTILLAS ═══ -->
<section id="plantillas" style="background:#201914;padding:clamp(48px,6vw,84px) clamp(18px,3vw,28px);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background:radial-gradient(70% 60% at 80% 10%,rgba(232,93,47,.18),transparent 60%);pointer-events:none"></div>
    <div style="position:relative;max-width:1180px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:clamp(26px,4vw,52px);align-items:center">
        <div>
            <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:19px;color:#F59E0B">un menú, tres personalidades</div>
            <h2 style="margin:10px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:clamp(30px,3.6vw,44px);font-weight:800;letter-spacing:-.03em;line-height:1.05;color:#FBF3E4">Plantillas con dirección de arte, no temas genéricos</h2>
            <p style="margin:16px 0 0;font-size:14.5px;line-height:1.65;color:#B8AA92;max-width:440px">Cada plantilla fue diseñada como si fuera para un solo restaurante: la <em style="color:#FBF3E4">Carta</em> de autor en papel, la <em style="color:#FBF3E4">Brasa</em> nocturna de parrilla y la <em style="color:#FBF3E4">Feria</em> pop de barrio. Cambia entre ellas sin tocar el QR.</p>
            <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;margin-top:24px;font-size:14px;font-weight:700;color:#FFD84D;border-bottom:2px solid #FFD84D;padding-bottom:2px" onmouseover="this.style.color='#fff';this.style.borderColor='#fff'" onmouseout="this.style.color='#FFD84D';this.style.borderColor='#FFD84D'">
                Explorar las plantillas
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </a>
        </div>
        <div style="display:flex;gap:clamp(10px,1.4vw,18px);justify-content:center;align-items:center;flex-wrap:wrap">
            <!-- Carta -->
            <div style="--r:-4deg;animation:md-float 7s ease-in-out infinite;width:172px;background:#F6F1E7;border-radius:16px;padding:16px 14px;box-shadow:0 20px 40px rgba(0,0,0,.4)">
                <div style="text-align:center;font-size:7px;font-weight:700;letter-spacing:.26em;color:#8A7B66">CARTA · INVIERNO</div>
                <div style="text-align:center;font-family:'Instrument Serif',Georgia,serif;font-size:21px;margin-top:6px;color:#211A12">El Fogón</div>
                <div style="width:18px;height:1px;background:#211A12;margin:8px auto"></div>
                <div style="display:flex;flex-direction:column;gap:7px;margin-top:4px">
                    <div style="display:flex;gap:5px;align-items:baseline"><span style="flex:1;height:5px;border-radius:3px;background:#D9CDB8"></span><span style="width:16px;height:5px;border-radius:3px;background:#8A7B66"></span></div>
                    <div style="display:flex;gap:5px;align-items:baseline"><span style="flex:1;height:5px;border-radius:3px;background:#D9CDB8"></span><span style="width:16px;height:5px;border-radius:3px;background:#8A7B66"></span></div>
                    <div style="display:flex;gap:5px;align-items:baseline"><span style="flex:1;height:5px;border-radius:3px;background:#E4DAC6"></span><span style="width:16px;height:5px;border-radius:3px;background:#B3A38B"></span></div>
                </div>
                <div style="margin-top:12px;background:#211A12;padding:8px;text-align:center"><span style="font-family:'Instrument Serif',Georgia,serif;font-size:9.5px;color:#F6F1E7">Del carbón, esta semana</span></div>
            </div>
            <!-- Brasa -->
            <div style="--r:2deg;animation:md-float 6.4s ease-in-out .7s infinite;width:172px;background:#0C0A09;border:1px solid #292524;border-radius:16px;padding:16px 14px;box-shadow:0 20px 40px rgba(0,0,0,.5)">
                <div style="text-align:center;font-size:7px;font-weight:700;letter-spacing:.3em;color:#A8A29E">PARRILLA · A · LEÑA</div>
                <div style="text-align:center;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:19px;font-weight:800;margin-top:6px;background:linear-gradient(175deg,#FAFAF9 30%,#F59E0B 80%);-webkit-background-clip:text;background-clip:text;color:transparent">EL FOGÓN</div>
                <div style="margin-top:10px;height:52px;border-radius:9px;background:radial-gradient(80% 80% at 30% 20%,rgba(245,158,11,.3),transparent 60%),linear-gradient(150deg,#2E2018,#150E09);border:1px solid #292524;position:relative">
                    <span style="position:absolute;left:8px;bottom:6px;font-size:7px;font-weight:700;letter-spacing:.18em;color:#F59E0B">LA CASA</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:7px;margin-top:10px">
                    <div style="display:flex;gap:5px;align-items:center"><span style="width:14px;height:14px;border-radius:4px;background:#2E2018;flex-shrink:0"></span><span style="flex:1;height:5px;border-radius:3px;background:#292524"></span><span style="width:16px;height:5px;border-radius:3px;background:#F59E0B"></span></div>
                    <div style="display:flex;gap:5px;align-items:center"><span style="width:14px;height:14px;border-radius:4px;background:#2B1B16;flex-shrink:0"></span><span style="flex:1;height:5px;border-radius:3px;background:#292524"></span><span style="width:16px;height:5px;border-radius:3px;background:#F59E0B"></span></div>
                </div>
            </div>
            <!-- Feria -->
            <div style="--r:-2deg;animation:md-float 7.6s ease-in-out 1.4s infinite;width:172px;background:#FFF6DE;border:1.5px solid #191410;border-radius:16px;padding:14px;box-shadow:5px 5px 0 rgba(0,0,0,.55)">
                <div style="display:flex;align-items:center;gap:7px">
                    <span style="width:22px;height:22px;border-radius:7px;background:#E85D2F;border:1.5px solid #191410;transform:rotate(-4deg);display:block;flex-shrink:0"></span>
                    <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:13px;font-weight:800;color:#191410">El Fogón</span>
                </div>
                <div style="margin-top:9px;background:#191410;border-radius:4px;padding:3px 6px"><span style="font-size:6.5px;font-weight:800;letter-spacing:.14em;color:#FFF6DE">✶ PEDIDOS AL TIRO ✶ SIN COMISIONES</span></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:9px">
                    <div style="background:#fff;border:1.5px solid #191410;border-radius:9px;overflow:hidden;box-shadow:2px 2px 0 #191410"><div style="height:26px;background:linear-gradient(140deg,#FFE08A,#FFC24D);border-bottom:1.5px solid #191410"></div><div style="padding:5px"><span style="display:block;width:80%;height:4px;border-radius:2px;background:#191410;opacity:.75"></span><span style="display:inline-block;margin-top:5px;font-size:6.5px;font-weight:800;background:#FFD84D;border:1px solid #191410;border-radius:3px;padding:1px 4px;transform:rotate(-2deg);color:#191410">$6.900</span></div></div>
                    <div style="background:#fff;border:1.5px solid #191410;border-radius:9px;overflow:hidden;box-shadow:2px 2px 0 #191410"><div style="height:26px;background:linear-gradient(140deg,#B9E8C6,#7BD096);border-bottom:1.5px solid #191410"></div><div style="padding:5px"><span style="display:block;width:70%;height:4px;border-radius:2px;background:#191410;opacity:.75"></span><span style="display:inline-block;margin-top:5px;font-size:6.5px;font-weight:800;background:#FFD84D;border:1px solid #191410;border-radius:3px;padding:1px 4px;transform:rotate(-2deg);color:#191410">$9.500</span></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ TESTIMONIO ═══ -->
<section style="max-width:900px;margin:0 auto;padding:clamp(48px,6vw,88px) clamp(18px,3vw,28px);text-align:center">
    <div style="font-size:44px;font-family:'Instrument Serif',Georgia,serif;line-height:1;color:#E85D2F">"</div>
    <p style="margin:4px auto 0;font-family:'Instrument Serif',Georgia,serif;font-size:clamp(22px,2.8vw,31px);line-height:1.4;letter-spacing:-.01em;max-width:700px">Imprimíamos 300 cartas por temporada. Ahora cambio el precio del día <em>desde la caja</em> y los pedidos por WhatsApp pasaron de 12 a 60 por semana.</p>
    <div style="display:flex;align-items:center;justify-content:center;gap:11px;margin-top:22px">
        <div style="width:38px;height:38px;border-radius:50%;background:#201914;color:#FFD84D;font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;font-family:'Bricolage Grotesque',Inter,sans-serif;flex-shrink:0">CV</div>
        <div style="text-align:left">
            <div style="font-size:13.5px;font-weight:700">Carolina Vera</div>
            <div style="font-size:12px;color:#8A7B62">Sazón de Barrio · Valparaíso</div>
        </div>
    </div>
</section>

<!-- ═══ PRECIOS ═══ -->
<section id="precios" style="max-width:1180px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(48px,6vw,88px)">
    <div style="text-align:center;max-width:560px;margin:0 auto 34px">
        <div style="font-family:'Instrument Serif',Georgia,serif;font-style:italic;font-size:19px;color:#E85D2F">sin letra chica</div>
        <h2 style="margin:8px 0 0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:clamp(30px,3.6vw,44px);font-weight:800;letter-spacing:-.03em">Precios en pesos, como debe ser</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:clamp(14px,2vw,22px);align-items:stretch">
        <!-- Free -->
        <div style="background:#fff;border:1.5px solid #201914;border-radius:20px;padding:24px;display:flex;flex-direction:column;gap:12px">
            <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:15px;font-weight:800">Free</span>
            <div style="display:flex;align-items:baseline;gap:5px"><span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:34px;font-weight:800;letter-spacing:-.03em">$0</span><span style="font-size:12.5px;color:#8A7B62">/mes</span></div>
            <div style="font-size:13px;line-height:1.9;color:#5C5245">Hasta 20 platos<br>1 plantilla<br>QR con marca MenuDigital</div>
            <div style="flex:1"></div>
            <a href="{{ route('register') }}" style="display:block;text-align:center;padding:11px;border-radius:999px;border:1.5px solid #201914;font-size:13.5px;font-weight:700;color:#201914" onmouseover="this.style.background='#FBF3E4'" onmouseout="this.style.background='transparent'">Empezar gratis</a>
        </div>
        <!-- Pro -->
        <div style="background:#201914;color:#FBF3E4;border:1.5px solid #201914;border-radius:20px;padding:24px;display:flex;flex-direction:column;gap:12px;box-shadow:6px 6px 0 #E85D2F;position:relative">
            <span style="position:absolute;top:-13px;right:18px;z-index:2;white-space:nowrap;font-size:10.5px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;background:#FFD84D;color:#201914;border:1.5px solid #201914;border-radius:999px;padding:4px 11px;transform:rotate(2deg)">Más elegido</span>
            <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:15px;font-weight:800">Pro</span>
            <div style="display:flex;align-items:baseline;gap:5px"><span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:34px;font-weight:800;letter-spacing:-.03em">$14.900</span><span style="font-size:12.5px;color:#B8AA92">/mes</span></div>
            <div style="font-size:13px;line-height:1.9;color:#D9CDB8">Platos ilimitados<br>3 plantillas + tu color<br>Pedidos por WhatsApp sin comisión<br>QR sin marca</div>
            <div style="flex:1"></div>
            <a href="{{ route('register') }}" style="display:block;text-align:center;padding:11px;border-radius:999px;background:#FFD84D;border:1.5px solid #FFD84D;font-size:13.5px;font-weight:800;color:#201914" onmouseover="this.style.background='#fff';this.style.borderColor='#fff'" onmouseout="this.style.background='#FFD84D';this.style.borderColor='#FFD84D'">Probar Pro 14 días</a>
        </div>
        <!-- Business -->
        <div style="background:#fff;border:1.5px solid #201914;border-radius:20px;padding:24px;display:flex;flex-direction:column;gap:12px">
            <span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:15px;font-weight:800">Business</span>
            <div style="display:flex;align-items:baseline;gap:5px"><span style="font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:34px;font-weight:800;letter-spacing:-.03em">$34.900</span><span style="font-size:12.5px;color:#8A7B62">/mes</span></div>
            <div style="font-size:13px;line-height:1.9;color:#5C5245">Hasta 5 locales<br>Dominio propio<br>Analítica de escaneos<br>Soporte prioritario</div>
            <div style="flex:1"></div>
            <a href="{{ route('register') }}" style="display:block;text-align:center;padding:11px;border-radius:999px;border:1.5px solid #201914;font-size:13.5px;font-weight:700;color:#201914" onmouseover="this.style.background='#FBF3E4'" onmouseout="this.style.background='transparent'">Hablar con ventas</a>
        </div>
    </div>
</section>

<!-- ═══ CTA FINAL ═══ -->
<section style="background:linear-gradient(120deg,#E85D2F,#C2410C);border-top:1.5px solid #201914;padding:clamp(52px,7vw,96px) clamp(18px,3vw,28px);position:relative;overflow:hidden">
    <div style="position:absolute;left:6%;top:18%;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.1);--r:0deg;animation:md-float 7s ease-in-out infinite"></div>
    <div style="position:absolute;right:9%;bottom:14%;width:130px;height:130px;border-radius:50%;background:rgba(32,25,20,.14);--r:0deg;animation:md-float 8s ease-in-out 1s infinite"></div>
    <div style="position:relative;max-width:760px;margin:0 auto;text-align:center">
        <h2 style="margin:0;font-family:'Bricolage Grotesque',Inter,sans-serif;font-size:clamp(32px,4.4vw,54px);font-weight:800;letter-spacing:-.035em;line-height:1.02;color:#FFF6DE">Publica tu carta <span style="font-family:'Instrument Serif',Georgia,serif;font-weight:400;font-style:italic">esta misma tarde</span></h2>
        <p style="margin:16px auto 0;max-width:440px;font-size:15px;line-height:1.6;color:rgba(255,246,222,.85)">Dos minutos para crear la cuenta. El QR queda listo para imprimir antes del turno de la noche.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:30px">
            <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:9px;font-size:15px;font-weight:800;color:#201914;background:#FFD84D;border:1.5px solid #201914;box-shadow:4px 4px 0 #201914;padding:15px 26px;border-radius:999px;font-family:'Bricolage Grotesque',Inter,sans-serif" onmouseover="this.style.boxShadow='1px 1px 0 #201914';this.style.transform='translate(3px,3px)'" onmouseout="this.style.boxShadow='4px 4px 0 #201914';this.style.transform='none'">Crear mi menú gratis</a>
            <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;color:#FFF6DE;border:1.5px solid rgba(255,246,222,.6);padding:15px 22px;border-radius:999px" onmouseover="this.style.borderColor='#FFF6DE'" onmouseout="this.style.borderColor='rgba(255,246,222,.6)'">Ya tengo cuenta</a>
        </div>
    </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer style="background:#201914;padding:26px clamp(18px,3vw,28px)">
    <div style="max-width:1180px;margin:0 auto;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:9px;flex:1;min-width:220px">
            <div style="width:24px;height:24px;border-radius:8px;background:#E85D2F;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/></svg>
            </div>
            <span style="font-size:12.5px;color:#8A7B62">© 2026 MenuDigital SpA · Hecho con hambre en Santiago</span>
        </div>
        <div style="display:flex;gap:18px;flex-wrap:wrap">
            <a href="#" style="font-size:12.5px;color:#B8AA92" onmouseover="this.style.color='#FBF3E4'" onmouseout="this.style.color='#B8AA92'">Términos</a>
            <a href="#" style="font-size:12.5px;color:#B8AA92" onmouseover="this.style.color='#FBF3E4'" onmouseout="this.style.color='#B8AA92'">Privacidad</a>
            <a href="#" style="font-size:12.5px;color:#B8AA92" onmouseover="this.style.color='#FBF3E4'" onmouseout="this.style.color='#B8AA92'">Soporte</a>
        </div>
    </div>
</footer>

</body>
</html>
