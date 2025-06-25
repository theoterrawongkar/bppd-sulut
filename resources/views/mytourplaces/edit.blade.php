<x-main-layout>

    {{-- Bagian Ubah Kuliner Saya --}}
    <section class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            {{-- Title --}}
            <h2 class="text-md md:text-xl font-semibold text-center md:text-left mb-4 text-[#486284]">
                {{ $tourPlace->business_name }}
            </h2>

            {{-- Layout Kuliner --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Galeri --}}
                <div class="" x-data="{
                    mainImage: '{{ $tourPlace->firstImage ? asset('storage/' . $tourPlace->firstImage->image) : asset('img/placeholder.webp') }}'
                }">

                    {{-- Gambar Utama --}}
                    <div class="aspect-video rounded-xl overflow-hidden shadow-md mb-4">
                        <img :src="mainImage" alt="Thumbnail Kuliner"
                            class="w-full h-full object-cover transition duration-300">
                    </div>

                    {{-- Galeri --}}
                    <div class="flex md:grid md:grid-cols-3 gap-2 overflow-x-auto md:overflow-visible">
                        @forelse ($tourPlace->images as $image)
                            <img src="{{ asset('storage/' . $image->image) }}" alt="Galeri Kuliner"
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

                {{-- Box Ubah --}}
                <div class="bg-white p-5 rounded-xl shadow-md">
                    {{-- Informasi Usaha Kuliner --}}
                    <div class="space-y-5">
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

                        <form method="POST" action="{{ route('mytourplace.update', $tourPlace->slug) }}"
                            enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-2 gap-3">
                                {{-- Kiri --}}
                                <div class="space-y-3">
                                    {{-- Nama Tempat Wisata --}}
                                    <div>
                                        <label for="business_name" class="block text-sm font-medium text-gray-700">Nama
                                            Tempat Wisata</label>
                                        <input id="business_name" type="text" name="business_name"
                                            value="{{ old('business_name', $tourPlace->business_name) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('business_name')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Alamat --}}
                                    <div>
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea id="address" name="address" rows="2"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284] resize-none">{{ old('address', $tourPlace->address) }}</textarea>
                                        @error('address')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Link Google Maps --}}
                                    <div class="relative">
                                        <label for="gmaps_link" class="block text-sm font-medium text-gray-700">Link
                                            Google Maps</label>
                                        <input id="gmaps_link" type="text" name="gmaps_link"
                                            value="{{ old('gmaps_link', $tourPlace->gmaps_link) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284] peer">

                                        <!-- Panduan -->
                                        <ol
                                            class="list-decimal list-inside absolute left-0 top-full mt-1 w-full sm:w-96 bg-white text-xs text-gray-600 border border-gray-300 rounded shadow-lg p-3 opacity-0 peer-focus:opacity-100 transition-opacity duration-200 z-10 whitespace-normal break-words pointer-events-none">
                                            <li>Buka situs Google Maps.</li>
                                            <li>Cari lokasi usaha Anda.</li>
                                            <li>Klik tombol “Bagikan”.</li>
                                            <li>Pilih tab “Sematkan peta”.</li>
                                            <li>
                                                Salin tautan dari atribut <code>src="..."</code> di kode iframe.<br>
                                                <span class="text-gray-400">Contoh:
                                                    <code>https://www.google.com/maps/embed?pb=...</code>
                                                </span>
                                            </li>
                                            <li>Tempelkan tautan ke kolom input ini.</li>
                                        </ol>

                                        @error('gmaps_link')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Deskripsi Tempat --}}
                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700">Deskripsi Tempat</label>
                                        <textarea id="description" name="description" rows="3"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284] resize-none">{{ old('description', $tourPlace->description) }}</textarea>
                                        @error('description')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Kanan --}}
                                <div class="space-y-3">
                                    {{-- Nama Pengelola --}}
                                    <div>
                                        <label for="owner_name" class="block text-sm font-medium text-gray-700">Nama
                                            Pengelola</label>
                                        <input id="owner_name" type="text" name="owner_name"
                                            value="{{ old('owner_name', $tourPlace->owner_name) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('owner_name')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email Pengelola --}}
                                    <div>
                                        <label for="owner_email" class="block text-sm font-medium text-gray-700">Email
                                            Pengelola</label>
                                        <input id="owner_email" type="email" name="owner_email"
                                            value="{{ old('owner_email', $tourPlace->owner_email) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('owner_email')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nomor Telepon --}}
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                            Telepon</label>
                                        <input id="phone" type="text" name="phone"
                                            value="{{ old('phone', $tourPlace->phone) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('phone')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Instagram --}}
                                    <div>
                                        <label for="instagram_link"
                                            class="block text-sm font-medium text-gray-700">Instagram (opsional)</label>
                                        <input id="instagram_link" type="text" name="instagram_link"
                                            value="{{ old('instagram_link', $tourPlace->instagram_link) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('instagram_link')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Facebook --}}
                                    <div>
                                        <label for="facebook_link"
                                            class="block text-sm font-medium text-gray-700">Facebook (opsional)</label>
                                        <input id="facebook_link" type="text" name="facebook_link"
                                            value="{{ old('facebook_link', $tourPlace->facebook_link) }}"
                                            class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        @error('facebook_link')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Select Menu & Tiket --}}
                            <div class="space-y-3">
                                {{-- Kategori --}}
                                <div>
                                    <label for="sub_category_id"
                                        class="block text-sm font-medium text-gray-700">Kategori Wisata</label>
                                    <select id="sub_category_id" name="sub_category_id"
                                        class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($tourSubCategories as $sub)
                                            <option value="{{ $sub->id }}" @selected(old('sub_category_id', $tourPlace->sub_category_id) == $sub->id)>
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
                                    <label for="ticket_price" class="block text-sm font-medium text-gray-700">Harga
                                        Tiket (opsional)</label>
                                    <input id="ticket_price" type="number" step="0.01" name="ticket_price"
                                        value="{{ old('ticket_price', $tourPlace->ticket_price) }}"
                                        class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak dipungut biaya</p>
                                    @error('ticket_price')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Upload Menu --}}
                            <div class="space-y-3">
                                {{-- Upload Foto Usaha (Gambar) --}}
                                <div>
                                    <label for="images" class="block text-sm font-medium text-gray-700">Foto
                                        Tempat
                                        Usaha (Gambar)</label>
                                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                                        class="w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-green-800 file:bg-green-200 hover:file:bg-green-300">
                                    <p class="mt-1 text-xs text-gray-500">Unggah maksimal 5 gambar. Format: JPG,
                                        PNG,
                                        JPEG. Maks: 2MB</p>

                                    @if ($tourPlace->images->count())
                                        <p class="text-xs text-gray-600 mt-1">Foto yang sudah diunggah:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-500">
                                            @foreach ($tourPlace->images as $img)
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
                                </div>
                            </div>

                            {{-- Fasilitas --}}
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">Fasilitas</label>
                                <div class="grid grid-cols-2 gap-1 mt-1 text-sm">
                                    @php
                                        $facilities = [
                                            'Ruang VIP',
                                            'WiFi',
                                            'Toilet',
                                            'AC',
                                            'Parkir',
                                            'Akses Jalan Memadai',
                                        ];
                                        $selectedFacilities = old('facility', $tourPlace->facility);
                                    @endphp
                                    @foreach ($facilities as $facility)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="facility[]" value="{{ $facility }}"
                                                {{ in_array($facility, $selectedFacilities) ? 'checked' : '' }}>
                                            <span>{{ $facility }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('facility')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jam Operasional --}}
                            <div class="space-y-3">
                                <label class="block mb-1 text-sm font-medium text-gray-700">Jam Operasional</label>

                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach ($days as $day)
                                    @php
                                        // Ambil dari old() kalau ada, jika tidak ambil dari data database
                                        $open = old("open_time.$day", $operatingHours[$day]->open_time ?? null);
                                        $close = old("close_time.$day", $operatingHours[$day]->close_time ?? null);
                                        $isClosed = old(
                                            "is_closed.$day",
                                            isset($operatingHours[$day]) && !$operatingHours[$day]->is_open,
                                        );
                                    @endphp

                                    <div x-data="{ closed: {{ $isClosed ? 'true' : 'false' }} }" class="grid grid-cols-4 gap-3 items-center">
                                        {{-- Hari --}}
                                        <div class="text-sm font-medium">{{ $day }}</div>

                                        {{-- Jam Buka --}}
                                        <div>
                                            <input type="time" name="open_time[{{ $day }}]"
                                                x-bind:disabled="closed"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                                value="{{ $open }}">
                                        </div>

                                        {{-- Jam Tutup --}}
                                        <div>
                                            <input type="time" name="close_time[{{ $day }}]"
                                                x-bind:disabled="closed"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                                                value="{{ $close }}">
                                        </div>

                                        {{-- Checkbox: Tutup Seharian --}}
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" name="is_closed[{{ $day }}]"
                                                value="1" x-model="closed"
                                                class="h-4 w-4 text-[#486284] border-gray-300 rounded"
                                                {{ $isClosed ? 'checked' : '' }}>
                                            <span class="text-sm">Tutup</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($tourPlace->status == 'Tutup Permanen')
                                <div class="flex justify-end">
                                    <button type="submit" name="action" value="open"
                                        onclick="return confirm('Apakah usaha ini telah buka kembali?')"
                                        class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                                        Buka Kembali
                                    </button>
                                </div>
                            @else
                                <div class="flex justify-end gap-3">
                                    <button type="submit" name="action" value="close"
                                        onclick="return confirm('Yakin ingin menutup usaha ini secara permanen?')"
                                        class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-800 transition">
                                        Tutup Permanen
                                    </button>
                                    <button type="submit" name="action" value="update"
                                        class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews Kuliner --}}
    {{-- @include('tourplaces.partials.reviews') --}}

</x-main-layout>
