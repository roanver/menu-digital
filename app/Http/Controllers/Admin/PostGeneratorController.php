<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Services\GeminiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostGeneratorController extends AdminController
{
    public function index(): View
    {
        $restaurant = $this->restaurant();
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->with(['menuItems' => fn ($q) => $q->where('is_available', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('admin.posts.index', compact('categories', 'restaurant'));
    }

    public function generate(Request $request): View|RedirectResponse
    {
        $restaurant = $this->restaurant();

        $validated = $request->validate([
            'item_id' => ['required', 'integer'],
        ]);

        $item = MenuItem::where('id', $validated['item_id'])
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        // Generate GD image
        $imageBase64 = $this->generateImage($item, $restaurant);

        // Generate copy with Gemini
        try {
            $service = new GeminiService();
            $copy = $service->generatePostCopy($item->name, $item->description ?? '', $item->price);
        } catch (\Exception $e) {
            $copy = '';
        }

        return view('admin.posts.generate', compact('item', 'restaurant', 'imageBase64', 'copy'));
    }

    public function downloadImage(Request $request): Response
    {
        $restaurant = $this->restaurant();

        $validated = $request->validate([
            'item_id' => ['required', 'integer'],
        ]);

        $item = MenuItem::where('id', $validated['item_id'])
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        $imageBase64 = $this->generateImage($item, $restaurant);
        $imageData = base64_decode($imageBase64);

        return response($imageData, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="post-' . \Illuminate\Support\Str::slug($item->name) . '.png"');
    }

    private function generateImage(MenuItem $item, $restaurant): string
    {
        $img = imagecreatetruecolor(1080, 1080);

        // Background with primary color
        $hex = ltrim($restaurant->primary_color ?? '#1C1917', '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $bg = imagecolorallocate($img, $r, $g, $b);
        imagefill($img, 0, 0, $bg);

        // If item has image, draw it
        if ($item->image) {
            $contents = Storage::get($item->image);
            $src = $contents ? @imagecreatefromstring($contents) : false;
            if ($src) {
                $sw = imagesx($src);
                $sh = imagesy($src);
                imagecopyresampled($img, $src, 0, 0, 0, 0, 1080, 1080, $sw, $sh);
                imagedestroy($src);

                // Dark overlay
                $overlay = imagecreatetruecolor(1080, 1080);
                $black = imagecolorallocatealpha($overlay, 0, 0, 0, 50);
                imagefill($overlay, 0, 0, $black);
                imagecopymerge($img, $overlay, 0, 0, 0, 0, 1080, 1080, 55);
                imagedestroy($overlay);
            }
        }

        // Colors for text
        $white  = imagecolorallocate($img, 255, 255, 255);
        $yellow = imagecolorallocate($img, 255, 216, 77);

        // Try TTF font, fall back to built-in
        $fontPath = public_path('fonts/inter-bold.ttf');
        $useTtf = file_exists($fontPath);

        $name  = $item->name;
        $price = '$' . number_format($item->price, 0, ',', '.');

        if ($useTtf) {
            // Name centered at ~700px
            $nameFontSize = 64;
            $nameBox = imagettfbbox($nameFontSize, 0, $fontPath, $name);
            $nameW = abs($nameBox[4] - $nameBox[0]);
            $nameX = max(60, (1080 - $nameW) / 2);
            imagettftext($img, $nameFontSize, 0, (int)$nameX, 700, $white, $fontPath, $name);

            // Price
            $priceFontSize = 48;
            $priceBox = imagettfbbox($priceFontSize, 0, $fontPath, $price);
            $priceW = abs($priceBox[4] - $priceBox[0]);
            $priceX = (1080 - $priceW) / 2;
            imagettftext($img, $priceFontSize, 0, (int)$priceX, 800, $yellow, $fontPath, $price);
        } else {
            // Built-in GD font (bigger number = bigger text)
            $gdfont = 5; // largest built-in font
            $charW = imagefontwidth($gdfont);

            $nameX = max(20, (int)((1080 - strlen($name) * $charW * 2) / 2));
            imagestring($img, $gdfont, $nameX, 680, $name, $white);

            $priceX = (int)((1080 - strlen($price) * $charW * 2) / 2);
            imagestring($img, $gdfont, $priceX, 760, $price, $yellow);
        }

        // Logo in bottom right if exists
        if ($restaurant->logo) {
            $logoContents = Storage::get($restaurant->logo);
            $logo = $logoContents ? @imagecreatefromstring($logoContents) : false;
            if ($logo) {
                $logoResized = imagecreatetruecolor(100, 100);
                imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, 100, 100, imagesx($logo), imagesy($logo));
                imagecopy($img, $logoResized, 960, 960, 0, 0, 100, 100);
                imagedestroy($logo);
                imagedestroy($logoResized);
            }
        }

        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        imagedestroy($img);

        return base64_encode($data);
    }
}
