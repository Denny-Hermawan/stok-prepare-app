<?php

namespace App\Http\Controllers;

use App\Models\PrepareLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $reports = PrepareLog::with('item')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->selectRaw('
                item_id,
                SUM(stok_prepare) as total_prepare,
                SUM(stok_terpakai) as total_terpakai,
                SUM(stok_sisa) as total_sisa
            ')
            ->groupBy('item_id')
            ->get();

        return view('reports.index', compact('reports', 'month', 'year'));
    }
}
