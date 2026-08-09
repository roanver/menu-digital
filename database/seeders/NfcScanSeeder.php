<?php

namespace Database\Seeders;

use App\Models\NfcScan;
use App\Models\NfcTag;
use App\Models\Restaurant;
use App\Models\WhatsappClick;
use Illuminate\Database\Seeder;

class NfcScanSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();

        if (! $restaurant) {
            $this->command->warn('No restaurant found. Run RestaurantSeeder first.');
            return;
        }

        // Ensure at least one NFC tag exists
        $tag = NfcTag::where('restaurant_id', $restaurant->id)->first()
            ?? NfcTag::create([
                'restaurant_id' => $restaurant->id,
                'code'          => 'DEMO01',
                'type'          => 'menu',
                'label'         => 'Mesa 1',
                'is_active'     => true,
                'scans_count'   => 0,
            ]);

        $tag2 = NfcTag::where('restaurant_id', $restaurant->id)
            ->where('id', '!=', $tag->id)
            ->first()
            ?? NfcTag::create([
                'restaurant_id' => $restaurant->id,
                'code'          => 'DEMO02',
                'type'          => 'review',
                'label'         => 'Reseña',
                'is_active'     => true,
                'scans_count'   => 0,
            ]);

        // Generate 28 days of NFC scan data (realistic pattern: weekends busier)
        $scanData   = [];
        $totalTag1  = 0;
        $totalTag2  = 0;

        for ($i = 27; $i >= 0; $i--) {
            $date    = now()->subDays($i)->toDateString();
            $dayOfW  = now()->subDays($i)->dayOfWeek; // 0=Sun,6=Sat
            $isWeekend = in_array($dayOfW, [0, 5, 6]);

            $base1 = $isWeekend ? rand(18, 40) : rand(8, 22);
            $base2 = $isWeekend ? rand(4, 12)  : rand(1, 6);

            // Slight upward trend in last 14 days
            if ($i < 14) {
                $base1 = (int) round($base1 * 1.15);
                $base2 = (int) round($base2 * 1.10);
            }

            $scanData[] = [
                'nfc_tag_id' => $tag->id,
                'date'       => $date,
                'count'      => $base1,
            ];
            $scanData[] = [
                'nfc_tag_id' => $tag2->id,
                'date'       => $date,
                'count'      => $base2,
            ];

            $totalTag1 += $base1;
            $totalTag2 += $base2;
        }

        NfcScan::upsert($scanData, ['nfc_tag_id', 'date'], ['count']);

        $tag->update(['scans_count' => $totalTag1]);
        $tag2->update(['scans_count' => $totalTag2]);

        // Generate 28 days of WhatsApp click data
        $waData = [];

        for ($i = 27; $i >= 0; $i--) {
            $date    = now()->subDays($i)->toDateString();
            $dayOfW  = now()->subDays($i)->dayOfWeek;
            $isWeekend = in_array($dayOfW, [0, 5, 6]);

            $retiro   = $isWeekend ? rand(6, 18) : rand(2, 10);
            $delivery = $isWeekend ? rand(3, 10) : rand(1, 5);

            if ($i < 14) {
                $retiro   = (int) round($retiro * 1.2);
                $delivery = (int) round($delivery * 1.2);
            }

            $waData[] = [
                'restaurant_id' => $restaurant->id,
                'date'          => $date,
                'type'          => 'retiro',
                'count'         => $retiro,
            ];
            $waData[] = [
                'restaurant_id' => $restaurant->id,
                'date'          => $date,
                'type'          => 'delivery',
                'count'         => $delivery,
            ];
        }

        WhatsappClick::upsert($waData, ['restaurant_id', 'date', 'type'], ['count']);

        $this->command->info("Seeded 28 days of NFC scans and WhatsApp click data for \"{$restaurant->name}\".");
    }
}
