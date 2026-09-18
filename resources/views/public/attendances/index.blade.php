@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Rekap Presensi: {{ $meeting->title }}</h1>
        <p class="text-gray-600">Tanggal: {{ $meeting->meeting_date }}</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('admin.meetings.toggle-attendance', $meeting->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-4 py-2 text-white rounded {{ $meeting->is_attendance_open ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
                {{ $meeting->is_attendance_open ? 'Tutup Presensi' : 'Buka Presensi' }}
            </button>
        </form>
        <a href="{{ route('public.attendances.create', $meeting->slug) }}" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Lihat Form Publik</a>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow text-center border-b-4 border-green-500">
        <h3 class="text-lg font-bold text-gray-700">Hadir</h3>
        <p class="text-3xl text-green-600">{{ $hadir }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow text-center border-b-4 border-yellow-500">
        <h3 class="text-lg font-bold text-gray-700">Izin</h3>
        <p class="text-3xl text-yellow-600">{{ $izin }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow text-center border-b-4 border-red-500">
        <h3 class="text-lg font-bold text-gray-700">Sakit</h3>
        <p class="text-3xl text-red-600">{{ $sakit }}</p>
    </div>
</div>

<div class="bg-white rounded shadow p-6">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 border-b">Waktu Isi</th>
                <th class="p-3 border-b">Nama Lengkap</th>
                <th class="p-3 border-b">Fraksi</th>
                <th class="p-3 border-b">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $absen)
            <tr>
                <td class="p-3 border-b">{{ $absen->created_at->format('H:i') }}</td>
                <td class="p-3 border-b font-medium">{{ $absen->member_name }}</td>
                <td class="p-3 border-b">{{ $absen->fraksi }}</td>
                <td class="p-3 border-b">
                    <span class="px-2 py-1 text-xs rounded text-white
                        {{ $absen->status == 'Hadir' ? 'bg-green-500' : ($absen->status == 'Izin' ? 'bg-yellow-500' : 'bg-red-500') }}">
                        {{ $absen->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-3 border-b text-center text-gray-500">Belum ada peserta yang mengisi presensi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
