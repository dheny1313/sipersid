@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Penerbangan</h1>
        <a href="{{ route('admin.flights.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm">&larr; Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('admin.flights.update', $flight->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Wajib untuk proses Update -->

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
                    <input type="text" name="passenger_name" value="{{ old('passenger_name', $flight->passenger_name) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor SPPD <span class="text-red-500">*</span></label>
                    <input type="text" name="sppd_number" value="{{ old('sppd_number', $flight->sppd_number) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Penerbangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="destination" value="{{ old('destination', $flight->destination) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maskapai Penerbangan <span class="text-red-500">*</span></label>
                    <input type="text" name="airline" value="{{ old('airline', $flight->airline) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Penerbangan</label>
                    <input type="text" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Booking / Tiket</label>
                    <input type="text" name="ticket_code" value="{{ old('ticket_code', $flight->ticket_code) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 font-mono outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                    <!-- Perlu di-format khusus Y-m-d\TH:i agar terbaca oleh input datetime-local HTML5 -->
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time', $flight->departure_time ? $flight->departure_time->format('Y-m-d\TH:i') : '') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Kembali (Opsional)</label>
                    <input type="datetime-local" name="return_time" value="{{ old('return_time', $flight->return_time ? $flight->return_time->format('Y-m-d\TH:i') : '') }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Lampiran Bukti</h3>
            <div class="mb-8">
                @if($flight->ticket_image)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Lampiran saat ini tersimpan.</p>
                                <a href="{{ Storage::url($flight->ticket_image) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Lampiran</a>
                            </div>
                        </div>
                    </div>
                @endif

                <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti/Unggah Tiket Baru (Opsional)</label>
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                    <input type="file" name="ticket_image" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-2">*Biarkan kosong jika tidak ingin mengubah lampiran yang sudah ada.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.flights.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
