<x-main-layout>

    {{-- Bagian Register Akun --}}
    <section class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 py-10">
        <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-lg rounded-lg overflow-hidden">

            {{-- Ilustrasi --}}
            <div class="md:w-1/2 h-48 md:h-auto bg-cover bg-center relative"
                style="background-image: url('{{ asset('img/register-banner.jpg') }}')">
                <div class="absolute inset-0 bg-black/50"></div>
            </div>

            {{-- Form Register Akun --}}
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8 bg-white">
                <h1 class="text-2xl font-semibold text-[#486284] mb-6">Daftar Akun</h1>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5"
                    x-data="{
                        role: '{{ old('role', 'Pengguna') }}',
                        showPassword: false,
                        showConfirm: false
                    }" x-cloak>
                    @csrf

                    {{-- Pilihan Jenis Akun --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Daftar Sebagai</label>
                        <select id="role" name="role" x-model="role"
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            <option value="Pengguna">Pengguna</option>
                            <option value="Pengusaha Kuliner">Pengusaha Kuliner</option>
                            <option value="Pengusaha Wisata">Pengusaha Wisata</option>
                            <option value="Seniman">Seniman</option>
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Form Tambahan Berdasarkan Role --}}
                    <div x-show="role === 'Pengusaha Kuliner'">
                        @include('auth.partials.culinary-register', [
                            'culinarySubCategories' => $culinarySubCategories,
                        ])
                    </div>

                    <div x-show="role === 'Pengusaha Wisata'">
                        @include('auth.partials.tour-register', [
                            'tourSubCategories' => $tourSubCategories,
                        ])
                    </div>

                    <div x-show="role === 'Seniman'">
                        @include('auth.partials.artist-register')
                    </div>

                    {{-- Divider --}}
                    <div class="flex items-center gap-2 mt-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="px-2 text-sm text-gray-500 whitespace-nowrap">Data Login</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]" />
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]" />
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                class="w-full mt-1 py-1 px-2 pr-10 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]" />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 focus:outline-none">
                                <x-icons.eye class="h-5 w-5" x-show="!showPassword" />
                                <x-icons.eye-off class="h-5 w-5" x-show="showPassword" />
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Kata Sandi</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                                name="password_confirmation"
                                class="w-full mt-1 py-1 px-2 pr-10 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]" />
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 focus:outline-none">
                                <x-icons.eye class="h-5 w-5" x-show="!showConfirm" />
                                <x-icons.eye-off class="h-5 w-5" x-show="showConfirm" />
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div>
                        <button type="submit"
                            class="w-full bg-[#486284] text-white py-2 px-4 rounded-md hover:bg-slate-700 transition duration-200">
                            Daftar
                        </button>
                    </div>

                    {{-- Link Login --}}
                    <p class="text-sm text-center text-gray-700">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-[#486284] font-medium hover:underline">
                            Masuk di sini
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </section>

</x-main-layout>
