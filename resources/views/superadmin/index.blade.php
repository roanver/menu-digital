<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<!-- Header -->
<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-10">
    <div class="max-w-[1200px] mx-auto px-6 h-[54px] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-[28px] h-[28px] rounded-[8px] bg-[#4F46E5] flex items-center justify-center">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <span class="text-[14px] font-bold text-[#111827]">MenuDigital</span>
            <span class="text-[#D1D5DB]">/</span>
            <span class="text-[13px] font-semibold text-[#6B7280]">Super Admin</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[12.5px] text-[#6B7280]">{{ auth()->user()->email }}</span>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="button" onclick="document.getElementById('logout-form').submit()"
                        class="text-[12.5px] font-semibold text-[#374151] hover:text-[#111827] transition-colors cursor-pointer">
                    Salir
                </button>
            </form>
        </div>
    </div>
</header>

<div class="max-w-[1200px] mx-auto px-6 py-8">

    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[12px] p-4 flex items-center gap-3 mb-6">
        <svg class="flex-none" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @foreach([
            ['Total', $stats['total'], '#4F46E5', '#EEF2FF', '#E0E7FF'],
            ['En trial', $stats['trial'], '#0891B2', '#ECFEFF', '#A5F3FC'],
            ['Pagados', $stats['paid'], '#059669', '#ECFDF5', '#6EE7B7'],
            ['Expirados', $stats['expired'], '#DC2626', '#FEF2F2', '#FECACA'],
        ] as [$label, $value, $color, $bg, $border])
        <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-4">
            <p class="text-[11.5px] font-semibold text-[#6B7280] mb-1">{{ $label }}</p>
            <p class="text-[28px] font-bold leading-none" style="color:{{ $color }}">{{ $value }}</p>
        </div>
        @endforeach
    </div>

    <!-- Table card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-[13px] font-bold text-[#111827]">Restaurantes ({{ $restaurants->count() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#F3F4F6]">
                        @foreach(['Restaurante', 'Owner', 'Plan', 'Trial ends', 'Sub ends', 'Activo', 'Acciones'] as $col)
                        <th class="text-left px-4 py-3 text-[11.5px] font-semibold text-[#6B7280] whitespace-nowrap">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                    @php
                        $owner = $restaurant->users->first();
                        $planMeta = [
                            'trial'   => ['label' => 'Trial',   'class' => 'bg-[#EEF2FF] text-[#4F46E5]'],
                            'basic'   => ['label' => 'Basic',   'class' => 'bg-[#ECFDF5] text-[#059669]'],
                            'pro'     => ['label' => 'Pro',     'class' => 'bg-[#F5F3FF] text-[#7C3AED]'],
                            'expired' => ['label' => 'Expirado','class' => 'bg-[#FEF2F2] text-[#DC2626]'],
                        ][$restaurant->plan] ?? ['label' => $restaurant->plan, 'class' => 'bg-[#F3F4F6] text-[#374151]'];
                    @endphp
                    <tr class="border-b border-[#F9FAFB] hover:bg-[#FAFAFA] transition-colors {{ $restaurant->deleted_at ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#111827]">{{ $restaurant->name }}</p>
                            <p class="text-[11px] text-[#9CA3AF]">/{{ $restaurant->slug }}</p>
                            @if($restaurant->deleted_at)
                                <span class="text-[10.5px] text-red-500 font-semibold">eliminado</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[#4B5563]">{{ $owner?->email ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $planMeta['class'] }}">
                                {{ $planMeta['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[#4B5563]">{{ $restaurant->trial_ends_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-[#4B5563]">{{ $restaurant->subscription_ends_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($restaurant->is_active)
                                <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#059669]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-[#059669]"></span> Sí
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[#DC2626]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-[#DC2626]"></span> No
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button
                                onclick="openModal({{ $restaurant->id }}, '{{ $restaurant->plan }}', '{{ $restaurant->trial_ends_at?->format('Y-m-d') ?? '' }}', '{{ $restaurant->subscription_ends_at?->format('Y-m-d') ?? '' }}', {{ $restaurant->is_active ? 'true' : 'false' }})"
                                class="inline-flex items-center gap-1.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[8px] px-3 py-1.5 text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                                Editar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-[13px] text-[#9CA3AF]">No hay restaurantes registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(17,24,39,.4);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-[16px] shadow-[0_20px_60px_rgba(16,24,40,.18)] w-full max-w-[400px] p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[14px] font-bold text-[#111827]">Editar restaurante</h3>
            <button onclick="closeModal()" class="text-[#9CA3AF] hover:text-[#374151] transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="modal-form" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1">Plan</label>
                <select id="modal-plan" name="plan"
                    class="w-full px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                    <option value="trial">Trial</option>
                    <option value="basic">Basic</option>
                    <option value="pro">Pro</option>
                    <option value="expired">Expirado</option>
                </select>
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1">Trial vence</label>
                <input id="modal-trial" type="date" name="trial_ends_at"
                    class="w-full px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1">Suscripción vence</label>
                <input id="modal-sub" type="date" name="subscription_ends_at"
                    class="w-full px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
            </div>

            <div class="flex items-center gap-3">
                <label class="text-[12px] font-semibold text-[#374151]">Activo</label>
                <input id="modal-active" type="hidden" name="is_active" value="0">
                <button type="button" id="toggle-active" onclick="toggleActive()"
                    class="relative inline-flex h-[22px] w-[40px] items-center rounded-full transition-colors focus:outline-none">
                    <span id="toggle-knob" class="inline-block h-[16px] w-[16px] transform rounded-full bg-white shadow-sm transition-transform"></span>
                </button>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="flex-1 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    Guardar
                </button>
                <button type="button" onclick="closeModal()"
                    class="flex-1 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[10px] px-4 py-[9px] text-[13px] font-semibold transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const baseUrl = '/superadmin/restaurants/';
let isActive = false;

function openModal(id, plan, trial, sub, active) {
    document.getElementById('modal-form').action = baseUrl + id + '/plan';
    document.getElementById('modal-plan').value = plan;
    document.getElementById('modal-trial').value = trial;
    document.getElementById('modal-sub').value = sub;
    isActive = active;
    updateToggle();
    document.getElementById('modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

function toggleActive() {
    isActive = !isActive;
    updateToggle();
}

function updateToggle() {
    const btn = document.getElementById('toggle-active');
    const knob = document.getElementById('toggle-knob');
    document.getElementById('modal-active').value = isActive ? '1' : '0';
    btn.style.backgroundColor = isActive ? '#4F46E5' : '#D1D5DB';
    knob.style.transform = isActive ? 'translateX(20px)' : 'translateX(2px)';
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>
