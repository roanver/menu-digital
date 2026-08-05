<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Services\AnthropicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuImportController extends AdminController
{
    public function showUpload(): View
    {
        return view('admin.menu-import.upload');
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'images'   => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['required', 'image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
        ], [
            'images.required'   => 'Debes subir al menos una imagen.',
            'images.max'        => 'Máximo 5 imágenes por importación.',
            'images.*.image'    => 'Cada archivo debe ser una imagen.',
            'images.*.max'      => 'Cada imagen no debe superar los 5 MB.',
            'images.*.mimes'    => 'Solo se aceptan formatos jpg, jpeg, png y webp.',
        ]);

        $base64Images = [];
        foreach ($request->file('images') as $file) {
            if ($file->isValid()) {
                $base64Images[] = base64_encode(file_get_contents($file->getRealPath()));
            }
        }

        try {
            $service = new AnthropicService();
            $categories = $service->extractMenuFromImages($base64Images);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al conectar con la IA. Intenta nuevamente: ' . $e->getMessage());
        }

        if (empty($categories)) {
            return back()->with('error', 'No se pudieron detectar platos en las imágenes. Intenta con fotos más claras.');
        }

        session(['import_preview' => $categories]);

        return redirect()->route('admin.import.review');
    }

    public function showReview(): View
    {
        $categories = session('import_preview', []);

        if (empty($categories)) {
            return redirect()->route('admin.import.upload')
                ->with('error', 'No hay datos para revisar. Por favor sube imágenes primero.');
        }

        return view('admin.menu-import.review', compact('categories'));
    }

    public function confirm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categories'               => ['required', 'array'],
            'categories.*.name'        => ['required', 'string', 'max:255'],
            'categories.*.items'       => ['nullable', 'array'],
            'categories.*.items.*.include' => ['nullable', 'boolean'],
            'categories.*.items.*.name'    => ['required_with:categories.*.items', 'string', 'max:255'],
            'categories.*.items.*.description' => ['nullable', 'string'],
            'categories.*.items.*.price'   => ['required_with:categories.*.items', 'integer', 'min:0'],
        ]);

        $restaurant = auth()->user()->restaurant;
        $imported = 0;

        foreach ($validated['categories'] as $catData) {
            $category = Category::firstOrCreate(
                ['restaurant_id' => $restaurant->id, 'name' => $catData['name']],
                [
                    'is_active'  => true,
                    'sort_order' => Category::where('restaurant_id', $restaurant->id)->max('sort_order') + 1,
                ]
            );

            foreach ($catData['items'] ?? [] as $itemData) {
                // Skip if not included
                if (isset($itemData['include']) && !$itemData['include']) {
                    continue;
                }

                $maxSort = MenuItem::where('restaurant_id', $restaurant->id)->max('sort_order') ?? 0;

                MenuItem::create([
                    'restaurant_id' => $restaurant->id,
                    'category_id'   => $category->id,
                    'name'          => $itemData['name'],
                    'description'   => $itemData['description'] ?? null,
                    'price'         => (int) ($itemData['price'] ?? 0),
                    'is_available'  => true,
                    'sort_order'    => $maxSort + 1,
                ]);

                $imported++;
            }
        }

        session()->forget('import_preview');

        // Clear menu cache
        \App\Services\MenuCacheService::forget($restaurant);

        return redirect()->route('admin.items.index')
            ->with('success', "¡Importación completada! {$imported} platos agregados al menú.");
    }
}
