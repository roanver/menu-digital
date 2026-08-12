<?php
namespace App\Http\Controllers\Admin;

use App\Models\NfcTag;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends AdminController
{
    public function index()
    {
        $restaurant = $this->restaurant();
        $tables     = $restaurant->tables()->with(['qrTag', 'nfcTag'])->get();
        $maxTables  = config('plans.plans.' . $restaurant->plan . '.max_tables', 0);

        return view('admin.tables.index', compact('restaurant', 'tables', 'maxTables'));
    }

    public function store(Request $request)
    {
        $restaurant = $this->restaurant();
        $this->checkLimit($restaurant);

        $request->validate(['name' => 'required|string|max:60']);

        DB::transaction(function () use ($restaurant, $request) {
            $maxOrder = $restaurant->tables()->max('order') ?? 0;

            $orphan = $this->findOrphanKitPair($restaurant->id);

            if ($orphan) {
                [$qrTag, $nfcTag] = $orphan;
                $qrTag->update(['label' => $request->name]);
                $nfcTag->update(['label' => $request->name]);

                RestaurantTable::create([
                    'restaurant_id'    => $restaurant->id,
                    'name'             => $request->name,
                    'qr_tag_id'        => $qrTag->id,
                    'nfc_tag_id'       => $nfcTag->id,
                    'is_active'        => true,
                    'order'            => $maxOrder + 1,
                    'nfc_chip_written' => true,
                ]);
            } else {
                ['qr' => $qrTag, 'nfc' => $nfcTag] = NfcTag::createTablePair($restaurant->id, $request->name);

                RestaurantTable::create([
                    'restaurant_id'    => $restaurant->id,
                    'name'             => $request->name,
                    'qr_tag_id'        => $qrTag->id,
                    'nfc_tag_id'       => $nfcTag->id,
                    'is_active'        => true,
                    'order'            => $maxOrder + 1,
                    'nfc_chip_written' => false,
                ]);
            }
        });

        return back()->with('success', 'Mesa creada.');
    }

    public function storeBulk(Request $request)
    {
        $restaurant = $this->restaurant();
        $request->validate([
            'from' => 'required|integer|min:1|max:200',
            'to'   => 'required|integer|min:1|max:200|gte:from',
        ]);

        $from  = (int) $request->from;
        $to    = (int) $request->to;
        $count = $to - $from + 1;

        $maxTables = config('plans.plans.' . $restaurant->plan . '.max_tables', 0);
        $current   = $restaurant->tables()->count();

        if ($maxTables === 0) {
            return back()->withErrors(['to' => 'Tu plan no incluye mesas.']);
        }

        if ($maxTables !== -1 && ($current + $count) > $maxTables) {
            return back()->withErrors(['to' => "Tu plan permite hasta {$maxTables} mesas. Ya tienes {$current}."]);
        }

        $maxOrder = $restaurant->tables()->max('order') ?? 0;

        DB::transaction(function () use ($restaurant, $from, $to, &$maxOrder) {
            for ($i = $from; $i <= $to; $i++) {
                $name   = 'Mesa ' . $i;
                $orphan = $this->findOrphanKitPair($restaurant->id);

                if ($orphan) {
                    [$qrTag, $nfcTag] = $orphan;
                    $qrTag->update(['label' => $name]);
                    $nfcTag->update(['label' => $name]);

                    RestaurantTable::create([
                        'restaurant_id'    => $restaurant->id,
                        'name'             => $name,
                        'qr_tag_id'        => $qrTag->id,
                        'nfc_tag_id'       => $nfcTag->id,
                        'is_active'        => true,
                        'order'            => ++$maxOrder,
                        'nfc_chip_written' => true,
                    ]);
                } else {
                    ['qr' => $qrTag, 'nfc' => $nfcTag] = NfcTag::createTablePair($restaurant->id, $name);

                    RestaurantTable::create([
                        'restaurant_id'    => $restaurant->id,
                        'name'             => $name,
                        'qr_tag_id'        => $qrTag->id,
                        'nfc_tag_id'       => $nfcTag->id,
                        'is_active'        => true,
                        'order'            => ++$maxOrder,
                        'nfc_chip_written' => false,
                    ]);
                }
            }
        });

        return back()->with('success', "Se crearon {$count} mesas.");
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $request->validate(['name' => 'required|string|max:60']);

        DB::transaction(function () use ($table, $request) {
            $table->update(['name' => $request->name]);
            $table->qrTag->update(['label' => $request->name]);
            $table->nfcTag->update(['label' => $request->name]);
        });

        return back()->with('success', 'Mesa actualizada.');
    }

    public function toggleActive(RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $active = ! $table->is_active;
        $table->update(['is_active' => $active]);
        $table->qrTag->update(['is_active' => $active]);
        $table->nfcTag->update(['is_active' => $active]);

        return back()->with('success', $active ? 'Mesa activada.' : 'Mesa desactivada.');
    }

    public function toggleChipWritten(RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $written = ! $table->nfc_chip_written;
        $table->update(['nfc_chip_written' => $written]);

        return back()->with('success', $written ? 'Marcado como grabado.' : 'Marcado como sin grabar.');
    }

    public function reorder(Request $request)
    {
        $restaurant = $this->restaurant();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        foreach ($request->ids as $order => $id) {
            RestaurantTable::where('id', $id)
                ->where('restaurant_id', $restaurant->id)
                ->update(['order' => $order + 1]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroy(RestaurantTable $table)
    {
        $this->authorizeTable($table);

        DB::transaction(function () use ($table) {
            $qrTag  = $table->qrTag;
            $nfcTag = $table->nfcTag;
            $table->delete();
            $qrTag->delete();
            $nfcTag->delete();
        });

        return back()->with('success', 'Mesa eliminada.');
    }

    public function downloadQr(RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $url      = route('nfc.menu', $table->qrTag->code);
        $qr       = QrCode::format('png')->size(600)->margin(2)->generate($url);
        $filename = 'qr-' . str($table->name)->slug() . '.png';

        return response($qr, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function printAll()
    {
        $restaurant   = $this->restaurant();
        $tables       = $restaurant->tables()->with('qrTag')->where('is_active', true)->get();

        $tablesWithQr = $tables->map(function ($table) {
            $url = route('nfc.menu', $table->qrTag->code);
            $qr  = QrCode::format('svg')->size(220)->margin(1)->generate($url);
            return ['table' => $table, 'qr' => $qr, 'url' => $url];
        });

        return view('admin.tables.print', compact('restaurant', 'tablesWithQr'));
    }

    private function checkLimit($restaurant): void
    {
        $maxTables = config('plans.plans.' . $restaurant->plan . '.max_tables', 0);
        if ($maxTables === 0) {
            abort(403, 'Tu plan no incluye mesas.');
        }
        if ($maxTables !== -1 && $restaurant->tables()->count() >= $maxTables) {
            abort(403, "Tu plan permite hasta {$maxTables} mesas.");
        }
    }

    private function authorizeTable(RestaurantTable $table): void
    {
        if ($table->restaurant_id !== $this->restaurant()->id) {
            abort(403);
        }
    }

    /**
     * Busca un par de tags de kit (QR + NFC) asignados al negocio pero sin mesa.
     * Ocurre cuando se elimina una mesa manualmente o en casos de migración parcial.
     * Retorna [qrTag, nfcTag] o null si no hay huérfanos.
     *
     * @return array{0: NfcTag, 1: NfcTag}|null
     */
    private function findOrphanKitPair(int $restaurantId): ?array
    {
        $first = NfcTag::where('restaurant_id', $restaurantId)
            ->where('type', 'menu')
            ->whereNotNull('kit_id')
            ->whereDoesntHave('table')
            ->orderBy('id')
            ->first();

        if (! $first) {
            return null;
        }

        $second = NfcTag::where('restaurant_id', $restaurantId)
            ->where('type', 'menu')
            ->where('kit_id', $first->kit_id)
            ->where('slot_label', $first->slot_label)
            ->whereDoesntHave('table')
            ->where('id', '!=', $first->id)
            ->orderBy('id')
            ->first();

        if (! $second) {
            return null;
        }

        return [$first, $second];
    }
}
