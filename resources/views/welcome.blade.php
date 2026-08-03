<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MenuDigital — Tu carta digital con QR en minutos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; } * { box-sizing: border-box; }</style>
</head>
<body class="bg-white text-[#111827] antialiased">

<!-- ===== STICKY HEADER ===== -->
<header style="position:sticky;top:0;z-index:30;background:rgba(255,255,255,0.9);backdrop-filter:blur(10px);border-bottom:1px solid #F3F4F6;">
    <div style="max-width:1160px;margin:0 auto;padding:13px clamp(18px,3vw,28px);display:flex;align-items:center;gap:14px;">

        <!-- Brand -->
        <a href="/" style="display:flex;align-items:center;gap:9px;text-decoration:none;color:#111827;">
            <div style="width:29px;height:29px;border-radius:9px;background:#4F46E5;box-shadow:0 1px 2px rgba(79,70,229,.4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <span style="font-size:15px;font-weight:700;letter-spacing:-0.02em;">MenuDigital</span>
        </a>

        <!-- Nav center (hidden on mobile) -->
        <nav style="flex:1;justify-content:center;gap:22px;" class="hidden md:flex">
            <a href="#como-funciona" style="font-size:13.5px;font-weight:500;color:#4B5563;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Cómo funciona</a>
            <a href="#plantillas" style="font-size:13.5px;font-weight:500;color:#4B5563;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Plantillas</a>
            <a href="#precios" style="font-size:13.5px;font-weight:500;color:#4B5563;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#4B5563'">Precios</a>
        </nav>

        <!-- Actions -->
        <div style="display:flex;align-items:center;gap:8px;margin-left:auto;">
            <a href="{{ route('login') }}" style="padding:8px 13px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">
                Ingresar
            </a>
            <a href="{{ route('register') }}" style="padding:9px 15px;border-radius:10px;background:#4F46E5;color:white;border:1px solid #4338CA;font-size:13px;font-weight:600;box-shadow:0 1px 2px rgba(79,70,229,.35);white-space:nowrap;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
                Crear menú gratis
            </a>
        </div>
    </div>
</header>

<!-- ===== HERO SECTION ===== -->
<style>
    @media (min-width: 1024px) {
        .hero-grid { grid-template-columns: 1fr 1fr !important; }
    }
