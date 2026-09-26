@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Portal Publikasi Berita</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola artikel, pengumuman, dan berita lembaga.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tulis Berita Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b font-semibold w-24">Sampul</th>
                    <th class="p-4 border-b font-semibold">Judul & Kategori</th>
                    <th class="p-4 border-b font-semibold text-center">Status</th>
                    <th class="p-4 border-b font-semibold">Tanggal & Penulis</th>
                    <th class="p-4 border-b font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($posts as $post)
                <tr class="hover:bg-slate-50 border-b border-gray-100 transition">
                    <td class="p-4">
                        <img src="{{ Storage::url($post->image) }}" alt="Sampul" class="w-20 h-14 object-cover rounded-md shadow-sm border">
                    </td>
                    <td class="p-4 whitespace-normal">
                        <div class="font-bold text-gray-800 text-base line-clamp-2">{{ $post->title }}</div>
                        <span class="inline-block mt-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-medium">{{ $post->category }}</span>
                    </td>
                    <td class="p-4 text-center">
                        @if($post->is_published)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">Dipublikasikan</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold border border-yellow-200">Draft (Disembunyikan)</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-medium text-gray-700">{{ $post->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Oleh: {{ $post->author->name ?? 'Admin' }}</div>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.posts.edit', $post->slug) }}" class="text-blue-500 hover:text-blue-700 transition bg-blue-50 hover:bg-blue-100 p-2 rounded-lg" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post->slug) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition bg-red-50 hover:bg-red-100 p-2 rounded-lg" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">Belum ada berita yang ditulis.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
