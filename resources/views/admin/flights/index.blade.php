@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Penerbangan Luar Kota</h1>
        <a href="{{ route('admin.flights.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Data</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="p-3 border-b">Nama Penumpang</th>
                <th class="p-3 border-b">Tujuan</th>
                <th class="p-3 border-b">Maskapai & No. Flight</th>
                <th class="p-3 border-b">Waktu Berangkat</th>
                <th class="p-3 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flights as $flight)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border-b">{{ $flight->passenger_name }}</td>
                <td class="p-3 border-b">{{ $flight->destination }}</td>
                <td class="p-3 border-b">{{ $flight->airline }} ({{ $flight->flight_number }})</td>
                <td class="p-3 border-b">{{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y, H:i') }}</td>
                <td class="p-3 border-b">
                    <a href="{{ route('admin.flights.edit', $flight->id) }}" class="text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
