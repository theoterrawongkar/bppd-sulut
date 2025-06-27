{{--  Bagian Reviews --}}
<section class="bg-gray-50">
    <div class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-row justify-between items-center gap-4 mb-4">
            <h2 class="text-lg font-semibold">Ulasan</h2>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="#review-list" class="text-gray-900 text-xs md:text-sm font-semibold underline">
                    Semua ulasan ({{ $totalReviews }})
                </a>
                <a href="#form-review"
                    class="bg-black text-white text-xs md:text-sm px-4 py-2 rounded-full hover:bg-gray-800">
                    Tulis Ulasan
                </a>
            </div>
        </div>

        {{-- Statistik Rating --}}
        <div class="flex flex-col md:flex-row items-start gap-6 mb-10">
            {{-- Rata-rata Rating --}}
            <div class="w-full md:w-1/6">
                <p class="text-4xl font-bold">{{ $averageRating }}</p>
                <span class="text-sm text-gray-600 mb-1 block">
                    @switch(true)
                        @case($averageRating >= 5)
                            Luar Biasa
                        @break

                        @case($averageRating >= 4)
                            Bagus
                        @break

                        @case($averageRating >= 3)
                            Biasa
                        @break

                        @case($averageRating >= 2)
                            Buruk
                        @break

                        @case($averageRating >= 1)
                            Sangat Buruk
                        @break

                        @default
                            Belum ada penilaian
                    @endswitch
                </span>
                <div class="flex items-center gap-1 text-green-600 text-lg">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= floor($averageRating) ? '★' : '☆' }}
                    @endfor
                    <span class="text-xs text-gray-500 ml-1">({{ $totalReviews }})</span>
                </div>
            </div>

            {{-- Bar Rating --}}
            <div class="flex-1 space-y-2 text-xs sm:text-sm w-full">
                @foreach ($ratings as $rate => $item)
                    <div class="flex items-center gap-2">
                        <span class="w-20 font-medium text-gray-700">{{ $item['label'] }}</span>
                        <div class="flex-1 bg-gray-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-green-600 h-full transition-all duration-300"
                                style="width: {{ $item['persentase'] }}%"></div>
                        </div>
                        <span class="w-6 text-right text-gray-600">{{ $item['jumlah'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Form dan Review --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

            {{-- Form Review --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $myReview ? 'Edit Ulasan Anda' : 'Tulis Ulasan Anda' }}
                </h3>

                @foreach (['success' => 'green', 'error' => 'red'] as $key => $color)
                    @if (session($key))
                        <div id="alert"
                            class="bg-{{ $color }}-100 text-{{ $color }}-700 px-4 py-2 rounded-lg text-sm mb-4 border border-{{ $color }}-300">
                            {{ session($key) }}
                        </div>
                    @endif
                @endforeach

                <form id="form-review" action="{{ route('tourplace.review.store', $tourPlace->slug) }}" method="POST"
                    class="space-y-4">
                    @csrf

                    {{-- Rating --}}
                    <div class="md:w-2/3">
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <select id="rating" name="rating"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-1 focus:ring-green-600 focus:outline-none transition">
                            <option value="">Pilih rating</option>
                            @foreach ([5 => 'Luar Biasa', 4 => 'Bagus', 3 => 'Biasa', 2 => 'Buruk', 1 => 'Sangat Buruk'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('rating', $myReview->rating ?? '') == $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('rating')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Comment --}}
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                        <textarea id="comment" name="comment" rows="5"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-1 focus:ring-green-600 focus:outline-none transition"
                            placeholder="Bagikan pengalaman Anda...">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                        @error('comment')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 text-sm font-semibold rounded-full hover:bg-green-700 transition">
                            {{ $myReview ? 'Update Ulasan' : 'Kirim Ulasan' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Review Pengguna Lain --}}
            <div>
                <h3 id="review-list" class="text-lg font-semibold text-gray-800 mb-4">Ulasan Pengguna Lain</h3>
                @forelse ($reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
                        <div class="flex items-start justify-between mb-1">
                            <div class="text-sm font-semibold text-gray-800">{{ $review->user->name }}</div>
                            <div class="text-yellow-500 text-sm">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mt-2">{{ $review->comment }}</p>
                        <div class="text-xs text-gray-400 mt-1">{{ $review->updated_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Belum ada ulasan dari pengguna lain.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- Scroll to alert or error --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const alertBox = document.getElementById('alert');
        const form = document.getElementById('form-review');
        const hasValidationErrors = document.querySelectorAll('.text-red-600').length > 0;

        if (alertBox) {
            alertBox.scrollIntoView({
                behavior: 'smooth'
            });
        } else if (hasValidationErrors && form) {
            form.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
</script>
