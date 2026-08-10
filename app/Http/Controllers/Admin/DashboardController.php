<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\NfcScan;
use App\Models\NfcTag;
use App\Models\WhatsappClick;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\RestaurantTable;

class DashboardController extends AdminController
{
    public function index(): View
    {
        $restaurant = $this->restaurant();

        $categoriesCount = Category::where('restaurant_id', $restaurant->id)->count();
        $itemsCount      = MenuItem::where('restaurant_id', $restaurant->id)->count();

        // IDs de tags del restaurante
        $tagIds = NfcTag::where('restaurant_id', $restaurant->id)->pluck('id');

        // Escaneos por día — últimos 14 días
        $today     = now()->toDateString();
        $day14ago  = now()->subDays(13)->toDateString();

        $scanRows = NfcScan::whereIn('nfc_tag_id', $tagIds)
            ->whereBetween('date', [$day14ago, $today])
            ->select('date', DB::raw('SUM(count) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Construir array de 14 días
        $days = [];
        for ($i = 13; $i >= 0; $i--) {
            $d       = now()->subDays($i)->toDateString();
            $days[]  = ['date' => $d, 'total' => (int) ($scanRows[$d]->total ?? 0)];
        }

        // Comparación 7 días vs 7 días anteriores
        $last7  = array_sum(array_column(array_slice($days, 7), 'total'));
        $prev7  = array_sum(array_column(array_slice($days, 0, 7), 'total'));
        $scanDelta = $prev7 > 0 ? round(($last7 - $prev7) / $prev7 * 100) : ($last7 > 0 ? 100 : 0);

        // Total del mes en curso
        $monthTotal = NfcScan::whereIn('nfc_tag_id', $tagIds)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');

        // Desglose por tag
        $tagBreakdown = NfcTag::where('restaurant_id', $restaurant->id)
            ->withSum(['scans as month_scans' => fn ($q) => $q->whereYear('date', now()->year)->whereMonth('date', now()->month)], 'count')
            ->orderByDesc('month_scans')
            ->get();

        $waClicksMonth = WhatsappClick::where('restaurant_id', $restaurant->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');

        $tableScans = collect();
        if (config('plans.plans.'.$restaurant->plan.'.max_tables', 0) !== 0) {
            $tableScans = $restaurant->tables()
                ->with(['nfcTag' => fn($q) => $q->with(['scans' => fn($q) => $q->whereYear('date', now()->year)->whereMonth('date', now()->month)])])
                ->get()
                ->map(fn($t) => [
                    'name'  => $t->name,
                    'scans' => $t->nfcTag ? $t->nfcTag->scans->sum('count') : 0,
                ])
                ->sortByDesc('scans')
                ->values();
        }

        return view('admin.dashboard', compact(
            'restaurant', 'categoriesCount', 'itemsCount',
            'days', 'last7', 'prev7', 'scanDelta', 'monthTotal', 'tagBreakdown',
            'waClicksMonth', 'tableScans'
        ));
    }
}
