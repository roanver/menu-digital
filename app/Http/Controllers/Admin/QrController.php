<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NfcTag;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    private function getOrCreateMenuTag(): NfcTag
    {
        $restaurant = auth()->user()->restaurant;

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

    public function show()
    {
        $restaurant = auth()->user()->restaurant;
        $tag        = $this->getOrCreateMenuTag();
        $url        = route('nfc.menu', $tag->code);
        $qr         = QrCode::format('svg')->size(300)->margin(1)->generate($url);

        return view('admin.qr.show', compact('restaurant', 'url', 'qr'));
    }

    public function download()
    {
        $restaurant = auth()->user()->restaurant;
        $tag        = $this->getOrCreateMenuTag();
        $url        = route('nfc.menu', $tag->code);
        $qr         = QrCode::format('png')->size(600)->margin(2)->generate($url);

        return response($qr, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $restaurant->slug . '.png"');
    }

    public function print()
    {
        $restaurant = auth()->user()->restaurant;
        $tag        = $this->getOrCreateMenuTag();
        $url        = route('nfc.menu', $tag->code);
        $qr         = QrCode::format('svg')->size(280)->margin(1)->generate($url);

        return view('admin.qr.print', compact('restaurant', 'url', 'qr'));
    }
}
