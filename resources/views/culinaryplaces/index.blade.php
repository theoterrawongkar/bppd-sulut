<x-main-layout>

    {{-- Bagian Header --}}
    <header class="relative">
        <img src="{{ asset('img/culinary-banner.jpg') }}" alt="Header Image" class="w-full h-64 md:h-96 object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-white text-2xl md:text-4xl font-bold">Kuliner Sulawesi Utara</h1>
            <p class="text-white text-sm mt-2 max-w-2xl text-balance">Beragam kuliner yang memanjakan lidah dengan
                sentuhan khas Sulawesi Utara</p>
        </div>
    </header>

    {{-- Bagian Kuliner --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('culinaryplace.index') }}" method="GET">
            {{-- Heading & Search --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
                <h3 class="text-md md:text-xl font-semibold uppercase text-center md:text-left">Kuliner</h3>
                <div class="relative w-full md:w-1/4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kuliner..."
                        autocomplete="off"
                        class="w-full border rounded-xl py-2 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                    <x-icons.search class="absolute right-3 top-2.5 w-5 h-5 text-gray-500 hover:text-blue-500 cursor-pointer" />
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                {{-- Sidebar: Kategori --}}
                <aside class="w-full md:w-1/4 lg:w-1/5 lg:max-w-[220px]">
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

                {{-- Kuliner Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1">
                    @forelse ($culinaryPlaces as $culinaryPlace)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="relative h-64 w-full">
                                <img src="{{ $culinaryPlace->firstImage ? asset('storage/' . $culinaryPlace->firstImage->image) : asset('img/placeholder.webp') }}"
                                    alt="{{ $culinaryPlace->business_name }}" class="w-full h-full object-cover">
                                <span
                                    class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full shadow bg-[#486284] text-white">
                                    {{ $culinaryPlace->subCategory->name }}
                                </span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-1 line-clamp-1">{{ $culinaryPlace->business_name }}</h3>
                                @php $rating = round($culinaryPlace->reviews_avg_rating ?? 0); @endphp
                                <div class="flex items-center text-yellow-400 text-sm mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <a href="{{ route('culinaryplace.show', $culinaryPlace->slug) }}"
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
                            Tidak ada kuliner ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </form>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $culinaryPlaces->links('pagination::main') }}
        </div>
    </section>

    {{-- Script: Debounce + Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('culinaryplace.index') }}"]');
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
