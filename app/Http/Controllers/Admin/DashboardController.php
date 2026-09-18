<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Meeting;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index ()
    {
        $totalMeetings = Meeting::count();
        $upcomingMeetings = Meeting::where('status', 'Scheduled')->count();
        $totalFlights = Flight::count();

        // Memastikan view resources/views/admin/dashboard.blade.php sudah Anda buat
        return view('admin.dashboard', compact('totalMeetings', 'upcomingMeetings', 'totalFlights'));
    }
}
