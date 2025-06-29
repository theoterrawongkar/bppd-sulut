<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Ubah Event</x-slot>

    {{-- Bagian Edit Event --}}
    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-5">
                {{-- Galeri --}}
                <div class="" x-data="{
                    mainImage: '{{ $eventPlace->firstImage ? asset('storage/' . $eventPlace->firstImage->image) : asset('img/placeholder.webp') }}'
                }">

                    {{-- Gambar Utama --}}
                    <div class="aspect-video rounded-xl overflow-hidden shadow-md mb-4">
                        <img :src="mainImage" alt="Thumbnail Kuliner"
                            class="w-full h-full object-cover transition duration-300">
                    </div>

                    {{-- Galeri Thumbnail --}}
                    <div class="flex md:grid md:grid-cols-3 gap-2 overflow-x-auto md:overflow-visible">
                        @forelse ($eventPlace->images as $image)
                            <img src="{{ asset('storage/' . $image->image) }}" alt="Galeri Kuliner"
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

                {{-- Partisipan Menunggu atau Ditolak --}}
                <div class="bg-white p-5 rounded-xl shadow-md mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Partisipan Menunggu & Ditolak</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-[#F4F8FC]">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">#</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Nama
                                        Panggung</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Telepon</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($pendingOrRejectedParticipants as $participant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 font-medium">
                                            {{ $participant->artistProfile->stage_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $participant->artistProfile->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @php
                                                $statusColor = match ($participant->status) {
                                                    'Menunggu Persetujuan'
                                                        => 'bg-yellow-200 text-yellow-800 border-yellow-400',
                                                    'Ditolak' => 'bg-red-200 text-red-800 border-red-400',
                                                    default => 'bg-gray-200 text-gray-800 border-gray-400',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs rounded-full border {{ $statusColor }}">
                                                {{ $participant->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                                            {{-- Lihat Profil --}}
                                            <a href="{{ route('dashboard.artistprofile.edit', $participant->artistProfile->id) }}"
                                                class="text-blue-600 text-xs hover:underline">Lihat</a>

                                            {{-- Tolak --}}
                                            <form
                                                action="{{ route('dashboard.eventparticipant.updateStatus', $participant->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Yakin ingin menolak partisipasi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit"
                                                    class="text-red-600 text-xs hover:underline">Tolak</button>
                                            </form>

                                            {{-- Setujui --}}
                                            <form
                                                action="{{ route('dashboard.eventparticipant.updateStatus', $participant->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Setujui partisipasi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Diterima">
                                                <button type="submit"
                                                    class="text-green-600 text-xs hover:underline">Setujui</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center px-4 py-4 text-gray-500 italic">
                                            Tidak ada partisipan yang menunggu atau ditolak.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Partisipan Diterima --}}
                <div class="bg-white p-5 rounded-xl shadow-md mt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Partisipan yang Diterima</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-[#F4F8FC]">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">#</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Nama
                                        Panggung</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Telepon</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($acceptedParticipants as $participant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 font-medium">
                                            {{ $participant->artistProfile->stage_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $participant->artistProfile->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <span
                                                class="bg-green-200 text-green-800 px-2 py-0.5 text-xs rounded-full border border-green-400">
                                                {{ $participant->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 space-x-2 text-center whitespace-nowrap">
                                            <a href="{{ route('dashboard.artistprofile.edit', $participant->artistProfile->id) }}"
                                                class="text-blue-600 text-xs hover:underline">Lihat</a>

                                            <form
                                                action="{{ route('dashboard.eventparticipant.updateStatus', $participant->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Yakin ingin membatalkan partisipasi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit"
                                                    class="text-red-600 text-xs cursor-pointer hover:underline">Batalkan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center px-4 py-4 text-gray-500 italic">
                                            Tidak ada partisipan yang diterima.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-md">
                {{-- Notifikasi --}}
                <div class="mb-5">
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
                </div>

                <form action="{{ route('dashboard.eventplace.update', $eventPlace->slug) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Kiri --}}
                        <div class="space-y-3">
                            {{-- Nama Event --}}
                            <div>
                                <label for="business_name" class="block text-sm font-medium text-gray-700">Nama
                                    Event</label>
                                <input id="business_name" type="text" name="business_name"
                                    value="{{ old('business_name', $eventPlace->business_name) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('business_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm resize-none border-gray-300 focus:outline-[#486284]">{{ old('address', $eventPlace->address) }}</textarea>
                                @error('address')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Link Google Maps --}}
                            <div class="relative">
                                <label for="gmaps_link" class="block text-sm font-medium text-gray-700">Link Google
                                    Maps</label>
                                <input id="gmaps_link" type="text" name="gmaps_link"
                                    value="{{ old('gmaps_link', $eventPlace->gmaps_link) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm peer focus:outline-[#486284]">
                                {{-- Panduan sama seperti create --}}
                                <ol
                                    class="list-decimal list-inside absolute left-0 top-full mt-1 w-full sm:w-96 bg-white text-xs text-gray-600 border border-gray-300 rounded shadow-lg p-3 opacity-0 peer-focus:opacity-100 transition-opacity duration-200 z-10 whitespace-normal break-words pointer-events-none">
                                    <li>Buka situs Google Maps.</li>
                                    <li>Cari lokasi usaha Anda.</li>
                                    <li>Klik tombol “Bagikan”.</li>
                                    <li>Pilih tab “Sematkan peta”.</li>
                                    <li>Salin tautan dari atribut <code>src="..."</code> di kode iframe.</li>
                                    <li>Tempelkan tautan ke kolom input ini.</li>
                                </ol>
                                @error('gmaps_link')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                    Event</label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm resize-none border-gray-300 focus:outline-[#486284]">{{ old('description', $eventPlace->description) }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kanan --}}
                        <div class="space-y-3">
                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700">Nama
                                    Penanggung
                                    Jawab</label>
                                <input id="owner_name" type="text" name="owner_name"
                                    value="{{ old('owner_name', $eventPlace->owner_name) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('owner_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="owner_email" class="block text-sm font-medium text-gray-700">Email
                                    Penanggung Jawab</label>
                                <input id="owner_email" type="email" name="owner_email"
                                    value="{{ old('owner_email', $eventPlace->owner_email) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('owner_email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                    Telepon</label>
                                <input id="phone" type="text" name="phone"
                                    value="{{ old('phone', $eventPlace->phone) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('phone')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram
                                    (opsional)</label>
                                <input id="instagram_link" type="text" name="instagram_link"
                                    value="{{ old('instagram_link', $eventPlace->instagram_link) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('instagram_link')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook
                                    (opsional)</label>
                                <input id="facebook_link" type="text" name="facebook_link"
                                    value="{{ old('facebook_link', $eventPlace->facebook_link) }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                @error('facebook_link')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Kategori --}}
                        <div>
                            <label for="sub_category_id"
                                class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select id="sub_category_id" name="sub_category_id"
                                class="w-full mt-1 py-1 px-2 text-sm border rounded-md shadow-sm border-gray-300 focus:outline-[#486284]">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($subCategories as $sub)
                                    <option value="{{ $sub->id }}" @selected(old('sub_category_id', $eventPlace->sub_category_id) == $sub->id)>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_category_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Tiket --}}
                        <div>
                            <label for="ticket_price" class="block text-sm font-medium text-gray-700">Harga Tiket
                                (opsional)</label>
                            <input id="ticket_price" type="number" step="0.01" name="ticket_price"
                                value="{{ old('ticket_price', $eventPlace->ticket_price) }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak dipungut biaya/gratis</p>
                            @error('ticket_price')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload Foto Baru --}}
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700">Tambah Foto Baru
                                (opsional)</label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                class="w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-green-800 file:bg-green-200 hover:file:bg-green-300">
                            <p class="mt-1 text-xs text-gray-500">Unggah maksimal 5 gambar. Format: JPG, PNG,
                                JPEG. Maks: 2MB</p>

                            @if ($eventPlace->images->count())
                                <p class="text-xs text-gray-600 mt-1">Foto yang sudah diunggah:</p>
                                <ul class="list-disc list-inside text-sm text-gray-500">
                                    @foreach ($eventPlace->images as $img)
                                        <li>
                                            <a href="{{ asset('storage/' . $img->image) }}" target="_blank"
                                                class="text-blue-500 underline">
                                                {{ basename($img->image) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @error('images')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu
                                Mulai</label>
                            <input id="start_time" type="datetime-local" name="start_time"
                                value="{{ old('start_time', \Carbon\Carbon::parse($eventPlace->start_time)->format('Y-m-d\TH:i')) }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            @error('start_time')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Berakhir --}}
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu
                                Berakhir</label>
                            <input id="end_time" type="datetime-local" name="end_time"
                                value="{{ old('end_time', \Carbon\Carbon::parse($eventPlace->end_time)->format('Y-m-d\TH:i')) }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            @error('end_time')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-app-layout>
