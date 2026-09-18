@extends('layouts.admin')

@section('content')
<!-- Header & Tombol Aksi -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Rekap Presensi Sidang</h1>
        <p class="text-gray-600">Agenda: <strong>{{ $meeting->title }}</strong> ({{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d/m/Y') }})</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Tombol Toggle Buka/Tutup Presensi -->
        <form action="{{ route('admin.meetings.toggle-attendance', $meeting->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-5 py-2.5 rounded-lg text-white font-semibold shadow transition duration-200 {{ $meeting->is_attendance_open ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                {{ $meeting->is_attendance_open ? '🔴 Tutup Akses Presensi' : '🟢 Buka Akses Presensi' }}
            </button>
        </form>

        <!-- Link Form Publik -->
        <a href="{{ route('public.attendances.create', $meeting->slug) }}" target="_blank" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow transition duration-200 flex items-center gap-2">
            <span>Lihat Form Publik</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>
    </div>
</div>

<!-- Informasi Status Presensi -->
<div class="mb-6 p-4 rounded-lg {{ $meeting->is_attendance_open ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-amber-50 border border-amber-200 text-amber-800' }} flex items-center justify-between">
    <div class="flex items-center gap-2">
        <span class="font-bold">Status Akses:</span>
        @if($meeting->is_attendance_open)
            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dibuka</span>
        @else
            <span class="bg-amber-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Ditutup</span>
        @endif
    </div>
    <p class="text-xs hidden sm:block">
        @if($meeting->is_attendance_open)
            Anggota dewan/peserta dapat melalukan presensi mandiri lewat form publik.
        @else
            Presensi terkunci. Klik tombol "Buka Akses Presensi" di atas agar peserta bisa mengisi.
        @endif
    </p>
</div>

<!-- Statistik Ringkas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <p class="text-sm font-semibold text-gray-500">Total Hadir</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $hadir ?? 0 }} <span class="text-sm font-normal text-gray-500">orang</span></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-amber-500">
        <p class="text-sm font-semibold text-gray-500">Total Izin</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $izin ?? 0 }} <span class="text-sm font-normal text-gray-500">orang</span></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <p class="text-sm font-semibold text-gray-500">Total Sakit</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $sakit ?? 0 }} <span class="text-sm font-normal text-gray-500">orang</span></p>
    </div>
</div>

<!-- Tabel Rekap Data Masuk -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800">Daftar Presensi Masuk</h2>
        <span class="text-sm text-gray-500">Total: {{ count($attendances ?? []) }} Orang</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm">
                    <th class="p-4 border-b font-semibold">Waktu Masuk</th>
                    <th class="p-4 border-b font-semibold">Nama Lengkap</th>
                    <th class="p-4 border-b font-semibold">Fraksi / Instansi</th>
                    <th class="p-4 border-b font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attendances as $absen)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-sm text-gray-500">{{ $absen->created_at->format('H:i:s') }} WITA</td>
                    <td class="p-4 font-semibold text-gray-800">{{ $absen->member_name }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $absen->fraksi }}</td>
                    <td class="p-4">
                        @if($absen->status == 'Hadir')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                        @elseif($absen->status == 'Izin')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Izin</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Sakit</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">
                        Belum ada data presensi yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
