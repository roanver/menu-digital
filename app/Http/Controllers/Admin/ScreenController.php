<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Screen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ScreenController extends AdminController
{
    public function index(): View
    {
        $restaurant = $this->restaurant();
        $screens    = Screen::where('restaurant_id', $restaurant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.screens.index', compact('screens', 'restaurant'));
    }

    public function create(): View
    {
        $restaurant = $this->restaurant();
        abort_unless($restaurant->planCan('screens'), 403);

        $categories = Category::where('restaurant_id', $restaurant->id)
            ->orderBy('sort_order')->get();

        return view('admin.screens.create', compact('categories', 'restaurant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->restaurant();
        abort_unless($restaurant->planCan('screens'), 403);

        if (Screen::where('restaurant_id', $restaurant->id)->count() >= $restaurant->maxScreens()) {
            return back()->with('error', 'Alcanzaste el límite de ' . $restaurant->maxScreens() . ' pantallas de tu plan.');
        }

        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:100'],
            'category_ids'         => ['nullable', 'array'],
            'category_ids.*'       => ['integer'],
            'columns'              => ['required', 'integer', 'min:1', 'max:4'],
            'orientation'          => ['required', 'in:landscape,portrait'],
            'show_images'          => ['nullable', 'boolean'],
            'show_promos_rotation' => ['nullable', 'boolean'],
            'is_active'            => ['nullable', 'boolean'],
            'theme'                => ['required', 'in:dark,warm,slate,chalk,neon'],
            'accent_color'         => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_image'             => ['nullable', 'image', 'max:5120'],
        ]);

        $bgImage = $request->hasFile('bg_image')
            ? $request->file('bg_image')->store('screens', 'public')
            : null;

        Screen::create([
            'restaurant_id'        => $restaurant->id,
            'name'                 => $validated['name'],
            'category_ids'         => $validated['category_ids'] ?? null,
            'columns'              => $validated['columns'],
            'orientation'          => $validated['orientation'],
            'show_images'          => $request->boolean('show_images'),
            'show_promos_rotation' => $request->boolean('show_promos_rotation'),
            'is_active'            => $request->boolean('is_active', true),
            'theme'                => $validated['theme'],
            'accent_color'         => $validated['accent_color'] ?: null,
            'bg_image'             => $bgImage,
        ]);

        return redirect()->route('admin.screens.index')
            ->with('success', 'Pantalla creada correctamente.');
    }

    public function edit(Screen $screen): View
    {
        $this->authorize($screen);
        $restaurant = $this->restaurant();

        $categories = Category::where('restaurant_id', $restaurant->id)
            ->orderBy('sort_order')->get();

        return view('admin.screens.edit', compact('screen', 'categories', 'restaurant'));
    }

    public function update(Request $request, Screen $screen): RedirectResponse
    {
        $this->authorize($screen);

        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:100'],
            'category_ids'         => ['nullable', 'array'],
            'category_ids.*'       => ['integer'],
            'columns'              => ['required', 'integer', 'min:1', 'max:4'],
            'orientation'          => ['required', 'in:landscape,portrait'],
            'show_images'          => ['nullable', 'boolean'],
            'show_promos_rotation' => ['nullable', 'boolean'],
            'is_active'            => ['nullable', 'boolean'],
            'theme'                => ['required', 'in:dark,warm,slate,chalk,neon'],
            'accent_color'         => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bg_image'             => ['nullable', 'image', 'max:5120'],
            'remove_bg_image'      => ['nullable', 'boolean'],
        ]);

        $bgImage = $screen->bg_image;

        if ($request->boolean('remove_bg_image') && $bgImage) {
            Storage::disk('public')->delete($bgImage);
            $bgImage = null;
        } elseif ($request->hasFile('bg_image')) {
            if ($bgImage) {
                Storage::disk('public')->delete($bgImage);
            }
            $bgImage = $request->file('bg_image')->store('screens', 'public');
        }

        $screen->update([
            'name'                 => $validated['name'],
            'category_ids'         => $validated['category_ids'] ?? null,
            'columns'              => $validated['columns'],
            'orientation'          => $validated['orientation'],
            'show_images'          => $request->boolean('show_images'),
            'show_promos_rotation' => $request->boolean('show_promos_rotation'),
            'is_active'            => $request->boolean('is_active'),
            'theme'                => $validated['theme'],
            'accent_color'         => $validated['accent_color'] ?: null,
            'bg_image'             => $bgImage,
        ]);

        return redirect()->route('admin.screens.index')
            ->with('success', 'Pantalla actualizada.');
    }

    public function destroy(Screen $screen): RedirectResponse
    {
        $this->authorize($screen);

        if ($screen->bg_image) {
            Storage::disk('public')->delete($screen->bg_image);
        }

        $screen->delete();

        return redirect()->route('admin.screens.index')
            ->with('success', 'Pantalla eliminada.');
    }

    private function authorize(Screen $screen): void
    {
        abort_if($screen->restaurant_id !== $this->restaurant()->id, 403);
    }
}
