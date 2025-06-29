<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Models\EventImage;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardEventController extends Controller
{
    public function index(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        // Ambil nilai pencarian dari hasil validasi
        $search = $validated['search'] ?? null;

        // Ambil data Event
        $eventPlaces = EventPlace::with('subCategory')->withCount('acceptedParticipants')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('business_name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('start_time', 'asc')
            ->paginate(10)
            ->withQueryString();

        $title = 'Event';

        return view('dashboard.eventplace.index', compact('title', 'eventPlaces'));
    }

    public function create()
    {
        // Ambil kategori utama "Event"
        $category = Category::where('slug', 'event')->with('subCategories')->first();
        $subCategories = $category?->subCategories ?? collect();

        // Judul Halaman
        $title = "Tambah Event";

        return view('dashboard.eventplace.create', compact('title', 'subCategories'));
    }

    public function store(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'business_name'     => 'required|string|max:255',
            'sub_category_id'   => 'required|exists:sub_categories,id',
            'owner_name'        => 'required|string|max:255',
            'owner_email'       => 'required|email',
            'phone'             => 'required|string|max:20',
            'instagram_link'    => 'nullable|url',
            'facebook_link'     => 'nullable|url',
            'address'           => 'required|string',
            'gmaps_link'        => 'required|url',
            'description'       => 'required|string',
            'ticket_price'      => 'nullable|numeric|min:0',
            'start_time'        => 'required|date',
            'end_time'          => 'required|date|after_or_equal:start_time',
            'images'            => 'required|array|min:3|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan data event_place
        $eventPlace = EventPlace::create([
            'user_id' => Auth::id(),
            'sub_category_id' => $validated['sub_category_id'],
            'business_name' => $validated['business_name'],
            'owner_name' => $validated['owner_name'],
            'owner_email' => $validated['owner_email'],
            'phone' => $validated['phone'],
            'instagram_link' => $validated['instagram_link'] ?? null,
            'facebook_link' => $validated['facebook_link'] ?? null,
            'address' => $validated['address'],
            'gmaps_link' => $validated['gmaps_link'],
            'description' => $validated['description'],
            'ticket_price' => $validated['ticket_price'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        // Simpan gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('event_images', 'public');
                EventImage::create([
                    'event_place_id' => $eventPlace->id,
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('dashboard.eventplace.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(string $slug)
    {
        // Ambil data Event
        $eventPlace = EventPlace::with('subCategory', 'images')->where('slug', $slug)->firstOrFail();

        // Ambil kategori utama "Event"
        $category = Category::where('slug', 'event')->with('subCategories')->first();
        $subCategories = $category?->subCategories ?? collect();

        // Judul Halaman
        $title = "Ubah Event";

        return view('dashboard.eventplace.edit', compact('title', 'eventPlace', 'subCategories'));
    }

    public function update(Request $request, string $slug)
    {
        // Ambil data Event
        $eventPlace = EventPlace::with('subCategory', 'images')->where('slug', $slug)->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'business_name'     => 'required|string|max:255',
            'sub_category_id'   => 'required|exists:sub_categories,id',
            'owner_name'        => 'required|string|max:255',
            'owner_email'       => 'required|email',
            'phone'             => 'required|string|max:20',
            'instagram_link'    => 'nullable|url',
            'facebook_link'     => 'nullable|url',
            'address'           => 'required|string',
            'gmaps_link'        => 'required|url',
            'description'       => 'required|string',
            'ticket_price'      => 'nullable|numeric|min:0',
            'start_time'        => 'required|date',
            'end_time'          => 'required|date|after_or_equal:start_time',
            'images'            => 'nullable|array|min:3|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data utama event
        $eventPlace->update([
            'sub_category_id' => $validated['sub_category_id'],
            'business_name'   => $validated['business_name'],
            'owner_name'      => $validated['owner_name'],
            'owner_email'     => $validated['owner_email'],
            'phone'           => $validated['phone'],
            'instagram_link'  => $validated['instagram_link'] ?? null,
            'facebook_link'   => $validated['facebook_link'] ?? null,
            'address'         => $validated['address'],
            'gmaps_link'      => $validated['gmaps_link'],
            'description'     => $validated['description'],
            'ticket_price'    => $validated['ticket_price'] ?? null,
            'start_time'      => $validated['start_time'],
            'end_time'        => $validated['end_time'],
        ]);

        if ($request->hasFile('images')) {

            // 1. Menghapus semua gambar lama (file dan record DB)
            foreach ($eventPlace->images as $img) {
                Storage::disk('public')->delete($img->image);
                $img->delete();
            }

            // 2. Menyimpan gambar baru
            foreach ($request->file('images') as $image) {
                $path = $image->store('event_images', 'public');
                $eventPlace->images()->create([
                    'image' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        // Ambil data Event
        $eventPlace = EventPlace::where('slug', $slug)->firstOrFail();

        // Hapus semua gambar dari storage
        foreach ($eventPlace->images as $img) {
            if (Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        // Hapus Event
        $eventPlace->delete();

        return redirect()->route('dashboard.eventplace.index')->with('success', 'Event berhasil dihapus.');
    }
}
