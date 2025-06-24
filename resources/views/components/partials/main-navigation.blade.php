<!-- Bagian Navigasi -->
<nav class="bg-white shadow-sm border-b border-blue-200 py-2 text-[#486284]" x-data="{ open: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/application-logo.svg') }}" alt="Logo Badan Promosi Pariwisata Daerah Sulut"
                    class="h-12 w-12 object-contain" />

                <div class="leading-none">
                    <a href="{{ route('home') }}" class="block">
                        <h1 class="text-sm font-semibold uppercase leading-none text-[#486284]">
                            Badan Promosi <span class="block">Pariwisata Daerah</span>
                        </h1>
                        <span class="text-xs text-gray-500">Provinsi Sulawesi Utara</span>
                    </a>
                </div>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                <a href="{{ route('home') }}"
                    class="transition-colors hover:text-[#10B981] {{ Route::is('home') || Route::is('eventplace.*') ? 'text-[#10B981] font-bold' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('tourplace.index') }}"
                    class="transition-colors hover:text-[#10B981] {{ Route::is('tourplace.*') ? 'text-[#10B981] font-bold' : '' }}">Telusuri
                </a>
                <a href="{{ route('culinaryplace.index') }}"
                    class="transition-colors hover:text-[#10B981] {{ Route::is('culinaryplace.*') ? 'text-[#10B981] font-bold' : '' }}">Kuliner</a>
                <a href="{{ route('about') }}"
                    class="transition-colors hover:text-[#10B981] {{ Route::is('about') ? 'text-[#10B981] font-bold' : '' }}">Tentang
                    Kami</a>
                @auth
                    <div x-cloak x-data="{ open: false }" class="relative">
                        <!-- Tombol Avatar -->
                        <button @click="open = !open" class="flex items-center gap-1 focus:outline-none cursor-pointer">
                            <!-- Avatar -->
                            <div class="w-11 h-11 rounded-full overflow-hidden border border-gray-300">
                                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/profile-placeholder.webp') }}"
                                    alt="Avatar" class="object-cover w-full h-full">
                            </div>
                            <!-- Icon panah -->
                            <x-icons.arrow-down class="w-4 h-4 text-gray-600" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 z-50 overflow-hidden">

                            <!-- Header: Avatar, Nama, Role -->
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-300">
                                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/profile-placeholder.webp') }}"
                                        alt="Avatar" class="object-cover w-full h-full">
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-800 line-clamp-1">
                                        {{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500 capitalize line-clamp-1">{{ auth()->user()->role }}
                                    </div>
                                </div>
                            </div>

                            <!-- Menu -->
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil Saya
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Usaha Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-600">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="transition-colors hover:text-[#10B981] {{ Route::is('login') ? 'text-[#10B981] font-bold' : '' }}">
                        Login
                    </a>
                @endauth
            </nav>

            <!-- Tombol Hamburger (Mobile) -->
            <div class="md:hidden">
                <button @click="open = true" class="focus:outline-none focus:ring-2 focus:ring-[#486284] rounded"
                    aria-label="Buka menu">
                    <svg class="w-6 h-6 text-[#486284]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="open = false"
        aria-hidden="true">
    </div>

    <!-- Sidebar Mobile -->
    <div x-show="open" x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 w-64 h-full bg-white z-50 p-6 shadow-xl md:hidden" style="display: none;">

        <!-- Tombol Tutup -->
        <button @click="open = false"
            class="absolute top-4 right-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#486284]"
            aria-label="Tutup menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Menu Sidebar -->
        <nav class="mt-8 flex flex-col space-y-4 text-sm font-medium">
            <a href="{{ route('home') }}"
                class="transition-colors hover:text-[#10B981] {{ Route::is('home') || Route::is('eventplace.*') ? 'text-[#10B981] font-semibold' : '' }}">Beranda</a>
            <a href="{{ route('tourplace.index') }}"
                class="transition-colors hover:text-[#10B981] {{ Route::is('tourplace.*') ? 'text-[#10B981] font-semibold' : '' }}">Telusuri
            </a>
            <a href="{{ route('culinaryplace.index') }}"
                class="transition-colors hover:text-[#10B981] {{ Route::is('culinaryplace.*') ? 'text-[#10B981] font-semibold' : '' }}">Kuliner</a>
            <a href="{{ route('about') }}"
                class="transition-colors hover:text-[#10B981] {{ Route::is('about') ? 'text-[#10B981] font-semibold' : '' }}">Tentang
                Kami</a>

            @auth
                <div class="border-t pt-4">
                    <div class="flex items-center space-x-3 mb-3">
                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/profile-placeholder.webp') }}"
                                alt="Avatar" class="object-cover w-full h-full">
                        </div>

                        <!-- Nama & Role -->
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-[#486284]">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-xs text-gray-500 capitalize">
                                {{ auth()->user()->role }}
                            </div>
                        </div>
                    </div>

                    <a href="#" class="block py-1 text-gray-700 hover:text-[#10B981] hover:bg-gray-100 rounded">
                        Profil Saya
                    </a>
                    <a href="#" class="block py-1 text-gray-700 hover:text-[#10B981] hover:bg-gray-100 rounded">
                        Usaha Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-1 text-red-600 hover:bg-red-100 rounded">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="transition-colors hover:text-[#10B981] {{ Route::is('login') ? 'text-[#10B981] font-semibold' : '' }}">Login</a>
            @endauth
        </nav>
    </div>
</nav>
