<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Metadata -->
    <meta name="description"
        content="Website Badan Promosi Pariwisata Daerah Sulawesi Utara merupakan platform digital resmi yang dirancang untuk mempromosikan potensi pariwisata Sulawesi Utara secara luas.">
    <meta name="keywords" content="bppd sulut, promosi pariwisata daerah, wisata kuliner, destinasi wisata, seni">
    <meta name="author" content="BPPD Sulut">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/application-logo.svg') }}" type="image/x-icon">

    <!-- Judul Halaman -->
    <title>Dashboard BPPD Sulawesi Utara</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Framework Frontend -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Script Tambahan -->
    @isset($script)
        {{ $script }}
    @endisset

    <!-- Default CSS -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-jakarta antialiased">

    <div x-data="{ sidebarOpen: false }" class="relative h-screen flex overflow-hidden" x-cloak>

        <!-- Navigasi -->
        @include('components.partials.app-navigation')

        <!-- Layout Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('components.partials.app-header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>



</body>

</html>
