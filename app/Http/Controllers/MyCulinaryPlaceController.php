<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CulinaryPlace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyCulinaryPlaceController extends Controller
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

        // Ambil kategori utama "Kuliner"
        $category = Category::where('name', 'Kuliner')->with('subCategories')->first();
        $subCategories = $category?->subCategories ?? collect();

        // Ambil user ID login
        $userId = Auth::id();

        // Query
        $culinaryPlaces = CulinaryPlace::with(['subCategory', 'firstImage'])
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

        return view('myculinaryplaces.index', compact('culinaryPlaces', 'subCategories'));
    }

    public function create()
    {
        // Ambil kategori kuliner beserta sub kategorinya
        $category = Category::with('subCategories')->where('slug', 'kuliner')->first();
        $culinarySubCategories = $category?->subCategories ?? collect();

        return view('myculinaryplaces.create', compact('culinarySubCategories'));
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
            'types_of_food'     => 'required|in:Halal,Non Halal',
            'menu_path'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'facility'          => 'required|array',
            'facility.*'        => 'string',
            'images'            => 'required|array|min:1|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
            'open_time'         => 'required|array',
            'close_time'        => 'required|array',
            'is_closed'         => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Simpan file menu jika ada
            if ($request->hasFile('menu_path')) {
                $menuPath = $request->file('menu_path')->store('menus', 'public');
                $validated['menu_path'] = $menuPath;
            }

            // Buat culinaryPlace
            $culinaryPlace = CulinaryPlace::create([
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
                'types_of_food'   => $validated['types_of_food'],
                'facility'        => $validated['facility'],
                'menu_path'       => $validated['menu_path'] ?? null,
            ]);

            // Simpan gambar usaha (jika ada)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('culinary_images', 'public');
                    $culinaryPlace->images()->create(['image' => $path]);
                }
            }

            // Simpan jam operasional
            $culinaryPlace->operatingHours()->delete();

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
                $culinaryPlace->operatingHours()->create([
                    'day'        => $day,
                    'open_time'  => $isClosed ? null : $openTime,
                    'close_time' => $isClosed ? null : $closeTime,
                    'is_open'    => !$isClosed,
                ]);
            }
        });

        return redirect()->route('myculinaryplace.index')->with('success', 'Usaha kuliner berhasil ditambahkan. Menunggu persetujuan admin.');
    }

    public function edit(string $slug)
    {
        // Ambil data Kuliner
        $culinaryPlace = CulinaryPlace::with([
            'subCategory',
            'firstImage',
            'images',
            'operatingHours',
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        $category = Category::with('subCategories')->where('slug', 'kuliner')->first();
        $culinarySubCategories = $category?->subCategories ?? collect();

        $operatingHours = $culinaryPlace->operatingHours->keyBy('day');


        return view('myculinaryplaces.edit', compact(
            'culinaryPlace',
            'culinarySubCategories',
            'operatingHours',
        ));
    }

    public function update(Request $request, string $slug)
    {
        $culinaryPlace = CulinaryPlace::where('slug', $slug)->firstOrFail();

        if ($request->action === 'open') {
            $culinaryPlace->update(['status' => 'Menunggu Persetujuan']);
            return redirect()->back()->with('success', 'Usaha telah dibuka kembali, menunggu persetujuan admin.');
        }

        if ($request->action === 'close') {
            $culinaryPlace->update(['status' => 'Tutup Permanen']);
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
            'types_of_food'     => 'required|in:Halal,Non Halal',
            'menu_path'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'facility'          => 'required|array',
            'facility.*'        => 'string',
            'images'            => 'nullable|array|min:1|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
            'open_time'         => 'required|array',
            'close_time'        => 'required|array',
            'is_closed'         => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated, $culinaryPlace) {

            if ($request->hasFile('menu_path')) {
                if ($culinaryPlace->menu_path) {
                    Storage::disk('public')->delete($culinaryPlace->menu_path);
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
                'instagram_link'  => $validated['instagram_link'],
                'facebook_link'   => $validated['facebook_link'],
                'address'         => $validated['address'],
                'gmaps_link'      => $validated['gmaps_link'],
                'description'     => $validated['description'],
                'types_of_food'   => $validated['types_of_food'],
                'facility'        => $validated['facility'],
            ];

            // hanya jika menu_path baru tersedia
            if (isset($validated['menu_path'])) {
                $updateData['menu_path'] = $validated['menu_path'];
            }

            $culinaryPlace->update($updateData);

            if ($request->hasFile('images')) {
                foreach ($culinaryPlace->images as $img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $image->store('culinary_images', 'public');
                    $culinaryPlace->images()->create([
                        'image' => $path,
                    ]);
                }
            }

            // Simpan jam operasional
            $culinaryPlace->operatingHours()->delete();

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
                $culinaryPlace->operatingHours()->create([
                    'day'        => $day,
                    'open_time'  => $isClosed ? null : $openTime,
                    'close_time' => $isClosed ? null : $closeTime,
                    'is_open'    => !$isClosed,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Usaha Kuliner berhasil diperbarui.');
    }
}
