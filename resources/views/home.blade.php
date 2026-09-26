@extends('layouts.public')

@section('content')
    <!-- HERO SECTION DENGAN GRADIENT & PATTERN -->
    <div
        class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 pt-24 pb-32 lg:pt-32 lg:pb-40 overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span
                class="inline-block py-1 px-3 rounded-full bg-blue-800 bg-opacity-50 text-blue-200 text-xs font-bold tracking-widest uppercase mb-6 border border-blue-700 backdrop-blur-sm">
                Portal Informasi Publik
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-6 leading-tight">
                Transparansi Kedewanan <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-300">Dalam Genggaman
                    Anda</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg md:text-xl text-blue-100 mx-auto font-light leading-relaxed mb-10">
                Akses jadwal, hasil rapat paripurna, dan berita terkini secara *real-time*. Kami mewujudkan parlemen modern
                yang terbuka dan akuntabel.
            </p>
        </div>
    </div>

    <!-- FLOATING STATISTIC SECTION -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-20">
        <div
            class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 md:p-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
            <div>
                <p class="text-4xl font-black text-blue-600 mb-1">{{ $totalMeetings ?? 0 }}</p>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Sidang Bulan Ini</p>
            </div>
            <div>
                <p class="text-4xl font-black text-green-500 mb-1">{{ $completedMeetings ?? 0 }}</p>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Telah Selesai</p>
            </div>
            <div>
                <p class="text-4xl font-black text-amber-500 mb-1">{{ $scheduledMeetings ?? 0 }}</p>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Terjadwal</p>
            </div>
            <div>
                <p class="text-4xl font-black text-teal-500 mb-1">24/7</p>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Akses Publik</p>
            </div>
        </div>
    </div>

    <!-- AGENDA SIDANG & PENCARIAN -->
    <div id="agenda" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Arsip & Agenda Sidang</h2>
            <div class="w-24 h-1.5 bg-blue-600 mx-auto rounded-full mb-6"></div>
            <p class="text-gray-500 max-w-2xl mx-auto">Temukan jadwal, presensi, dan dokumen persidangan dengan mudah.</p>
        </div>

        <!-- FILTER PENCARIAN (MODERN SEARCH BAR) -->
        <form action="{{ route('home') }}#agenda" method="GET" class="max-w-3xl mx-auto mb-12">
            <div
                class="flex flex-col md:flex-row gap-3 bg-white p-2.5 rounded-2xl shadow-md border border-gray-200 transition-all focus-within:ring-4 focus-within:ring-blue-500/20 focus-within:border-blue-400">

                <!-- Input Text -->
                <div class="flex-grow flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul atau topik sidang..."
                        class="w-full border-none focus:ring-0 text-sm md:text-base p-2 outline-none bg-transparent placeholder-gray-400 text-gray-700">
                </div>

                <!-- Garis Pemisah (Desktop) -->
                <div class="hidden md:block w-px bg-gray-200 my-2"></div>

                <!-- Select Status -->
                <div
                    class="w-full md:w-48 pl-4 md:pl-2 flex items-center border-t md:border-t-0 border-gray-100 pt-3 md:pt-0">
                    <select name="status"
                        class="w-full border-none focus:ring-0 text-sm p-2 outline-none text-gray-600 bg-transparent cursor-pointer appearance-none font-medium">
                        <option value="">Semua Status</option>
                        <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Terjadwal
                        </option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Telah Selesai
                        </option>
                    </select>
                    <!-- Ikon Panah Dropdown -->
                    <svg class="w-4 h-4 text-gray-400 -ml-6 pointer-events-none" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <!-- Tombol Cari -->
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold text-sm transition shadow-sm hover:shadow shadow-blue-600/30 w-full md:w-auto mt-2 md:mt-0">
                    Cari Data
                </button>
            </div>

            <!-- Indikator Hasil Pencarian Aktif -->
            @if (request()->filled('search') || request()->filled('status'))
                <div class="text-center mt-4 flex items-center justify-center gap-2">
                    <span class="text-sm text-gray-500">Menampilkan hasil pencarian.</span>
                    <a href="{{ route('home') }}#agenda"
                        class="text-sm text-red-500 hover:text-red-700 font-semibold underline">Reset Filter</a>
                </div>
            @endif
        </form>

        <!-- Grid Kartu Sidang -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($meetings as $meeting)
                <div
                    class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition duration-300 group flex flex-col h-full">

                    <div
                        class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 group-hover:bg-blue-50/50 transition">
                        @if ($meeting->status == 'Completed')
                            <span
                                class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Selesai
                            </span>
                        @elseif($meeting->status == 'Scheduled')
                            <span
                                class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Terjadwal
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> {{ $meeting->status }}
                            </span>
                        @endif

                        <span class="text-sm font-semibold text-gray-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}
                        </span>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3
                                class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-700 transition line-clamp-2 leading-snug">
                                {{ $meeting->title }}
                            </h3>
                            <div class="space-y-2 mt-4">
                                <p class="text-sm text-gray-600 flex items-start gap-2">
                                    <span class="mt-0.5 text-gray-400"><svg class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg></span>
                                    <span class="line-clamp-1">{{ $meeting->location }}</span>
                                </p>
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <span class="text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg></span>
                                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('H:i') }} WITA
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('public.meetings.show', $meeting->slug) }}"
                            class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-600 hover:text-white hover:border-blue-600 transition duration-300">
                            Lihat Detail Sidang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-blue-50 border border-blue-100 rounded-2xl p-12 text-center">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pencarian Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-4">Maaf, kami tidak menemukan jadwal sidang yang sesuai dengan filter/kata
                        kunci Anda.</p>
                    <a href="{{ route('home') }}#agenda"
                        class="inline-block bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition">Lihat
                        Semua Sidang</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
