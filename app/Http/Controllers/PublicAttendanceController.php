<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter; // <-- Wajib ditambahkan

class PublicAttendanceController extends Controller
{
    public function store(Request $request, $slug)
    {
        // 1. BUAT KUNCI UNIK (Berdasarkan IP Address dan Slug Sidang)
        $throttleKey = 'presensi-umum:' . $slug . ':' . $request->ip();

        // // 2. CEK APAKAH SUDAH MELEBIHI BATAS (Maksimal 5 kali)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Pisahkan pesan error dan detik cooldown-nya
            return back()
                ->with('error', 'Anda mengirim data terlalu cepat.')
                ->with('cooldown_seconds', $seconds);
        }

        // 3. TAMBAHKAN HITUNGAN (Setiap kali tombol submit ditekan, hitungan bertambah)
        // Waktu blokir di-set selama 60 detik (1 menit)
        RateLimiter::hit($throttleKey, 60);


        // --- LOGIKA HONEYPOT (JEBAKAN BOT) ---
        if ($request->filled('username_jebakan')) {
            return back()->with('success', 'Terima kasih, presensi kehadiran umum Anda telah tercatat.');
        }


        // --- LOGIKA ASLI PRESENSI ---
        $meeting = Meeting::where('slug', $slug)->firstOrFail();

        if (!$meeting->is_attendance_open) {
            return back()->with('error', 'Mohon maaf, sesi presensi untuk agenda ini sedang ditutup.');
        }

        if ($request->jenis_peserta == 'Umum') {
            $request->validate([
                'member_name' => 'required|string|max:255',
                'remarks' => 'required|string|max:255',
            ]);

            $meeting->attendances()->create([
                'jenis_peserta' => 'Umum',
                'member_name' => $request->member_name,
                'fraksi' => 'Umum',
                'status' => 'Hadir',
                'remarks' => $request->remarks
            ]);

            return back()->with('success', 'Terima kasih, presensi kehadiran umum Anda telah tercatat.');
        }

        if ($request->jenis_peserta == 'DPR' && auth()->check()) {
            $request->validate([
                'fraksi' => 'required|string|max:255',
                'status' => 'required|in:Hadir,Izin,Sakit',
            ]);

            $meeting->attendances()->create([
                'jenis_peserta' => 'DPR',
                'member_name' => auth()->user()->name,
                'fraksi' => $request->fraksi,
                'status' => $request->status,
                'remarks' => $request->remarks
            ]);

            return back()->with('success', 'Presensi resmi Anggota Dewan berhasil dicatat.');
        }

        return back()->with('error', 'Terjadi kesalahan sistem atau akses tidak valid.');
    }
}
