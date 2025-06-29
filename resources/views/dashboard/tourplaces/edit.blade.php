<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">{{ $title }}</x-slot>

    {{-- Konten --}}
    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-5">

                {{-- Galeri --}}
                <div x-data="{ mainImage: '{{ $tourPlace->firstImage ? asset('storage/' . $tourPlace->firstImage->image) : asset('img/placeholder.webp') }}' }">
                    <div class="aspect-video rounded-xl overflow-hidden shadow-md mb-4">
                        <img :src="mainImage" alt="Thumbnail Wisata"
                            class="w-full h-full object-cover transition duration-300">
                    </div>

                    <div class="flex md:grid md:grid-cols-3 gap-2 overflow-x-auto md:overflow-visible">
                        @forelse ($tourPlace->images as $image)
                            <img src="{{ asset('storage/' . $image->image) }}" alt="Galeri Wisata"
                                class="w-24 md:w-full h-24 object-cover rounded-md flex-shrink-0 border-2 border-transparent hover:border-[#486284] cursor-pointer transition duration-200"
                                @click="mainImage = '{{ asset('storage/' . $image->image) }}'">
                        @empty
                            @for ($i = 0; $i < 4; $i++)
                                <img src="{{ asset('img/placeholder.webp') }}" alt="Galeri Kosong"
                                    class="w-24 md:w-full h-24 object-cover rounded-md flex-shrink-0 border border-gray-300 cursor-not-allowed">
                            @endfor
                        @endforelse
                    </div>
                </div>

                {{-- Informasi Akun --}}
                <div class="bg-white p-5 rounded-xl shadow-md mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Akun</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nama</label>
                            <input type="text" value="{{ $tourPlace->user->name ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="text" value="{{ $tourPlace->user->email ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status Akun</label>
                            <div class="flex flex-col lg:flex-row items-center gap-3 mt-1">
                                <input type="text"
                                    class="w-full py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm"
                                    value="{{ $tourPlace->user->is_active ? 'Aktif' : 'Tidak Aktif' }}" disabled>
                                <form action="{{ route('dashboard.user.toggle', $tourPlace->user->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-1 text-white text-sm rounded-md
                                        {{ $tourPlace->user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        {{ $tourPlace->user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Wisata Lainnya --}}
                <div class="bg-white p-5 rounded-xl shadow-md mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Wisata Lainnya oleh Pengguna Ini</h2>
                    <ul class="space-y-2 text-sm">
                        @forelse ($otherTourPlaces as $otherPlace)
                            <li class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $otherPlace->business_name }}</span>
                                    <div class="text-xs text-gray-500">{{ $otherPlace->address }}</div>
                                    <div class="mt-1">
                                        @php
                                            $badgeColor = match ($otherPlace->status) {
                                                'Menunggu Persetujuan'
                                                    => 'bg-yellow-200 border border-yellow-400 text-yellow-800',
                                                'Ditolak' => 'bg-red-200 border border-red-400 text-red-800',
                                                'Diterima' => 'bg-green-200 border border-green-400 text-green-800',
                                                'Tutup Permanen' => 'bg-gray-200 border border-gray-400 text-gray-800',
                                                default => 'bg-gray-100 border border-gray-400 text-gray-800',
                                            };
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $badgeColor }}">
                                            {{ $otherPlace->status }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('dashboard.tourplace.edit', $otherPlace->slug) }}"
                                    class="text-blue-600 hover:underline text-xs">Lihat Detail</a>
                            </li>
                        @empty
                            <li class="text-gray-500 text-sm italic">Tidak ada wisata lain yang dimiliki oleh pengguna
                                ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="bg-white p-5 rounded-xl shadow-md">
                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="mb-5 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm border border-green-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm border border-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-5">
                    {{-- Grid Info --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Tempat Wisata</label>
                                <input type="text" value="{{ $tourPlace->business_name }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea rows="3" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm resize-none">{{ $tourPlace->address }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi Tempat</label>
                                <textarea rows="4" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm resize-none">{{ $tourPlace->description }}</textarea>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pengelola</label>
                                <input type="text" value="{{ $tourPlace->owner_name }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Pengelola</label>
                                <input type="email" value="{{ $tourPlace->owner_email }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input type="text" value="{{ $tourPlace->phone }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instagram (opsional)</label>
                                <input type="text" value="{{ $tourPlace->instagram_link }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Facebook (opsional)</label>
                                <input type="text" value="{{ $tourPlace->facebook_link }}" disabled
                                    class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Kategori & Harga --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori Wisata</label>
                            <input type="text" value="{{ $tourPlace->subCategory->name }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Tiket</label>
                            <input type="text"
                                value="{{ $tourPlace->ticket_price ? 'Rp ' . number_format($tourPlace->ticket_price, 0, ',', '.') : 'Gratis' }}"
                                disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                        <div class="flex flex-wrap gap-2 text-sm">
                            @foreach ($tourPlace->facility ?? [] as $fasilitas)
                                <span class="px-2 py-1 bg-gray-200 rounded">{{ $fasilitas }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Jam Operasional --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Operasional</label>
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                            @foreach ($operatingHours as $day => $hour)
                                <div class="border p-2 rounded bg-gray-50">
                                    <div class="font-semibold">{{ $day }}</div>
                                    <div>{{ $hour->is_open ? "$hour->open_time - $hour->close_time" : 'Tutup' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi di Google Maps</label>
                        <div class="h-[150px] rounded-md overflow-hidden border border-gray-300">
                            <iframe src="{{ $tourPlace->gmaps_link }}" class="w-full h-full" style="border:0;"
                                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end mt-5">
                    <form action="{{ route('dashboard.tourplace.update', $tourPlace->slug) }}" method="POST"
                        class="space-x-1">
                        @csrf
                        @method('PUT')

                        @if ($tourPlace->status === 'Diterima')
                            {{-- Jika sudah diterima, tampilkan tombol "Tolak" --}}
                            <button type="submit" name="action" value="reject"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Tolak
                            </button>
                        @elseif ($tourPlace->status === 'Ditolak')
                            {{-- Jika sudah ditolak, tampilkan tombol "Terima" --}}
                            <button type="submit" name="action" value="accept"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                Terima
                            </button>
                        @elseif ($tourPlace->status === 'Menunggu Persetujuan')
                            {{-- Jika menunggu, tampilkan kedua tombol --}}
                            <button type="submit" name="action" value="reject"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Tolak
                            </button>
                            <button type="submit" name="action" value="accept"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                Terima
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
