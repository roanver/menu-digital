<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Services\MenuCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuItemController extends AdminController
{
    public function index(): View
    {
        $restaurant = auth()->user()->restaurant;

        $categories = Category::where('restaurant_id', $restaurant->id)
            ->with(['menuItems' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id');
            }])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.items.index', compact('categories', 'restaurant'));
    }

    public function create(): View
    {
        $restaurant = auth()->user()->restaurant;
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = auth()->user()->restaurant;
        $restaurantCategoryIds = Category::where('restaurant_id', $restaurant->id)->pluck('id')->toArray();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'in:' . implode(',', $restaurantCategoryIds ?: [0])],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_available'=> ['nullable', 'boolean'],
            'is_promo'    => ['nullable', 'boolean'],
            'promo_price' => ['nullable', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'variants'    => ['nullable', 'array'],
            'variants.*.name'        => ['required_with:variants', 'string', 'max:255'],
            'variants.*.price_delta' => ['nullable', 'integer'],
        ], [
            'image.max'   => 'La imagen no debe superar los 2 MB.',
            'image.image' => 'El archivo debe ser una imagen (jpg, png, webp).',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $this->saveImageAsWebp($request->file('image'));
        }

        $maxSort = MenuItem::where('restaurant_id', $restaurant->id)->max('sort_order') ?? 0;
        $isPromo = isset($validated['is_promo']) ? (bool) $validated['is_promo'] : false;

        $item = MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id'   => $validated['category_id'],
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'image'         => $imagePath,
            'is_available'  => isset($validated['is_available']) ? (bool) $validated['is_available'] : true,
            'is_promo'      => $isPromo,
            'promo_price'   => $isPromo ? ($validated['promo_price'] ?? null) : null,
            'sort_order'    => $maxSort + 1,
        ]);

        $this->syncVariants($item, $request->input('variants', []));

        MenuCacheService::forget($restaurant);

        return redirect()->route('admin.items.index')
            ->with('success', 'Ítem creado correctamente.');
    }

    public function edit(MenuItem $item): View
    {
        $this->authorizeItem($item);

        $restaurant = auth()->user()->restaurant;
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->orderBy('sort_order')
            ->get();

        $item->load('variants');

        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, MenuItem $item): RedirectResponse
    {
        $this->authorizeItem($item);

        $restaurant = auth()->user()->restaurant;
        $restaurantCategoryIds = Category::where('restaurant_id', $restaurant->id)->pluck('id')->toArray();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'in:' . implode(',', $restaurantCategoryIds ?: [0])],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_available'=> ['nullable', 'boolean'],
            'is_promo'    => ['nullable', 'boolean'],
            'promo_price' => ['nullable', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'variants'    => ['nullable', 'array'],
            'variants.*.name'        => ['required_with:variants', 'string', 'max:255'],
            'variants.*.price_delta' => ['nullable', 'integer'],
        ], [
            'image.max'   => 'La imagen no debe superar los 2 MB.',
            'image.image' => 'El archivo debe ser una imagen (jpg, png, webp).',
        ]);

        $isPromo = isset($validated['is_promo']) ? (bool) $validated['is_promo'] : false;

        $updateData = [
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'price'        => $validated['price'],
            'is_available' => isset($validated['is_available']) ? (bool) $validated['is_available'] : false,
            'is_promo'     => $isPromo,
            'promo_price'  => $isPromo ? ($validated['promo_price'] ?? null) : null,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $this->deleteImageFile($item->image);
            $updateData['image'] = $this->saveImageAsWebp($request->file('image'));
        }

        $item->update($updateData);

        $this->syncVariants($item, $request->input('variants', []));

        MenuCacheService::forget(auth()->user()->restaurant);

        return redirect()->route('admin.items.index')
            ->with('success', 'Ítem actualizado correctamente.');
    }

    public function destroy(MenuItem $item): RedirectResponse
    {
        $this->authorizeItem($item);

        $this->deleteImageFile($item->image);
        $item->variants()->delete();
        $item->delete();

        MenuCacheService::forget(auth()->user()->restaurant);

        return redirect()->route('admin.items.index')
            ->with('success', 'Ítem eliminado correctamente.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->restaurant;

        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $order => $id) {
            MenuItem::where('id', $id)
                ->where('restaurant_id', $restaurant->id)
                ->update(['sort_order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function syncVariants(MenuItem $item, array $variants): void
    {
        $item->variants()->delete();

        foreach ($variants as $index => $variantData) {
            if (empty($variantData['name'])) {
                continue;
            }

            $item->variants()->create([
                'name'        => $variantData['name'],
                'price_delta' => (int) ($variantData['price_delta'] ?? 0),
                'sort_order'  => $index + 1,
            ]);
        }
    }

    private function authorizeItem(MenuItem $item): void
    {
        if ($item->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403);
        }
    }
}
