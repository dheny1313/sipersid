<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Menampilkan halaman tabel dinamis presensi
    public function index($id)
    {
        // Cari data meeting beserta data presensinya (jika sudah ada)
        $meeting = Meeting::with('attendances')->findOrFail($id);

        // Lempar ke view
        return view('admin.attendances.index', compact('meeting'));
    }


    public function show($id)
    {
        // Cari data meeting beserta data presensinya (jika sudah ada)
        $meeting = Meeting::with('attendances')->findOrFail($id);

        // Lempar ke view
        return view('admin.attendances.show', compact('meeting'));
    }

    // Memproses penyimpanan massal (Bulk Insert)
    public function store(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        // Validasi input array (bisa kosong jika admin menghapus semua baris)
        $request->validate([
            'attendances' => 'nullable|array',
            'attendances.*.member_name' => 'required|string|max:255',
            'attendances.*.fraksi' => 'required|string|max:255',
            'attendances.*.status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'attendances.*.remarks' => 'nullable|string',
        ]);

        // LOGIKA WIPE & REPLACE
        // 1. Hapus semua data presensi lama di sidang ini
        $meeting->attendances()->delete();

        // 2. Insert data presensi baru (jika array-nya ada isinya)
        if ($request->has('attendances')) {
            $meeting->attendances()->createMany($request->attendances);
        }

        return back()->with('success', 'Data presensi seluruh anggota berhasil disimpan/diperbarui.');
    }
}
