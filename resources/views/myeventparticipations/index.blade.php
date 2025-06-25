<x-main-layout>
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('myeventparticipation.index') }}" method="GET">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
                <h3 class="text-md md:text-xl font-semibold uppercase text-center md:text-left">
                    Event yang Saya Ikuti
                </h3>

                <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Cari event yang diikuti..."
                            class="w-full border rounded-xl py-2 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                        <x-icons.search
                            class="absolute right-3 top-2.5 w-5 h-5 text-gray-500 hover:text-blue-500 cursor-pointer" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
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
                    @forelse ($events as $participant)
                        @php
                            $event = $participant->eventPlace;
                        @endphp
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden">
                            <div class="flex flex-col sm:flex-row">
                                {{-- Gambar --}}
                                <div class="relative w-full sm:w-1/4 sm:h-auto">
                                    <img src="{{ $event->firstImage ? asset('storage/' . $event->firstImage->image) : asset('img/placeholder.webp') }}"
                                        alt="{{ $event->business_name }}" class="w-full h-full object-cover">
                                    <span
                                        class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full shadow bg-[#486284] text-white">
                                        {{ $event->subCategory->name }}
                                    </span>
                                </div>

                                {{-- Konten --}}
                                <div class="w-full p-4 flex flex-col justify-between space-y-3">
                                    <div>
                                        <div
                                            class="flex flex-col-reverse lg:flex-row lg:items-center lg:justify-between mb-2 gap-2">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $event->business_name }}
                                            </h3>

                                            {{-- Status Partisipasi --}}
                                            @php
                                                $status = $participant->status;
                                                $badgeColor = match ($status) {
                                                    'Menunggu Persetujuan'
                                                        => 'bg-yellow-200 border border-yellow-400 text-yellow-800',
                                                    'Ditolak' => 'bg-red-200 border border-red-400 text-red-800',
                                                    'Diterima' => 'bg-green-200 border border-green-400 text-green-800',
                                                    'Berhenti Permanen'
                                                        => 'bg-gray-200 border border-gray-400 text-gray-800',
                                                    default => 'bg-gray-100 text-gray-800 border border-gray-400',
                                                };
                                            @endphp

                                            <span
                                                class="px-3 py-1 text-sm font-semibold rounded-full w-max {{ $badgeColor }}">
                                                {{ $status }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600 line-clamp-3">{{ $event->description }}</p>

                                        <div class="mt-2 space-y-1 text-sm text-[#486284]">
                                            <p class="font-semibold">Hubungi:</p>
                                            <p class="flex items-center gap-2"><x-icons.phone /> {{ $event->phone }}
                                            </p>
                                            @if ($event->instagram_link)
                                                <a href="{{ $event->instagram_link }}"
                                                    class="flex items-center gap-2 hover:underline">
                                                    <x-icons.instagram /> Instagram
                                                </a>
                                            @endif
                                            @if ($event->facebook_link)
                                                <a href="{{ $event->facebook_link }}"
                                                    class="flex items-center gap-2 hover:underline">
                                                    <x-icons.facebook /> Facebook
                                                </a>
                                            @endif
                                            <p class="flex items-center gap-2"><x-icons.location />
                                                {{ $event->address }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="text-sm text-gray-400">
                                            Dibuat pada {{ $event->created_at->translatedFormat('d F Y') }}
                                        </div>

                                        <a href="{{ route('eventplace.show', $event->slug) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                            Lihat
                                            <x-icons.edit class="w-4 h-4 ml-2" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">Tidak ada partisipasi event ditemukan.</div>
                    @endforelse
                </div>
            </div>
        </form>

        <div class="mt-10">
            {{ $events->links('pagination::main') }}
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('myeventparticipation.index') }}"]');
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
