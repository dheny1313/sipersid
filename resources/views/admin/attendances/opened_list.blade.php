@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Presensi Aktif</h1>
                <p class="text-sm text-gray-500 mt-1">Pilih sidang untuk mengelola, mengekspor, atau mengimpor data
                    kehadiran.</p>
            </div>
        </div>

        <!-- Kotak Menampilkan Rincian Error Validasi Excel -->
        @if (session('import_errors'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <h3 class="font-bold mb-2">Gagal Mengimpor Data Excel! Ditemukan kesalahan berikut:</h3>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <p class="text-xs mt-3 italic text-red-600">*Harap perbaiki file Excel Anda dan coba unggah kembali.</p>
            </div>
        @endif
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border-b text-center w-12">No</th>
                        <th class="p-3 border-b">Agenda / Judul Sidang</th>
                        <th class="p-3 border-b">Tanggal & Lokasi</th>
                        <th class="p-3 border-b text-center">Data Masuk</th>
                        <th class="p-3 border-b text-center">Aksi & Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $index => $meeting)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3 border-b font-semibold text-gray-800">{{ $meeting->title }}</td>
                            <td class="p-3 border-b text-sm">
                                <div class="text-gray-800">
                                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y, H:i') }}</div>
                                <div class="text-gray-500 text-xs mt-1">📍 {{ $meeting->location }}</div>
                            </td>
                            <td class="p-3 border-b text-center">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $meeting->attendances_count }} Orang
                                </span>
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Kelola (Input Manual) -->
                                    <a href="{{ route('admin.attendances.show', $meeting->id) }}"
                                        class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded text-xs font-semibold transition border border-blue-200 hover:border-blue-600">
                                        Kelola
                                    </a>


                                    <!-- Tombol Export Excel -->
                                    <a href="{{ route('admin.attendances.export', $meeting->id) }}"
                                        class="bg-green-50 text-green-600 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded text-xs font-semibold transition border border-green-200 hover:border-green-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Export
                                    </a>

                                    <form action="{{ route('admin.attendances.import', $meeting->id) }}" method="POST"
                                        enctype="multipart/form-data" class="m-0 p-0 flex items-center">
                                        @csrf
                                        <label
                                            class="bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white px-3 py-1.5 rounded text-xs font-semibold transition border border-yellow-200 hover:border-yellow-600 flex items-center gap-1 cursor-pointer m-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                </path>
                                            </svg>
                                            Import
                                            <!-- Input file di-hidden, jika dipilih otomatis tersubmit via Javascript -->
                                            <input type="file" name="file_excel" class="hidden" accept=".xlsx,.xls,.csv"
                                                onchange="this.form.submit()">
                                        </label>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p>Tidak ada jadwal sidang yang akses presensinya sedang dibuka.</p>
                                    <a href="{{ route('admin.meetings.index') }}"
                                        class="text-blue-500 hover:underline mt-2 text-sm">Buka presensi di halaman Kelola
                                        Sidang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
