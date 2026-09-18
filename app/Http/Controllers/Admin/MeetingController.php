<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $meetings = Meeting::orderBy('meeting_date', 'desc')->get();
        return view('admin.meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('admin.meetings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        Meeting::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'meeting_date' => $request->meeting_date,
            'location' => $request->location,
            'description' => $request->description,
            'user_id' => auth()->id() ?? 1, // Default ke user ID 1 jika auth bermasalah
        ]);

        return redirect()->route('admin.meetings.index')->with('success', 'Jadwal sidang berhasil ditambahkan.');
    }

    public function edit(Meeting $meeting)
    {
        // Memuat data sidang beserta lampirannya
        $meeting->load('attachments');
        return view('admin.meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $meeting->update([
            'status' => $request->status,
            'result_summary' => $request->result_summary,
        ]);

        return back()->with('success', 'Hasil sidang berhasil diperbarui.');
    }

    // --- FUNGSI UNTUK LAMPIRAN DOKUMEN ---

    public function storeAttachment(Request $request, Meeting $meeting)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Maksimal 10MB
            'file_title' => 'required|string|max:255',
        ]);

        // Simpan file ke folder storage/app/public/meeting-attachments
        $path = $request->file('file')->store('meeting-attachments', 'public');

        $meeting->attachments()->create([
            'file_title' => $request->file_title,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Dokumen lampiran berhasil diunggah.');
    }

    public function destroyAttachment(MeetingAttachment $attachment)
    {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Hapus data dari database
        $attachment->delete();

        return back()->with('success', 'Dokumen lampiran berhasil dihapus.');
    }


    public function toggleAttendance(Meeting $meeting)
    {
        $meeting->update([
            'is_attendance_open' => !$meeting->is_attendance_open
        ]);

        $status = $meeting->is_attendance_open ? 'dibuka' : 'ditutup';
        return back()->with('success', "Akses presensi berhasil $status.");
    }
}
