<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Persidangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-700">LogoInstansi</a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Jadwal Sidang</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Hasil Sidang</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Portal Berita</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Login Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-800 text-white text-center py-6 mt-10">
        <p>&copy; {{ date('Y') }} Sistem Informasi Persidangan. All rights reserved.</p>
    </footer>

</body>
</html>
