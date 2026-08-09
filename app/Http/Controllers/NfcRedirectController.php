<?php

namespace App\Http\Controllers;

use App\Models\NfcScan;
use App\Models\NfcTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class NfcRedirectController extends Controller
{
    // GET /r/{code} → redirect a target_url (reseña Google)
    public function review(string $code): RedirectResponse|Response
    {
        $tag = NfcTag::with('restaurant')->where('code', $code)->where('type', 'review')->first();

        if (!$tag) {
            return response()->view('nfc.not-found', [], 404);
        }

        if (!$tag->is_active || ($tag->restaurant && !$tag->restaurant->is_active)) {
            return response()->view('nfc.inactive', compact('tag'), 200);
        }

        $this->countScan($tag);

        return redirect()->away($tag->target_url);
    }

    // GET /m/{code} → redirect a /{slug}
    public function menu(string $code): RedirectResponse|Response
    {
        $tag = NfcTag::with('restaurant')->where('code', $code)->where('type', 'menu')->first();

        if (!$tag || !$tag->restaurant) {
            return response()->view('nfc.not-found', [], 404);
        }

        if (!$tag->is_active || !$tag->restaurant->is_active) {
            return response()->view('nfc.inactive', compact('tag'), 200);
        }

        $this->countScan($tag);

        // Pasar label de mesa a sesión para que el carrito lo use
        if ($tag->label) {
            session(['nfc_table_label' => $tag->label]);
        } else {
            session()->forget('nfc_table_label');
        }

        return redirect()->to('/' . $tag->restaurant->slug);
    }

    private function countScan(NfcTag $tag): void
    {
        // Upsert atómico por día
        NfcScan::upsert(
            [['nfc_tag_id' => $tag->id, 'date' => now()->toDateString(), 'count' => 1]],
            ['nfc_tag_id', 'date'],
            ['count' => DB::raw('count + 1')]
        );

        $tag->increment('scans_count');
        $tag->update(['last_scanned_at' => now()]);
    }
}
