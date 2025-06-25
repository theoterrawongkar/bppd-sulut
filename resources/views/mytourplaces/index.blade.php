<x-main-layout>

    {{-- Bagian Wisata Saya --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('mytourplace.index') }}" method="GET">
            {{-- Heading & Search --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
                {{-- Judul --}}
                <h3 class="text-md md:text-xl font-semibold uppercase text-center md:text-left">
                    Wisata Saya
                </h3>

                {{-- Search + Tambah --}}
                <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                    {{-- Search Input --}}
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Cari wisata saya..."
                            class="w-full border rounded-xl py-2 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                        <x-icons.search
                            class="absolute right-3 top-2.5 w-5 h-5 text-gray-500 hover:text-blue-500 cursor-pointer" />
                    </div>

                    {{-- Tombol Tambah --}}
                    <a href="{{ route('mytourplace.create') }}"
                        class="bg-[#3b5d85] text-white font-semibold uppercase px-4 py-3 rounded-xl text-sm hover:bg-[#2f4c6a] transition">
                        Tambah Wisata
                    </a>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                {{-- Sidebar Kategori --}}
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

                {{-- Konten Wisata --}}
                <div class="grid grid-cols-1 gap-6 flex-1">
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
                    @forelse ($tourPlaces as $tourPlace)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden">
                            <div class="flex flex-col sm:flex-row">
                                {{-- Gambar --}}
                                <div class="relative w-full sm:w-1/4 sm:h-auto">
                                    <img src="{{ $tourPlace->firstImage ? asset('storage/' . $tourPlace->firstImage->image) : asset('img/placeholder.webp') }}"
                                        alt="{{ $tourPlace->business_name }}" class="w-full h-full object-cover">
                                    <span
                                        class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full shadow bg-[#486284] text-white">
                                        {{ $tourPlace->subCategory->name }}
                                    </span>
                                </div>

                                {{-- Konten --}}
                                <div class="w-full p-4 flex flex-col justify-between space-y-3">
                                    <div>
                                        <div
                                            class="flex flex-col-reverse lg:flex-row lg:items-center lg:justify-between mb-2 gap-2">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $tourPlace->business_name }}
                                            </h3>

                                            {{-- Badge Status --}}
                                            @php
                                                $status = $tourPlace->status;
                                                $badgeColor = match ($status) {
                                                    'Menunggu Persetujuan'
                                                        => 'bg-yellow-200 border border-yellow-400 text-yellow-800',
                                                    'Ditolak' => 'bg-red-200 border border-red-400 text-red-800',
                                                    'Diterima' => 'bg-green-200 border border-green-400 text-green-800',
                                                    'Tutup Permanen'
                                                        => 'bg-gray-200 border border-gray-400 text-gray-800',
                                                    default => 'bg-gray-100 text-gray-800 border border-gray-400',
                                                };
                                            @endphp

                                            <span
                                                class="px-3 py-1 text-sm font-semibold rounded-full w-max self-start lg:self-auto {{ $badgeColor }}">
                                                {{ $status }}
                                            </span>
                                        </div>

                                        {{-- Deskripsi & Kontak --}}
                                        <p class="text-sm text-gray-600 line-clamp-3">{{ $tourPlace->description }}
                                        </p>
                                        <div class="mt-2 space-y-1">
                                            <h4 class="font-semibold text-sm text-[#486284]">Hubungi Kami:</h4>
                                            <p class="flex items-center gap-2 text-sm text-[#486284]">
                                                <x-icons.phone /> {{ $tourPlace->phone }}
                                            </p>
                                            @if ($tourPlace->instagram_link)
                                                <a href="{{ $tourPlace->instagram_link }}"
                                                    class="flex items-center gap-2 text-sm text-[#486284] hover:underline">
                                                    <x-icons.instagram /> Instagram
                                                </a>
                                            @endif
                                            @if ($tourPlace->facebook_link)
                                                <a href="{{ $tourPlace->facebook_link }}"
                                                    class="flex items-center gap-2 text-sm text-[#486284] hover:underline">
                                                    <x-icons.facebook /> Facebook
                                                </a>
                                            @endif
                                            <p class="flex items-center gap-2 text-sm text-[#486284]">
                                                <x-icons.location class="shrink-0" /> {{ $tourPlace->address }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="text-sm text-gray-400">
                                            Dibuat pada {{ $tourPlace->created_at->translatedFormat('d F Y') }}
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('mytourplace.edit', $tourPlace->slug) }}"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                                Ubah
                                                <x-icons.edit class="w-4 h-4 ml-2" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500">
                            Tidak ada wisata ditemukan.
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
            const form = document.querySelector('form[action="{{ route('mytourplace.index') }}"]');
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
