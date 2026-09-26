@extends('layouts.public')

@section('content')

<!-- HEADER PORTAL BERITA -->
<div class="bg-slate-900 pt-16 pb-20 border-b-4 border-blue-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4">Portal <span class="text-blue-500">Berita</span></h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto font-light mb-8">Informasi terkini, pengumuman, dan kegiatan seputar Sekretariat DPRD Kota Banjarbaru.</p>

        <!-- Search & Filter Bar -->
        <form action="{{ route('public.posts.index') }}" method="GET" class="max-w-3xl mx-auto">
            <div class="flex flex-col md:flex-row gap-2 bg-white p-2 rounded-2xl shadow-lg">
                <div class="flex-grow flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari topik berita..." class="w-full border-none focus:ring-0 text-sm p-3 outline-none bg-transparent">
                </div>
                <div class="hidden md:block w-px bg-gray-200 my-2"></div>
                <div class="w-full md:w-56 pl-4 md:pl-2 flex items-center border-t md:border-t-0 border-gray-100">
                    <select name="category" class="w-full border-none focus:ring-0 text-sm p-3 outline-none text-gray-600 bg-transparent cursor-pointer font-medium">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition">Cari</button>
            </div>
        </form>
    </div>
</div>

<!-- DAFTAR BERITA (GRID KARTU) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
        <a href="{{ route('public.posts.show', $post->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 transition duration-300 group flex flex-col h-full">
            <div class="relative h-56 overflow-hidden">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    {{ $post->category }}
                </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3 font-medium uppercase tracking-wider">
                    <span>📅 {{ $post->created_at->format('d M Y') }}</span>
                    <span>•</span>
                    <span>✍️ {{ $post->author->name ?? 'Admin' }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 leading-snug mb-3 group-hover:text-blue-600 transition line-clamp-3">
                    {{ $post->title }}
                </h3>
                <div class="mt-auto">
                    <span class="text-blue-600 font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                        Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-12 text-center bg-gray-50 rounded-2xl border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <h3 class="text-xl font-bold text-gray-700">Tidak ada berita ditemukan</h3>
            <p class="text-gray-500 mt-2">Coba gunakan kata kunci atau kategori lain.</p>
        </div>
        @endforelse
    </div>

    <!-- Paginasi -->
    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>

@endsection
