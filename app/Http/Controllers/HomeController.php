<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Mendapatkan bulan dan tahun saat ini untuk statistik
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

        // Ambil 6 agenda sidang terbaru untuk ditampilkan di Card Landing Page
        $meetings = Meeting::orderBy('meeting_date', 'desc')->take(6)->get();

        // Kirim data ke view home.blade.php
        return view('home', compact('totalMeetings', 'completedMeetings', 'meetings'));
    }
}
