<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class MyEventParticipationController extends Controller
{
    public function index(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'sub_category' => 'nullable|string|exists:sub_categories,slug',
        ]);

        $search = $validated['search'] ?? null;
        $sub_category = $validated['sub_category'] ?? null;

        // Ambil user ID login
        $userId = Auth::id();

        // Ambil kategori utama "Event"
        $category = Category::where('slug', 'event')->with('subCategories')->first();
        $subCategories = $category?->subCategories ?? collect();

        // Query event yang diikuti user
        $events = EventParticipant::with(['eventPlace.subCategory', 'eventPlace.firstImage', 'artistProfile'])
            ->where('user_id', $userId)
            ->whereHas('eventPlace', function ($query) use ($search, $sub_category) {
                $query->when($search, function ($q) use ($search) {
                    $q->where('business_name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })
                    ->when($sub_category, function ($q) use ($sub_category) {
                        $q->whereHas('subCategory', function ($subQ) use ($sub_category) {
                            $subQ->where('slug', $sub_category);
                        });
                    });
            })
            ->latest()
            ->paginate(9)
            ->appends($request->query());

        return view('myeventparticipations.index', compact('events', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_place_id' => 'required|exists:event_places,id',
        ]);

        $userId = Auth::id();
        $eventPlaceId = $request->input('event_place_id');

        // Cek apakah user sudah mendaftar sebelumnya
        $existing = EventParticipant::where('user_id', $userId)
            ->where('event_place_id', $eventPlaceId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah mendaftar di event ini.');
        }

        EventParticipant::create([
            'user_id' => $userId,
            'event_place_id' => $eventPlaceId,
            'status' => 'Menunggu Persetujuan',
            'artist_profile_id' => Auth::user()->artistProfile->id
        ]);

        return redirect()->route('myeventparticipation.index')->with('success', 'Berhasil mendaftar! Menunggu persetujuan.');
    }

    public function destroy(string $slug)
    {
        // Ambil data user yang login
        $user = Auth::user();

        // Ambil event berdasarkan slug
        $eventPlace = EventPlace::where('slug', $slug)->firstOrFail();

        // Ambil partisipasi berdasarkan user_id dan event_id
        $participant = EventParticipant::where([
            'user_id' => $user->id,
            'event_place_id' => $eventPlace->id,
        ])->first();

        // Cegah jika user tidak memiliki data ini
        if (!$participant) {
            return redirect()->route('myeventparticipation.index')
                ->with('error', 'Anda tidak terdaftar pada event ini.');
        }

        // Cegah jika bukan pemilik (opsional karena sudah difilter di atas)
        if ($participant->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $participant->delete();

        return redirect()->route('myeventparticipation.index')
            ->with('success', 'Partisipasi Anda berhasil dibatalkan.');
    }
}
