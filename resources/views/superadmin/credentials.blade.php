<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Credenciales · Super Admin · MenuDigital</title>
    @vite(['resources/css/app.css'])
    <style>body { font-family: system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-20">
    <div class="max-w-[700px] mx-auto px-6 h-[56px] flex items-center gap-3">
        <a href="{{ route('superadmin.index') }}" class="text-[13px] font-medium text-[#6B7280] hover:text-[#111827] no-underline">← Super Admin</a>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[13px] font-semibold text-[#059669]">Restaurante creado</span>
    </div>
</header>

<div class="max-w-[700px] mx-auto px-6 py-8 space-y-5">

    <div class="bg-[#ECFDF5] border border-[#A7F3D0] rounded-[16px] p-5">
        <div class="text-[14px] font-bold text-[#047857] mb-1">Restaurante creado correctamente</div>
        <div class="text-[12.5px] text-[#065F46]">Guarda estas credenciales antes de cerrar esta página.</div>
    </div>

    @if($data['restaurant'])
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5 space-y-3">
        <h2 class="text-[13px] font-bold text-[#111827]">Restaurante</h2>
        <div class="grid grid-cols-2 gap-3 text-[13px]">
            <div><span class="text-[#9CA3AF] text-[11px] block mb-[2px] font-semibold uppercase tracking-wide">Nombre</span>{{ $data['restaurant']->name }}</div>
            <div><span class="text-[#9CA3AF] text-[11px] block mb-[2px] font-semibold uppercase tracking-wide">Slug</span>/{{ $data['restaurant']->slug }}</div>
            <div><span class="text-[#9CA3AF] text-[11px] block mb-[2px] font-semibold uppercase tracking-wide">Plan</span>{{ ucfirst($data['restaurant']->plan) }}</div>
        </div>
    </div>
    @endif

    @if($data['owner'])
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5 space-y-3">
        <h2 class="text-[13px] font-bold text-[#111827]">Credenciales del dueño</h2>
        <div class="space-y-2 text-[13px]">
            <div class="flex items-center gap-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] px-3 py-2.5">
                <span class="text-[#9CA3AF] text-[12px] w-[70px]">Email</span>
                <code class="font-mono font-semibold text-[#111827] select-all">{{ $data['owner']->email }}</code>
            </div>
            <div class="flex items-center gap-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] px-3 py-2.5">
                <span class="text-[#9CA3AF] text-[12px] w-[70px]">Contraseña</span>
                <code class="font-mono font-semibold text-[#111827] select-all">{{ $data['password'] }}</code>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($data['tags']))
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5 space-y-3">
        <h2 class="text-[13px] font-bold text-[#111827]">Códigos NFC generados ({{ count($data['tags']) }})</h2>
        <div class="space-y-2">
            @foreach($data['tags'] as $tag)
            <div class="flex items-center gap-3 bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] px-3 py-2.5">
                <code class="font-mono text-[13px] font-bold text-[#4F46E5] bg-[#EEF2FF] px-2 py-[2px] rounded-[6px]">{{ $tag->code }}</code>
                <span class="text-[13px] text-[#374151]">{{ $tag->label }}</span>
                <span class="ml-auto text-[11px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-full px-2 py-[2px]">{{ $tag->type }}</span>
            </div>
            @endforeach
        </div>
        <p class="text-[12px] text-[#9CA3AF]">URL para grabar en cada chip: <code class="text-[#4F46E5]">{{ url('/m/') }}/{código}</code></p>
    </div>
    @endif

    <a href="{{ route('superadmin.create') }}"
       class="block text-center bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[12px] px-4 py-3 text-[13px] font-semibold no-underline transition-colors">
        Crear otro restaurante
    </a>
</div>

</body>
</html>
