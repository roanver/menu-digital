<?php

namespace App\Http\Controllers\Admin;

use App\Models\BusinessHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessHoursController extends AdminController
{
    public function index(): View
    {
        $restaurant = auth()->user()->restaurant;

        $hours = [];
        $existing = BusinessHour::where('restaurant_id', $restaurant->id)
            ->get()
            ->keyBy('day_of_week');

        for ($d = 0; $d <= 6; $d++) {
            $hours[$d] = $existing[$d] ?? new BusinessHour([
                'restaurant_id' => $restaurant->id,
                'day_of_week'   => $d,
                'is_closed'     => false,
            ]);
        }

        return view('admin.hours', compact('restaurant', 'hours'));
    }

    public function update(Request $request): RedirectResponse
    {
        $restaurant = auth()->user()->restaurant;

        $validated = $request->validate([
            'hours'                  => ['required', 'array'],
            'hours.*.is_closed'      => ['nullable', 'boolean'],
            'hours.*.opens_at'       => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at'      => ['nullable', 'date_format:H:i'],
            'hours.*.opens_at_2'     => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at_2'    => ['nullable', 'date_format:H:i'],
        ]);

        foreach ($validated['hours'] as $day => $data) {
            $day = (int) $day;
            if ($day < 0 || $day > 6) continue;

            BusinessHour::updateOrCreate(
                ['restaurant_id' => $restaurant->id, 'day_of_week' => $day],
                [
                    'is_closed'   => (bool) ($data['is_closed'] ?? false),
                    'opens_at'    => $data['opens_at']    ?? null,
                    'closes_at'   => $data['closes_at']   ?? null,
                    'opens_at_2'  => $data['opens_at_2']  ?? null,
                    'closes_at_2' => $data['closes_at_2'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Horarios actualizados correctamente.');
    }
}
