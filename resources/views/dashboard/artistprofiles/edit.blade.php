<x-app-layout>
    <x-slot name="title">{{ $artistProfile->stage_name }}</x-slot>

    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-5">
                {{-- Portofolio (gambar / PDF preview) --}}
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Portofolio</h2>

                    @php
                        $isImage = Str::endsWith($artistProfile->portfolio_path, ['.jpg', '.jpeg', '.png', '.webp']);
                        $isPdf = Str::endsWith($artistProfile->portfolio_path, ['.pdf']);
                        $fileUrl = asset('storage/' . $artistProfile->portfolio_path);
                    @endphp

                    @if ($isImage)
                        <a href="{{ $fileUrl }}" target="_blank"
                            class="block aspect-video rounded-xl overflow-hidden shadow-md">
                            <img src="{{ $fileUrl }}" alt="Portofolio"
                                class="w-full h-full object-cover hover:opacity-90 transition duration-200">
                        </a>
                        <div class="mt-2 text-sm text-blue-600 underline">
                            <a href="{{ $fileUrl }}" target="_blank">Lihat Gambar Penuh</a>
                        </div>
                    @elseif ($isPdf)
                        <div class="w-full aspect-video rounded-xl overflow-hidden shadow-md">
                            <iframe src="{{ $fileUrl }}" class="w-full h-full border border-gray-300 rounded-md"
                                allowfullscreen></iframe>
                        </div>
                        <div class="mt-2 text-sm text-blue-600 underline">
                            <a href="{{ $fileUrl }}" target="_blank">Buka PDF di Tab Baru</a>
                        </div>
                    @else
                        <a href="{{ $fileUrl }}" target="_blank" class="text-blue-600 underline text-sm">Lihat
                            Portofolio (Dokumen)</a>
                    @endif
                </div>

                {{-- Informasi Akun --}}
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Akun</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nama</label>
                            <input type="text" value="{{ $artistProfile->user->name ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="text" value="{{ $artistProfile->user->email ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status Akun</label>
                            <div class="flex flex-col lg:flex-row items-center gap-3 mt-1">
                                <input type="text"
                                    class="w-full py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm"
                                    value="{{ $artistProfile->user->is_active ? 'Aktif' : 'Tidak Aktif' }}" disabled>
                                <form action="{{ route('dashboard.user.toggle', $artistProfile->user->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-1 text-white text-sm rounded-md
                                        {{ $artistProfile->user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        {{ $artistProfile->user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Event yang Diikuti --}}
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Event yang Diikuti</h2>
                    <ul class="space-y-3 text-sm">
                        @forelse ($artistProfile->eventParticipants as $participant)
                            @if ($participant->eventPlace)
                                <li class="flex justify-between items-start border-b pb-3">
                                    <div class="space-y-1">
                                        <div class="font-medium text-gray-800">
                                            {{ $participant->eventPlace->business_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $participant->eventPlace->address }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($participant->eventPlace->start_time)->translatedFormat('d F Y H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($participant->eventPlace->end_time)->translatedFormat('d F Y H:i') }}
                                        </div>
                                        <div class="mt-1">
                                            @php
                                                $badgeColor = match ($participant->status) {
                                                    'Menunggu Persetujuan'
                                                        => 'bg-yellow-200 border-yellow-400 text-yellow-800',
                                                    'Ditolak' => 'bg-red-200 border-red-400 text-red-800',
                                                    'Diterima' => 'bg-green-200 border-green-400 text-green-800',
                                                    'Berhenti Permanen' => 'bg-gray-200 border-gray-400 text-gray-800',
                                                    default => 'bg-gray-100 border-gray-400 text-gray-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-0.5 text-xs rounded-full font-semibold border {{ $badgeColor }}">
                                                {{ $participant->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('dashboard.eventplace.edit', $participant->eventPlace->slug) }}"
                                        class="text-blue-600 hover:underline text-xs mt-1">Lihat Event</a>
                                </li>
                            @endif
                        @empty
                            <li class="text-gray-500 text-sm italic">Belum mengikuti event apa pun.</li>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Panggung</label>
                        <input type="text" value="{{ $artistProfile->stage_name }}" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Seniman</label>
                        <input type="text" value="{{ $artistProfile->owner_email }}" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" value="{{ $artistProfile->phone }}" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bidang Kesenian</label>
                        <input type="text" value="{{ $artistProfile->field }}" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea rows="5" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm resize-none">{{ $artistProfile->description }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instagram</label>
                            <input type="text" value="{{ $artistProfile->instagram_link ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Facebook</label>
                            <input type="text" value="{{ $artistProfile->facebook_link ?? '-' }}" disabled
                                class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Profil</label>
                        <input type="text" value="{{ $artistProfile->status }}" disabled
                            class="w-full mt-1 py-1 px-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end mt-5">
                    <form action="{{ route('dashboard.artistprofile.update', $artistProfile->id) }}" method="POST"
                        class="space-x-1">
                        @csrf
                        @method('PUT')

                        @if ($artistProfile->status === 'Diterima')
                            {{-- Jika sudah diterima, tampilkan tombol "Tolak" --}}
                            <button type="submit" name="action" value="reject"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Tolak
                            </button>
                        @elseif ($artistProfile->status === 'Ditolak')
                            {{-- Jika sudah ditolak, tampilkan tombol "Terima" --}}
                            <button type="submit" name="action" value="accept"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                Terima
                            </button>
                        @elseif ($artistProfile->status === 'Menunggu Persetujuan')
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
