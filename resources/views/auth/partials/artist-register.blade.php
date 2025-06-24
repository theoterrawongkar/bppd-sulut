{{-- Informasi Profil Seniman --}}
<div class="space-y-5 mt-4">

    <div class="grid grid-cols-2 gap-3">
        {{-- Kiri --}}
        <div class="space-y-3">
            {{-- Nama Panggung --}}
            <div>
                <label for="stage_name" class="block text-sm font-medium text-gray-700">Nama Panggung</label>
                <input id="stage_name" type="text" name="stage_name" value="{{ old('stage_name') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('stage_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm resize-none focus:outline-[#486284]">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kanan --}}
        <div class="space-y-3">
            {{-- Email Penanggung Jawab --}}
            <div>
                <label for="owner_email" class="block text-sm font-medium text-gray-700">Email Penanggung Jawab</label>
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

            {{-- Instagram --}}
            <div>
                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram (opsional)</label>
                <input id="instagram_link" type="text" name="instagram_link" value="{{ old('instagram_link') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('instagram_link')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Facebook --}}
            <div>
                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook (opsinal)</label>
                <input id="facebook_link" type="text" name="facebook_link" value="{{ old('facebook_link') }}"
                    class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                @error('facebook_link')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Select Menu & Upload Menu --}}
    <div class="space-y-3">
        {{-- Bidang Seni --}}
        <div>
            <label for="field" class="block text-sm font-medium text-gray-700">Bidang</label>
            <select id="field" name="field"
                class="w-full mt-1 py-1 px-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-[#486284]">
                <option value="">-- Pilih Bidang --</option>
                @php
                    $fields = [
                        'Seni Rupa',
                        'Seni Musik',
                        'Seni Tari',
                        'Seni Teater',
                        'Seni Sastra',
                        'Seni Media/Audiovisual',
                    ];
                @endphp
                @foreach ($fields as $option)
                    <option value="{{ $option }}" @selected(old('field') == $option)>{{ $option }}</option>
                @endforeach
            </select>
            @error('field')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload Portofolio --}}
        <div>
            <label for="portfolio_path" class="block text-sm font-medium text-gray-700">Upload Portofolio
                (PDF/Gambar)</label>
            <input id="portfolio_path" type="file" name="portfolio_path" accept=".pdf,image/*"
                class="w-full mt-1 text-sm border border-gray-300 rounded-md shadow-sm file:px-3 file:py-1 file:border-0 file:text-blue-800 file:bg-blue-200 hover:file:bg-blue-300">
            <p class="mt-1 text-xs text-gray-500">Unggah maksimal 1 file. Format: PDF, JPG, PNG, JPEG. Size: 2MB</p>
            @error('portfolio_path')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
