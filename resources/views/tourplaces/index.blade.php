<x-main-layout>

    {{-- Bagian Header --}}
    <header class="relative">
        <img src="{{ asset('img/tour-banner.jpg') }}" alt="Header Image" class="w-full h-64 md:h-96 object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-white text-2xl md:text-4xl font-bold">Destinasi Sulawesi Utara</h1>
            <p class="text-white text-sm mt-2 max-w-2xl text-balance">Temukan tempat wisata terbaik di Sulawesi</p>
        </div>
    </header>

    {{-- Bagian Wisata --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('tourplace.index') }}" method="GET">
            {{-- Heading & Search --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
                <h3 class="text-md md:text-xl font-semibold uppercase text-center md:text-left">Destinasi</h3>
                <div class="relative w-full md:w-1/4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari destinasi..."
                        autocomplete="off"
                        class="w-full border rounded-xl py-2 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                {{-- Sidebar: Kategori --}}
                <aside class="w-full md:w-64">
                    <div class="bg-white rounded-lg shadow p-4">
                        <label class="block text-sm font-medium text-gray-500 mb-3">KATEGORI</label>
                        <select name="sub_category"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                            <option value="" {{ request('sub_category') == '' ? 'selected' : '' }}>Semua Kategori
                            </option>
                            @foreach ($subCategories as $subCategory)
                                <option value="{{ $subCategory->slug }}"
                                    {{ request('sub_category') == $subCategory->slug ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </aside>

                {{-- Destinasi Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1">
                    @forelse ($tourPlaces as $tourPlace)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="relative h-64 w-full">
                                <img src="{{ $tourPlace->firstImage ? asset('storage/' . $tourPlace->firstImage->image) : asset('img/placeholder.webp') }}"
                                    alt="{{ $tourPlace->business_name }}" class="w-full h-full object-cover">
                                <span
                                    class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full shadow bg-[#486284] text-white">
                                    {{ $tourPlace->subCategory->name }}
                                </span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-1 line-clamp-1">{{ $tourPlace->business_name }}</h3>
                                @php $rating = round($tourPlace->reviews_avg_rating ?? 0); @endphp
                                <div class="flex items-center text-yellow-400 text-sm mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <a href="{{ route('tourplace.show', $tourPlace->slug) }}"
                                    class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                    Lihat
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500">
                            Tidak ada destinasi ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </form>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $tourPlaces->links('pagination::main') }}
        </div>
    </section>

    {{-- Script: Debounce + Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('tourplace.index') }}"]');
            const searchInput = form?.querySelector('input[name="search"]');
            const categorySelect = form?.querySelector('select[name="sub_category"]');

            if (!form || !categorySelect) return;

            let timer;

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        localStorage.setItem('scrollPosition', window.scrollY);
                        form.submit();
                    }, 700);
                });
            }

            categorySelect.addEventListener('change', () => {
                localStorage.setItem('scrollPosition', window.scrollY);
                form.submit();
            });

            const scrollY = localStorage.getItem('scrollPosition');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

</x-main-layout>
