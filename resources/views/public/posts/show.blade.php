@extends('layouts.public')

@section('content')

<!-- HERO IMAGE & JUDUL -->
<div class="relative w-full h-[60vh] min-h-[400px] bg-slate-900">
    <img src="{{ Storage::url($post->image) }}" alt="Sampul Berita" class="absolute inset-0 w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>

    <div class="absolute bottom-0 inset-x-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 text-center">
            <span class="inline-block bg-blue-600 text-white font-bold px-4 py-1.5 rounded-full text-sm mb-6 shadow-lg">
                {{ $post->category }}
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-6 drop-shadow-md">
                {{ $post->title }}
            </h1>
            <div class="flex items-center justify-center gap-4 text-sm text-gray-300 font-medium">
                <span class="flex items-center gap-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $post->author->name ?? 'Admin' }}</span>
                <span>•</span>
                <span class="flex items-center gap-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $post->created_at->format('d F Y, H:i') }} WITA</span>
            </div>
        </div>
    </div>
</div>

<!-- KONTEN BERITA -->
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Share Buttons -->
        <div class="flex items-center justify-center gap-4 mb-12 pb-8 border-b border-gray-100">
            <span class="text-sm font-bold text-gray-400 uppercase">Bagikan:</span>
            <!-- Tombol Copy Link menggunakan JS sederhana -->
            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!');" class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-blue-100 hover:text-blue-600 transition" title="Salin Tautan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            </button>
        </div>

        <!-- Render HTML dari Trix Editor -->
        <!-- Class 'prose' dari Tailwind Typography membuat HTML Trix terlihat sangat rapi -->
        <article class="prose prose-lg prose-blue max-w-none text-gray-700 leading-relaxed">
            {!! $post->content !!}
        </article>

        <!-- Tombol Kembali -->
        <div class="mt-16 pt-8 border-t border-gray-100">
            <a href="{{ route('public.posts.index') }}" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:text-blue-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Indeks Berita
            </a>
        </div>
    </div>
</div>

<!-- BERITA TERKAIT -->
@if($relatedPosts->count() > 0)
<div class="bg-gray-50 py-16 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-2xl font-black text-gray-900 mb-8 text-center">Berita Terkait</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedPosts as $rel)
            <a href="{{ route('public.posts.show', $rel->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                <img src="{{ Storage::url($rel->image) }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                <div class="p-6">
                    <p class="text-xs text-blue-600 font-bold uppercase mb-2">{{ $rel->category }}</p>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2">{{ $rel->title }}</h4>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
