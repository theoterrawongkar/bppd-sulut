<x-main-layout>

    {{-- Script Tambahan --}}
    <x-slot name="script">
        <style>
            /* Pastikan container bisa discroll horizontal di layar kecil */
            #calendar {
                overflow-x: auto;
                width: 100%;
            }

            /* Skala font dan padding di layar kecil */
            @media (max-width: 768px) {
                .fc .fc-toolbar {
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .fc-toolbar-title {
                    font-size: 1rem;
                    text-align: center;
                }

                .fc-button {
                    font-size: 0.75rem;
                    padding: 4px 6px !important;
                }

                .fc-col-header-cell-cushion,
                .fc-daygrid-day-number {
                    font-size: 0.75rem;
                    padding: 4px !important;
                }

                .fc-daygrid-event {
                    font-size: 0.7rem;
                    padding: 1px 4px !important;
                    line-height: 1rem;
                }
            }

            .fc-button-primary {
                background-color: #486284 !important;
                border-color: #486284 !important;
                color: white !important;
            }

            .fc-button-primary:hover {
                background-color: #3b5d75 !important;
                border-color: #3b5d75 !important;
            }

            .fc-toolbar-title {
                font-weight: 600;
                color: #374151;
            }

            .fc .fc-scrollgrid {
                border-color: #e5e7eb;
            }

            .fc-day-today {
                background-color: #fef3c7 !important;
            }

            .fc-daygrid-event {
                background-color: #486284 !important;
                color: white !important;
                padding: 2px 6px;
                border-radius: 8px;
                font-weight: 500;
            }
        </style>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>

        <script>
            let calendar; // buat variabel global

            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    contentHeight: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: @json($calendarEvents),
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.open(info.event.url, '_blank');
                            info.jsEvent.preventDefault();
                        }
                    }
                });
            });

            // Fungsi untuk render ulang saat tab aktif
            function handleTabChange(tab) {
                if (tab === 'calendar' && calendar) {
                    setTimeout(() => calendar.render(), 10);
                }
            }
        </script>
    </x-slot>

    {{-- Bagian Header --}}
    <header class="relative">
        <img src="{{ asset('img/home-banner.webp') }}" alt="Header Image" class="w-full h-64 md:h-96 object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-white text-2xl md:text-4xl font-bold">Welcome to Sulawesi Utara</h1>
            <p class="text-white text-sm mt-2 max-w-2xl text-balance">
                Looking for ideas this week? Explore exciting destinations, try delicious local food,
                and don’t miss the latest events around you!
            </p>
        </div>
    </header>

    {{-- Bagian Rekomendasi --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h2 class="text-md md:text-xl font-semibold mb-8 uppercase text-[#486284]">Rekomendasi</h2>
        <div class="flex justify-center flex-wrap gap-8 md:gap-16">
            @php
                $recomendations = [
                    [
                        'img' => asset('img/recomendations/mountains.svg'),
                        'label' => 'Pegunungan',
                        'slug' => 'gunung',
                        'url' => route('tourplace.index', ['sub_category' => 'gunung']),
                    ],
                    [
                        'img' => asset('img/recomendations/beach.svg'),
                        'label' => 'Pantai',
                        'slug' => 'pantai',
                        'url' => route('tourplace.index', ['sub_category' => 'pantai']),
                    ],
                    [
                        'img' => asset('img/recomendations/culinary.svg'),
                        'label' => 'Kuliner',
                        'slug' => null,
                        'url' => route('culinaryplace.index'),
                    ],
                    [
                        'img' => asset('img/recomendations/island.svg'),
                        'label' => 'Pulau',
                        'slug' => 'pulau',
                        'url' => route('tourplace.index', ['sub_category' => 'pulau']),
                    ],
                    [
                        'img' => asset('img/recomendations/tour.svg'),
                        'label' => 'Wisata',
                        'slug' => null,
                        'url' => route('tourplace.index'),
                    ],
                ];
            @endphp

            @foreach ($recomendations as $item)
                <a href="{{ $item['url'] }}" class="flex flex-col items-center w-24">
                    <img src="{{ $item['img'] }}" alt="{{ $item['label'] }}" class="w-20 h-20 object-contain mb-2">
                    <span class="text-sm font-medium text-center">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Bagian Event --}}
    <section class="container mx-auto py-10 px-4 sm:px-6 lg:px-8" x-data="{ tab: 'event' }" x-init="$watch('tab', value => handleTabChange(value))">
        {{-- Tabs --}}
        <div class="flex justify-center mb-10">
            <div class="bg-white shadow-md rounded-lg inline-flex text-sm overflow-hidden">
                <button @click="tab = 'event'"
                    :class="tab === 'event' ? 'bg-[#486284] text-white' : 'hover:bg-gray-200 text-gray-500'"
                    class="px-6 py-2 font-semibold transition">Event</button>
                <button @click="tab = 'calendar'"
                    :class="tab === 'calendar' ? 'bg-[#486284] text-white' : 'hover:bg-gray-200 text-gray-500'"
                    class="px-6 py-2 font-semibold transition">Kalender</button>
            </div>
        </div>

        {{-- Judul --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
            <h3 class="text-md md:text-xl font-semibold uppercase text-[#486284] text-center md:text-left"
                x-text="tab === 'event' ? 'Event' : 'Kalender'"></h3>

            {{-- Form Pencarian --}}
            <form x-show="tab === 'event'" id="searchEventForm" action="{{ route('home') }}" method="GET"
                class="w-full md:w-1/4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..."
                        class="w-full border border-[#486284] rounded-xl py-2 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#3b5d85]">
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-[#486284]" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>
            </form>
        </div>


        {{-- List Event --}}
        <div x-show="tab === 'event'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($eventPlaces as $eventPlace)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden">
                    <a href="{{ route('eventplace.show', $eventPlace->slug) }}">
                        @php $status = $eventPlace->status; @endphp
                        <div class="relative aspect-video w-full">
                            <img src="{{ $eventPlace->firstImage ? asset('storage/' . $eventPlace->firstImage->image) : asset('img/placeholder.webp') }}"
                                alt="Event" class="w-full h-full object-cover">
                            <span
                                class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full shadow
                                {{ match ($status['type']) {
                                    'upcoming' => 'bg-blue-600 text-white',
                                    'ongoing' => 'bg-green-600 text-white',
                                    'ended' => 'bg-gray-500 text-white',
                                } }}">
                                {{ $status['text'] }}
                            </span>
                        </div>
                        <div class="p-4">
                            <span class="block text-xs text-center text-gray-500">
                                {{ \Carbon\Carbon::parse($eventPlace->start_time)->format('d/m/Y H:i') }} -
                                {{ \Carbon\Carbon::parse($eventPlace->end_time)->format('d/m/Y H:i') }}
                            </span>
                            <h3 class="font-semibold my-2 text-[#486284] line-clamp-2">{{ $eventPlace->business_name }}
                            </h3>
                            <p class="text-xs line-clamp-3">{{ $eventPlace->description }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">Tidak ada event yang ditemukan.</div>
            @endforelse
        </div>

        {{-- Kalender --}}
        <div id="calendar" x-show="tab === 'calendar'"
            class="w-full min-h-[500px] md:min-h-[650px] bg-white border border-gray-200 rounded-xl shadow p-4 overflow-x-auto">
        </div>

        {{-- Pagination --}}
        <div class="mt-10" x-show="tab === 'event'">
            {{ $eventPlaces->links('pagination::main') }}
        </div>
    </section>

    {{-- Script: Debounce & Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('searchEventForm');
            const input = form.querySelector('input[name="search"]');
            let timer;

            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    localStorage.setItem('scrollPosition', window.scrollY);
                    form.submit();
                }, 700);
            });

            const scrollY = localStorage.getItem('scrollPosition');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

</x-main-layout>
