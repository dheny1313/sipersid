@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Penerbangan Luar Kota</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola logistik tiket, SPPD, dan jadwal keberangkatan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Export -->
                <a href="{{ route('admin.flights.export') }}"
                    class="bg-green-50 text-green-600 hover:bg-green-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition border border-green-200 hover:border-green-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Excel
                </a>

                <!-- Form Import Tersembunyi (Disamarkan menjadi tombol) -->
                <form action="{{ route('admin.flights.import') }}" method="POST" enctype="multipart/form-data"
                    class="m-0 p-0">
                    @csrf
                    <label
                        class="bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition border border-yellow-200 hover:border-yellow-600 flex items-center gap-2 cursor-pointer m-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Excel
                        <input type="file" name="file_excel" class="hidden" accept=".xlsx,.xls,.csv"
                            onchange="this.form.submit()">
                    </label>
                </form>

                <!-- Tombol Tambah Data -->
                <a href="{{ route('admin.flights.create') }}"
                    class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Data
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div
                class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel Data -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                        <th class="p-4 border-b font-semibold">Penumpang & SPPD</th>
                        <th class="p-4 border-b font-semibold">Rute & Waktu</th>
                        <th class="p-4 border-b font-semibold">Maskapai & Tiket</th>
                        <th class="p-4 border-b font-semibold text-center">Bukti/Lampiran</th>
                        <th class="p-4 border-b font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($flights as $flight)
                        <tr class="hover:bg-slate-50 border-b border-gray-100 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $flight->passenger_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">SPPD: {{ $flight->sppd_number }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-blue-600">{{ $flight->destination }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Berangkat: {{ $flight->departure_time->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2 font-medium text-gray-700">
                                    ✈️ {{ $flight->airline }}
                                    <span
                                        class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">{{ $flight->flight_number }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Kode Booking: <span
                                        class="font-mono font-bold tracking-wider text-gray-700">{{ $flight->ticket_code ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                @if ($flight->ticket_image)
                                    <a href="{{ Storage::url($flight->ticket_image) }}" target="_blank"
                                        class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-full text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Tombol Detail (Memicu Modal Pop-up) -->
                                    <button type="button" onclick="openDetailModal(this)"
                                        data-name="{{ $flight->passenger_name }}" data-sppd="{{ $flight->sppd_number }}"
                                        data-dest="{{ $flight->destination }}" data-airline="{{ $flight->airline }}"
                                        data-flight="{{ $flight->flight_number ?? '-' }}"
                                        data-code="{{ $flight->ticket_code ?? '-' }}"
                                        data-dep="{{ $flight->departure_time->format('d F Y, H:i') }}"
                                        data-ret="{{ $flight->return_time ? $flight->return_time->format('d F Y, H:i') : '-' }}"
                                        data-image="{{ $flight->ticket_image ? Storage::url($flight->ticket_image) : '' }}"
                                        class="text-teal-500 hover:text-teal-700 transition bg-teal-50 hover:bg-teal-100 p-1.5 rounded-lg"
                                        title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.flights.edit', $flight->id) }}"
                                        class="text-blue-500 hover:text-blue-700 transition bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data penerbangan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 transition bg-red-50 hover:bg-red-100 p-1.5 rounded-lg"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="font-medium text-gray-600">Belum ada data penerbangan.</p>
                                <p class="text-sm mt-1">Silakan klik "Tambah Data" atau "Import Excel".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- MODAL POP-UP DETAIL PENERBANGAN -->
    <!-- ========================================== -->
    <div id="detailModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">

            <!-- Modal Header -->
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Detail Informasi Perjalanan
                </h2>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Data Penumpang -->
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Nama Penumpang</p>
                        <p id="modal-name" class="text-gray-800 font-bold text-lg">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Nomor SPPD</p>
                        <p id="modal-sppd" class="text-gray-800 font-medium">-</p>
                    </div>

                    <!-- Data Rute & Maskapai -->
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Maskapai & Tujuan</p>
                        <p class="text-gray-800 font-medium"><span id="modal-airline"
                                class="text-blue-600 font-bold"></span> ➔ <span id="modal-dest"></span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">No. Penerbangan & Kode
                        </p>
                        <p class="text-gray-800 font-medium"><span id="modal-flight"></span> | Kode: <span
                                id="modal-code" class="font-mono font-bold tracking-widest text-slate-700"></span></p>
                    </div>

                    <!-- Waktu -->
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Waktu Berangkat</p>
                        <p id="modal-dep" class="text-gray-800 font-medium">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Waktu Kembali</p>
                        <p id="modal-ret" class="text-gray-800 font-medium">-</p>
                    </div>
                </div>

                <!-- Area Tiket -->
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-3">Lampiran Bukti / Tiket</p>

                    <div id="modal-no-image" class="hidden text-gray-400 text-sm italic">
                        Tidak ada lampiran tiket elektronik.
                    </div>

                    <div id="modal-image-container" class="hidden">
                        <a id="modal-image-link" href="#" target="_blank"
                            class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-100 px-6 py-2 rounded-lg text-sm font-semibold transition border border-blue-200">
                            📄 Buka / Unduh Lampiran
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                <button onclick="closeDetailModal()"
                    class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Khusus Modal -->
    <script>
        function openDetailModal(button) {
            // Ambil data dari attribute 'data-*' pada tombol yang diklik
            document.getElementById('modal-name').innerText = button.getAttribute('data-name');
            document.getElementById('modal-sppd').innerText = button.getAttribute('data-sppd');
            document.getElementById('modal-dest').innerText = button.getAttribute('data-dest');
            document.getElementById('modal-airline').innerText = button.getAttribute('data-airline');
            document.getElementById('modal-flight').innerText = button.getAttribute('data-flight');
            document.getElementById('modal-code').innerText = button.getAttribute('data-code');
            document.getElementById('modal-dep').innerText = button.getAttribute('data-dep');
            document.getElementById('modal-ret').innerText = button.getAttribute('data-ret');

            // Cek apakah ada file lampiran
            let imageUrl = button.getAttribute('data-image');
            if (imageUrl && imageUrl.trim() !== '') {
                document.getElementById('modal-image-container').classList.remove('hidden');
                document.getElementById('modal-no-image').classList.add('hidden');
                document.getElementById('modal-image-link').setAttribute('href', imageUrl);
            } else {
                document.getElementById('modal-image-container').classList.add('hidden');
                document.getElementById('modal-no-image').classList.remove('hidden');
            }

            // Tampilkan Modal
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            // Sembunyikan Modal
            document.getElementById('detailModal').classList.add('hidden');
        }

        // (Opsional) Tutup modal jika user mengklik area gelap di luar modal
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>
@endsection
