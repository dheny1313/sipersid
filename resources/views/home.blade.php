@extends('layouts.public')

@section('content')
<div class="bg-blue-700 text-white py-20 text-center">
    <h1 class="text-4xl font-bold mb-4">Sistem Informasi & Dokumentasi Persidangan</h1>
    <p class="text-lg opacity-90 mb-8">Transparansi dan kemudahan akses informasi agenda kedewanan.</p>

    <div class="flex justify-center space-x-10">
        <div class="bg-blue-800 px-6 py-4 rounded-lg">
            <h3 class="text-3xl font-bold">{{ $totalMeetings ?? 0 }}</h3>
            <p class="text-sm">Total Sidang Bulan Ini</p>
        </div>
        <div class="bg-blue-800 px-6 py-4 rounded-lg">
            <h3 class="text-3xl font-bold">{{ $completedMeetings ?? 0 }}</h3>
            <p class="text-sm">Sidang Selesai</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4">Agenda Sidang Terkini</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($meetings as $meeting)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 {{ $meeting->status == 'Completed' ? 'border-green-500' : 'border-yellow-500' }}">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-semibold text-white px-2 py-1 rounded {{ $meeting->status == 'Completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                    {{ $meeting->status }}
                </span>
                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $meeting->title }}</h3>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">📍 Lokasi: {{ $meeting->location }}</p>

            <a href="{{ route('public.meetings.show', $meeting->slug) }}" class="text-blue-600 font-semibold text-sm hover:underline">
                Lihat Detail Sidang &rarr;
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
