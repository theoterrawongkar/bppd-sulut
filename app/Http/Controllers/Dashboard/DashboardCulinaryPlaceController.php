<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\CulinaryPlace;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardCulinaryPlaceController extends Controller
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

        // Query Data Kuliner
        $culinaryPlaces = CulinaryPlace::with('subCategory')
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
        $title = 'Kuliner';

        return view('dashboard.culinaryplaces.index', compact('title', 'culinaryPlaces'));
    }

    public function edit(string $slug)
    {
        // Ambil data kuliner beserta relasinya
        $culinaryPlace = CulinaryPlace::with([
            'user',
            'subCategory',
            'images',
            'operatingHours',
        ])->where('slug', $slug)->firstOrFail();

        // Ambil tempat kuliner lain dari user yang sama (kecuali current)
        $otherCulinaryPlaces = CulinaryPlace::where('user_id', $culinaryPlace->user_id)
            ->whereKeyNot($culinaryPlace->id)
            ->get();

        // Format jam operasional per hari
        $operatingHours = $culinaryPlace->operatingHours->keyBy('day');

        return view('dashboard.culinaryplaces.edit', [
            'title' => 'Ubah Kuliner',
            'culinaryPlace' => $culinaryPlace,
            'otherCulinaryPlaces' => $otherCulinaryPlaces,
            'operatingHours' => $operatingHours,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $culinaryPlace = CulinaryPlace::where('slug', $slug)->firstOrFail();

        if ($request->action === 'accept') {
            $culinaryPlace->status = 'Diterima';
            $culinaryPlace->save();

            return back()->with('success', 'Usaha Kuliner berhasil diterima.');
        }

        if ($request->action === 'reject') {
            $culinaryPlace->status = 'Ditolak';
            $culinaryPlace->save();

            return back()->with('success', 'Usaha Kuliner berhasil ditolak.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
    }

    public function destroy(string $slug)
    {
        // Ambil data Wisata
        $culinaryPlace = CulinaryPlace::where('slug', $slug)->firstOrFail();

        // Hapus semua gambar dari storage
        foreach ($culinaryPlace->images as $img) {
            if (Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        // Hapus Event
        $culinaryPlace->delete();

        return redirect()->route('dashboard.culinaryplace.index')->with('success', 'Usaha kuliner berhasil dihapus.');
    }
}
