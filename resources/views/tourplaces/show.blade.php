<x-main-layout>

    {{-- Bagian Lihat Wisata --}}
    <section class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            {{-- Title  --}}
            <h2 class="text-md md:text-xl font-semibold text-center md:text-left mb-4 text-[#486284]">
                {{ $tourPlace->business_name }}
            </h2>

            {{-- Image Section  --}}
            <div x-data="{ mainImage: {{ json_encode($tourPlace->firstImage ? asset('storage/' . $tourPlace->firstImage->image) : asset('img/placeholder.webp')) }} }" class="flex flex-col lg:flex-row gap-4 mb-6">
                {{-- Main Image  --}}
                <div class="w-full lg:w-3/4">
                    <div class="aspect-video bg-gray-200 rounded-xl overflow-hidden shadow-md">
                        <img :src="mainImage" alt="Thumbnail Wisata"
                            class="w-full h-full object-cover object-center transition duration-300 ease-in-out">
                    </div>
                </div>
                {{-- Thumbnail Gallery  --}}
                <div class="w-full lg:w-1/4">
                    <div class="flex gap-2 overflow-x-auto lg:overflow-visible lg:flex-col">
                        @forelse ($tourPlace->images as $image)
                            <img src="{{ asset('storage/' . $image->image) }}" alt="Galeri Wisata"
                                class="w-24 h-24 lg:w-full lg:h-28 object-cover rounded-md cursor-pointer flex-shrink-0 border-2 border-transparent hover:border-[#486284] transition duration-200"
                                @click="mainImage = '{{ asset('storage/' . $image->image) }}'">
                        @empty
                            @for ($i = 0; $i < 4; $i++)
                                <img src="{{ asset('img/placeholder.webp') }}" alt="Galeri Kosong"
                                    class="w-24 h-24 lg:w-full lg:h-28 object-cover rounded-md flex-shrink-0 border border-gray-300">
                            @endfor
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Info & Schedule  --}}
            <div class="grid sm:grid-cols-2 md:grid-cols-[2fr_1fr] lg:grid-cols-[3fr_1fr] gap-6">
                {{-- Information Card  --}}
                <div class="bg-white p-4 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold mb-2 text-[#486284]">Informasi</h2>
                    <p class="text-sm mb-3">{{ $tourPlace->description }}</p>
                    <div class="flex flex-col md:flex-row gap-6 text-sm">
                        {{-- Contact Info  --}}
                        <div class="flex-1 space-y-1">
                            <h3 class="font-semibold text-[#486284]">Hubungi Kami:</h3>
                            <p class="flex items-center gap-2 text-[#486284]">
                                <x-icons.phone /> {{ $tourPlace->phone }}
                            </p>
                            @if ($tourPlace->instagram_link)
                                <a href="{{ $tourPlace->instagram_link }}"
                                    class="flex items-center gap-2 text-[#486284] hover:underline">
                                    <x-icons.instagram /> Instagram
                                </a>
                            @endif
                            @if ($tourPlace->facebook_link)
                                <a href="{{ $tourPlace->facebook_link }}"
                                    class="flex items-center gap-2 text-[#486284] hover:underline">
                                    <x-icons.facebook /> Facebook
                                </a>
                            @endif
                            <p class="flex items-center gap-2 text-[#486284]">
                                <x-icons.location /> {{ $tourPlace->address }}
                            </p>
                        </div>
                        {{-- Detail & Facilities  --}}
                        <div class="flex-1 space-y-3">
                            <div>
                                <h3 class="font-semibold text-[#486284] mb-1">Detail Kami:</h3>
                                <p class="flex items-center gap-2 text-[#486284]">
                                    <x-icons.ticket />
                                    {{ $tourPlace->ticket_price == 0 ? 'Gratis' : 'Rp ' . number_format($tourPlace->ticket_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#486284] mb-1">Fasilitas:</h3>
                                @foreach ($tourPlace->facility as $item)
                                    <p class="inline text-[#486284]">{{ $item }},</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <iframe src="{{ $tourPlace->gmaps_link }}" width="100%" height="200"
                        class="mt-2 rounded-xl border-0" allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                {{-- Operational Hours  --}}
                <div class="w-full">
                    <div class="bg-white rounded-lg py-4 lg:py-6 px-4 lg:px-10 shadow-md">
                        <h2 class="text-lg font-semibold mb-4 text-[#486284]">Waktu</h2>
                        <div class="flex items-center justify-center gap-1 mb-4">
                            <span class="{{ $isOpen ? 'text-green-600 font-semibold' : 'text-gray-400' }}">Buka</span>
                            <span class="text-gray-400">/</span>
                            <span class="{{ !$isOpen ? 'text-red-600 font-semibold' : 'text-gray-400' }}">Tutup</span>
                        </div>
                        <ul class="text-xs md:text-sm font-semibold space-y-1">
                            @foreach ($operatingHours as $hour)
                                <li class="flex justify-between">
                                    <span>{{ $hour['day'] }}</span>
                                    <span>{{ $hour['open'] === '-' || $hour['close'] === '-' ? 'Tutup' : $hour['open'] . '–' . $hour['close'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews Wisata --}}
    @include('tourplaces.partials.reviews')

</x-main-layout>
