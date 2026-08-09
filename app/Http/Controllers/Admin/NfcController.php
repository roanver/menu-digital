<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NfcTag;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NfcController extends Controller
{
    public function index(Request $request): View
    {
        $query = NfcTag::with('restaurant')->latest();

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->active === '1');
        }

        $tags = $query->paginate(25)->withQueryString();
        $restaurants = Restaurant::orderBy('name')->get();

        return view('superadmin.nfc.index', compact('tags', 'restaurants'));
    }

    public function create(): View
    {
        $restaurants = Restaurant::orderBy('name')->get();
        return view('superadmin.nfc.create', compact('restaurants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'          => ['required', 'in:review,menu'],
            'restaurant_id' => ['nullable', 'exists:restaurants,id'],
            'label'         => ['nullable', 'string', 'max:255'],
            'target_url'    => ['nullable', 'url', 'max:2048', function (string $attribute, mixed $value, \Closure $fail) {
                if ($value) {
                    $host = parse_url($value, PHP_URL_HOST);
                    $allowed = [
                        'google.com', 'www.google.com', 'g.page', 'search.google.com', 'maps.app.goo.gl',
                        'instagram.com', 'www.instagram.com',
                        'facebook.com', 'www.facebook.com',
                        'tiktok.com', 'www.tiktok.com',
                    ];
                    if (! in_array($host, $allowed)) {
                        $fail('Host no permitido: ' . $host . '. Se aceptan URLs de Google, Instagram, Facebook y TikTok.');
                    }
                }
            }],
            'is_active'     => ['boolean'],
        ]);

        $validated['code']      = NfcTag::generateCode();
        $validated['is_active'] = $request->boolean('is_active', true);

        NfcTag::create($validated);

        return redirect()->route('superadmin.nfc.index')
            ->with('success', 'Tag NFC creado correctamente. Código: ' . $validated['code']);
    }

    public function edit(NfcTag $tag): View
    {
        $restaurants = Restaurant::orderBy('name')->get();
        return view('superadmin.nfc.edit', compact('tag', 'restaurants'));
    }

    public function update(Request $request, NfcTag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'type'          => ['required', 'in:review,menu'],
            'restaurant_id' => ['nullable', 'exists:restaurants,id'],
            'label'         => ['nullable', 'string', 'max:255'],
            'target_url'    => ['nullable', 'url', 'max:2048', function (string $attribute, mixed $value, \Closure $fail) {
                if ($value) {
                    $host = parse_url($value, PHP_URL_HOST);
                    $allowed = [
                        'google.com', 'www.google.com', 'g.page', 'search.google.com', 'maps.app.goo.gl',
                        'instagram.com', 'www.instagram.com',
                        'facebook.com', 'www.facebook.com',
                        'tiktok.com', 'www.tiktok.com',
                    ];
                    if (! in_array($host, $allowed)) {
                        $fail('Host no permitido: ' . $host . '. Se aceptan URLs de Google, Instagram, Facebook y TikTok.');
                    }
                }
            }],
            'is_active'     => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $tag->update($validated);

        return redirect()->route('superadmin.nfc.index')
            ->with('success', 'Tag NFC actualizado correctamente.');
    }

    public function destroy(NfcTag $tag): RedirectResponse
    {
        $tag->scans()->delete();
        $tag->delete();

        return redirect()->route('superadmin.nfc.index')
            ->with('success', 'Tag NFC eliminado.');
    }

    public function bulkCreate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantity'      => ['required', 'integer', 'min:1', 'max:50'],
            'type'          => ['required', 'in:review,menu'],
            'restaurant_id' => ['nullable', 'exists:restaurants,id'],
            'label_prefix'  => ['nullable', 'string', 'max:100'],
        ]);

        $created = [];
        for ($i = 1; $i <= $validated['quantity']; $i++) {
            $code = NfcTag::generateCode();
            $label = $validated['label_prefix'] ? $validated['label_prefix'] . ' ' . $i : null;
            NfcTag::create([
                'code'          => $code,
                'type'          => $validated['type'],
                'restaurant_id' => $validated['restaurant_id'] ?? null,
                'label'         => $label,
                'is_active'     => true,
            ]);
            $created[] = $code;
        }

        return redirect()->route('superadmin.nfc.index')
            ->with('success', count($created) . ' tags NFC creados correctamente.');
    }

    public function printLabels(Request $request): View
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:nfc_tags,id'],
        ])['ids'];

        $tags = NfcTag::with('restaurant')->whereIn('id', $ids)->get();

        return view('superadmin.nfc.print', compact('tags'));
    }
}
