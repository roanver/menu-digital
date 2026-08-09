<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\WhatsappClick;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsappClickController extends Controller
{
    public function track(Request $request): JsonResponse
    {
        $slug = $request->input('slug');
        $type = in_array($request->input('type'), ['retiro', 'delivery']) ? $request->input('type') : 'unknown';

        if (! $slug) {
            return response()->json(['ok' => false], 400);
        }

        try {
            $restaurant = Restaurant::where('slug', $slug)->select('id')->first();
            if (! $restaurant) {
                return response()->json(['ok' => false], 404);
            }

            WhatsappClick::upsert(
                [['restaurant_id' => $restaurant->id, 'date' => now()->toDateString(), 'type' => $type, 'count' => 1]],
                ['restaurant_id', 'date', 'type'],
                ['count' => DB::raw('count + 1')]
            );
        } catch (\Throwable) {
            // Fire and forget — nunca falla visiblemente
        }

        return response()->json(['ok' => true]);
    }
}
