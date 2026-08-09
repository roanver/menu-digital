<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuAudit;
use App\Models\MenuItem;
use App\Services\AnthropicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuAdvisorController extends AdminController
{
    public function show(): View
    {
        $restaurant = $this->restaurant();

        $audit = MenuAudit::where('restaurant_id', $restaurant->id)
            ->latest()
            ->first();

        $itemsCount = MenuItem::where('restaurant_id', $restaurant->id)->count();

        return view('admin.advisor.show', compact('restaurant', 'audit', 'itemsCount'));
    }

    public function generate(): RedirectResponse
    {
        $restaurant = $this->restaurant();

        $categories = Category::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->with(['menuItems' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return redirect()->route('admin.advisor.show')
                ->with('error', 'Agrega al menos una categoría con ítems antes de analizar la carta.');
        }

        $menuData = $categories->map(fn ($cat) => [
            'name'  => $cat->name,
            'items' => $cat->menuItems->map(fn ($item) => [
                'name'         => $item->name,
                'price'        => $item->price,
                'description'  => $item->description ?? '',
                'is_available' => $item->is_available,
            ])->all(),
        ])->all();

        try {
            $service = new AnthropicService();
            $result  = $service->analyzeMenu($menuData);

            MenuAudit::create([
                'restaurant_id' => $restaurant->id,
                'suggestions'   => $result['suggestions'],
                'score'         => $result['score'],
            ]);

            return redirect()->route('admin.advisor.show')
                ->with('success', 'Análisis completado. Revisa las sugerencias a continuación.');
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.advisor.show')
                ->with('error', 'Error al analizar la carta: ' . $e->getMessage());
        }
    }
}
