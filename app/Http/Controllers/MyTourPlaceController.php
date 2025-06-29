<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TourPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MyTourPlaceController extends Controller
{
    public function index(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'sub_category' => 'nullable|string|exists:sub_categories,slug',
        ]);

        // Inisialisasi Input
        $search = $validated['search'] ?? null;
        $sub_category = $validated['sub_category'] ?? null;

        // Ambil kategori utama "Wisata"
        $category = Category::where('slug', 'wisata')->with('subCategories')->first();
        $subCategories = $category?->subCategories ?? collect();

        // Ambil user ID login
        $userId = Auth::id();

        // Query
        $tourPlaces = TourPlace::with(['subCategory', 'firstImage'])
            ->where('user_id', $userId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('business_name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($sub_category, function ($query, $sub_category) {
                $query->whereHas('subCategory', function ($q) use ($sub_category) {
                    $q->where('slug', $sub_category);
                });
            })
            ->latest()
            ->paginate(9)
            ->appends($request->query());

        return view('mytourplaces.index', compact('tourPlaces', 'subCategories'));
    }

    public function create()
    {
        // Ambil kategori wisata beserta sub kategorinya
        $category = Category::with('subCategories')->where('slug', 'wisata')->first();
        $tourSubCategories = $category?->subCategories ?? collect();

        return view('mytourplaces.create', compact('tourSubCategories'));
    }

    public function store(Request $request)
    {
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
            'facility'          => 'required|array',
            'facility.*'        => 'string',
            'images'            => 'required|array|min:3|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
            'open_time'         => 'required|array',
            'close_time'        => 'required|array',
            'is_closed'         => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Buat tourPlace
            $tourPlace = TourPlace::create([
                'user_id'         => Auth::id(),
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
                'ticket_price'   => $validated['ticket_price'] ?? null,
                'facility'        => $validated['facility'],
            ]);

            // Simpan gambar
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tour_images', 'public');
                    $tourPlace->images()->create(['image' => $path]);
                }
            }

            // Simpan jam operasional
            $tourPlace->operatingHours()->delete();

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            foreach ($days as $day) {
                // Ambil nilai input dengan fallback null jika tidak ada
                $openTime = $request->open_time[$day] ?? null;
                $closeTime = $request->close_time[$day] ?? null;

                // Checkbox akan bernilai "1" jika dicentang
                $isClosed = isset($request->is_closed[$day]) && $request->is_closed[$day] == 1;

                // Jika open & close time kosong, anggap tutup
                if (!$openTime && !$closeTime) {
                    $isClosed = true;
                }

                // Simpan data
                $tourPlace->operatingHours()->create([
                    'day'        => $day,
                    'open_time'  => $isClosed ? null : $openTime,
                    'close_time' => $isClosed ? null : $closeTime,
                    'is_open'    => !$isClosed,
                ]);
            }
        });

        return redirect()->route('mytourplace.index')->with('success', 'Usaha Wisata berhasil ditambahkan, menunggu persetujuan admin.');
    }

    public function edit(string $slug)
    {
        // Ambil data Wisata
        $tourPlace = TourPlace::with([
            'subCategory',
            'firstImage',
            'images',
            'operatingHours',
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        // Cek Akses
        Gate::authorize('view', $tourPlace);

        $category = Category::with('subCategories')->where('slug', 'wisata')->first();
        $tourSubCategories = $category?->subCategories ?? collect();

        $operatingHours = $tourPlace->operatingHours->keyBy('day');


        return view('mytourplaces.edit', compact(
            'tourPlace',
            'tourSubCategories',
            'operatingHours',
        ));
    }

    public function update(Request $request, string $slug)
    {
        // Ambil data Wisata
        $tourPlace = TourPlace::where('slug', $slug)->firstOrFail();

        // Cek Akses
        Gate::authorize('update', $tourPlace);

        if ($request->action === 'open') {
            $tourPlace->update(['status' => 'Menunggu Persetujuan']);
            return redirect()->back()->with('success', 'Usaha telah dibuka kembali, menunggu persetujuan admin.');
        }

        if ($request->action === 'close') {
            $tourPlace->update(['status' => 'Tutup Permanen']);
            return redirect()->back()->with('success', 'Usaha telah ditutup permanen.');
        }

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
            'facility'          => 'required|array',
            'facility.*'        => 'string',
            'images'            => 'nullable|array|min:3|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
            'open_time'         => 'required|array',
            'close_time'        => 'required|array',
            'is_closed'         => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated, $tourPlace) {

            if ($request->hasFile('menu_path')) {
                if ($tourPlace->menu_path) {
                    Storage::disk('public')->delete($tourPlace->menu_path);
                }
                $menuPath = $request->file('menu_path')->store('menus', 'public');
                $validated['menu_path'] = $menuPath; // <- tambahkan menu_path ke $validated
            } else {
                unset($validated['menu_path']);
            }

            $updateData = [
                'sub_category_id' => $validated['sub_category_id'],
                'business_name'   => $validated['business_name'],
                'owner_name'      => $validated['owner_name'],
                'owner_email'     => $validated['owner_email'],
                'phone'           => $validated['phone'],
                'instagram_link'  => $validated['instagram_link']  ?? null,
                'facebook_link'   => $validated['facebook_link'] ?? null,
                'address'         => $validated['address'],
                'gmaps_link'      => $validated['gmaps_link'],
                'description'     => $validated['description'],
                'ticket_price'    => $validated['ticket_price'] ?? null,
                'facility'        => $validated['facility'],
            ];

            $tourPlace->update($updateData);

            if ($request->hasFile('images')) {
                foreach ($tourPlace->images as $img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $image->store('tour_images', 'public');
                    $tourPlace->images()->create([
                        'image' => $path,
                    ]);
                }
            }

            // Simpan jam operasional
            $tourPlace->operatingHours()->delete();

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            foreach ($days as $day) {
                // Ambil nilai input dengan fallback null jika tidak ada
                $openTime = $request->open_time[$day] ?? null;
                $closeTime = $request->close_time[$day] ?? null;

                // Checkbox akan bernilai "1" jika dicentang
                $isClosed = isset($request->is_closed[$day]) && $request->is_closed[$day] == 1;

                // Jika open & close time kosong, anggap tutup
                if (!$openTime && !$closeTime) {
                    $isClosed = true;
                }

                // Simpan data
                $tourPlace->operatingHours()->create([
                    'day'        => $day,
                    'open_time'  => $isClosed ? null : $openTime,
                    'close_time' => $isClosed ? null : $closeTime,
                    'is_open'    => !$isClosed,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Usaha Wisata berhasil diperbarui.');
    }
}
