{{-- Informasi Tempat Wisata --}}
<div class="space-y-5 mt-4">

    <div class="grid grid-cols-2 gap-3">
        {{-- Kiri --}}
        <div class="space-y-3">
            {{-- Nama Tempat Wisata --}}
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700">Nama Tempat Wisata</label>
                <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('business_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="address" name="address" rows="2"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284] resize-none">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link Google Maps --}}
            <div class="relative">
                <label for="gmaps_link" class="block text-sm font-medium text-gray-700">Link Google Maps</label>
                <input id="gmaps_link" type="text" name="gmaps_link" value="{{ old('gmaps_link') }}"
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

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Tempat</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284] resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kanan --}}
        <div class="space-y-3">
            {{-- Nama Pemilik --}}
            <div>
                <label for="owner_name" class="block text-sm font-medium text-gray-700">Nama Pengelola</label>
                <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('owner_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Pengelola --}}
            <div>
                <label for="owner_email" class="block text-sm font-medium text-gray-700">Email Pengelola</label>
                <input id="owner_email" type="email" name="owner_email" value="{{ old('owner_email') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('owner_email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Telepon --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('phone')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link Instagram --}}
            <div>
                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram (opsional)</label>
                <input id="instagram_link" type="text" name="instagram_link" value="{{ old('instagram_link') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('instagram_link')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link Facebook --}}
            <div>
                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook (opsional)</label>
                <input id="facebook_link" type="text" name="facebook_link" value="{{ old('facebook_link') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('facebook_link')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Select Menu & Tiket --}}
    <div class="space-y-3">
        {{-- Kategori/Sub Kategori Wisata --}}
        <div>
            <label for="sub_category_id" class="block text-sm font-medium text-gray-700">Kategori Wisata</label>
            <select id="sub_category_id" name="sub_category_id"
                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($tourSubCategories as $sub)
                    <option value="{{ $sub->id }}" @selected(old('sub_category_id') == $sub->id)>
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
            <label for="ticket_price" class="block text-sm font-medium text-gray-700">Harga Tiket (opsional)</label>
            <input id="ticket_price" type="number" step="0.01" name="ticket_price"
                value="{{ old('ticket_price') }}"
                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak dipungut biaya/gratis</p>
            @error('ticket_price')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Upload Menu --}}
    <div class="space-y-3">
        {{-- Upload Foto Tempat Wisata --}}
        <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Foto Tempat Wisata (Gambar)</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*"
                class="w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-green-800 file:bg-green-200 hover:file:bg-green-300">
            <p class="mt-1 text-xs text-gray-500">Unggah maksimal 5 gambar. Format: JPG, PNG, JPEG. Maks: 2MB</p>
            @error('images')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Fasilitas --}}
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Fasilitas</label>
        <div class="grid grid-cols-2 gap-1 mt-1 text-sm">
            @php
                $facilities = ['Ruang VIP', 'WiFi', 'Toilet', 'AC', 'Parkir', 'Akses Jalan Memadai'];
            @endphp
            @foreach ($facilities as $facility)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="facility[]" value="{{ $facility }}"
                        {{ in_array($facility, old('facility', [])) ? 'checked' : '' }}>
                    <span>{{ $facility }}</span>
                </label>
            @endforeach
        </div>
        @error('facility')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Jam Operasional --}}
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Jam Operasional</label>

        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        @endphp

        <div class="space-y-3">
            @foreach ($days as $day)
                @php
                    $isClosed = old("is_closed.$day") == '1';
                @endphp
                <div x-data="{ closed: {{ $isClosed ? 'true' : 'false' }} }" class="grid grid-cols-4 gap-3 items-center">
                    <div class="text-sm font-medium">{{ $day }}</div>
                    <div><input type="time" name="open_time[{{ $day }}]" x-bind:disabled="closed"
                            class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                            value="{{ old("open_time.$day") }}"></div>
                    <div><input type="time" name="close_time[{{ $day }}]" x-bind:disabled="closed"
                            class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm"
                            value="{{ old("close_time.$day") }}"></div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_closed[{{ $day }}]" value="1"
                            x-model="closed" class="h-4 w-4 text-[#486284] border-gray-300 rounded"
                            {{ $isClosed ? 'checked' : '' }}>
                        <span class="text-sm">Tutup</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
