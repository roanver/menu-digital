<?php

namespace App\Http\Controllers;

use App\Models\NfcScan;
use App\Models\NfcTag;
use App\Models\RestaurantTable;
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
        $tag = NfcTag::with(['restaurant', 'kit'])->where('code', $code)->where('type', 'menu')->first();

        if (!$tag) {
            return response()->view('nfc.not-found', [], 404);
        }

        // Chip de kit todavía no activado — primera impresión del producto
        if (!$tag->restaurant_id && $tag->kit_id) {
            $kitToken = $tag->kit?->token;
            return response()->view('nfc.pending-activation', ['kitToken' => $kitToken], 200);
        }

        if (!$tag->restaurant) {
            return response()->view('nfc.not-found', [], 404);
        }

        if (!$tag->is_active || !$tag->restaurant->is_active) {
            return response()->view('nfc.inactive', compact('tag'), 200);
        }

        $this->countScan($tag);

        // Resolver nombre de mesa desde cualquiera de los dos FKs (QR o chip NFC).
        // Try/catch: si la columna qr_tag_id aún no existe (migración pendiente), cae al NFC legacy.
        try {
            $table = RestaurantTable::where('qr_tag_id', $tag->id)
                ->orWhere('nfc_tag_id', $tag->id)
                ->first();
        } catch (\Exception $e) {
            $table = RestaurantTable::where('nfc_tag_id', $tag->id)->first();
        }

        $label = $table ? $table->name : $tag->label;

        if ($label) {
            session(['nfc_table_label' => $label]);
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
