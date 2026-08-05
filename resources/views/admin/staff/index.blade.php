<x-admin-layout>
@push('pageTitle')Equipo@endpush
<div class="max-w-[740px] space-y-5">

    @if(session('staff_password'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[14px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <div>
            <p class="text-[13px] font-semibold text-[#065F46]">Acceso creado para {{ session('staff_email') }}</p>
            <p class="text-[12px] text-[#047857] mt-0.5">Contraseña temporal: <span class="font-mono font-bold bg-white rounded px-1.5 py-0.5 border border-[#A7F3D0]">{{ session('staff_password') }}</span> — compártela de forma segura. El empleado puede cambiarla desde su perfil.</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[14px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[14px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-[13px] font-semibold text-[#991B1B]">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-5 items-start">

        <!-- Staff list -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6] flex items-center justify-between">
                <div>
                    <h2 class="text-[13px] font-bold text-[#111827]">Empleados activos</h2>
                    <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Acceden al panel con su propio correo</p>
                </div>
                @if($staff->count() > 0)
                <span class="text-[11.5px] font-bold text-[#4F46E5] bg-[#EEF2FF] rounded-full px-[9px] py-[2px]">{{ $staff->count() }}</span>
                @endif
            </div>

            @if($staff->isEmpty())
            <div class="px-5 py-12 text-center">
                <div class="w-[52px] h-[52px] rounded-[16px] bg-[#F3F4F6] flex items-center justify-center mx-auto mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <p class="text-[14px] font-semibold text-[#374151] mb-1">Sin empleados aún</p>
                <p class="text-[12.5px] text-[#9CA3AF]">Invita a tu primer empleado con el formulario.</p>
            </div>
            @else
            <div class="divide-y divide-[#F3F4F6]">
                @foreach($staff as $employee)
                <div class="flex items-center gap-3 px-5 py-4 hover:bg-[#FAFBFF] transition-colors">
                    <div class="w-[40px] h-[40px] rounded-full bg-gradient-to-br from-[#4F46E5] to-[#6366f1] flex items-center justify-center flex-none shadow-sm">
                        <span class="text-[14px] font-bold text-white">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13.5px] font-semibold text-[#111827] truncate">{{ $employee->name }}</p>
                        <p class="text-[12px] text-[#6B7280] truncate">{{ $employee->email }}</p>
                    </div>
                    <span class="text-[10.5px] font-bold text-[#4F46E5] bg-[#EEF2FF] rounded-[6px] px-[7px] py-[2px] flex-none">Staff</span>
                    <form method="POST" action="{{ route('admin.staff.destroy', $employee) }}" onsubmit="return confirm('¿Eliminar a {{ $employee->name }}? Perderá el acceso al panel.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-[32px] h-[32px] flex items-center justify-center rounded-[8px] text-[#6B7280] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Invite card -->
        <div class="bg-white border border-[#E5E7EB] rounded-[16px] shadow-[0_1px_3px_rgba(16,24,40,.05)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F3F4F6]">
                <h2 class="text-[13px] font-bold text-[#111827]">Invitar empleado</h2>
                <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">Se generará una contraseña temporal</p>
            </div>
            <form method="POST" action="{{ route('admin.staff.store') }}" class="px-5 py-5 space-y-4">
                @csrf
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Nombre</span>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                        placeholder="Ej: María González">
                    @error('name')<p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>@enderror
                </label>
                <label class="flex flex-col gap-[6px]">
                    <span class="text-[12px] font-semibold text-[#374151]">Correo electrónico</span>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-[10px] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] shadow-[0_1px_2px_rgba(16,24,40,.03)] focus:border-[#4F46E5] focus:shadow-[0_0_0_3px_rgba(79,70,229,.14)] focus:outline-none placeholder:text-[#9CA3AF]"
                        placeholder="maria@ejemplo.com">
                    @error('email')<p class="text-[11.5px] text-[#DC2626]">{{ $message }}</p>@enderror
                </label>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                    Crear acceso
                </button>
            </form>
        </div>

    </div>

</div>
</x-admin-layout>
