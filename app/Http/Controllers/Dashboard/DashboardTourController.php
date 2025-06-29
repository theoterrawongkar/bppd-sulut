<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TourPlace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardTourController extends Controller
{
    public function index(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Menunggu Persetujuan,Ditolak,Diterima,Tutup Permanen',
        ]);

        $search = $validated['search'] ?? null;
        $status = $validated['status'] ?? null;

        // Query Data Wisata
        $tourPlaces = TourPlace::with('subCategory')
            ->when($search, function ($query) use ($search) {
                $query->where('business_name', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Judul Halaman
        $title = 'Wisata';

        return view('dashboard.tourplaces.index', compact('title', 'tourPlaces'));
    }

    public function edit(string $slug)
    {
        // Ambil data wisata
        $tourPlace = TourPlace::with('user', 'subCategory', 'images', 'operatingHours')
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil wisata lain dari user yang sama
        $otherTourPlaces = TourPlace::where('user_id', $tourPlace->user_id)
            ->where('id', '!=', $tourPlace->id)
            ->get();

        // Jam Operasional
        $operatingHours = $tourPlace->operatingHours->keyBy('day');

        // Judul Halaman
        $title = "Ubah Wisata";

        return view('dashboard.tourplaces.edit', compact('title', 'tourPlace', 'otherTourPlaces', 'operatingHours'));
    }

    public function update(Request $request, string $slug)
    {
        $tourPlace = TourPlace::where('slug', $slug)->firstOrFail();

        if ($request->action === 'accept') {
            $tourPlace->status = 'Diterima';
            $tourPlace->save();

            return back()->with('success', 'Tempat wisata berhasil diterima.');
        }

        if ($request->action === 'reject') {
            $tourPlace->status = 'Ditolak';
            $tourPlace->save();

            return back()->with('success', 'Tempat wisata berhasil ditolak.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
    }

    public function destroy(string $slug)
    {
        // Ambil data Wisata
        $tourPlace = TourPlace::where('slug', $slug)->firstOrFail();

        // Hapus semua gambar dari storage
        foreach ($tourPlace->images as $img) {
            if (Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        // Hapus Event
        $tourPlace->delete();

        return redirect()->route('dashboard.tourplace.index')->with('success', 'Tempat wisata berhasil dihapus.');
    }
}
