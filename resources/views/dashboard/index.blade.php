<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">{{ $title }}</x-slot>

    {{-- Bagian Dashboard --}}
    <section class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: User --}}
            <div class="space-y-4 lg:col-span-2">

                {{-- Total User Aktif --}}
                <div class="bg-white shadow rounded-xl p-4 flex items-center space-x-4">
                    <div
                        class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl">
                        👥
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">User Aktif</div>
                        <div class="text-xl font-bold text-blue-600">{{ $totalActiveUsers }}</div>
                    </div>
                </div>

                {{-- Breakdown per Role --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $roles = [
                            ['label' => 'Admin', 'icon' => '👑'],
                            ['label' => 'Pengguna', 'icon' => '🧑'],
                            ['label' => 'Pengusaha Kuliner', 'icon' => '🍽️'],
                            ['label' => 'Pengusaha Wisata', 'icon' => '🏝️'],
                            ['label' => 'Seniman', 'icon' => '🎨'],
                        ];
                    @endphp

                    @foreach ($roles as $role)
                        <div class="bg-white shadow rounded-xl p-4 flex items-center space-x-4">
                            <div
                                class="w-10 h-10 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center text-xl">
                                {{ $role['icon'] }}
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">{{ $role['label'] }}</div>
                                <div class="text-xl font-bold text-gray-800">{{ $activeUsers[$role['label']] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kolom Kanan: Kuliner, Wisata, Event --}}
            <div class="flex flex-col justify-between space-y-4 h-full">
                @php
                    $dataStats = [
                        [
                            'label' => 'Kuliner Disetujui',
                            'icon' => '🍽️',
                            'value' => $totalCulinaryApproved,
                            'color' => 'text-green-600',
                            'bg' => 'bg-green-100',
                        ],
                        [
                            'label' => 'Wisata Disetujui',
                            'icon' => '🏝️',
                            'value' => $totalTourApproved,
                            'color' => 'text-teal-600',
                            'bg' => 'bg-teal-100',
                        ],
                        [
                            'label' => 'Total Event',
                            'icon' => '🎭',
                            'value' => $totalEvent,
                            'color' => 'text-indigo-600',
                            'bg' => 'bg-indigo-100',
                        ],
                    ];
                @endphp

                @foreach ($dataStats as $stat)
                    <div class="bg-white shadow rounded-xl p-4 flex items-center space-x-4">
                        <div
                            class="w-10 h-10 {{ $stat['bg'] }} {{ $stat['color'] }} rounded-full flex items-center justify-center text-xl">
                            {{ $stat['icon'] }}
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
                            <div class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Statistik Pengunjung --}}
        <div class="bg-white shadow rounded-xl p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Statistik Pengunjung - Tahun {{ $selectedYear }}</h2>
                <form method="GET" action="{{ route('dashboard') }}" id="filterForm" class="flex items-center gap-2">
                    <label for="year" class="text-sm">Pilih Tahun:</label>
                    <select name="year" id="year" onchange="document.getElementById('filterForm').submit()"
                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <canvas id="visitorChart" height="120"></canvas>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');

        const visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($visitorChartData->pluck('month')) !!},
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: {!! json_encode($visitorChartData->pluck('total')) !!},
                    fill: false,
                    borderColor: 'rgba(96, 165, 250, 1)',
                    backgroundColor: 'rgba(96, 165, 250, 0.2)',
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(96, 165, 250, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>
