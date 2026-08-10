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
        $tables = $restaurant->tables()->with('nfcTag')->get();
        $maxTables = config('plans.plans.' . $restaurant->plan . '.max_tables', 0);
        $freeChips = NfcTag::where('restaurant_id', $restaurant->id)
            ->where('type', 'menu')
            ->where('is_physical', true)
            ->whereDoesntHave('table')
            ->get();

        return view('admin.tables.index', compact('restaurant', 'tables', 'maxTables', 'freeChips'));
    }

    public function store(Request $request)
    {
        $restaurant = $this->restaurant();
        $this->checkLimit($restaurant);

        $request->validate(['name' => 'required|string|max:60']);

        DB::transaction(function () use ($restaurant, $request) {
            $tag = NfcTag::create([
                'code'          => NfcTag::generateCode(),
                'type'          => 'menu',
                'restaurant_id' => $restaurant->id,
                'label'         => $request->name,
                'is_active'     => true,
                'is_physical'   => false,
            ]);
            $maxOrder = $restaurant->tables()->max('order') ?? 0;
            RestaurantTable::create([
                'restaurant_id' => $restaurant->id,
                'name'          => $request->name,
                'nfc_tag_id'    => $tag->id,
                'is_active'     => true,
                'order'         => $maxOrder + 1,
            ]);
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

        $from = (int) $request->from;
        $to   = (int) $request->to;
        $count = $to - $from + 1;

        $maxTables = config('plans.plans.' . $restaurant->plan . '.max_tables', 0);
        $current = $restaurant->tables()->count();

        if ($maxTables === 0) {
            return back()->withErrors(['to' => 'Tu plan no incluye mesas.']);
        }

        if ($maxTables !== -1 && ($current + $count) > $maxTables) {
            return back()->withErrors(['to' => "Tu plan permite hasta {$maxTables} mesas. Ya tenés {$current}."]);
        }

        $maxOrder = $restaurant->tables()->max('order') ?? 0;

        DB::transaction(function () use ($restaurant, $from, $to, &$maxOrder) {
            for ($i = $from; $i <= $to; $i++) {
                $name = 'Mesa ' . $i;
                $tag = NfcTag::create([
                    'code'          => NfcTag::generateCode(),
                    'type'          => 'menu',
                    'restaurant_id' => $restaurant->id,
                    'label'         => $name,
                    'is_active'     => true,
                    'is_physical'   => false,
                ]);
                RestaurantTable::create([
                    'restaurant_id' => $restaurant->id,
                    'name'          => $name,
                    'nfc_tag_id'    => $tag->id,
                    'is_active'     => true,
                    'order'         => ++$maxOrder,
                ]);
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
            $table->nfcTag->update(['label' => $request->name]);
        });

        return back()->with('success', 'Mesa actualizada.');
    }

    public function toggleActive(RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $active = !$table->is_active;
        $table->update(['is_active' => $active]);
        $table->nfcTag->update(['is_active' => $active]);
        return back()->with('success', $active ? 'Mesa activada.' : 'Mesa desactivada.');
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

        if ($table->nfcTag->is_physical) {
            return back()->withErrors(['table' => 'Desvinculá el chip NFC antes de eliminar la mesa.']);
        }

        DB::transaction(function () use ($table) {
            $tag = $table->nfcTag;
            $table->delete();
            $tag->delete();
        });

        return back()->with('success', 'Mesa eliminada.');
    }

    public function linkNfc(Request $request, RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $restaurant = $this->restaurant();

        $request->validate(['nfc_tag_id' => 'required|integer']);

        $chip = NfcTag::where('id', $request->nfc_tag_id)
            ->where('restaurant_id', $restaurant->id)
            ->where('type', 'menu')
            ->where('is_physical', true)
            ->whereDoesntHave('table')
            ->firstOrFail();

        DB::transaction(function () use ($table, $chip) {
            $oldTag = $table->nfcTag;
            $table->update(['nfc_tag_id' => $chip->id]);
            $chip->update(['label' => $table->name, 'is_active' => $table->is_active]);
            if (!$oldTag->is_physical) {
                $oldTag->delete();
            }
        });

        return back()->with('success', 'Chip vinculado a la mesa.');
    }

    public function unlinkNfc(RestaurantTable $table)
    {
        $this->authorizeTable($table);

        if (!$table->nfcTag->is_physical) {
            return back()->withErrors(['table' => 'Esta mesa no tiene chip físico vinculado.']);
        }

        DB::transaction(function () use ($table) {
            $newTag = NfcTag::create([
                'code'          => NfcTag::generateCode(),
                'type'          => 'menu',
                'restaurant_id' => $table->restaurant_id,
                'label'         => $table->name,
                'is_active'     => $table->is_active,
                'is_physical'   => false,
            ]);
            $table->update(['nfc_tag_id' => $newTag->id]);
            // chip stays, now free
        });

        return back()->with('success', 'Chip desvinculado. La mesa conserva su QR.');
    }

    public function downloadQr(RestaurantTable $table)
    {
        $this->authorizeTable($table);
        $url = route('nfc.menu', $table->nfcTag->code);
        $qr  = QrCode::format('png')->size(600)->margin(2)->generate($url);
        $filename = 'qr-' . str($table->name)->slug() . '.png';

        return response($qr, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function printAll()
    {
        $restaurant = $this->restaurant();
        $tables = $restaurant->tables()->with('nfcTag')->where('is_active', true)->get();

        $tablesWithQr = $tables->map(function ($table) {
            $url = route('nfc.menu', $table->nfcTag->code);
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
}
