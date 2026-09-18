@extends('layouts.public')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white rounded-t-lg shadow p-8 border-b-4 {{ $meeting->status == 'Completed' ? 'border-green-500' : 'border-yellow-500' }}">
        <div class="mb-4">
            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm mr-2">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d F Y, H:i') }}</span>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">📍 {{ $meeting->location }}</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $meeting->title }}</h1>
        <p class="text-gray-700 leading-relaxed">{{ $meeting->description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Ringkasan Hasil Sidang
                </h3>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($meeting->result_summary ?? 'Hasil sidang belum dipublikasikan atau sidang belum selesai.')) !!}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📄 Dokumen Lampiran</h3>
                @if($meeting->attachments->count() > 0)
                    <ul class="space-y-3">
                        @foreach($meeting->attachments as $file)
                        <li class="flex justify-between items-center p-3 bg-gray-50 border rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-800">{{ $file->file_title }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">
                                Unduh File
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic">Tidak ada dokumen lampiran untuk persidangan ini.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Rekap Kehadiran</h3>
                @php
                    $hadir = $meeting->attendances->where('status', 'Hadir')->count();
                    $izin = $meeting->attendances->where('status', 'Izin')->count();
                    $sakit = $meeting->attendances->where('status', 'Sakit')->count();
                    $alpa = $meeting->attendances->where('status', 'Alpa')->count();
                    $total = $meeting->attendances->count();
                @endphp

                @if($total > 0)
                <ul class="space-y-2 mb-6">
                    <li class="flex justify-between text-gray-700"><span class="font-semibold">Hadir:</span> <span class="text-green-600 font-bold">{{ $hadir }}</span></li>
                    <li class="flex justify-between text-gray-700"><span class="font-semibold">Izin:</span> <span class="text-blue-600 font-bold">{{ $izin }}</span></li>
                    <li class="flex justify-between text-gray-700"><span class="font-semibold">Sakit:</span> <span class="text-yellow-600 font-bold">{{ $sakit }}</span></li>
                    <li class="flex justify-between text-gray-700"><span class="font-semibold">Alpa:</span> <span class="text-red-600 font-bold">{{ $alpa }}</span></li>
                </ul>
                <div class="bg-blue-50 text-blue-800 p-3 rounded text-center font-bold">
                    Tingkat Kehadiran: {{ number_format(($hadir / $total) * 100, 1) }}%
                </div>
                @else
                <p class="text-gray-500 italic text-sm">Data presensi belum dimasukkan oleh sekretariat.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
