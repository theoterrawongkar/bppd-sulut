<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        // Ambil Data Pengguna
        $user = Auth::user();
        $artistProfile = null;

        // Ambil data seniman jika role seniman
        if ($user->role === 'Seniman') {
            $artistProfile = $user->artistProfile;
        }

        return view('profiles.edit', compact('user', 'artistProfile'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */

        // Ambil Data Pengguna
        $user = Auth::user();

        // Validasi Input
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email:dns|unique:users,email,' . $user->id,
            'avatar'            => 'nullable|image|max:2048',
            'password'          => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
            ]
        ], [
            'password.regex' => 'Kata sandi harus mengandung minimal satu angka (0–9) dan satu simbol (@, #, $, %, dll.)',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->password_current, $user->password)) {
            return back()->withErrors(['password_current' => 'Password saat ini tidak sesuai.']);
        }

        // Simpan user
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Ganti avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Update password jika ada input baru
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        // Jika Seniman DAN sudah memiliki profil artist
        if ($user->role === 'Seniman') {
            $artistData = $request->validate([
                'stage_name'       => 'required|string|max:255',
                'owner_email'      => 'required|email',
                'phone'            => 'required|string|max:20',
                'field'            => 'required|string|max:255',
                'description'      => 'required|string',
                'portfolio_path'   => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
                'instagram_link'   => 'nullable|url',
                'facebook_link'    => 'nullable|url',
            ]);

            $profile = $user->artistProfile;

            // Isi ulang data dari hasil validasi
            $profile->fill($artistData);

            if ($request->hasFile('portfolio_path')) {
                // Hapus file lama jika ada
                if ($profile->portfolio_path && Storage::disk('public')->exists($profile->portfolio_path)) {
                    Storage::disk('public')->delete($profile->portfolio_path);
                }

                // Simpan file baru
                $profile->portfolio_path = $request->file('portfolio_path')->store('portfolios', 'public');
            }

            $profile->save();
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
