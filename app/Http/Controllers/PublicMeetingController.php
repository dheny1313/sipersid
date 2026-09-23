<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class PublicMeetingController extends Controller
{
    //
    public function index()
    {
        // Menampilkan semua jadwal sidang dengan fitur pagination (10 data per halaman)
        $meetings = Meeting::orderBy('meeting_date', 'desc')->paginate(10);

        // Catatan: Anda perlu membuat view 'public.meetings.index' jika ingin menggunakan halaman ini
        return view('public.meetings.index', compact('meetings'));
    }

    public function show($slug)
    {
        // Mencari data sidang berdasarkan slug (URL).
        // Menggunakan 'with' untuk eager loading data relasi (lampiran & presensi) agar query lebih optimal.
        // firstOrFail() akan menampilkan halaman 404 jika slug tidak ditemukan.
        $meeting = Meeting::where('slug', $slug)
            ->with(['attachments', 'attendances'])
            ->firstOrFail();

        // Kirim data ke view public/meetings/show.blade.php
        return view('public.meetings.show', compact('meeting'));
    }
}
