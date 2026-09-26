@extends('layouts.public')

@section('content')

<!-- ========================================== -->
<!-- 1. HERO HEADER SIDANG -->
<!-- ========================================== -->
<div class="relative bg-gradient-to-r from-slate-900 to-blue-900 pt-16 pb-24 overflow-hidden border-b-4 {{ $meeting->status == 'Completed' ? 'border-green-500' : 'border-amber-500' }}">
    <!-- Efek Latar Belakang -->
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-blue-200 mb-6 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}#agenda" class="hover:text-white transition">Agenda Sidang</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-400 line-clamp-1 max-w-[200px]">{{ $meeting->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            @if($meeting->status == 'Completed')
                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase shadow-sm">Telah Selesai</span>
            @else
                <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase shadow-sm animate-pulse">Menunggu / Terjadwal</span>
            @endif
            <span class="bg-blue-800 bg-opacity-50 text-blue-100 border border-blue-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d F Y, H:i') }} WITA
            </span>
            <span class="bg-blue-800 bg-opacity-50 text-blue-100 border border-blue-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $meeting->location }}
            </span>
        </div>

        <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight mb-4">{{ $meeting->title }}</h1>
        <p class="text-lg text-blue-100 max-w-3xl font-light">{{ $meeting->description ?? 'Tidak ada deskripsi tambahan untuk agenda sidang ini.' }}</p>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. KONTEN UTAMA (KIRI: HASIL, KANAN: PRESENSI) -->
