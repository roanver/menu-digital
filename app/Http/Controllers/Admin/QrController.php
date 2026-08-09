<?php

namespace App\Http\Controllers\Admin;

use App\Models\NfcTag;
use App\Models\Restaurant;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends AdminController
{
    private function getOrCreateMenuTag(): NfcTag
    {
        $restaurant = $this->restaurant();

        $tag = NfcTag::where('restaurant_id', $restaurant->id)
            ->where('type', 'menu')
            ->first();

        if (! $tag) {
            $tag = NfcTag::create([
                'code'          => NfcTag::generateCode(),
                'type'          => 'menu',
                'restaurant_id' => $restaurant->id,
                'label'         => 'QR Menú',
                'is_active'     => true,
            ]);
        }

        return $tag;
    }

    /**
     * Plan gratis → URL directa (/{slug}), QR estático sin estadísticas.
     * Planes pagados → redirect propio (/m/{code}), permite cambiar destino
     *                  y contar escaneos sin reimprimir.
     */
    private function resolveQrUrl(Restaurant $restaurant): string
    {
        if (! $restaurant->planCan('nfc')) {
            return url('/' . $restaurant->slug);
        }

        return route('nfc.menu', $this->getOrCreateMenuTag()->code);
    }

    public function show()
    {
        $restaurant = $this->restaurant();
        $url        = $this->resolveQrUrl($restaurant);
        $qr         = QrCode::format('svg')->size(300)->margin(1)->generate($url);

        return view('admin.qr.show', compact('restaurant', 'url', 'qr'));
    }

    public function download()
    {
        $restaurant = $this->restaurant();
        $url        = $this->resolveQrUrl($restaurant);
        $qr         = QrCode::format('png')->size(600)->margin(2)->generate($url);

        return response($qr, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $restaurant->slug . '.png"');
    }

    public function print()
    {
        $restaurant = $this->restaurant();
        $url        = $this->resolveQrUrl($restaurant);
        $qr         = QrCode::format('svg')->size(280)->margin(1)->generate($url);

        return view('admin.qr.print', compact('restaurant', 'url', 'qr'));
    }
}
