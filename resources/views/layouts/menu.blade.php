<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }}</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $restaurant->name }}">
    <meta property="og:description" content="{{ $restaurant->description ?? 'Menú digital de ' . $restaurant->name . ' en Chile.' }}">
    @if($restaurant->logo)
    <meta property="og:image" content="{{ Storage::url($restaurant->logo) }}">
    @endif
    <meta property="og:url" content="{{ url('/' . $restaurant->slug) }}">
    <meta property="og:site_name" content="MenuDigital">
    <meta name="twitter:card" content="summary_large_image">
    @vite(['resources/css/menu.css', 'resources/js/menu.js'])
    <style>
        html,body{margin:0;padding:0;-webkit-font-smoothing:antialiased}
        *{box-sizing:border-box}
        a{text-decoration:none;color:inherit}
        .hsc::-webkit-scrollbar{height:0}
        [x-cloak]{display:none!important}
        body,body>div{min-height:100vh;min-height:100dvh}
        body{font-family:Inter,system-ui,sans-serif}
    </style>
    @if(isset($jsonLd))
    <script type="application/ld+json">{{ json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</script>
    @endif
    @stack('head')
</head>
<body>

@php
    $whatsappNum = $restaurant->whatsapp ? preg_replace('/\D/', '', $restaurant->whatsapp) : null;
@endphp

<div x-data="menuCart('{{ $restaurant->slug }}', {{ $restaurant->accepts_orders ? 'true' : 'false' }}, '{{ $whatsappNum }}', {{ $restaurant->accepts_delivery ? 'true' : 'false' }}, {{ $restaurant->delivery_cost ?? 0 }}, {{ $restaurant->min_order ?? 0 }}, '{{ session("nfc_table_label", "") }}', '{{ route("wa.click") }}', '{{ csrf_token() }}')"
     style="min-height:100vh;min-height:100dvh;">

    @yield('body')

</div>

@include('components.menu-cart', ['restaurant' => $restaurant])

</body>
</html>
