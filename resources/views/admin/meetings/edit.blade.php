@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Sidang: {{ $meeting->title }}</h1>
    <a href="{{ route('admin.meetings.index') }}" class="text-gray-500 hover:text-gray-700 underline">&larr; Kembali</a>
</div>

<!-- Menampilkan Pesan Sukses -->
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- KOLOM KIRI: FORM EDIT DATA & HASIL SIDANG -->
    <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Data & Hasil Sidang</h2>

        <form action="{{ route('admin.meetings.update', $meeting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Judul/Agenda Sidang</label>
                    <input type="text" name="title" value="{{ old('title', $meeting->title) }}" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pelaksanaan</label>
                    <input type="datetime-local" name="meeting_date" value="{{ old('meeting_date', date('Y-m-d\TH:i', strtotime($meeting->meeting_date))) }}" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi/Ruang Sidang</label>
                <input type="text" name="location" value="{{ old('location', $meeting->location) }}" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status Sidang</label>
                <select name="status" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="Scheduled" {{ $meeting->status == 'Scheduled' ? 'selected' : '' }}>Dijadwalkan (Scheduled)</option>
                    <option value="In Progress" {{ $meeting->status == 'In Progress' ? 'selected' : '' }}>Sedang Berlangsung (In Progress)</option>
                    <option value="Completed" {{ $meeting->status == 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="Postponed" {{ $meeting->status == 'Postponed' ? 'selected' : '' }}>Ditunda (Postponed)</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Hasil / Kesimpulan (Opsional)</label>
                <textarea name="result_summary" rows="4" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Tuliskan hasil atau kesimpulan sidang di sini...">{{ old('result_summary', $meeting->result_summary) }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- KOLOM KANAN: PRESENSI & LAMPIRAN -->
    <div class="space-y-6">

        <!-- KOTAK PRESENSI -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-500">
            <h2 class="text-lg font-semibold mb-4 text-center">Status Presensi</h2>

            <form action="{{ route('admin.meetings.toggle-attendance', $meeting->id) }}" method="POST" class="text-center mb-4">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full py-3 rounded font-bold text-white transition shadow {{ $meeting->is_attendance_open ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
                    {{ $meeting->is_attendance_open ? 'Tutup Akses Presensi' : 'Buka Akses Presensi' }}
                </button>
            </form>

            @if($meeting->is_attendance_open)
                <a href="{{ route('admin.attendances.show', $meeting->id) }}" class="block text-center bg-gray-800 text-white py-2 rounded hover:bg-gray-900 transition">
                    Kelola Data Kehadiran &rarr;
                </a>
            @else
                <p class="text-sm text-gray-500 text-center">Buka akses presensi terlebih dahulu untuk menginput kehadiran anggota.</p>
            @endif
        </div>

        <!-- KOTAK LAMPIRAN DOKUMEN -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-4">Lampiran Dokumen</h2>

            <!-- Daftar Lampiran -->
            <ul class="mb-4 space-y-2">
                @forelse($meeting->attachments as $file)
                    <li class="flex justify-between items-center bg-gray-50 p-2 rounded text-sm border">
                        <span class="truncate pr-2 font-medium text-gray-700">
                            📄 {{ $file->file_title }}
                        </span>
                        <form action="{{ route('admin.attachments.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Hapus lampiran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                        </form>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 text-center italic py-2">Belum ada lampiran.</li>
                @endforelse
            </ul>

            <hr class="my-4">

            <!-- Form Upload -->
            <!-- Form Upload Banyak File Sekaligus -->
            <form action="{{ route('admin.meetings.attachment.store', $meeting->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Pilih File (Bisa lebih dari 1 file sekaligus)</label>

                    {{-- Perhatikan name="files[]" dan tambahan atribut "multiple" --}}
                    <input type="file" name="files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>

                    <p class="text-xs text-gray-400 mt-1">*Nama file akan otomatis menyesuaikan nama asli dokumen Anda.</p>
                </div>
                <button type="submit" class="w-full bg-blue-100 text-blue-700 py-2 rounded text-sm font-bold hover:bg-blue-200 transition">
                    + Unggah Semua File
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
