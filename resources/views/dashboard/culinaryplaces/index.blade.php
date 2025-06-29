<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">{{ $title }}</x-slot>

    {{-- Bagian Kuliner --}}
    <section>
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4">
            {{-- Judul --}}
            <h1 class="text-xl font-bold">Manajemen Kuliner</h1>

            {{-- Form Pencarian dan Status --}}
            <form id="searchCulinaryForm" action="{{ route('dashboard.culinaryplace.index') }}" method="GET"
                class="flex flex-wrap items-center gap-2">
                {{-- Input pencarian --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full lg:w-56 px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="Cari nama kuliner..." autocomplete="off">

                {{-- Select status --}}
                <select name="status"
                    class="w-full lg:w-auto px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu Persetujuan"
                        {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan
                    </option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Tutup Permanen" {{ request('status') == 'Tutup Permanen' ? 'selected' : '' }}>Tutup
                        Permanen</option>
                </select>

                {{-- Tombol Cari manual --}}
                <button type="submit"
                    class="w-full lg:w-auto px-4 py-2 text-sm border border-blue-500 rounded-md shadow-sm bg-blue-600 text-white cursor-pointer hover:bg-blue-700">
                    Cari
                </button>
            </form>

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

        {{-- Tabel Kuliner --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#F4F8FC]">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama Usaha</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama Pemilik</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kategori</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Status</th>
                        <th
                            class="w-16 px-2 py-2 text-center text-sm font-medium text-gray-700 leading-tight break-words">
                            Tanggal Pengajuan
                        </th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($culinaryPlaces as $culinaryPlace)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 font-semibold">{{ $culinaryPlace->business_name }}</td>
                            <td class="px-4 py-2">{{ Str::limit($culinaryPlace->owner_name, 20, '...') }}</td>
                            <td class="px-4 py-2">{{ $culinaryPlace->subCategory->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-center whitespace-nowrap">
                                {{-- Badge Status --}}
                                @php
                                    $status = $culinaryPlace->status;
                                    $badgeColor = match ($status) {
                                        'Menunggu Persetujuan'
                                            => 'bg-yellow-200 border border-yellow-400 text-yellow-800',
                                        'Ditolak' => 'bg-red-200 border border-red-400 text-red-800',
                                        'Diterima' => 'bg-green-200 border border-green-400 text-green-800',
                                        'Tutup Permanen' => 'bg-gray-200 border border-gray-400 text-gray-800',
                                        default => 'bg-gray-100 border border-gray-400 text-gray-800',
                                    };
                                @endphp

                                <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $badgeColor }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($culinaryPlace->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 space-x-2 text-center whitespace-nowrap">
                                <a href="{{ route('dashboard.culinaryplace.edit', $culinaryPlace->slug) }}"
                                    class="text-yellow-600 hover:underline">Ubah</a>
                                <form action="{{ route('dashboard.culinaryplace.destroy', $culinaryPlace->slug) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus kuliner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 cursor-pointer hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center px-4 py-4 text-gray-500">Belum ada data kuliner.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginator --}}
        <div class="mt-5">
            {{ $culinaryPlaces->links('pagination::app') }}
        </div>
    </section>

    {{-- Script: Debounce + Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('searchCulinaryForm');
            if (!form) return;

            const input = form.querySelector('input[name="search"]');
            const statusSelect = form.querySelector('select[name="status"]');
            let debounceTimer;

            // Debounce saat mengetik
            if (input) {
                input.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        localStorage.setItem('scrollPosition', window.scrollY);
                        form.submit();
                    }, 1000);
                });
            }

            // Submit otomatis saat status diubah
            if (statusSelect) {
                statusSelect.addEventListener('change', () => {
                    localStorage.setItem('scrollPosition', window.scrollY);
                    form.submit();
                });
            }

            // Kembalikan posisi scroll
            const scrollY = localStorage.getItem('scrollPosition');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

</x-app-layout>
