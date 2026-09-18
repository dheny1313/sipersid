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
// HALAMAN PUBLIK
// -------------------------------------------------------------

// Halaman Utama / Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Sidang Publik
Route::get('/sidang', [PublicMeetingController::class, 'index'])->name('public.meetings.index');
Route::get('/sidang/{slug}', [PublicMeetingController::class, 'show'])->name('public.meetings.show');

// Halaman Berita Publik
Route::get('/berita', [PublicPostController::class, 'index'])->name('public.posts.index');
Route::get('/berita/{slug}', [PublicPostController::class, 'show'])->name('public.posts.show');

Route::get('/sidang/{slug}/presensi', [PublicAttendanceController::class, 'create'])->name('public.attendances.create');
Route::post('/sidang/{slug}/presensi', [PublicAttendanceController::class, 'store'])->name('public.attendances.store');


// -------------------------------------------------------------
// HALAMAN AUTH & ADMIN (Membutuhkan Login)
// -------------------------------------------------------------

// Profil bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group Route Khusus Fitur Admin & Sekretariat
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin (Sekarang URL-nya /admin/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Portal Berita
    Route::resource('posts', PostController::class);

    // CRUD Sidang (Jadwal & Hasil Sidang)
    Route::resource('meetings', MeetingController::class);
    Route::post('meetings/{meeting}/upload-attachment', [MeetingController::class, 'storeAttachment'])->name('meetings.attachment.store');
    Route::delete('attachments/{attachment}', [MeetingController::class, 'destroyAttachment'])->name('attachments.destroy');

    // Presensi Sidang Paripurna
    Route::get('meetings/{meeting}/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('meetings/{meeting}/attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    // Tambahkan di bawah route resource meetings
Route::patch('meetings/{meeting}/toggle-attendance', [MeetingController::class, 'toggleAttendance'])->name('meetings.toggle-attendance');

    // CRUD Data Penerbangan Rapat Luar Kota
    Route::resource('flights', FlightController::class);
});

require __DIR__.'/auth.php';
