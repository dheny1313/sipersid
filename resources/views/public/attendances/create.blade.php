<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Sidang - {{ $meeting->title }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-2">Form Presensi</h2>
        <p class="text-center text-gray-600 mb-6 font-medium">{{ $meeting->title }}</p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @else
            <form action="{{ route('public.attendances.store', $meeting->slug) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="member_name" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Fraksi / Instansi</label>
                    <input type="text" name="fraksi" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Status Kehadiran</label>
                    <select name="status" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-bold">
                    Kirim Presensi
                </button>
            </form>
        @endif
    </div>
</body>
</html>
