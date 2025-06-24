<x-main-layout>

    {{-- Bagian Login --}}
    <section class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6">
        <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-lg rounded-lg overflow-hidden">

            {{-- Ilustrasi --}}
            <div class="md:w-1/2 h-48 md:h-auto bg-cover bg-center relative"
                style="background-image: url('{{ asset('img/login-banner.jpg') }}')">
                <div class="absolute inset-0 bg-black/50"></div>
            </div>

            {{-- Form Login --}}
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8 bg-white">
                <h1 class="text-2xl font-semibold text-[#486284] mb-6">Masuk</h1>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Notifikasi --}}
                    @if (session('success'))
                        <div id="alert"
                            class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm border border-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="alert"
                            class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm border border-red-300">
                            {{ session('error') }}
                        </div>
                    @endif

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
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" id="password" name="password"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]" />

                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-gray-500 focus:outline-none">
                                <x-icons.eye class="h-5 w-5" x-show="!show" />
                                <x-icons.eye-off class="h-5 w-5" x-show="show" />
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember"
                            class="text-[#486284] border-gray-300 rounded shadow-sm focus:ring-[#486284]">
                        <label for="remember" class="ml-2 text-sm text-gray-900">Ingat saya</label>
                    </div>

                    {{-- Tombol Login --}}
                    <div>
                        <button type="submit"
                            class="w-full bg-[#486284] text-white py-2 px-4 rounded-md hover:bg-slate-700 transition duration-200">
                            Masuk
                        </button>
                    </div>

                    {{-- Daftar --}}
                    <p class="text-sm text-center text-gray-700">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[#486284] font-medium hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </section>

</x-main-layout>
