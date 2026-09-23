@extends('layouts.public')

@section('content')

    <!-- Menampilkan Pesan Sukses -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan Pesan Error / Gagal -->
    @if (session('error'))
        <div id="error-alert" class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            {{ session('error') }}

            <!-- Area ini akan ditangkap oleh JavaScript -->
            @if (session('cooldown_seconds'))
                Silakan tunggu <span id="live-timer"
                    class="font-bold text-red-900 text-lg">{{ session('cooldown_seconds') }}</span> detik sebelum mencoba
                lagi.
            @endif
        </div>
    @endif
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div
            class="bg-white rounded-t-lg shadow p-8 border-b-4 {{ $meeting->status == 'Completed' ? 'border-green-500' : 'border-yellow-500' }}">
            <div class="mb-4">
                <span
                    class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm mr-2">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d F Y, H:i') }}</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">📍 {{ $meeting->location }}</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $meeting->title }}</h1>
            <p class="text-gray-700 leading-relaxed">{{ $meeting->description }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ringkasan Hasil Sidang
                    </h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($meeting->result_summary ?? 'Hasil sidang belum dipublikasikan atau sidang belum selesai.')) !!}
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">📄 Dokumen Lampiran</h3>
                    @if ($meeting->attachments->count() > 0)
                        <ul class="space-y-3">
                            @foreach ($meeting->attachments as $file)
                                <li
                                    class="flex justify-between items-center p-3 bg-gray-50 border rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-800">{{ $file->file_title }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                        class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">
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

                    @if ($total > 0)
                        <ul class="space-y-2 mb-6">
                            <li class="flex justify-between text-gray-700"><span class="font-semibold">Hadir:</span> <span
                                    class="text-green-600 font-bold">{{ $hadir }}</span></li>
                            <li class="flex justify-between text-gray-700"><span class="font-semibold">Izin:</span> <span
                                    class="text-blue-600 font-bold">{{ $izin }}</span></li>
                            <li class="flex justify-between text-gray-700"><span class="font-semibold">Sakit:</span> <span
                                    class="text-yellow-600 font-bold">{{ $sakit }}</span></li>
                            <li class="flex justify-between text-gray-700"><span class="font-semibold">Alpa:</span> <span
                                    class="text-red-600 font-bold">{{ $alpa }}</span></li>
                        </ul>
                        <div class="bg-blue-50 text-blue-800 p-3 rounded text-center font-bold">
                            Tingkat Kehadiran: {{ number_format(($hadir / $total) * 100, 1) }}%
                        </div>
                    @else
                        <p class="text-gray-500 italic text-sm">Data presensi belum dimasukkan oleh sekretariat.</p>
                    @endif
                </div>
            </div>


            <!-- Cek apakah admin sudah membuka akses presensi -->
            @if ($meeting->is_attendance_open)
                <div class="bg-blue-50 p-6 rounded-lg mt-8">
                    <h3 class="text-xl font-bold mb-4">Presensi Kehadiran Sidang</h3>

                    <!-- JIKA USER BELUM LOGIN (PRESENSI UMUM) -->
                    @guest

                        <form action="{{ route('public.attendances.store', $meeting->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_peserta" value="Umum">

                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="member_name" required class="w-full border p-2 rounded">
                            </div>
                            <div class="mb-3">
                                <label>Instansi / Asal / Keterangan</label>
                                <!-- Kita pinjam kolom 'remarks' atau 'fraksi' untuk menyimpan asal instansi umum -->
                                <input type="text" name="remarks" placeholder="Contoh: Warga Desa X / Mahasiswa" required
                                    class="w-full border p-2 rounded">
                            </div>
                            <input type="hidden" name="status" value="Hadir">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Hadir Sebagai Tamu
                                Umum</button>
                        </form>

                        <p class="mt-4 text-sm text-gray-500">Anggota Dewan? <a href="{{ route('login') }}"
                                class="text-blue-600 underline">Login di sini</a> untuk presensi resmi.</p>
                    @endguest

                    <!-- JIKA USER SUDAH LOGIN (PRESENSI DPR/ADMIN) -->
                    @auth
                        <form action="{{ route('public.attendances.store', $meeting->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_peserta" value="DPR">

                            <!-- Nama otomatis diambil dari akun yang sedang login -->
                            <input type="hidden" name="member_name" value="{{ auth()->user()->name }}">

                            <div class="mb-3">
                                <label>Fraksi / Jabatan Anda</label>
                                <input type="text" name="fraksi" required class="w-full border p-2 rounded">
                            </div>
                            <div class="mb-3">
                                <label>Status Kehadiran</label>
                                <select name="status" class="w-full border p-2 rounded">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Kirim Presensi
                                Resmi</button>
                        </form>
                    @endauth
                </div>
            @else
                <div class="bg-gray-100 p-4 rounded-lg mt-8 text-center text-gray-600">
                    Formulir presensi belum dibuka oleh Sekretariat.
                </div>
            @endif





        </div>
    </div>

    <!-- SCRIPT HITUNG MUNDUR OTOMATIS -->
    @if (session('cooldown_seconds'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil sisa detik dari server Laravel
                let timeLeft = {{ session('cooldown_seconds') }};

                const timerSpan = document.getElementById('live-timer');
                const errorAlert = document.getElementById('error-alert');

                // Cari semua tombol submit di halaman ini dan nonaktifkan sementara
                const submitButtons = document.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed'); // Efek pudar transparan
                });

                // Jalankan interval setiap 1000 milidetik (1 detik)
                const countdown = setInterval(() => {
                    timeLeft--; // Kurangi 1 detik

                    if (timerSpan) {
                        timerSpan.textContent = timeLeft; // Update angka di layar
                    }

                    // Jika waktu sudah habis (mencapai 0)
                    if (timeLeft <= 0) {
                        clearInterval(countdown); // Hentikan timer

                        // Ubah tampilan kotak error menjadi warna biru/hijau (Siap)
                        if (errorAlert) {
                            errorAlert.innerHTML =
                                "Waktu tunggu selesai. Anda sudah bisa mengisi form presensi kembali.";
                            errorAlert.classList.remove('bg-red-100', 'border-red-500', 'text-red-700');
                            errorAlert.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
                        }

                        // Aktifkan kembali tombol submit agar bisa diklik
                        submitButtons.forEach(btn => {
                            btn.disabled = false;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        });
                    }
                }, 1000);
            });
        </script>
    @endif
@endsection
