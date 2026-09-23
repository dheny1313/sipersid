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

    public function edit($id)
    {
        // Cari manual yang terbukti berhasil, dan langsung muat relasi lampirannya
        $meeting = Meeting::with('attachments')->findOrFail($id);

        // Panggil halaman view
        return view('admin.meetings.edit', compact('meeting'));
    }


    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        $meeting->update([
            'title' => $request->title,
            // Optional: Update slug jika judul berubah (bisa dilewati jika ingin slug tetap)
            // 'slug' => Str::slug($request->title) . '-' . time(),
            'meeting_date' => $request->meeting_date,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status ?? $meeting->status,
            'result_summary' => $request->result_summary,
        ]);

        return back()->with('success', 'Data & Hasil sidang berhasil diperbarui.');
    }

    // --- FUNGSI UNTUK LAMPIRAN DOKUMEN ---
    // --- FUNGSI UNTUK LAMPIRAN DOKUMEN (MULTI-UPLOAD SEKALIGUS) ---

    public function storeAttachment(Request $request, $id)
    {
        // 1. Cari data berdasarkan ID manual (Bypass error 404)
        $meeting = Meeting::findOrFail($id);

        // 2. Validasi input harus berupa array (karena multiple files)
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // Maksimal 10MB per file
        ]);

        // 3. Looping semua file yang diupload dan simpan
        foreach ($request->file('files') as $file) {
            $path = $file->store('meeting-attachments', 'public');

            $meeting->attachments()->create([
                'file_title' => $file->getClientOriginalName(), // Otomatis pakai nama asli file
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);
        }

        return back()->with('success', 'Semua dokumen lampiran berhasil diunggah.');
    }

    public function destroyAttachment($id)
    {
        // Cari attachment manual
        $attachment = MeetingAttachment::findOrFail($id);

        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Hapus data dari database
        $attachment->delete();

        return back()->with('success', 'Dokumen lampiran berhasil dihapus.');
    }

    // --- FUNGSI UNTUK PRESENSI ---

    public function toggleAttendance($id)
    {
        // Cari data manual
        $meeting = Meeting::findOrFail($id);

        $meeting->update([
            'is_attendance_open' => !$meeting->is_attendance_open
        ]);

        $status = $meeting->is_attendance_open ? 'dibuka' : 'ditutup';
        return back()->with('success', "Akses presensi berhasil $status.");
    }

    public function destroy(Meeting $meeting)
    {
        // 1. Hapus semua file fisik lampiran terlebih dahulu
        foreach ($meeting->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        // 2. Data di tabel meeting_attachments & attendances biasanya akan otomatis
        // terhapus jika Anda menggunakan `onDelete('cascade')` di Migration database.
        // Jika tidak, hapus manual: $meeting->attachments()->delete();

        // 3. Hapus data meeting utama
        $meeting->delete();

        return redirect()->route('admin.meetings.index')->with('success', 'Jadwal sidang beserta lampirannya berhasil dihapus.');
    }
}
