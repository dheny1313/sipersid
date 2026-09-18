@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Sidang & Rapat</h1>
        <a href="{{ route('admin.meetings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Jadwal</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="p-3 border-b">No</th>
                <th class="p-3 border-b">Agenda / Judul</th>
                <th class="p-3 border-b">Tanggal Pelaksanaan</th>
                <th class="p-3 border-b">Status</th>
                <th class="p-3 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetings as $index => $meeting)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border-b">{{ $index + 1 }}</td>
                <td class="p-3 border-b">{{ $meeting->title }}</td>
                <td class="p-3 border-b">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y, H:i') }}</td>
                <td class="p-3 border-b">
                    <span class="px-2 py-1 rounded text-xs text-white {{ $meeting->status == 'Completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ $meeting->status }}
                    </span>
                </td>
                <td class="p-3 border-b space-x-2">
                    <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="text-blue-500 hover:underline">Edit/Hasil</a>
                    <a href="{{ route('admin.attendances.index', $meeting->id) }}" class="text-green-500 hover:underline">Presensi</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
