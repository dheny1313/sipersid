@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit & Hasil Sidang</h1>
        <form action="{{ route('admin.meetings.update', $meeting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Status Sidang</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="Scheduled" {{ $meeting->status == 'Scheduled' ? 'selected' : '' }}>Dijadwalkan (Scheduled)</option>
                    <option value="Completed" {{ $meeting->status == 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Ringkasan Hasil Sidang (Result Summary)</label>
                <textarea name="result_summary" rows="6" class="w-full border p-2 rounded">{{ $meeting->result_summary }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Hasil</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Lampiran Dokumen</h2>
        <ul class="mb-4 space-y-2">
            @foreach($meeting->attachments as $file)
            <li class="flex justify-between items-center bg-gray-50 p-2 border rounded">
                <span class="text-sm truncate">{{ $file->file_title }}</span>
                <form action="{{ route('admin.attachments.destroy', $file->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 text-xs hover:underline">Hapus</button>
                </form>
            </li>
            @endforeach
        </ul>

        <hr class="my-4">
        <form action="{{ route('admin.meetings.attachment.store', $meeting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-xs text-gray-700 mb-1">Nama Dokumen</label>
                <input type="text" name="file_title" class="w-full border p-2 rounded text-sm" placeholder="Contoh: Notula Rapat">
            </div>
            <div class="mb-3">
                <input type="file" name="file" class="w-full text-sm" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded text-sm">Upload Lampiran</button>
        </form>
    </div>
</div>
@endsection
