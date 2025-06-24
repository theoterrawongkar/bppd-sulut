<x-main-layout>

    {{-- Bagian Edit Profil --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div
            class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden grid grid-cols-1 md:grid-cols-3">

            {{-- Kolom Kiri: Informasi Pengguna --}}
            <div
                class="bg-gradient-to-br from-[#486284] to-[#31445f] text-white p-6 flex flex-col items-center justify-center space-y-4">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/profile-placeholder.webp') }}"
                    alt="Foto Profil" class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover">
                <div class="text-center">
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-sm opacity-80">{{ $user->email }}</p>
                    <p class="mt-1 text-sm bg-white text-[#486284] px-3 py-1 rounded-full inline-block">
                        {{ $user->role }}</p>
                    <p class="text-xs mt-2">
                        Status:
                        <span class="font-medium {{ $user->is_active ? 'text-green-300' : 'text-red-300' }}">
                            {{ $user->is_active ? 'Aktif' : 'Non Aktif' }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan: Form Edit --}}
            <div class="col-span-2 p-6 sm:p-8 bg-white">
                <h2 class="text-xl font-semibold text-[#486284] mb-5">Profil Saya</h2>

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm mb-4 border border-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama dan Foto --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Avatar --}}
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                            <input type="file" id="avatar" name="avatar"
                                class="block w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-blue-800 file:bg-blue-200 hover:file:bg-blue-300">
                            <p class="mt-1 text-xs text-gray-500">Unggah maksimal 1 file. Format: JPG, PNG, JPEG.
                                Maks: 2MB</p>

                            @if ($user->avatar)
                                <p class="text-xs text-gray-600 mt-1">Saat ini: <a
                                        href="{{ asset('storage/' . $user->avatar) }}" target="_blank"
                                        class="underline text-blue-500">Lihat Gambar</a></p>
                            @endif

                            @error('avatar')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password"
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                    </div>

                    {{-- Tambahan Jika Role Seniman --}}
                    @if ($user->role === 'Seniman')
                        @include('profiles.partials.artist-profile')
                    @endif

                    {{-- Divider --}}
                    <div class="flex items-center gap-2 mt-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="px-2 text-sm text-gray-500 whitespace-nowrap">Verifikasi Akses</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    {{-- Password Saat Ini --}}
                    <div>
                        <label for="password_current" class="block text-sm font-medium text-gray-700">Kata Sandi Saat
                            Ini</label>
                        <input type="password" id="password_current" name="password_current" required
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                        @error('password_current')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-main-layout>
