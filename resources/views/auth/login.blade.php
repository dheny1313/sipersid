<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Internal | SIM-SIDANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased min-h-screen flex selection:bg-blue-500 selection:text-white">

    <!-- BAGIAN KIRI: Branding & Informasi (Hanya muncul di layar PC/Laptop) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-900 via-indigo-900 to-blue-800 relative overflow-hidden items-center justify-center p-12">
        <!-- Pattern Latar Belakang -->
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>

        <!-- Ornamen Lingkaran Bercahaya -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 translate-x-1/2 translate-y-1/2"></div>

        <div class="relative z-10 text-center max-w-lg">
            <!-- Ikon Keamanan / Portal -->
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/20 shadow-xl">
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>

            <h1 class="text-4xl font-black text-white tracking-tight mb-4 leading-tight">
                SIM-SIDANG <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-indigo-300">Portal Internal</span>
            </h1>
            <p class="text-blue-100/80 text-lg font-light leading-relaxed mb-12">
                Sistem Manajemen Persidangan Sekretariat DPRD Kota Banjarbaru. Akses dasbor ini dibatasi khusus untuk staf sekretariat dan anggota dewan yang berwenang.
            </p>

        </div>
    </div>

    <!-- BAGIAN KANAN: Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-6 sm:p-12 relative">

        <!-- Tombol Kembali ke Beranda -->
        <a href="{{ route('home') }}" class="absolute top-6 left-6 sm:top-8 sm:left-8 flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>

        <div class="w-full max-w-md">
            <div class="mb-10 text-center lg:text-left mt-12 lg:mt-0">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Selamat Datang! 👋</h2>
                <p class="text-sm text-gray-500 mt-2 font-medium">Silakan masukkan kredensial Anda untuk masuk.</p>
            </div>

            <!-- Menampilkan Pesan Session (seperti reset password sukses) -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@dprd-banjarbaru.go.id"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500 ring-red-500' : 'border-gray-200' }} rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-sm font-medium">
                    </div>
                    <!-- Pesan Error Email -->
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 font-bold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-bold text-gray-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">Lupa Sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500 ring-red-500' : 'border-gray-200' }} rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-sm font-medium">
                    </div>
                    <!-- Pesan Error Password -->
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-bold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer transition">
                    <label for="remember_me" class="ml-2 text-sm font-bold text-gray-600 cursor-pointer">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-blue-200 active:scale-[0.98]">
                    Masuk ke Dashboard
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>

            
        </div>
    </div>

</body>
</html>
