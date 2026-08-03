<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function show()
    {
        $restaurant = auth()->user()->restaurant;
        $url = url('/' . $restaurant->slug);
        $qr = QrCode::format('svg')->size(300)->margin(1)->generate($url);

        return view('admin.qr.show', compact('restaurant', 'url', 'qr'));
    }

    public function download()
    {
        $restaurant = auth()->user()->restaurant;
        $url = url('/' . $restaurant->slug);
        $qr = QrCode::format('png')->size(600)->margin(2)->generate($url);

        return response($qr, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $restaurant->slug . '.png"');
    }
}
