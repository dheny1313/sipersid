@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Data Penerbangan</h1>
        <a href="{{ route('admin.flights.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm">&larr; Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <!-- PENTING: enctype multipart form data untuk upload gambar -->
        <form action="{{ route('admin.flights.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Pesan Error Validasi Keseluruhan -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Penumpang & SPPD</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penumpang <span class="text-red-500">*</span></label>
                    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Masukkan nama lengkap" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor SPPD <span class="text-red-500">*</span></label>
                    <input type="text" name="sppd_number" value="{{ old('sppd_number') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Contoh: 094/SPPD/2026" required>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Penerbangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="destination" value="{{ old('destination') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Misal: Jakarta" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maskapai Penerbangan <span class="text-red-500">*</span></label>
                    <input type="text" name="airline" value="{{ old('airline') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Misal: Garuda Indonesia" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Penerbangan</label>
                    <input type="text" name="flight_number" value="{{ old('flight_number') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Misal: GA-123">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Booking / Tiket</label>
                    <input type="text" name="ticket_code" value="{{ old('ticket_code') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 font-mono outline-none transition" placeholder="Misal: ABCD12">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Kembali (Opsional)</label>
                    <input type="datetime-local" name="return_time" value="{{ old('return_time') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Lampiran Bukti</h3>
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Tiket / Boarding Pass (Opsional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <input type="file" name="ticket_image" accept=".jpg,.jpeg,.png,.pdf" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, atau PDF. Maksimal 2MB.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.flights.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
