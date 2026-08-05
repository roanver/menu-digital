<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin · MenuDigital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] text-[#111827] antialiased">

<!-- Header -->
<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-20 shadow-[0_1px_3px_rgba(16,24,40,.05)]">
    <div class="max-w-[1200px] mx-auto px-6 h-[56px] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-[30px] h-[30px] rounded-[9px] bg-[#4F46E5] flex items-center justify-center">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7"/>
                </svg>
            </div>
            <span class="text-[15px] font-bold text-[#111827]">MenuDigital</span>
            <span class="text-[#D1D5DB] font-light">/</span>
            <span class="text-[13px] font-semibold text-[#4F46E5] bg-[#EEF2FF] rounded-[7px] px-[8px] py-[3px]">Super Admin</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.nfc.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] text-[12.5px] font-semibold px-3 py-1.5 rounded-[8px] no-underline transition-colors">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8.32a7.43 7.43 0 0 1 0 7.36M9.46 6.21a11.76 11.76 0 0 1 0 11.58M12.91 4.1a15.91 15.91 0 0 1 .01 15.8"/></svg>
                Tags NFC
            </a>
            <span class="text-[12px] text-[#9CA3AF]">{{ auth()->user()->email }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="text-[12.5px] font-semibold text-[#374151] hover:text-[#111827] transition-colors cursor-pointer">
                    Salir
                </button>
            </form>
        </div>
    </div>
</header>

<div class="max-w-[1200px] mx-auto px-6 py-8">

    @if($errors->any())
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[12px] p-4 flex items-center gap-3 mb-6">
        <svg class="flex-none" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-[13px] font-semibold text-[#DC2626]">{{ $errors->first() }}</p>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[12px] p-4 flex items-center gap-3 mb-6">
        <svg class="flex-none" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="rounded-[18px] p-5 hover:-translate-y-[2px] transition-all" style="background:linear-gradient(135deg,#1e1b4b,#312e81)">
            <p class="text-[11px] font-bold uppercase tracking-[.1em] text-indigo-300 mb-3">Total</p>
            <p class="text-[36px] font-bold leading-none text-white">{{ $stats['total'] }}</p>
            <p class="text-[12px] text-indigo-200 mt-1">restaurantes</p>
        </div>
        <div class="bg-white border border-[#E5E7EB] rounded-[18px] p-5 hover:shadow-[0_4px_12px_rgba(16,24,40,.1)] hover:-translate-y-[2px] transition-all">
            <p class="text-[11px] font-bold uppercase tracking-[.1em] text-[#0891B2] mb-3">Trial</p>
            <p class="text-[36px] font-bold leading-none text-[#0891B2]">{{ $stats['trial'] }}</p>
            <p class="text-[12px] text-[#9CA3AF] mt-1">en período de prueba</p>
        </div>
        <div class="bg-white border border-[#E5E7EB] rounded-[18px] p-5 hover:shadow-[0_4px_12px_rgba(16,24,40,.1)] hover:-translate-y-[2px] transition-all">
            <p class="text-[11px] font-bold uppercase tracking-[.1em] text-[#059669] mb-3">Pagados</p>
            <p class="text-[36px] font-bold leading-none text-[#059669]">{{ $stats['paid'] }}</p>
            <p class="text-[12px] text-[#9CA3AF] mt-1">suscripción activa</p>
        </div>
        <div class="bg-white border border-[#FEE2E2] rounded-[18px] p-5 hover:shadow-[0_4px_12px_rgba(220,38,38,.08)] hover:-translate-y-[2px] transition-all">
            <p class="text-[11px] font-bold uppercase tracking-[.1em] text-[#DC2626] mb-3">Expirados</p>
            <p class="text-[36px] font-bold leading-none text-[#DC2626]">{{ $stats['expired'] }}</p>
            <p class="text-[12px] text-[#9CA3AF] mt-1">sin plan activo</p>
        </div>
    </div>

    <!-- Table card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-[13px] font-bold text-[#111827]">Restaurantes</h2>
            <span class="text-[11.5px] font-semibold text-[#6B7280] bg-[#F3F4F6] rounded-full px-[9px] py-[2px]">{{ $restaurants->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#F3F4F6]">
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Restaurante</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Owner</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Plan</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Trial ends</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Sub ends</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Activo</th>
                        <th class="text-left px-4 py-3 text-[11px] font-bold uppercase tracking-[.06em] text-[#6B7280]">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                    @php
                        $owner = $restaurant->users->first();
                        $planMeta = [
                            'carta'   => ['label' => 'Carta',   'class' => 'bg-[#EEF2FF] text-[#4F46E5]'],
                            'pedidos' => ['label' => 'Pedidos', 'class' => 'bg-[#ECFDF5] text-[#059669]'],
                            'full'    => ['label' => 'Full',    'class' => 'bg-[#F5F3FF] text-[#7C3AED]'],
                            'trial'   => ['label' => 'Trial',   'class' => 'bg-[#FEF3C7] text-[#B45309]'],
                            'expired' => ['label' => 'Expirado','class' => 'bg-[#FEF2F2] text-[#DC2626]'],
                        ][$restaurant->plan] ?? ['label' => $restaurant->plan, 'class' => 'bg-[#F3F4F6] text-[#374151]'];
                    @endphp
                    <tr class="border-b border-[#F9FAFB] hover:bg-[#FAFAFA] transition-colors {{ $restaurant->deleted_at ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-[#111827]">{{ $restaurant->name }}</p>
                            <p class="text-[11px] text-[#9CA3AF] font-mono mt-0.5">/{{ $restaurant->slug }}</p>
                            @if($restaurant->deleted_at)
                                <span class="text-[10.5px] text-red-500 font-semibold">eliminado</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-[#4B5563]">{{ $owner?->email ?? '—' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $planMeta['class'] }}">
                                {{ $planMeta['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-[#4B5563]">{{ $restaurant->trial_ends_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3.5 text-[#4B5563]">{{ $restaurant->subscription_ends_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3.5">
                            @if($restaurant->is_active)
                                <span class="inline-flex items-center gap-1.5 text-[11.5px] font-semibold text-[#059669]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-[#059669]"></span> Sí
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-[11.5px] font-semibold text-[#DC2626]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-[#DC2626]"></span> No
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <button
                                onclick="openModal({{ $restaurant->id }}, '{{ $restaurant->plan }}', '{{ $restaurant->trial_ends_at?->format('Y-m-d') ?? '' }}', '{{ $restaurant->subscription_ends_at?->format('Y-m-d') ?? '' }}', {{ $restaurant->is_active ? 'true' : 'false' }})"
                                class="inline-flex items-center gap-1.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#E5E7EB] rounded-[8px] px-3 py-1.5 text-[12px] font-semibold shadow-[0_1px_2px_rgba(16,24,40,.04)] transition-colors">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                Editar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-[13px] text-[#9CA3AF]">No hay restaurantes registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(17,24,39,.5);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-[18px] shadow-[0_20px_60px_rgba(16,24,40,.2)] w-full max-w-[420px] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#F3F4F6] flex items-center justify-between">
            <h3 class="text-[14px] font-bold text-[#111827]">Editar restaurante</h3>
            <button onclick="closeModal()" class="w-[28px] h-[28px] flex items-center justify-center rounded-[8px] text-[#9CA3AF] hover:bg-[#F3F4F6] hover:text-[#374151] transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="modal-form" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Plan</label>
                <select id="modal-plan" name="plan"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
                    <option value="carta">Carta ($8.000/mes)</option>
                    <option value="pedidos">Pedidos ($15.000/mes)</option>
                    <option value="full">Full ($25.000/mes)</option>
                </select>
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Trial vence</label>
                <input id="modal-trial" type="date" name="trial_ends_at"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1.5">Suscripción vence</label>
                <input id="modal-sub" type="date" name="subscription_ends_at"
                    class="w-full px-3 py-[10px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.12)]">
            </div>

            <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-[10px] border border-[#E5E7EB]">
                <div>
                    <label class="text-[13px] font-semibold text-[#111827]">Activo</label>
                    <p class="text-[11px] text-[#9CA3AF] mt-0.5">El restaurante puede acceder al panel</p>
                </div>
                <input id="modal-active" type="hidden" name="is_active" value="0">
                <button type="button" id="toggle-active" onclick="toggleActive()"
                    class="relative inline-flex h-[24px] w-[42px] items-center rounded-full transition-colors focus:outline-none">
                    <span id="toggle-knob" class="inline-block h-[20px] w-[20px] transform rounded-full bg-white shadow-md transition-transform"></span>
                </button>
            </div>

            <div class="flex gap-2 pt-1">
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
