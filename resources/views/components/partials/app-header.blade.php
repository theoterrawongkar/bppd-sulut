{{-- Header --}}
<header class="h-18 flex items-center justify-between px-6 bg-[#486284] text-white shadow-xl">
    <div class="flex items-center">
        <button class="md:hidden" @click="sidebarOpen = true">
            <x-icons.hamburger class="mr-4" />
        </button>
        <h1 class="font-bold">{{ $title ?? 'Dashboard' }}</h1>
    </div>
    <div class="flex items-center space-x-4">
        <span class="text-sm">Hi, {{ Str::limit(Auth::user()->name, 10, '...') }}</span>
        <img class="w-10 h-10 rounded-full border-1 border-white"
            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('img/profile-placeholder.webp') }}"
            alt="Avatar">
    </div>
</header>
