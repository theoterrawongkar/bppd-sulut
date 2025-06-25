<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Category;
use App\Models\TourPlace;
use Illuminate\Http\Request;
use App\Models\ArtistProfile;
use App\Models\CulinaryPlace;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        $categories = Category::with('subCategories')
            ->whereIn('slug', ['kuliner', 'wisata', 'event'])
            ->get()
            ->keyBy('slug');

        return view('auth.register', [
            'culinarySubCategories' => $categories['kuliner']?->subCategories ?? collect(),
            'tourSubCategories' => $categories['wisata']?->subCategories ?? collect(),
            'eventSubCategories' => $categories['event']?->subCategories ?? collect(),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->role == 'Pengguna') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email:dns|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
                ],
                'role' => 'required|in:Pengguna',
            ], [
                'password.regex' => 'Kata sandi harus mengandung minimal satu angka (0–9) dan satu simbol (@, #, $, %, dll.)',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['role'] === 'Pengguna' ? true : false,
            ]);

            return redirect('/login')->with('success', 'Akun berhasil dibuat, login untuk melanjutkan.');
        }

        if ($request->role == 'Pengusaha Kuliner') {
            // Validasi input
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|email:dns|unique:users,email',
                'password'          => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
                ],
                'role'              => 'required|in:Pengusaha Kuliner',

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
                'images'            => 'required|array|min:3|max:5',
                'images.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
                'open_time'         => 'required|array',
                'close_time'        => 'required|array',
                'is_closed'         => 'nullable|array',
            ], [
                'password.regex' => 'Kata sandi harus mengandung minimal satu angka (0–9) dan satu simbol (@, #, $, %, dll.)',
            ]);

            DB::transaction(function () use ($request, $validated) {
                // Buat User
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'password' => Hash::make($validated['password']),
                ]);

                // Simpan file menu
                $menuPath = $request->file('menu_path')->store('menus', 'public');

                // Simpan culinary_place
                $culinaryPlace = CulinaryPlace::create([
                    'user_id'         => $user->id,
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
                    'menu_path'       => $menuPath,
                    'facility'        => $validated['facility'],
                ]);

                // Simpan gambar
                if ($request->hasFile('images')) {
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

            return redirect()->route('login')->with('success', 'Akun dan Usaha kuliner berhasil didaftarkan, menunggu persetujuan admin.');
        }

        if ($request->role === 'Pengusaha Wisata') {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|email:dns|unique:users,email',
                'password'          => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
                ],
                'role'              => 'required|in:Pengusaha Wisata',

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
            ], [
                'password.regex' => 'Kata sandi harus mengandung minimal satu angka (0–9) dan satu simbol (@, #, $, %, dll.)',
            ]);

            DB::transaction(function () use ($validated, $request) {
                // Buat User
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'role'     => $validated['role'],
                    'password' => Hash::make($validated['password']),
                ]);

                // Simpan tour_places
                $tourPlace = TourPlace::create([
                    'user_id'         => $user->id,
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
                    'ticket_price'    => $validated['ticket_price'],
                    'facility'        => $validated['facility'],
                ]);

                // Simpan gambar
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('tour_images', 'public');
                        $tourPlace->images()->create([
                            'image' => $path
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

            return redirect()->route('login')->with('success', 'Akun dan Tempat Wisata berhasil didaftarkan, menunggu persetujuan admin.');
        }

        if ($request->role === 'Seniman') {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|email:dns|unique:users,email',
                'password'          => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
                ],
                'role'              => 'required|in:Seniman',

                'stage_name'        => 'required|string|max:255',
                'owner_email'       => 'required|email',
                'phone'             => 'required|string|max:20',
                'portfolio_path'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'field'             => 'required|in:Seni Rupa,Seni Musik,Seni Tari,Seni Teater,Seni Sastra,Seni Media/Audiovisual',
                'description'       => 'required|string',
                'instagram_link'    => 'nullable|url',
                'facebook_link'     => 'nullable|url',
            ], [
                'password.regex' => 'Kata sandi harus mengandung minimal satu angka dan satu simbol.',
            ]);

            DB::transaction(function () use ($request, $validated) {
                // Buat User
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'password' => Hash::make($validated['password']),
                ]);

                // Upload file portfolio
                $portfolioPath = $request->file('portfolio_path')->store('portfolios', 'public');

                // Simpan artist_profile
                ArtistProfile::create([
                    'user_id'         => $user->id,
                    'stage_name'      => $validated['stage_name'],
                    'owner_email'     => $validated['owner_email'],
                    'phone'           => $validated['phone'],
                    'portfolio_path'  => $portfolioPath,
                    'field'           => $validated['field'],
                    'description'     => $validated['description'],
                    'instagram_link'  => $validated['instagram_link'],
                    'facebook_link'   => $validated['facebook_link'],
                ]);
            });

            return redirect()->route('login')->with('success', 'Akun dan Profil Seniman berhasil didaftarkan. Menunggu persetujuan admin.');
        }
    }
}
