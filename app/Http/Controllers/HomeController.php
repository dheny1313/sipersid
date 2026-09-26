<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    // Tambahkan Request $request di dalam kurung agar bisa menangkap input pencarian
    public function index(Request $request)
    {
        // ----------------------------------------------------
        // 1. STATISTIK UNTUK KARTU MELAYANG (FLOATING CARDS)
        // ----------------------------------------------------
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Hitung total sidang bulan ini
        $totalMeetings = Meeting::whereMonth('meeting_date', $currentMonth)
                                ->whereYear('meeting_date', $currentYear)
                                ->count();

        // Hitung sidang yang sudah selesai bulan ini
        $completedMeetings = Meeting::where('status', 'Completed')
                                    ->whereMonth('meeting_date', $currentMonth)
                                    ->whereYear('meeting_date', $currentYear)
                                    ->count();

        // Hitung sidang yang berstatus Terjadwal/Menunggu bulan ini
        $scheduledMeetings = Meeting::where('status', 'Scheduled')
                                    ->whereMonth('meeting_date', $currentMonth)
                                    ->whereYear('meeting_date', $currentYear)
                                    ->count();

        // ----------------------------------------------------
        // 2. LOGIKA PENCARIAN DAN FILTER AGENDA SIDANG
        // ----------------------------------------------------
        $query = Meeting::query();

        // Jika user mengetikkan kata kunci di kotak pencarian
        if ($request->filled('search')) {
            // Wajib menggunakan function($q) agar orWhere tidak merusak filter status
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Jika user memilih dropdown status (Terjadwal / Selesai)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Eksekusi pengambilan data. Kita gunakan get() alih-alih take(6)
        // agar saat user mencari, semua hasil pencarian yang relevan muncul.
        $meetings = $query->orderBy('meeting_date', 'desc')->get();

        // Kirim semua variabel ke view home.blade.php
        return view('home', compact('totalMeetings', 'completedMeetings', 'scheduledMeetings', 'meetings'));
    }
}
