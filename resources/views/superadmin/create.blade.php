<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear restaurante · Super Admin · MenuDigital</title>
    @vite(['resources/css/app.css'])
    <style>body { font-family: system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-20">
    <div class="max-w-[700px] mx-auto px-6 h-[56px] flex items-center gap-3">
        <a href="{{ route('superadmin.index') }}" class="text-[13px] font-medium text-[#6B7280] hover:text-[#111827] no-underline">← Super Admin</a>
        <span class="text-[#D1D5DB]">/</span>
        <span class="text-[13px] font-semibold text-[#4F46E5]">Nuevo restaurante</span>
    </div>
</header>

<div class="max-w-[700px] mx-auto px-6 py-8">

    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 mb-6 text-[13px] text-[#DC2626]">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5 space-y-4">
            <h2 class="text-[13px] font-bold text-[#111827]">Restaurante</h2>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Nombre del restaurante</label>
                <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Slug (URL)</label>
                <div class="flex items-center gap-2">
                    <span class="text-[13px] text-[#9CA3AF]">menudigital.app/</span>
                    <input type="text" name="restaurant_slug" value="{{ old('restaurant_slug') }}" required
                        class="flex-1 px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                </div>
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Plan</label>
                <select name="plan" class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                    <option value="carta">Carta ($8.000/mes)</option>
                    <option value="pedidos">Pedidos ($15.000/mes)</option>
                    <option value="full">Full ($25.000/mes)</option>
                </select>
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Número de mesas (chips NFC de mesa)</label>
                <input type="number" name="tables" value="{{ old('tables', 0) }}" min="0" max="50"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                <p class="text-[11.5px] text-[#9CA3AF] mt-1">Se crearán tags NFC tipo menú para cada mesa, más uno para el QR general.</p>
            </div>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-[16px] p-5 space-y-4">
            <h2 class="text-[13px] font-bold text-[#111827]">Usuario dueño</h2>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Nombre</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}" required
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Email</label>
                <input type="email" name="owner_email" value="{{ old('owner_email') }}" required
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Contraseña temporal</label>
                <input type="text" name="owner_password" value="{{ old('owner_password') }}" required
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] focus:outline-none focus:border-[#4F46E5]">
                <p class="text-[11.5px] text-[#9CA3AF] mt-1">El dueño debería cambiarla al primer ingreso.</p>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-[#4F46E5] hover:bg-[#4338CA] text-white rounded-[12px] px-4 py-[12px] text-[14px] font-bold shadow-[0_2px_8px_rgba(79,70,229,.4)] transition-colors">
            Crear restaurante y generar credenciales
        </button>
    </form>
</div>

</body>
</html>
