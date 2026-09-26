@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header alert (Untuk menampilkan pesan flash 'success' dari controller) -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kelola Sidang & Rapat</h1>
            <a href="{{ route('admin.meetings.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Tambah Jadwal
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-3 border-b">No</th>
                        <th class="p-3 border-b">Agenda / Judul</th>
                        <th class="p-3 border-b">Tanggal Pelaksanaan</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Mengganti @foreach dengan @forelse untuk handle data kosong --}}
                    @forelse($meetings as $index => $meeting)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">{{ $index + 1 }}</td>
                            <td class="p-3 border-b font-medium text-gray-800">{{ $meeting->title }}</td>
                            {{-- Jika sudah di-cast di model, cukup gunakan ->format() --}}
                            <td class="p-3 border-b">{{ $meeting->meeting_date->format('d M Y, H:i') }}</td>
                            <td class="p-3 border-b">
                                <span
                                    class="px-2 py-1 rounded text-xs text-white {{ $meeting->status == 'Completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    {{ $meeting->status }}
                                </span>
                            </td>
                            <td class="p-3 border-b text-center space-x-2">
                                <a href="{{ route('admin.meetings.edit', $meeting->id) }}"
                                    class="text-blue-500 hover:text-blue-700 text-sm font-semibold">Kelola</a>

                                    {{-- agar tombol presensi aktif saat kelola presensi ditekan saja --}}
                                @if ($meeting->is_attendance_open == true)
                                    <a href="{{ route('admin.attendances.show', $meeting->id) }}"
                                        class="text-green-500 hover:text-green-700 text-sm font-semibold">Presensi</a>
                                @endif

                                {{-- Tombol Hapus (Harus menggunakan tag form) --}}
                                <form action="{{ route('admin.meetings.destroy', $meeting) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini beserta seluruh lampirannya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 text-sm font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                Belum ada data jadwal sidang yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
