<x-admin-layout>
@push('pageTitle')Equipo@endpush
<div class="max-w-[740px] space-y-5">

    @if(session('staff_password'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[14px] p-4 flex gap-3">
        <svg class="flex-none mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <div>
            <p class="text-[13px] font-semibold text-[#065F46]">Acceso creado para {{ session('staff_email') }}</p>
            <p class="text-[12px] text-[#047857] mt-0.5">Contraseña temporal: <span class="font-mono font-bold">{{ session('staff_password') }}</span> — compártela de forma segura. El empleado puede cambiarla desde su perfil.</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-[#ECFDF5] border border-[#6EE7B7] rounded-[14px] p-4">
        <p class="text-[13px] font-semibold text-[#065F46]">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[14px] p-4">
        <p class="text-[13px] font-semibold text-[#991B1B]">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Invite card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-5">
        <h2 class="text-[13px] font-bold text-[#111827] mb-4">Invitar empleado</h2>
        <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent"
                    placeholder="Ej: María González">
                @error('name')<p class="text-[11.5px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-[12px] font-semibold text-[#374151] mb-1">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-[9px] bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px] text-[13px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent"
                    placeholder="maria@ejemplo.com">
                @error('email')<p class="text-[11.5px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-[#4F46E5] hover:bg-[#4338CA] text-white border border-[#4338CA] rounded-[10px] px-[14px] py-[9px] text-[13px] font-semibold shadow-[0_1px_2px_rgba(79,70,229,.35)] transition-colors">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                Crear acceso
            </button>
        </form>
    </div>

    <!-- Staff list card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[14px] shadow-[0_1px_2px_rgba(16,24,40,.04)] p-5">
        <h2 class="text-[13px] font-bold text-[#111827] mb-4">Empleados activos</h2>

        @if($staff->isEmpty())
        <p class="text-[13px] text-[#6B7280] text-center py-6">No hay empleados aún. Invita a tu primer empleado arriba.</p>
        @else
        <div class="space-y-2">
            @foreach($staff as $employee)
            <div class="flex items-center gap-3 p-3 bg-[#F9FAFB] border border-[#E5E7EB] rounded-[10px]">
                <div class="w-[34px] h-[34px] rounded-full bg-[#EEF2FF] border border-[#E0E7FF] flex items-center justify-center flex-none">
                    <span class="text-[12px] font-bold text-[#4F46E5]">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-[#111827] truncate">{{ $employee->name }}</p>
                    <p class="text-[12px] text-[#6B7280] truncate">{{ $employee->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.staff.destroy', $employee) }}" onsubmit="return confirm('¿Eliminar a {{ $employee->name }}? Perderá el acceso al panel.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[12px] font-semibold text-red-500 hover:text-red-700 transition-colors px-2 py-1">
                        Eliminar
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
</x-admin-layout>
