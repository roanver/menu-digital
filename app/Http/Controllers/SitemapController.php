<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::where('is_active', true)
            ->whereNotNull('slug')
            ->select('slug', 'updated_at')
            ->orderBy('slug')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= '  <url><loc>' . url('/') . '</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\n";

        foreach ($restaurants as $restaurant) {
            $loc     = htmlspecialchars(url('/' . $restaurant->slug));
            $lastmod = $restaurant->updated_at?->toAtomString() ?? now()->toAtomString();
            $xml .= "  <url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
