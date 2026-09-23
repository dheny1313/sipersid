@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Presensi Anggota</h1>
        <p class="text-sm text-gray-500 mt-1">Agenda: <span class="font-semibold">{{ $meeting->title }}</span></p>
    </div>
    <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="text-gray-500 hover:text-gray-700 underline font-medium">&larr; Kembali ke Sidang</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow border-t-4 border-green-500">
    <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Formulir Pengisian Cepat</h2>
            <button type="button" onclick="addRow()" class="bg-blue-50 text-blue-600 px-4 py-2 rounded text-sm font-bold border border-blue-200 hover:bg-blue-100 transition shadow-sm">
                + Tambah Baris Anggota
            </button>
        </div>

        <form action="{{ route('admin.attendances.store', $meeting->id) }}" method="POST">
            @csrf

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm">
                            <th class="p-3 border-b w-12 text-center">No</th>
                            <th class="p-3 border-b">Nama Anggota / Peserta</th>
                            <th class="p-3 border-b w-48">Fraksi / Jabatan</th>
                            <th class="p-3 border-b w-40">Status</th>
                            <th class="p-3 border-b w-64">Keterangan</th>
                            <th class="p-3 border-b w-16 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-tbody">
                        {{-- Jika data sudah ada di database, tampilkan sebagai baris input --}}
                        @foreach($meeting->attendances as $index =>$att)
                        <tr class="hover:bg-gray-50 attendance-row">
                            <td class="p-3 border-b text-center row-number text-sm font-medium text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3 border-b">
                                <input type="text" name="attendances[{{ $index }}][member_name]" value="{{ $att->member_name }}" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            </td>
                            <td class="p-3 border-b">
                                <input type="text" name="attendances[{{ $index }}][fraksi]" value="{{ $att->fraksi }}" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            </td>
                            <td class="p-3 border-b">
                                <select name="attendances[{{ $index }}][status]" class="w-full border rounded p-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                                    <option value="Hadir" {{ $att->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Izin" {{ $att->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Sakit" {{ $att->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="Alpa" {{ $att->status == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </td>
                            <td class="p-3 border-b">
                                <input type="text" name="attendances[{{ $index }}][remarks]" value="{{ $att->remarks }}" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Isi jika izin/sakit...">
                            </td>
                            <td class="p-3 border-b text-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded transition">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Jika tabel kosong, pesan ini muncul (dihandle via JS) -->
            <div id="empty-state" class="hidden text-center py-8 text-gray-500 border border-t-0 border-gray-200 rounded-b-lg">
                Belum ada data peserta. Klik "Tambah Baris Anggota" di atas.
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-8 py-2.5 rounded font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition transform hover:-translate-y-0.5">
                    Simpan Seluruh Presensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT JAVASCRIPT UNTUK TABEL DINAMIS -->
<script>
    // Menyimpan indeks array agar unique setiap nambah baris baru
    let arrayIndex = {{ count($meeting->attendances) > 0 ? count($meeting->attendances) : 0 }};
    const tbody = document.getElementById('attendance-tbody');
    const emptyState = document.getElementById('empty-state');

    // Cek apakah tabel kosong saat pertama dimuat
    checkEmptyState();

    // Fungsi menambah baris
    function addRow() {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 attendance-row';

        tr.innerHTML = `
            <td class="p-3 border-b text-center row-number text-sm font-medium text-gray-500"></td>
            <td class="p-3 border-b">
                <input type="text" name="attendances[${arrayIndex}][member_name]" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Ketik nama..." required>
            </td>
            <td class="p-3 border-b">
                <input type="text" name="attendances[${arrayIndex}][fraksi]" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Ketik fraksi..." required>
            </td>
            <td class="p-3 border-b">
                <select name="attendances[${arrayIndex}][status]" class="w-full border rounded p-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpa">Alpa</option>
                </select>
            </td>
            <td class="p-3 border-b">
                <input type="text" name="attendances[${arrayIndex}][remarks]" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Isi jika izin/sakit...">
            </td>
            <td class="p-3 border-b text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded transition">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        arrayIndex++;
        updateRowNumbers();
        checkEmptyState();
    }

    // Fungsi menghapus baris
    function removeRow(btn) {
        const row = btn.closest('tr');
        row.remove();
        updateRowNumbers();
        checkEmptyState();
    }

    // Fungsi merapikan nomor urut (1, 2, 3...)
    function updateRowNumbers() {
        const rows = document.querySelectorAll('.row-number');
        rows.forEach((td, index) => {
            td.textContent = index + 1;
        });
    }

    // Fungsi memunculkan peringatan jika tabel kosong
    function checkEmptyState() {
        if(tbody.children.length === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
</script>
@endsection