<!-- ========================================== -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10 pb-20">

    <!-- Area Notifikasi Alert -->
    <div class="mb-6 max-w-3xl">
        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-lg shadow-green-100 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div id="error-alert" class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-lg shadow-red-100">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <div class="font-bold mb-1">{{ session('error') }}</div>
                        @if (session('cooldown_seconds'))
                            <p class="text-sm">Silakan tunggu <span id="live-timer" class="font-black text-red-900 text-lg">{{ session('cooldown_seconds') }}</span> detik sebelum mencoba lagi.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI (Hasil & Lampiran) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Ringkasan Hasil Sidang -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 border border-gray-100 p-8">
                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    Ringkasan Hasil Sidang
                </h3>

                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    @if($meeting->result_summary)
                        {!! nl2br(e($meeting->result_summary)) !!}
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium">Hasil sidang belum dipublikasikan atau sidang masih berlangsung.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dokumen Lampiran -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 border border-gray-100 p-8">
                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    Dokumen Lampiran
                </h3>

                @if ($meeting->attachments->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($meeting->attachments as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="group flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition duration-300">
                                <div class="w-12 h-12 bg-white rounded-lg shadow-sm flex items-center justify-center mr-4 group-hover:text-blue-600 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-blue-700 transition">{{ $file->file_title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        Unduh Dokumen <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm text-center py-4 bg-gray-50 rounded-xl">Tidak ada dokumen lampiran yang tersedia untuk publik.</p>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN (Rekap & Form Presensi) -->
        <div class="space-y-8">

            <!-- Statistik Kehadiran -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden">
                <div class="p-6 bg-slate-50 border-b border-gray-100">
                    <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Rekapitulasi Kehadiran
                    </h3>
                </div>

                <div class="p-6">
                    @php
                        $hadir = $meeting->attendances->where('status', 'Hadir')->count();
                        $izin = $meeting->attendances->where('status', 'Izin')->count();
                        $sakit = $meeting->attendances->where('status', 'Sakit')->count();
                        $alpa = $meeting->attendances->where('status', 'Alpa')->count();
                        $total = $meeting->attendances->count();
                    @endphp

                    @if ($total > 0)
                        <!-- Persentase Lingkaran / Progress -->
                        <div class="mb-6 pb-6 border-b border-gray-100 text-center">
                            <span class="block text-4xl font-black text-blue-600 mb-1">{{ number_format(($hadir / $total) * 100, 1) }}%</span>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tingkat Kehadiran</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Hadir -->
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <span class="w-3 h-3 rounded-full bg-green-500"></span> Hadir
                                </span>
                                <span class="font-bold text-gray-900">{{ $hadir }} <span class="text-gray-400 text-xs font-normal">org</span></span>
                            </div>
                            <!-- Izin -->
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <span class="w-3 h-3 rounded-full bg-blue-500"></span> Izin
                                </span>
                                <span class="font-bold text-gray-900">{{ $izin }} <span class="text-gray-400 text-xs font-normal">org</span></span>
                            </div>
                            <!-- Sakit -->
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Sakit
                                </span>
                                <span class="font-bold text-gray-900">{{ $sakit }} <span class="text-gray-400 text-xs font-normal">org</span></span>
                            </div>
                            <!-- Alpa -->
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span> Alpa
                                </span>
                                <span class="font-bold text-gray-900">{{ $alpa }} <span class="text-gray-400 text-xs font-normal">org</span></span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 italic text-sm">Data presensi belum direkapitulasi oleh sekretariat.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Formulir Presensi (Dinamis) -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 border border-blue-100 overflow-hidden relative">

                @if ($meeting->is_attendance_open)
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                    <div class="p-6 bg-blue-50/50 border-b border-blue-100">
                        <h3 class="text-lg font-black text-gray-900">Form Presensi Kehadiran</h3>
                        <p class="text-xs text-gray-500 mt-1">Silakan isi daftar hadir Anda di bawah ini.</p>
                    </div>

                    <div class="p-6">
                        <!-- JIKA USER BELUM LOGIN (PRESENSI UMUM) -->
                        @guest
                            <form action="{{ route('public.attendances.store', $meeting->slug) }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- HONEYPOT (JEBAKAN BOT) -->
                                <div style="display: none;">
                                    <label>Kosongkan ini jika Anda manusia</label>
                                    <input type="text" name="username_jebakan" tabindex="-1" autocomplete="off">
                                </div>

                                <input type="hidden" name="jenis_peserta" value="Umum">
                                <input type="hidden" name="status" value="Hadir">

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                                    <input type="text" name="member_name" required placeholder="Ketik nama lengkap..." class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Instansi / Organisasi / Desa <span class="text-red-500">*</span></label>
                                    <input type="text" name="remarks" required placeholder="Contoh: Warga Desa X / Mahasiswa" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white">
                                </div>

                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-200 mt-2">
                                    Simpan Kehadiran (Umum)
                                </button>
                            </form>

                            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                                <p class="text-xs text-gray-500">Anggota Dewan Perwakilan Rakyat?</p>
                                <a href="{{ route('login') }}" class="inline-block mt-1 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                                    Login untuk Presensi Resmi &rarr;
                                </a>
                            </div>
                        @endguest

                        <!-- JIKA USER SUDAH LOGIN (PRESENSI DPR/ADMIN) -->
                        @auth
                            <div class="mb-5 flex items-center gap-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                                <div class="w-10 h-10 rounded-full bg-indigo-200 text-indigo-700 flex items-center justify-center font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">Login Sebagai</p>
                                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                </div>
                            </div>

                            <form action="{{ route('public.attendances.store', $meeting->slug) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="jenis_peserta" value="DPR">
                                <input type="hidden" name="member_name" value="{{ auth()->user()->name }}">

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Fraksi / Komisi <span class="text-red-500">*</span></label>
                                    <input type="text" name="fraksi" required placeholder="Ketik nama fraksi..." class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Kehadiran <span class="text-red-500">*</span></label>
                                    <select name="status" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white font-medium cursor-pointer">
                                        <option value="Hadir">✔️ Hadir (Mengikuti Sidang)</option>
                                        <option value="Izin">📝 Izin (Dinas Luar/Keperluan Lain)</option>
                                        <option value="Sakit">🏥 Sakit (Dengan Keterangan Dokter)</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-indigo-200 mt-2">
                                    Kirim Presensi Resmi
                                </button>
                            </form>
                        @endauth
                    </div>
                @else
                    <div class="p-8 text-center bg-gray-50">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Presensi Ditutup</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Formulir presensi saat ini belum dibuka atau sudah ditutup oleh Sekretariat.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT HITUNG MUNDUR (DIAMBIL DARI VERSI LAMA) -->
@if (session('cooldown_seconds'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = {{ session('cooldown_seconds') }};
        const timerSpan = document.getElementById('live-timer');
        const errorAlert = document.getElementById('error-alert');
        const submitButtons = document.querySelectorAll('button[type="submit"]');

        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        });

        const countdown = setInterval(() => {
            timeLeft--;
            if (timerSpan) timerSpan.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                if (errorAlert) {
                    errorAlert.innerHTML = "<div class='flex items-center gap-2 font-bold'><svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg> Waktu tunggu selesai. Silakan isi kembali.</div>";
                    errorAlert.className = "p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl shadow-lg shadow-blue-100";
                }
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
