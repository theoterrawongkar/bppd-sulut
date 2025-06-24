<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CulinaryPlace;
use Illuminate\Support\Facades\Auth;

class CulinaryPlaceController extends Controller
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

        // Query
        $culinaryPlaces = CulinaryPlace::with(['subCategory', 'firstImage'])
            ->withAvg('reviews', 'rating')
            ->where('status', 'Diterima')
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
            ->orderByDesc('reviews_avg_rating')
            ->paginate(9)
            ->appends($request->query());

        return view('culinaryplaces.index', compact('culinaryPlaces', 'subCategories'));
    }

    public function show(string $slug)
    {
        // Ambil data Kuliner
        $culinaryPlace = CulinaryPlace::with([
            'subCategory',
            'firstImage',
            'images',
            'operatingHours',
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        // Daftar hari dalam seminggu
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $today = Carbon::now()->locale('id')->isoFormat('dddd');

        // Ambil data jam operasional hari ini
        $todayData = $culinaryPlace->operatingHours->firstWhere('day', $today);

        // Cek apakah tempat Kuliner sedang buka saat ini
        $isOpen = false;
        if ($todayData && $todayData->is_open) {
            $now = Carbon::now();
            $openTime = Carbon::parse($todayData->open_time);
            $closeTime = Carbon::parse($todayData->close_time);

            $isOpen = $now->between($openTime, $closeTime);
        }

        // Format jam operasional harian untuk ditampilkan
        $operatingHours = collect($days)->map(function ($day) use ($culinaryPlace) {
            $dayData = $culinaryPlace->operatingHours->firstWhere('day', $day);

            return [
                'day'   => $day,
                'open'  => $dayData && $dayData->is_open ? Carbon::parse($dayData->open_time)->format('H.i') : '-',
                'close' => $dayData && $dayData->is_open ? Carbon::parse($dayData->close_time)->format('H.i') : '-',
            ];
        });

        // Hitung total ulasan dan rata-rata rating
        $totalReviews = $culinaryPlace->reviews->count();
        $averageRating = round($culinaryPlace->reviews->avg('rating'), 1);

        // Hitung jumlah rating untuk setiap tingkat bintang
        $ratingCounts = $culinaryPlace->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Format distribusi rating dari bintang 5 ke 1
        $ratings = collect([
            5 => 'Luar biasa',
            4 => 'Bagus',
            3 => 'Biasa',
            2 => 'Buruk',
            1 => 'Sangat buruk',
        ])->map(function ($label, $rate) use ($ratingCounts, $totalReviews) {
            $jumlah = $ratingCounts[$rate] ?? 0;
            $persentase = $totalReviews ? round(($jumlah / $totalReviews) * 100) : 0;

            return [
                'label'      => $label,
                'jumlah'     => $jumlah,
                'persentase' => $persentase,
            ];
        });

        // Ambil data Review
        $reviews = $culinaryPlace->reviews()->with('user')->latest()->get();

        // Ambil data Review saya
        $myReview = $culinaryPlace->reviews()->where('user_id', Auth::id())->first();

        return view('culinaryplaces.show', compact(
            'culinaryPlace',
            'operatingHours',
            'isOpen',
            'totalReviews',
            'averageRating',
            'ratings',
            'reviews',
            'myReview'
        ));
    }

    public function storeReview(Request $request, string $slug)
    {
        // Validasi Input
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Ambil data Kuliner dan User yang sedang login
        $culinaryPlace = CulinaryPlace::where('slug', $slug)->firstOrFail();
        $userId = Auth::id();

        // Cek apakah User sudah pernah memberi ulasan
        $existingReview = $culinaryPlace->reviews()
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            $existingReview->update($validated);
            return redirect()->back()->with('success', 'Ulasan Anda berhasil diperbarui!');
        }

        // Tambah Review
        $culinaryPlace->reviews()->create([
            'user_id' => $userId,
            'rating'  => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan!');
    }
}
