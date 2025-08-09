<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PrepareLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrepareController extends Controller
{
    public function index()
    {
        $prepareLogs = PrepareLog::with(['item', 'user'])
            ->whereDate('tanggal', today())
            ->latest()
            ->paginate(10);

        $items = Item::all();

        return view('prepare.index', compact('prepareLogs', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'shift' => 'required|in:1,2',
            'item_id' => 'required|exists:items,id',
            'stok_awal' => 'required|integer|min:0',
            'stok_prepare' => 'required|integer|min:0',
            'stok_terpakai' => 'required|integer|min:0'
        ]);

        PrepareLog::updateOrCreate(
            [
                'tanggal' => $request->tanggal,
                'shift' => $request->shift,
                'item_id' => $request->item_id
            ],
            [
                'stok_awal' => $request->stok_awal,
                'stok_prepare' => $request->stok_prepare,
                'stok_terpakai' => $request->stok_terpakai,
                'user_id' => Auth::id()
            ]
        );

        return redirect()->route('prepare.index')->with('success', 'Data prepare berhasil disimpan');
    }

    public function checkExisting(Request $request)
    {
        $existing = PrepareLog::where([
            'tanggal' => $request->tanggal,
            'shift' => $request->shift,
            'item_id' => $request->item_id
        ])->first();

        return response()->json([
            'exists' => $existing ? true : false,
            'data' => $existing
        ]);
    }

    /**
     * Get stok awal otomatis berdasarkan stok sisa terakhir
     */
    public function getStokAwal(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal);
        $shift = (int) $request->shift;
        $itemId = (int) $request->item_id;

        // Cari stok sisa terakhir berdasarkan prioritas:
        // 1. Shift sebelumnya di hari yang sama
        // 2. Shift terakhir di hari sebelumnya

        $stokAwal = 0;

        if ($shift == 2) {
            // Jika shift 2, cari shift 1 di hari yang sama
            $lastLog = PrepareLog::where([
                'tanggal' => $tanggal->format('Y-m-d'),
                'shift' => 1,
                'item_id' => $itemId
            ])->first();

            if ($lastLog) {
                $stokAwal = $lastLog->stok_sisa;
            }
        } else {
            // Jika shift 1, cari shift terakhir dari hari sebelumnya
            $lastLog = PrepareLog::where('item_id', $itemId)
                ->where(function($query) use ($tanggal) {
                    // Cari di hari sebelumnya
                    $query->where('tanggal', '<', $tanggal->format('Y-m-d'));
                })
                ->orderBy('tanggal', 'desc')
                ->orderBy('shift', 'desc')
                ->first();

            if ($lastLog) {
                $stokAwal = $lastLog->stok_sisa;
            }
        }

        // Jika tidak ada data sebelumnya, stok awal = 0
        return response()->json([
            'stok_awal' => $stokAwal,
            'message' => $stokAwal > 0 ? 'Stok awal diambil dari data sebelumnya' : 'Tidak ada data sebelumnya, stok awal dimulai dari 0'
        ]);
    }
}
