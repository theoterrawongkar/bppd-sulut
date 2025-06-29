<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">{{ $title }}</x-slot>

    {{-- Bagian Tambah Event --}}
    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Galeri (Placeholder) --}}
            <div x-data="{ mainImage: @js(asset('img/placeholder.webp')) }">
                {{-- Gambar Utama --}}
                <div class="aspect-video rounded-xl overflow-hidden shadow-md mb-4">
                    <img :src="mainImage" alt="Thumbnail Wisata"
                        class="w-full h-full object-cover transition duration-300">
                </div>

                {{-- Galeri Thumbnail Placeholder --}}
                <div class="flex md:grid md:grid-cols-3 gap-2 overflow-x-auto md:overflow-visible">
                    @for ($i = 0; $i < 4; $i++)
                        <img src="{{ asset('img/placeholder.webp') }}" alt="Galeri Kosong"
                            class="w-24 md:w-full h-24 object-cover rounded-md flex-shrink-0 border border-gray-300 cursor-not-allowed">
                    @endfor
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-md">

                {{-- Formulir Tambah Event Place --}}
                <form action="{{ route('dashboard.eventplace.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Kiri --}}
                        <div class="space-y-3">
                            {{-- Nama Event --}}
                            <div>
                                <label for="business_name" class="block text-sm font-medium text-gray-700">Nama
                                    Event</label>
                                <input id="business_name" type="text" name="business_name"
                                    value="{{ old('business_name') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                @error('business_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm resize-none focus:outline-[#486284]">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Link Google Maps --}}
                            <div class="relative">
                                <label for="gmaps_link" class="block text-sm font-medium text-gray-700">Link Google
                                    Maps</label>
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
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                    Event</label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm resize-none focus:outline-[#486284]">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kanan --}}
                        <div class="space-y-3">
                            {{-- Nama Pemilik --}}
                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700">Nama Penanggung
                                    Jawab</label>
                                <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                @error('owner_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="owner_email" class="block text-sm font-medium text-gray-700">Email
                                    Penanggung
                                    Jawab</label>
                                <input id="owner_email" type="email" name="owner_email"
                                    value="{{ old('owner_email') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                @error('owner_email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Telepon --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                    Telepon</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                @error('phone')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Instagram --}}
                            <div>
                                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram
                                    (opsional)</label>
                                <input id="instagram_link" type="text" name="instagram_link"
                                    value="{{ old('instagram_link') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                @error('instagram_link')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Facebook --}}
                            <div>
                                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook
                                    (opsional)</label>
                                <input id="facebook_link" type="text" name="facebook_link"
                                    value="{{ old('facebook_link') }}"
                                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
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
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($subCategories as $sub)
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
                            <label for="ticket_price" class="block text-sm font-medium text-gray-700">Harga Tiket
                                (opsional)</label>
                            <input id="ticket_price" type="number" step="0.01" name="ticket_price"
                                value="{{ old('ticket_price') }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak dipungut biaya/gratis</p>
                            @error('ticket_price')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload Foto Event --}}
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700">Foto Tempat
                                Event (Gambar)</label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                class="w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-green-800 file:bg-green-200 hover:file:bg-green-300">
                            <p class="mt-1 text-xs text-gray-500">Unggah maksimal 5 gambar. Format: JPG, PNG,
                                JPEG. Maks: 2MB</p>
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
                                value="{{ old('start_time') }}"
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
                                value="{{ old('end_time') }}"
                                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                            @error('end_time')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-app-layout>