</style>
<section>
    <div class="hero-grid" style="display:grid;grid-template-columns:1fr;gap:clamp(28px,4vw,60px);align-items:center;max-width:1160px;margin:0 auto;padding:clamp(40px,6vw,84px) clamp(18px,3vw,28px);">
        <!-- Left -->
        <div>
            <!-- Badge -->
            <div style="display:inline-flex;align-items:center;gap:7px;font-size:11.5px;font-weight:600;color:#3730A3;background:#EEF2FF;border:1px solid #E0E7FF;border-radius:9999px;padding:5px 11px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#4F46E5;flex-shrink:0;"></span>
                Usado por 1.200 restaurantes en Chile
            </div>

            <!-- H1 -->
            <h1 style="margin-top:18px;font-size:clamp(34px,4.4vw,52px);line-height:1.06;font-weight:700;letter-spacing:-0.035em;">
                Tu carta, en un QR que actualizas en 30 segundos
            </h1>

            <!-- Paragraph -->
            <p style="margin-top:18px;max-width:490px;font-size:15px;line-height:1.65;color:#4B5563;">
                Se acabó reimprimir la carta cada vez que sube un precio. Edita desde el celular, elige una plantilla y tus comensales ven el cambio al instante — con pedidos directos por WhatsApp.
            </p>

            <!-- CTA buttons -->
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:26px;">
                <a href="{{ route('register') }}" style="padding:12px 20px;border-radius:11px;background:#4F46E5;color:white;border:1px solid #4338CA;font-size:14px;font-weight:600;box-shadow:0 1px 2px rgba(79,70,229,.35);text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
                    Crear mi menú gratis
                </a>
                <a href="/la-buena-mesa" style="display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:11px;background:white;color:#374151;border:1px solid #E5E7EB;font-size:14px;font-weight:600;box-shadow:0 1px 2px rgba(16,24,40,.04);text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 17h3v3M17 14h3"/>
                    </svg>
                    Ver un menú de ejemplo
                </a>
            </div>

            <!-- Trust points -->
            <div style="display:flex;align-items:center;gap:18px;margin-top:22px;flex-wrap:wrap;">
                @foreach(['Sin app', 'Actualización inmediata', 'QR gratis'] as $point)
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#4B5563;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ $point }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Phone mockup -->
        <div style="display:flex;justify-content:center;position:relative;">
            <!-- Gradient blob -->
            <div style="position:absolute;inset:6% 8%;background:radial-gradient(60% 60% at 50% 30%,rgba(79,70,229,.18),transparent 70%);filter:blur(28px);"></div>

            <!-- Phone frame -->
            <div style="position:relative;width:290px;height:584px;border-radius:34px;background:#111827;padding:8px;box-shadow:0 26px 60px rgba(16,24,40,.28);">
                <!-- Screen -->
                <div style="width:100%;height:100%;border-radius:27px;background:#F8FAFC;overflow:hidden;display:flex;flex-direction:column;">

                    <!-- Screen header -->
                    <div style="background:white;padding:14px 14px 10px;border-bottom:1px solid #F3F4F6;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:#4F46E5;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:white;flex-shrink:0;">EF</div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#111827;">El Fogón</div>
                                <div style="font-size:10.5px;color:#22C55E;font-weight:500;">Abierto · Retiro y delivery</div>
                            </div>
                        </div>
                    </div>

                    <!-- Category chips -->
                    <div style="display:flex;gap:6px;padding:10px 12px;background:white;border-bottom:1px solid #F3F4F6;overflow-x:auto;">
                        <div style="padding:5px 12px;border-radius:9999px;background:#4F46E5;color:white;font-size:11px;font-weight:600;white-space:nowrap;">Todo</div>
                        <div style="padding:5px 12px;border-radius:9999px;background:white;color:#4B5563;border:1px solid #E5E7EB;font-size:11px;font-weight:500;white-space:nowrap;">Entradas</div>
                        <div style="padding:5px 12px;border-radius:9999px;background:white;color:#4B5563;border:1px solid #E5E7EB;font-size:11px;font-weight:500;white-space:nowrap;">Fondos</div>
                    </div>

                    <!-- Food cards grid -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:10px 10px;flex:1;overflow:hidden;">
                        @foreach([['Empanada de pino','$1.200'],['Cazuela de vacuno','$5.900'],['Pastel de choclo','$5.500'],['Sopaipillas','$800']] as $item)
                        <div style="background:white;border-radius:10px;overflow:hidden;border:1px solid #F3F4F6;">
                            <div style="height:66px;background:#E5E7EB;"></div>
                            <div style="padding:7px 8px;">
                                <div style="font-size:10.5px;font-weight:600;color:#111827;margin-bottom:4px;">{{ $item[0] }}</div>
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <span style="font-size:11px;font-weight:700;color:#4F46E5;">{{ $item[1] }}</span>
                                    <button style="width:20px;height:20px;border-radius:6px;background:#4F46E5;color:white;border:none;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- WhatsApp FAB -->
                    <div style="position:absolute;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(37,211,102,.4);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section id="como-funciona" style="border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;background:#F9FAFB;">
    <div style="max-width:1160px;margin:0 auto;padding:clamp(34px,4.5vw,58px) clamp(18px,3vw,28px);">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:clamp(16px,2vw,26px);">

            @foreach([
                ['1', 'Crea tu cuenta', 'Registra tu restaurante en 2 minutos. Sin tarjeta de crédito.'],
                ['2', 'Agrega tu carta', 'Sube categorías, platos, precios y fotos desde el celular.'],
                ['3', 'Comparte el QR', 'Imprime el QR, ponlo en las mesas y listo. Tus clientes piden al instante.'],
            ] as [$num, $title, $body])
            <div>
                <div style="width:32px;height:32px;border-radius:10px;background:white;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;font-size:12.5px;font-weight:700;color:#4F46E5;margin-bottom:13px;box-shadow:0 1px 2px rgba(16,24,40,.04);">{{ $num }}</div>
                <div style="font-size:15px;font-weight:700;letter-spacing:-0.01em;">{{ $title }}</div>
                <p style="margin-top:7px;font-size:13.5px;line-height:1.625;color:#6B7280;">{{ $body }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>

<!-- ===== FEATURES SECTION ===== -->
<section>
    <div style="max-width:1160px;margin:0 auto;padding:clamp(40px,5vw,72px) clamp(18px,3vw,28px);">
        <h2 style="font-size:clamp(24px,2.8vw,32px);font-weight:700;letter-spacing:-0.028em;">Todo lo que necesita una carta digital</h2>
        <p style="font-size:14.5px;line-height:1.625;color:#6B7280;margin-top:11px;max-width:560px;">Herramientas pensadas para restaurantes que quieren ofrecer una experiencia moderna sin complicarse.</p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px;margin-top:30px;">

            @php
            $features = [
                [
                    'title' => 'Menú siempre actualizado',
                    'body'  => 'Cambia precios, agrega o pausa platos en segundos. Sin reimprimir nada.',
                    'icon'  => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
                ],
                [
                    'title' => 'QR listo para imprimir',
                    'body'  => 'Genera tu QR desde el panel y descárgalo en alta resolución.',
                    'icon'  => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 17h3v3M17 14h3"/>',
                ],
                [
                    'title' => 'Pedidos por WhatsApp',
                    'body'  => 'Botón flotante en tu menú para que los clientes pidan directo a tu chat.',
                    'icon'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
                ],
                [
                    'title' => '3 plantillas de diseño',
                    'body'  => 'Minimal, Dark o Cards. Cambia el look cuando quieras sin perder datos.',
                    'icon'  => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
                ],
                [
                    'title' => 'Fotos de los platos',
                    'body'  => 'Sube imágenes de alta calidad. Se convierten a WebP automáticamente.',
                    'icon'  => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
                ],
                [
                    'title' => 'Sin app para el cliente',
                    'body'  => 'El menú se abre con la cámara del celular, sin descargar nada.',
                    'icon'  => '<rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/>',
                ],
            ];
            @endphp

            @foreach($features as $f)
            <div style="background:white;border:1px solid #E5E7EB;border-radius:14px;padding:18px;box-shadow:0 1px 2px rgba(16,24,40,.04);">
                <div style="width:34px;height:34px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;margin-bottom:13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['icon'] !!}</svg>
                </div>
                <div style="font-size:14px;font-weight:700;letter-spacing:-0.01em;">{{ $f['title'] }}</div>
                <p style="margin-top:6px;font-size:13px;line-height:1.625;color:#6B7280;">{{ $f['body'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>

<!-- ===== TEMPLATES SECTION ===== -->
<section id="plantillas">
    <div style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(40px,5vw,72px);">
        <div style="background:#0F1020;border-radius:22px;padding:clamp(26px,3.4vw,44px);">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:clamp(24px,3vw,48px);align-items:center;">

                <!-- Left text -->
                <div>
                    <h2 style="font-size:clamp(22px,2.4vw,30px);font-weight:700;letter-spacing:-0.028em;color:white;">Elige la plantilla que va con tu local</h2>
                    <p style="font-size:14px;line-height:1.65;color:#9CA3AF;margin-top:12px;">Tres estilos distintos. Todos con tu marca, colores y fotos. Cambia cuando quieras.</p>

                    <div style="margin-top:26px;display:flex;flex-direction:column;gap:14px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:white;flex-shrink:0;"></span>
                            <div>
                                <span style="font-size:14px;font-weight:600;color:white;">Minimal</span>
                                <span style="font-size:13px;color:#8A8A99;margin-left:8px;">Lista limpia, sin fotos</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:#818CF8;flex-shrink:0;"></span>
                            <div>
                                <span style="font-size:14px;font-weight:600;color:white;">Dark</span>
                                <span style="font-size:13px;color:#8A8A99;margin-left:8px;">Fondo oscuro, tarjetas</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:#4F46E5;flex-shrink:0;"></span>
                            <div>
                                <span style="font-size:14px;font-weight:600;color:white;">Cards</span>
                                <span style="font-size:13px;color:#8A8A99;margin-left:8px;">Grilla 2 col. con fotos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: 3 mini phone previews -->
                <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">

                    <!-- Minimal template -->
                    <div style="width:132px;height:196px;border-radius:18px;background:white;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.3);flex-shrink:0;">
                        <div style="background:#F9FAFB;padding:10px 10px 8px;border-bottom:1px solid #F3F4F6;">
                            <div style="font-size:8px;font-weight:700;color:#111827;">El Fogón</div>
                            <div style="font-size:6.5px;color:#6B7280;margin-top:1px;">Carta digital</div>
                        </div>
                        <div style="padding:8px 10px;display:flex;flex-direction:column;gap:6px;">
                            @foreach(['Empanada de pino','Cazuela de vacuno','Pastel de choclo'] as $i => $dish)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:6px;border-bottom:1px solid #F3F4F6;">
                                <span style="font-size:7px;color:#111827;font-weight:500;">{{ $dish }}</span>
                                <span style="font-size:7px;color:#4F46E5;font-weight:700;">${{ ['1.200','5.900','5.500'][$i] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dark template -->
                    <div style="width:132px;height:196px;border-radius:18px;background:#0F1020;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.4);flex-shrink:0;">
                        <div style="background:#1a1b35;padding:10px 10px 8px;">
                            <div style="font-size:8px;font-weight:700;color:white;">El Fogón</div>
                            <div style="font-size:6.5px;color:#818CF8;margin-top:1px;">Carta digital</div>
                        </div>
                        <div style="padding:8px 10px;display:flex;flex-direction:column;gap:6px;">
                            @foreach(['Empanada de pino','Cazuela de vacuno','Pastel de choclo'] as $i => $dish)
                            <div style="background:#1a1b35;border-radius:6px;padding:5px 7px;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:7px;color:#E5E7EB;font-weight:500;">{{ $dish }}</span>
                                <span style="font-size:7px;color:#818CF8;font-weight:700;">${{ ['1.200','5.900','5.500'][$i] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cards template -->
                    <div style="width:132px;height:196px;border-radius:18px;background:#F8FAFC;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.3);flex-shrink:0;">
                        <div style="background:white;padding:10px 10px 8px;border-bottom:1px solid #F3F4F6;">
                            <div style="font-size:8px;font-weight:700;color:#111827;">El Fogón</div>
                            <div style="font-size:6.5px;color:#22C55E;margin-top:1px;">Abierto · Cards</div>
                        </div>
                        <div style="padding:7px;display:grid;grid-template-columns:1fr 1fr;gap:5px;">
                            @foreach([['Empanada','$1.200'],['Cazuela','$5.900'],['Pastel','$5.500'],['Sopaipilla','$800']] as $item)
                            <div style="background:white;border-radius:7px;overflow:hidden;border:1px solid #F3F4F6;">
                                <div style="height:34px;background:#E5E7EB;"></div>
                                <div style="padding:4px 5px;">
                                    <div style="font-size:6px;font-weight:600;color:#111827;">{{ $item[0] }}</div>
                                    <div style="font-size:6px;color:#4F46E5;font-weight:700;margin-top:1px;">{{ $item[1] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRICING SECTION ===== -->
<section id="precios">
    <div style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(40px,5vw,72px);">
        <h2 style="font-size:clamp(24px,2.8vw,32px);font-weight:700;letter-spacing:-0.028em;">Precios claros, en pesos</h2>
        <p style="font-size:14.5px;line-height:1.625;color:#6B7280;margin-top:11px;">Empieza gratis y crece cuando tu negocio lo necesite. Sin sorpresas.</p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-top:26px;">

            <!-- Free -->
            <div style="border:1px solid #E5E7EB;border-radius:16px;padding:24px;background:white;">
                <div style="font-size:15px;font-weight:700;color:#111827;">Free</div>
                <div style="margin-top:14px;">
                    <span style="font-size:32px;font-weight:700;color:#111827;">$0</span>
                    <span style="font-size:13px;color:#6B7280;margin-left:4px;">para siempre</span>
                </div>
                <ul style="margin-top:18px;display:flex;flex-direction:column;gap:9px;list-style:none;padding:0;">
                    @foreach(['Hasta 20 items','1 plantilla','QR incluido','Botón WhatsApp'] as $feat)
                    <li style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#4B5563;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" style="display:block;margin-top:22px;padding:11px;border-radius:10px;border:1px solid #E5E7EB;background:white;font-size:13.5px;font-weight:600;color:#374151;text-align:center;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                    Empezar gratis
                </a>
            </div>

            <!-- Pro -->
            <div style="border:2px solid #4F46E5;border-radius:16px;padding:24px;background:white;box-shadow:0 0 0 3px rgba(79,70,229,.13);position:relative;">
                <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#4F46E5;color:white;font-size:11px;font-weight:700;padding:4px 12px;border-radius:9999px;white-space:nowrap;">Popular</div>
                <div style="font-size:15px;font-weight:700;color:#111827;">Pro</div>
                <div style="margin-top:14px;">
                    <span style="font-size:32px;font-weight:700;color:#111827;">$14.900</span>
                    <span style="font-size:13px;color:#6B7280;margin-left:4px;">al mes</span>
                </div>
                <ul style="margin-top:18px;display:flex;flex-direction:column;gap:9px;list-style:none;padding:0;">
                    @foreach(['Items y categorías ilimitados','3 plantillas + colores','QR sin marca','Soporte por WhatsApp'] as $feat)
                    <li style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#4B5563;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" style="display:block;margin-top:22px;padding:11px;border-radius:10px;background:#4F46E5;color:white;border:1px solid #4338CA;font-size:13.5px;font-weight:600;text-align:center;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
                    Empezar con Pro
                </a>
            </div>

            <!-- Business -->
            <div style="border:1px solid #E5E7EB;border-radius:16px;padding:24px;background:white;">
                <div style="font-size:15px;font-weight:700;color:#111827;">Business</div>
                <div style="margin-top:14px;">
                    <span style="font-size:32px;font-weight:700;color:#111827;">$34.900</span>
                    <span style="font-size:13px;color:#6B7280;margin-left:4px;">al mes</span>
                </div>
                <ul style="margin-top:18px;display:flex;flex-direction:column;gap:9px;list-style:none;padding:0;">
                    @foreach(['Hasta 5 locales','Todo lo de Pro','Analítica de escaneos','Soporte prioritario'] as $feat)
                    <li style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#4B5563;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                <a href="https://wa.me/56900000000?text=Hola,%20quiero%20saber%20sobre%20MenuDigital%20Business" target="_blank" style="display:block;margin-top:22px;padding:11px;border-radius:10px;border:1px solid #E5E7EB;background:white;font-size:13.5px;font-weight:600;color:#374151;text-align:center;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                    Contactar
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section>
    <div style="max-width:1160px;margin:0 auto;padding:0 clamp(18px,3vw,28px) clamp(44px,5vw,80px);">
        <div style="border:1px solid #E0E7FF;background:#EEF2FF;border-radius:20px;padding:clamp(26px,3.2vw,40px);display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between;">
            <div style="flex:1;min-width:240px;">
                <h2 style="font-size:clamp(20px,2.4vw,27px);font-weight:700;letter-spacing:-0.02em;color:#1E1B4B;">Publica tu carta esta misma tarde</h2>
                <p style="font-size:14px;line-height:1.65;color:#4338CA;margin-top:8px;">En menos de 10 minutos tienes tu carta digital con QR lista para compartir con tus clientes.</p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0;">
                <a href="{{ route('register') }}" style="padding:11px 20px;border-radius:10px;background:#4F46E5;color:white;border:1px solid #4338CA;font-size:13.5px;font-weight:600;box-shadow:0 1px 2px rgba(79,70,229,.35);text-decoration:none;white-space:nowrap;transition:background .15s;" onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
                    Crear cuenta gratis
                </a>
                <a href="{{ route('login') }}" style="padding:11px 18px;border-radius:10px;background:white;color:#4338CA;border:1px solid #C7D2FE;font-size:13.5px;font-weight:600;text-decoration:none;white-space:nowrap;transition:background .15s;" onmouseover="this.style.background='#EEF2FF'" onmouseout="this.style.background='white'">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer style="border-top:1px solid #F3F4F6;">
    <div style="max-width:1160px;margin:0 auto;padding:22px clamp(18px,3vw,28px);display:flex;align-items:center;gap:14px;flex-wrap:wrap;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:9px;">
            <div style="width:22px;height:22px;border-radius:7px;background:#4F46E5;flex-shrink:0;"></div>
            <span style="font-size:12.5px;color:#9CA3AF;">© 2026 MenuDigital SpA · Santiago, Chile</span>
        </div>
        <div style="display:flex;gap:18px;">
            <a href="#" style="font-size:12.5px;color:#6B7280;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Términos</a>
            <a href="#" style="font-size:12.5px;color:#6B7280;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Privacidad</a>
            <a href="#" style="font-size:12.5px;color:#6B7280;text-decoration:none;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6B7280'">Soporte</a>
        </div>
    </div>
</footer>

</body>
</html>
