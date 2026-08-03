<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin — MenuDigital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 p-6">

    <div class="max-w-7xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Super Admin — MenuDigital</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Salir</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-md text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Restaurante</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Slug</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Owner</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Plan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Trial ends</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Sub ends</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Activo</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Actualizar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($restaurants as $restaurant)
                        @php
                            $planColors = [
                                'trial'   => 'bg-blue-100 text-blue-800',
                                'basic'   => 'bg-green-100 text-green-800',
                                'pro'     => 'bg-purple-100 text-purple-800',
                                'expired' => 'bg-red-100 text-red-800',
                            ];
                            $badgeColor = $planColors[$restaurant->plan] ?? 'bg-gray-100 text-gray-800';
                            $ownerEmail = optional($restaurant->users->first())->email ?? '—';
                        @endphp
                        <tr class="{{ $restaurant->deleted_at ? 'opacity-50' : '' }}">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $restaurant->name }}
                                @if($restaurant->deleted_at)
                                    <span class="ml-1 text-xs text-red-500">(eliminado)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $restaurant->slug }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $ownerEmail }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-bold {{ $badgeColor }}">
                                    {{ strtoupper($restaurant->plan) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ optional($restaurant->trial_ends_at)->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ optional($restaurant->subscription_ends_at)->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($restaurant->is_active)
                                    <span class="text-green-600 font-semibold">Si</span>
                                @else
                                    <span class="text-red-500 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST"
                                      action="{{ route('superadmin.plan.update', $restaurant) }}"
                                      class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="plan"
                                            class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                                        @foreach(['trial', 'basic', 'pro', 'expired'] as $planOption)
                                            <option value="{{ $planOption }}"
                                                    {{ $restaurant->plan === $planOption ? 'selected' : '' }}>
                                                {{ ucfirst($planOption) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="date"
                                           name="subscription_ends_at"
                                           value="{{ optional($restaurant->subscription_ends_at)->format('Y-m-d') }}"
                                           class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">

                                    <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors">
                                        Guardar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-400">No hay restaurantes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
