<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class PublicAttendanceController extends Controller
{

public function create($slug)
    {
        $meeting = Meeting::where('slug', $slug)->firstOrFail();

        // Cek apakah admin sudah membuka presensi
        if (!$meeting->is_attendance_open) {
            abort(403, 'Maaf, akses presensi untuk sidang ini sedang ditutup atau belum dibuka oleh Admin.');
        }

        return view('public.attendances.create', compact('meeting'));
    }

    public function store(Request $request, $slug)
    {
        $meeting = Meeting::where('slug', $slug)->firstOrFail();

        if (!$meeting->is_attendance_open) {
            return back()->with('error', 'Presensi sudah ditutup.');
        }

        $request->validate([
            'member_name' => 'required|string|max:255',
            'fraksi' => 'required|string|max:255',
            'status' => 'required|in:Hadir,Izin,Sakit',
        ]);

        $meeting->attendances()->create($request->all());

        return back()->with('success', 'Presensi Anda berhasil dicatat. Terima kasih.');
    }
}
