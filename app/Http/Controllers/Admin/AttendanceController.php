<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //
    public function index(Meeting $meeting)
    {
        return view('admin.attendances.index', compact('meeting'));
    }

    public function store(Request $request, Meeting $meeting)
    {
        // Validasi input array
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.member_name' => 'required|string',
            'attendances.*.fraksi' => 'required|string',
            'attendances.*.status' => 'required|in:Hadir,Izin,Sakit,Alpa',
        ]);

        // Hapus data presensi lama (jika ada) agar tidak terjadi duplikasi saat diupdate ulang
        $meeting->attendances()->delete();

        // Simpan data presensi baru
        foreach ($request->attendances as $attendanceData) {
            $meeting->attendances()->create($attendanceData);
        }

        return redirect()->route('admin.meetings.index')->with('success', 'Presensi sidang berhasil disimpan.');
    }
}
