<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\EventPlace;
use Illuminate\Http\Request;
use App\Models\ArtistProfile;
use App\Models\EventParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardArtistProfileController extends Controller
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

        // Query Data Seniman
        $artistProfiles = ArtistProfile::when($search, function ($query) use ($search) {
            $query->where('stage_name', 'like', '%' . $search . '%');
        })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Judul Halaman
        $title = 'Seniman';

        return view('dashboard.artistprofiles.index', compact('title', 'artistProfiles'));
    }

    public function edit(string $id)
    {
        // Ambil profil seniman + user + event yang diikuti
        $artistProfile = ArtistProfile::with(['user', 'eventParticipants.eventPlace'])->findOrFail($id);

        // Ambil partisipasi event langsung dari relasi
        $joinedEvents = $artistProfile->eventParticipants;

        $title = 'Ubah Seniman';

        return view('dashboard.artistprofiles.edit', compact('title', 'artistProfile', 'joinedEvents'));
    }

    public function update(Request $request, string $id)
    {
        $artistProfile = ArtistProfile::findOrFail($id);

        if ($request->action === 'accept') {
            $artistProfile->status = 'Diterima';
            $artistProfile->save();

            return back()->with('success', 'Profil seniman berhasil diterima.');
        }

        if ($request->action === 'reject') {
            $artistProfile->status = 'Ditolak';
            $artistProfile->save();

            return back()->with('success', 'Profil seniman berhasil ditolak.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
    }

    public function destroy(string $id)
    {
        // Ambil data profil seniman
        $artistProfile = ArtistProfile::findOrFail($id);

        // Hapus file portofolio jika ada
        if ($artistProfile->portfolio_path && Storage::disk('public')->exists($artistProfile->portfolio_path)) {
            Storage::disk('public')->delete($artistProfile->portfolio_path);
        }

        // Hapus user terkait
        if ($artistProfile->user) {
            $artistProfile->user->delete();
        }

        // Hapus artist profile
        $artistProfile->delete();

        return redirect()->route('dashboard.artistprofile.index')->with('success', 'Profil seniman dan akun pengguna berhasil dihapus.');
    }
}
