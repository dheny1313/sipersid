<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMeetingController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicAttendanceController;

// -------------------------------------------------------------
// 1. HALAMAN PUBLIK
// -------------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sidang', [PublicMeetingController::class, 'index'])->name('public.meetings.index');
Route::get('/sidang/{slug}', [PublicMeetingController::class, 'show'])->name('public.meetings.show');

Route::get('/berita', [PublicPostController::class, 'index'])->name('public.posts.index');
Route::get('/berita/{slug}', [PublicPostController::class, 'show'])->name('public.posts.show');

//presensi public
// Artinya: Maksimal 5 kali submit (request) dalam waktu 1 menit untuk setiap alamat IP.
Route::post('/sidang/{slug}/presensi', [PublicAttendanceController::class, 'store'])
    ->name('public.attendances.store');
//->middleware('throttle:5,1');

// -------------------------------------------------------------
// 2. HALAMAN AUTH & PROFIL (Wajib Login)
// -------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------------------------------------------
// 3. HALAMAN ADMIN & SEKRETARIAT (Wajib Login & Prefix Admin)
// -------------------------------------------------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Portal Berita
    Route::resource('posts', PostController::class);

    // --- RUTE KUSTOM MEETING (HARUS DI ATAS RESOURCE) ---

    // Presensi Sidang

    // --- MENU KHUSUS DAFTAR PRESENSI TERBUKA ---
    Route::get('/attendances/opened', [AttendanceController::class, 'listOpened'])->name('attendances.opened');

    // (Ini rute lama Anda yang sudah ada, biarkan saja di bawah rute baru)
    Route::get('meetings/{meeting}/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('meetings/{meeting}/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('meetings/{meeting}/attendances', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::post('meetings/{meeting}/attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    // Export & Import Presensi Excel
    Route::get('meetings/{meeting}/attendances/export', [AttendanceController::class, 'exportExcel'])->name('attendances.export');
    Route::post('meetings/{meeting}/attendances/import', [AttendanceController::class, 'importExcel'])->name('attendances.import');

    // Fitur Tambahan Meeting
    Route::post('meetings/{meeting}/upload-attachment', [MeetingController::class, 'storeAttachment'])->name('meetings.attachment.store');
    Route::patch('meetings/{meeting}/toggle-attendance', [MeetingController::class, 'toggleAttendance'])->name('meetings.toggle-attendance');

    // Hapus Lampiran
    Route::delete('attachments/{attachment}', [MeetingController::class, 'destroyAttachment'])->name('attachments.destroy');

    // --- ROUTE RESOURCE UTAMA ---
    // CRUD Sidang (Otomatis generate index, create, store, show, edit, update, destroy)
    Route::resource('meetings', MeetingController::class);

    // CRUD Data Penerbangan Rapat Luar Kota
    Route::resource('flights', FlightController::class);
});

require __DIR__ . '/auth.php';
