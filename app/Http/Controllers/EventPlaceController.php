<?php

namespace App\Http\Controllers;

use App\Models\EventPlace;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class EventPlaceController extends Controller
{
    public function show(string $slug)
    {
        // Ambil detail event dan gambar
        $eventPlace = EventPlace::with(['firstImage', 'images'])->where('slug', $slug)->firstOrFail();

        // Cek apakah user sudah mendaftar
        $alreadyRegistered = false;
        if (Auth::check()) {
            $alreadyRegistered = EventParticipant::where('user_id', Auth::id())
                ->where('event_place_id', $eventPlace->id)
                ->exists();
        }

        // Kirim ke view
        return view('eventplaces.show', compact('eventPlace', 'alreadyRegistered'));
    }
}
