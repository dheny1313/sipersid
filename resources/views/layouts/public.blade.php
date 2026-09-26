<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-SIDANG | Sistem Informasi Persidangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js untuk animasi menu mobile -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Mencegah scroll saat menu mobile terbuka */
        body.menu-open {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans text-gray-800 antialiased" x-data="{ mobileMenuOpen: false }"
    :class="{ 'menu-open': mobileMenuOpen }">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <!-- Placeholder Logo Garuda / Instansi -->
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-700 to-blue-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                        S
                    </div>
                    <div>
                        <a href="{{ route('home') }}"
                            class="text-2xl font-black text-gray-900 tracking-tight leading-none">
                            SIM<span class="text-blue-600">-SIDANG</span>
                        </a>
                        <p class="text-xs text-gray-500 font-medium">Sekretariat DPRD Kota Banjarbaru</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600 transition' }}">Sidang</a>

                    <a href="{{ route('public.posts.index') }}"
                        class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition">Portal Berita</a>

                    <div class="pl-4 border-l border-gray-200">
                        @auth
                            <a href="{{ route('admin.dashboard') }}"
                                class="bg-blue-50 text-blue-700 px-5 py-2.5 rounded-full text-sm font-bold hover:bg-blue-100 transition shadow-sm border border-blue-200">Masuk
                                Panel</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition">Login
                                Internal</a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-500 hover:text-blue-600 focus:outline-none p-2">
                        <svg class="h-7 w-7" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-7 w-7" x-show="mobileMenuOpen" x-cloak fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition x-cloak
            class="md:hidden bg-white border-t border-gray-100 absolute w-full shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}"
                    class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">Sidang</a>
                <a href="{{ route('public.posts.index') }}"
                    class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600">Portal
                    Berita</a>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-bold">Login
                        Internal</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT AREA -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 pt-16 pb-8 border-t-4 border-blue-600 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight mb-4">SIM<span
                            class="text-blue-500">-SIDANG</span></h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">Sistem Informasi Manajemen Persidangan yang
                        mendigitalisasi proses penjadwalan, presensi, dan pelaporan sidang untuk mewujudkan parlemen
                        yang modern dan transparan.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-blue-400 transition">Jadwal Paripurna</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Pengumuman & Berita</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Panduan Presensi Publik</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Hubungi Sekretariat</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <span>📍</span>
                            <span>Gedung DPRD Kota Banjarbaru<br>Kalimantan Selatan, 70714</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>✉️</span>
                            <span>sekretariat@dprd-banjarbaru.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Sekretariat DPRD. All rights reserved.</p>
                <p class="text-gray-600 text-xs font-mono">DheTech Solutions</p>
            </div>
        </div>
    </footer>

</body>

</html>
