<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input dari query string
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        // Ambil nilai pencarian dari hasil validasi
        $search = $validated['search'] ?? null;
        $now = Carbon::now();

        // Ambil event dengan status urutan + relasi gambar pertama
        $eventPlaces = EventPlace::with('firstImage')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('business_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->addSelect([
                '*',
                DB::raw("
                CASE
                    WHEN start_time <= '{$now}' AND end_time >= '{$now}' THEN 0
                    WHEN start_time > '{$now}' THEN 1
                    ELSE 2
                END AS event_status_order
            ")
            ])
            ->orderBy('event_status_order')
            ->orderBy('start_time')
            ->paginate(8)
            ->appends($request->query());

        // Ambil event dengan status urutan dan map data untuk kalender
        $calendarEvents = $eventPlaces->map(function ($event) use ($now) {
            $statusColor = match (true) {
                $event->start_time <= $now && $event->end_time >= $now => '#10b981',
                $event->start_time > $now => '#3b82f6',
                default => '#9ca3af',
            };

            return [
                'title' => $event->business_name,
                'start' => $event->start_time->toDateString(),
                'end' => \Carbon\Carbon::parse($event->end_time)->addDay()->toDateString(),
                'url' => route('eventplace.show', $event->slug),
                'color' => $statusColor,
            ];
        });

        return view('home', compact('eventPlaces', 'calendarEvents'));
    }
}
