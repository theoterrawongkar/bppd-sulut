<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visitor;
use App\Models\TourPlace;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use App\Models\CulinaryPlace;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('year', now()->year);

        // Data User
        $totalActiveUsers = User::where('is_active', true)->count();
        $activeUsers = User::where('is_active', true)
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        // Data Kuliner, Wisata, Event
        $totalCulinaryApproved = CulinaryPlace::where('status', 'Diterima')->count();
        $totalTourApproved = TourPlace::where('status', 'Diterima')->count();
        $totalEvent = EventPlace::count();

        // Ambil data pengunjung untuk tahun tertentu
        $visitorStats = Visitor::selectRaw('MONTH(visited_at) as month, COUNT(*) as total')
            ->whereYear('visited_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Siapkan data bulan 1–12
        $months = collect(range(1, 12));
        $visitorChartData = $months->map(function ($month) use ($visitorStats) {
            return [
                'month' => Carbon::create()->month($month)->translatedFormat('F'),
                'total' => $visitorStats->firstWhere('month', $month)->total ?? 0,
            ];
        });

        // Daftar tahun yang tersedia
        $startYear = now()->year - 4;
        $currentYear = now()->year;
        $availableYears = collect(range($startYear, $currentYear))->reverse();

        // Judul
        $title = 'Dashboard';

        return view('dashboard.index', compact(
            'title',
            'totalActiveUsers',
            'activeUsers',
            'totalCulinaryApproved',
            'totalTourApproved',
            'totalEvent',
            'visitorChartData',
            'selectedYear',
            'availableYears'
        ));
    }
}
