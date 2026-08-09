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
        $restaurant = $this->restaurant();

        // Refresh monthly counter if the reset date has passed
        $this->maybeResetImportCounter($restaurant);

        return view('admin.menu-import.upload', compact('restaurant'));
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

        $restaurant = $this->restaurant();
        $maxImports = $restaurant->maxAiImports();

        // Plan sin acceso a IA
        if ($maxImports === 0) {
            session([
                'import_preview' => [],
                'import_error'   => 'Tu plan no incluye importación por IA. Puedes escribir los ítems manualmente.',
            ]);
            return redirect()->route('admin.import.review');
        }

        // Resetear counter si cambió el mes
        $this->maybeResetImportCounter($restaurant);

        // Límite mensual alcanzado
        if ($maxImports !== -1 && $restaurant->ai_imports_this_month >= $maxImports) {
            $resetDate = $restaurant->ai_imports_reset_at
                ? $restaurant->ai_imports_reset_at->format('d/m/Y')
                : '1/' . now()->addMonth()->format('m/Y');

            session([
                'import_preview' => [],
                'import_error'   => "Usaste las {$maxImports} importaciones de este mes. Se renuevan el {$resetDate}. Podés cargar tu carta manualmente.",
            ]);
            return redirect()->route('admin.import.review');
        }

        $base64Images = [];
        foreach ($request->file('images') as $file) {
            if ($file->isValid()) {
                $base64Images[] = [
                    base64_encode(file_get_contents($file->getRealPath())),
                    $file->getMimeType() ?: 'image/jpeg',
                ];
            }
        }

        try {
            $service    = new AnthropicService();
            $categories = $service->extractMenuFromImages($base64Images);
        } catch (\Exception $e) {
            session([
                'import_images_preview' => $base64Images,
                'import_error'          => 'No pudimos leer la carta automáticamente. Podés escribir los platos acá abajo.',
                'import_preview'        => [],
            ]);

            return redirect()->route('admin.import.review');
        }

        if (empty($categories)) {
            session([
                'import_images_preview' => $base64Images,
                'import_error'          => 'No pudimos leer la carta automáticamente. Podés escribir los platos acá abajo.',
                'import_preview'        => [],
            ]);

            return redirect()->route('admin.import.review');
        }

        // Contar la llamada a la API (se cobró, se descuenta aunque no se confirme)
        $restaurant->increment('ai_imports_this_month');

        session(['import_preview' => $categories]);

        return redirect()->route('admin.import.review');
    }

    public function showReview(): View
    {
        $categories  = session('import_preview', []);
        $importError = session('import_error');

        if (empty($categories) && !$importError) {
            return redirect()->route('admin.import.upload')
                ->with('error', 'No hay datos para revisar. Por favor sube imágenes primero.');
        }

        return view('admin.menu-import.review', compact('categories', 'importError'));
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

        $restaurant = $this->restaurant();
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

        \App\Services\MenuCacheService::forget($restaurant);

        $vertical = $restaurant->vertical();
        $itemsLabel = strtolower($vertical['items_label'] ?? 'platos');

        return redirect()->route('admin.items.index')
            ->with('success', "¡Importación completada! {$imported} {$itemsLabel} agregados al menú.");
    }

    private function maybeResetImportCounter($restaurant): void
    {
        $now = now();

        if ($restaurant->ai_imports_reset_at === null || $now->gte($restaurant->ai_imports_reset_at)) {
            $restaurant->update([
                'ai_imports_this_month' => 0,
                'ai_imports_reset_at'   => $now->copy()->startOfMonth()->addMonth(),
            ]);
            $restaurant->refresh();
        }
    }
}
