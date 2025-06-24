<!-- Bagian Footer -->
<footer class="bg-[#486284] text-white pt-10 pb-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-10">

            <!-- Logo dan Deskripsi -->
            <div class="md:col-span-2">
                <div class="flex flex-col items-start gap-4">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/application-logo.svg') }}"
                            alt="Logo Badan Promosi Pariwisata Daerah Sulut" class="h-12 w-12 object-contain" />

                        <div class="leading-none">
                            <h1 class="text-sm font-semibold uppercase leading-none">
                                Badan Promosi <span class="block">Pariwisata Daerah</span>
                            </h1>
                            <span class="text-xs text-gray-200">Provinsi Sulawesi Utara</span>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-200">
                        Berkomitmen mengembangkan potensi pariwisata Sulawesi Utara secara berkelanjutan untuk
                        meningkatkan kesejahteraan masyarakat serta melestarikan budaya dan keindahan alam daerah.
                    </p>
                </div>
            </div>

            <!-- Navigasi -->
            <div>
                <h2 class="text-sm font-semibold mb-3 uppercase">Navigasi</h2>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li><a href="{{ route('home') }}" class="hover:underline">Beranda</a></li>
                    <li><a href="{{ route('tourplace.index') }}" class="hover:underline">Rekomendasi Wisata</a></li>
                    <li><a href="{{ route('culinaryplace.index') }}" class="hover:underline">Kuliner & Jajanan</a></li>
                    <li><a href="#" class="hover:underline">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Tautan Eksternal -->
            <div>
                <h2 class="text-sm font-semibold mb-3 uppercase">Tautan Terkait</h2>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li><a href="https://sulutprov.go.id" target="_blank" class="hover:underline">Pemerintah Provinsi
                            Sulut</a></li>
                    <li><a href="https://kemlu.go.id" target="_blank" class="hover:underline">Kementerian Pariwisata
                            RI</a></li>
                    <li><a href="#" class="hover:underline">Portal Pariwisata Nasional</a></li>
                    <li><a href="#" class="hover:underline">Visit North Sulawesi</a></li>
                </ul>
            </div>

            <!-- Sosial Media -->
            <div>
                <h2 class="text-sm font-semibold mb-3 uppercase">Ikuti Kami</h2>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li><a href="#" class="hover:underline">Instagram</a></li>
                    <li><a href="#" class="hover:underline">Facebook</a></li>
                    <li><a href="#" class="hover:underline">YouTube</a></li>
                    <li><a href="#" class="hover:underline">TikTok</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-10 border-t border-white/20 pt-5 text-center text-sm text-gray-200">
            &copy; 2025 Badan Promosi Pariwisata Daerah Sulawesi Utara. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>
