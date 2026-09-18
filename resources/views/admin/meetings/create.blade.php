@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Jadwal Sidang Baru</h1>

    <form action="{{ route('admin.meetings.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Judul Sidang / Agenda</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required placeholder="Contoh: Rapat Paripurna ke-5">
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-2">Tanggal & Waktu</label>
                <input type="datetime-local" name="meeting_date" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Lokasi / Ruang Sidang</label>
                <input type="text" name="location" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Deskripsi Agenda</label>
            <textarea name="description" rows="4" class="w-full border p-2 rounded"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Jadwal</button>
    </form>
</div>
@endsection
