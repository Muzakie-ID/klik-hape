<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU ATAS
        $stats = [
            'total_leads' => Waitlist::count(),
            'ready_products' => Product::where('stock', '>', 0)->where('status', 'published')->count(),
            'empty_products' => Product::where('stock', 0)->where('status', 'published')->count(),
            'wa_sent' => Waitlist::where('is_notified', true)->count(),
        ];

        // 2. GRAFIK PERTUMBUHAN LEADS (30 HARI TERAKHIR)
        // Kita ambil data harian
        $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay();

        $leadsPerDay = Waitlist::where('created_at', '>=', $thirtyDaysAgo)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Isi tanggal yang kosong dengan nilai 0 agar grafik nyambung rapi
        $chartLabels = [];
        $chartData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays(29 - $i)->format('d M');

            $chartLabels[] = $displayDate;
            $chartData[] = isset($leadsPerDay[$date]) ? $leadsPerDay[$date]->total : 0;
        }

        // 3. HP PALING BANYAK DIINCAR (MOST WANTED) - STOK KOSONG
        $mostWanted = Waitlist::select('product_id', DB::raw('count(*) as total_waiting'))
            ->where('is_notified', false) // Yang belum dikabari = masih ngantri aktif
            ->groupBy('product_id')
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'stock')->where('stock', 0);
            }])
            ->having('total_waiting', '>', 0)
            ->orderByDesc('total_waiting')
            ->limit(5)
            ->get()
            ->filter(function($item) {
                // Pastikan produknya masih ada dan stoknya benar-benar 0
                return $item->product !== null && $item->product->stock == 0;
            })->values();

        $wantedLabels = $mostWanted->pluck('product.name')->toArray();
        $wantedData = $mostWanted->pluck('total_waiting')->toArray();

        return view('dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'wantedLabels',
            'wantedData'
        ));
    }
}
