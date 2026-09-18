@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Utama</h1>
    <p class="text-gray-600">Selamat datang di Sistem Informasi Persidangan.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm font-semibold mb-1">Total Sidang Terdaftar</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $totalMeetings ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm font-semibold mb-1">Sidang Akan Datang</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $upcomingMeetings ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm font-semibold mb-1">Total Penerbangan SPPD</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $totalFlights ?? 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Panduan Cepat</h2>
    <ul class="list-disc list-inside text-gray-600 space-y-2">
        <li>Gunakan menu <strong>Kelola Sidang</strong> untuk membuat jadwal atau mengunggah hasil paripurna.</li>
        <li>Gunakan menu <strong>Data Penerbangan</strong> untuk merekap tiket perjalanan dinas.</li>
        <li>Pastikan file lampiran PDF maksimal berukuran 10MB.</li>
    </ul>
</div>
@endsection
