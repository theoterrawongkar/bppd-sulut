<x-main-layout>

    {{-- Bagian Lihat Event --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h2 class="text-md md:text-xl text-center md:text-left font-semibold mb-4 text-[#486284]">
            {{ $eventPlace->business_name }}
        </h2>

        {{-- Waktu & Status --}}
        <div class="flex flex-col md:flex-row items-center text-sm text-gray-500 gap-2 mb-5">
            <x-icons.calendar />
            <span>
                {{ $eventPlace->start_time->format('d M Y H:i') }} - {{ $eventPlace->end_time->format('d M Y H:i') }}
            </span>

            @php $status = $eventPlace->status; @endphp
            <span
                class="px-2 py-1 text-xs font-medium rounded-full shadow
                {{ match ($status['type']) {
                    'upcoming' => 'bg-blue-600 text-white',
                    'ongoing' => 'bg-green-600 text-white',
                    'ended' => 'bg-gray-500 text-white',
                } }}">
                {{ $status['text'] }}
            </span>
        </div>

        {{-- Layout Event --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Galeri --}}
            <div class="md:col-span-2" x-data="{
                mainImage: '{{ $eventPlace->firstImage ? asset('storage/' . $eventPlace->firstImage->image) : asset('img/placeholder.webp') }}'
            }">

                {{-- Gambar Utama --}}
                <div class="aspect-video rounded-xl overflow-hidden shadow-md mb-4">
                    <img :src="mainImage" alt="Thumbnail Event"
                        class="w-full h-full object-cover transition duration-300">
                </div>

                {{-- Galeri --}}
                <div class="flex md:grid md:grid-cols-3 gap-2 overflow-x-auto md:overflow-visible">
                    @forelse ($eventPlace->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Galeri Event"
                            class="w-24 md:w-full h-24 object-cover rounded-md flex-shrink-0 border-2 border-transparent hover:border-[#486284] cursor-pointer transition duration-200"
                            @click="mainImage = '{{ asset('storage/' . $image->image) }}'">
                    @empty
                        @for ($i = 0; $i < 4; $i++)
                            <img src="{{ asset('img/placeholder.webp') }}" alt="Galeri Kosong"
                                class="w-24 md:w-full h-24 object-cover rounded-md flex-shrink-0 border border-gray-300">
                        @endfor
                    @endforelse
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Box Pendaftaran --}}
                <div class="bg-white p-4 rounded-xl shadow-md text-center">
                    <p class="mb-3 text-sm">Daftarkan dirimu dan jadilah bagian dari event ini</p>
                    @if ($alreadyRegistered)
                        <div class="mt-4 mb-1">
                            <form action="{{ route('myeventparticipation.destroy', $eventPlace->slug) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan partisipasi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-1/2 bg-red-600 hover:bg-red-700 text-white px-2 py-1.5 rounded-full">
                                    Batal Partisipasi
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Form daftar --}}
                        <form action="{{ route('myeventparticipation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_place_id" value="{{ $eventPlace->id }}">
                            <button type="submit" onclick="return confirm('Ingin mengikuti kegiatan ini?');"
                                class="w-1/2 bg-green-600 hover:bg-green-700 text-white px-2 py-1.5 rounded-full">
                                Daftar
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Box Informasi --}}
                <div class="bg-white p-4 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold mb-2 text-[#486284]">Informasi</h2>
                    <p class="text-sm mb-3">{{ $eventPlace->description }}</p>
                    <div class="flex flex-col gap-3 text-sm text-gray-700">

                        @if ($eventPlace->instagram_link)
                            <a href="{{ $eventPlace->instagram_link }}"
                                class="flex items-center gap-2 text-[#486284] hover:underline">
                                <x-icons.instagram /> Instagram
                            </a>
                        @endif

                        @if ($eventPlace->facebook_link)
                            <a href="{{ $eventPlace->facebook_link }}"
                                class="flex items-center gap-2 text-[#486284] hover:underline">
                                <x-icons.facebook /> Facebook
                            </a>
                        @endif

                        <p class="flex items-center gap-2 text-[#486284]">
                            <x-icons.ticket />
                            {{ $eventPlace->ticket_price == 0 ? 'Gratis' : 'Rp ' . number_format($eventPlace->ticket_price, 0, ',', '.') }}
                        </p>

                        <p class="flex items-center gap-2 text-[#486284]">
                            <x-icons.location />
                            {{ $eventPlace->address }}
                        </p>
                    </div>
                </div>

                {{-- Google Maps --}}
                <iframe src="{{ $eventPlace->gmaps_link }}" width="100%" height="200" style="border:0;"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-xl">
                </iframe>
            </div>
        </div>
    </section>

</x-main-layout>
