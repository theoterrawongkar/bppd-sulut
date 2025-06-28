<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">{{ $title }}</x-slot>

    {{-- Bagian Event --}}
    <section>
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4">
            {{-- Judul --}}
            <h1 class="text-xl font-bold">Manajemen Event</h1>

            {{-- Tambah dan Form Search --}}
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                {{-- Tombol Tambah --}}
                <a href="{{ route('dashboard.eventplace.create') }}"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                    Tambah Event
                </a>

                {{-- Form Pencarian --}}
                <form action="{{ route('dashboard.eventplace.index') }}" method="GET" class="flex items-center gap-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full lg:w-56 px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Cari nama event..." autocomplete="off">
                    <button type="submit"
                        class="px-4 py-2 text-sm border border-blue-500 rounded-md shadow-sm bg-blue-600 text-white cursor-pointer hover:bg-blue-700">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="mb-5">
            @if (session('success'))
                <div id="alert"
                    class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm border border-red-300">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Tabel Event --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#F4F8FC]">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama Event</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kategori</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Tanggal</th>
                        <th
                            class="w-16 px-2 py-2 text-sm font-medium text-gray-700 text-center leading-tight break-words">
                            Partisipan Diterima
                        </th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($eventPlaces as $eventPlace)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 font-semibold">{{ $eventPlace->business_name }}</td>
                            <td class="px-4 py-2">{{ $eventPlace->subCategory->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($eventPlace->start_time)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($eventPlace->end_time)->format('d M Y') }}
                            </td>
                            <td class="px-2 py-2 text-center">{{ $eventPlace->accepted_participants_count }}</td>
                            <td class="px-4 py-2 space-x-2 text-center whitespace-nowrap">
                                <a href="{{ route('dashboard.eventplace.edit', $eventPlace->slug) }}"
                                    class="text-yellow-600 hover:underline">Ubah</a>
                                <form action="{{ route('dashboard.eventplace.destroy', $eventPlace->slug) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 cursor-pointer hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-4 text-gray-500">Belum ada data event.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginator --}}
        <div class="mt-5">
            {{ $eventPlaces->links('pagination::app') }}
        </div>
    </section>
</x-app-layout>
