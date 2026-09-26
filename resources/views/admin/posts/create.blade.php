@extends('layouts.admin')

@section('content')
<!-- Memanggil Library Trix Editor untuk Rich Text -->
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

<style>
    /* Styling tambahan agar editor Trix terlihat tinggi dan modern */
    trix-editor { min-height: 300px; background-color: white; border-radius: 0.5rem; }
    trix-toolbar [data-trix-button-group="file-tools"] { display: none; } /* Sembunyikan upload file di dalam artikel sementara */
</style>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Tulis Berita Baru</h1>
        <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm">&larr; Kembali</a>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- BAGIAN KIRI: Konten Utama -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none font-medium text-lg" placeholder="Masukkan judul yang menarik..." required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Isi Berita / Konten <span class="text-red-500">*</span></label>
                        <!-- Input tersembunyi yang datanya diisi otomatis oleh Trix Editor -->
                        <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                        <trix-editor input="content" class="border-gray-300"></trix-editor>
                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN: Sidebar Pengaturan -->
            <div class="space-y-6">
                <!-- Status & Kategori -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Pengaturan Publikasi</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Publikasi</label>
                        <select name="is_published" class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="1">Langsung Terbitkan (Publik)</option>
                            <option value="0">Simpan sebagai Draft</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Berita</label>
                        <select name="category" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Berita Utama">Berita Utama</option>
                            <option value="Pengumuman">Pengumuman</option>
                            <option value="Kegiatan">Kegiatan Sidang / Dewan</option>
                            <option value="Opini">Opini / Artikel</option>
                        </select>
                    </div>
                </div>

                <!-- Upload Gambar Sampul -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Gambar Sampul <span class="text-red-500">*</span></h3>

                    <!-- Tempat Preview Gambar Muncul -->
                    <div id="imagePreviewContainer" class="hidden mb-4 rounded-lg overflow-hidden border">
                        <img id="imagePreview" src="" alt="Preview" class="w-full h-40 object-cover">
                    </div>

                    <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png,.webp" onchange="previewFile()" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required>
                    <p class="text-xs text-gray-400 mt-2">Rekomendasi rasio 16:9. Maksimal 3MB.</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white rounded-xl py-3 font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Simpan & Publikasikan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- SCRIPT LIVE IMAGE PREVIEW -->
<script>
    function previewFile() {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        const file = document.getElementById('imageInput').files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // Ubah src gambar dan tampilkan kotaknya
            preview.src = reader.result;
            container.classList.remove('hidden');
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
