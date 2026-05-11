<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Klik Hape') }} - Pusat HP Bekas Berkualitas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js untuk interaktivitas (seperti slider dan modal) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Sembunyikan scrollbar tapi tetap bisa scroll (untuk galeri/kategori horizontal) */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ searchOpen: false }">

    <!-- Container Utama (Membatasi lebar maksimal agar seperti layar HP walau dibuka di PC) -->
    <div class="max-w-md mx-auto min-h-screen bg-white relative shadow-2xl overflow-hidden">

        <!-- Navbar / Header Atas -->
        <header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <!-- Logo Bawaan -->
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight text-gray-800">Klik Hape</span>
            </a>

            <!-- Tombol Cari -->
            <button @click="searchOpen = true; setTimeout(() => $refs.searchInput.focus(), 100)" class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </header>

        <!-- Search Overlay (Full screen search) -->
        <div x-show="searchOpen" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="absolute inset-0 z-50 bg-white">

            <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                <button @click="searchOpen = false" class="p-2 -ml-2 text-gray-500 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <form action="{{ route('home') }}" method="GET" class="flex-1 relative">
                    <input type="text" name="q" x-ref="searchInput" value="{{ request('q') }}" placeholder="Cari iPhone 13, Samsung..." class="w-full bg-gray-100 border-none rounded-full py-2.5 pl-4 pr-10 text-sm focus:ring-2 focus:ring-indigo-500" required>
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
            <div class="p-6">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-4">Pencarian Populer</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('home', ['q' => 'iPhone 11']) }}" class="px-4 py-2 bg-gray-50 text-gray-700 text-sm rounded-full border border-gray-200">iPhone 11</a>
                    <a href="{{ route('home', ['q' => 'Samsung S22']) }}" class="px-4 py-2 bg-gray-50 text-gray-700 text-sm rounded-full border border-gray-200">Samsung S22</a>
                    <a href="{{ route('home', ['q' => 'Poco']) }}" class="px-4 py-2 bg-gray-50 text-gray-700 text-sm rounded-full border border-gray-200">Poco</a>
                </div>
            </div>
        </div>

        <!-- Konten Halaman (Index / Show akan masuk ke sini) -->
        <main class="pb-24">
            @yield('content')
        </main>

        <!-- Footer Kecil -->
        <footer class="bg-gray-50 border-t border-gray-100 py-6 text-center mt-auto relative z-10 pb-28">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Klik Hape. All rights reserved.</p>
        </footer>

    </div>

</body>
</html>
