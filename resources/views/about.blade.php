<x-main-layout>

    {{-- Bagian Header --}}
    <header class="relative">
        <img src="{{ asset('img/about-banner.webp') }}" alt="Header Image" class="w-full h-64 md:h-96 object-cover">

        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-white text-2xl md:text-4xl font-bold">Tentang Kami</h1>
            <p class="text-white text-sm mt-2 max-w-2xl text-balance">
                Website Badan Promosi Pariwisata Daerah Sulawesi Utara merupakan platform digital resmi yang dirancang
                untuk mempromosikan potensi pariwisata Sulawesi Utara secara luas.
            </p>
        </div>
    </header>

    {{-- Bagian Tentang BPPD --}}
    <section class="py-16 bg-white px-6 lg:px-24 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-[#486284] mb-4">
            Badan Promosi Pariwisata Daerah
        </h2>
        <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed">
            BPPD Sulawesi Utara hadir untuk mendorong pertumbuhan sektor pariwisata melalui promosi destinasi unggulan,
            kerja sama strategis, dan penyediaan informasi yang akurat bagi wisatawan domestik dan mancanegara.
        </p>
    </section>

</x-main-layout>
