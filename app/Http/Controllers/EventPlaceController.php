<?php

namespace App\Http\Controllers;

use App\Models\EventPlace;
use Illuminate\Http\Request;

class EventPlaceController extends Controller
{
    public function show(string $slug)
    {
        // Ambil event
        $eventPlace = EventPlace::with(['firstImage', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // // Kirim data ke view
        return view('eventplaces.show', compact('eventPlace'));
    }
}
