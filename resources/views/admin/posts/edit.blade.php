@extends('layouts.admin')

@section('content')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<style>
    trix-editor { min-height: 300px; background-color: white; border-radius: 0.5rem; }
    trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
</style>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Berita</h1>
        <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm">&larr; Kembali</a>
    </div>

    <form action="{{ route('admin.posts.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none font-medium text-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Isi Berita / Konten</label>
                        <input id="content" type="hidden" name="content" value="{{ old('content', $post->content) }}">
                        <trix-editor input="content" class="border-gray-300"></trix-editor>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Pengaturan Publikasi</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Publikasi</label>
                        <select name="is_published" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="1" {{ $post->is_published ? 'selected' : '' }}>Langsung Terbitkan</option>
                            <option value="0" {{ !$post->is_published ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Berita</label>
                        <select name="category" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Berita Utama" {{ $post->category == 'Berita Utama' ? 'selected' : '' }}>Berita Utama</option>
                            <option value="Pengumuman" {{ $post->category == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Kegiatan" {{ $post->category == 'Kegiatan' ? 'selected' : '' }}>Kegiatan Sidang / Dewan</option>
                            <option value="Opini" {{ $post->category == 'Opini' ? 'selected' : '' }}>Opini / Artikel</option>
                        </select>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Gambar Sampul</h3>

                    <div id="imagePreviewContainer" class="mb-4 rounded-lg overflow-hidden border">
                        <img id="imagePreview" src="{{ Storage::url($post->image) }}" alt="Preview Saat Ini" class="w-full h-40 object-cover">
                    </div>

                    <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png,.webp" onchange="previewFile()" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-2">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white rounded-xl py-3 font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Update Berita
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewFile() {
        const preview = document.getElementById('imagePreview');
        const file = document.getElementById('imageInput').files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            preview.src = reader.result;
        }, false);

        if (file) { reader.readAsDataURL(file); }
    }
</script>
@endsection
