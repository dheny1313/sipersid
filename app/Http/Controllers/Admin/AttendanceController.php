<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class AttendanceController extends Controller
{
    // Menampilkan halaman tabel dinamis presensi
    public function index($id)
    {
        // Cari data meeting beserta data presensinya (jika sudah ada)
        $meeting = Meeting::with('attendances')->findOrFail($id);

        // Lempar ke view
        return view('admin.attendances.index', compact('meeting'));
    }


    public function show($id)
    {
        // Cari data meeting beserta data presensinya (jika sudah ada)
        $meeting = Meeting::with('attendances')->findOrFail($id);

        // Lempar ke view
        return view('admin.attendances.show', compact('meeting'));
    }

    // Memproses penyimpanan massal (Bulk Insert)
    public function store(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        // Validasi input array (bisa kosong jika admin menghapus semua baris)
        $request->validate([
            'attendances' => 'nullable|array',
            'attendances.*.member_name' => 'required|string|max:255',
            'attendances.*.fraksi' => 'required|string|max:255',
            'attendances.*.status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'attendances.*.remarks' => 'nullable|string',
        ]);

        // LOGIKA WIPE & REPLACE
        // 1. Hapus semua data presensi lama di sidang ini
        $meeting->attendances()->delete();

        // 2. Insert data presensi baru (jika array-nya ada isinya)
        if ($request->has('attendances')) {
            $meeting->attendances()->createMany($request->attendances);
        }

        return back()->with('success', 'Data presensi seluruh anggota berhasil disimpan/diperbarui.');
    }

    // Menampilkan DAFTAR meeting yang presensinya SEDANG DIBUKA
    public function listOpened()
    {
        // Ambil data sidang yang is_attendance_open = true (1)
        // withCount('attendances') untuk menghitung otomatis jumlah yang sudah presensi
        $meetings = Meeting::where('is_attendance_open', true)
            ->withCount('attendances')
            ->orderBy('meeting_date', 'desc')
            ->get();

        return view('admin.attendances.opened_list', compact('meetings'));
    }


   // ---------------------------------------------------------
    // 4. EXPORT EXCEL (UNDUH DATA)
    // ---------------------------------------------------------
    public function exportExcel($id)
    {
        $meeting = Meeting::findOrFail($id);
        $fileName = 'Rekap_Presensi_' . $meeting->slug . '.xlsx';

        return Excel::download(new AttendanceExport($id), $fileName);
    }

    // ---------------------------------------------------------
    // 5. IMPORT EXCEL (UNGGAH DATA)
    // ---------------------------------------------------------
    public function importExcel(Request $request, $id)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new AttendanceImport($id), $request->file('file_excel'));
            return back()->with('success', 'Data presensi dari Excel berhasil ditambahkan!');

        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris Excel ke-" . $failure->row() . ": " . implode(', ', $failure->errors());
            }

            return back()->with('import_errors', $errorMessages);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal! Pastikan file benar dan header kolomnya adalah: jenis_peserta, nama_lengkap, fraksi, status, keterangan.');
        }
    }
}
