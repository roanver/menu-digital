<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Services\MenuCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends AdminController
{
    public function index(): View
    {
        $restaurant = auth()->user()->restaurant;
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.categories.index', compact('categories', 'restaurant'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = auth()->user()->restaurant;

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $maxSort = Category::where('restaurant_id', $restaurant->id)->max('sort_order') ?? 0;

        Category::create([
            'restaurant_id' => $restaurant->id,
            'name'          => $validated['name'],
            'is_active'     => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
            'sort_order'    => $maxSort + 1,
        ]);

        MenuCacheService::forget($restaurant);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        $this->authorizeCategory($category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name'      => $validated['name'],
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : false,
        ]);

        MenuCacheService::forget(auth()->user()->restaurant);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $category->delete();

        MenuCacheService::forget(auth()->user()->restaurant);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->restaurant;

        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $order => $id) {
            Category::where('id', $id)
                ->where('restaurant_id', $restaurant->id)
                ->update(['sort_order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function authorizeCategory(Category $category): void
    {
        if ($category->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403);
        }
    }
}
