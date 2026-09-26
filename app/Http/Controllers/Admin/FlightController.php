<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\FlightExport;
use App\Imports\FlightImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::orderBy('departure_time', 'desc')->get();
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        return view('admin.flights.create');
    }

    public function store(Request $request)
    {
        // Masukkan SEMUA field form ke dalam validasi agar datanya tertangkap
        $validated = $request->validate([
            'passenger_name' => 'required|string|max:255',
            'sppd_number'    => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'airline'        => 'required|string|max:255',
            'flight_number'  => 'nullable|string|max:255',
            'ticket_code'    => 'nullable|string|max:255',
            'departure_time' => 'required|date',
            'return_time'    => 'nullable|date',
            'ticket_image'   => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048', // File maks 2MB
        ]);

        // Logika Penyimpanan File
        if ($request->hasFile('ticket_image')) {
            $folderPath = 'flights/tickets/' . date('Y/m');
            $validated['ticket_image'] = $request->file('ticket_image')->store($folderPath, 'public');
        }

        // Simpan ke database
        Flight::create($validated);

        return redirect()->route('admin.flights.index')->with('success', 'Data penerbangan dan tiket berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);

        // Masukkan SEMUA field form ke dalam validasi
        $validated = $request->validate([
            'passenger_name' => 'required|string|max:255',
            'sppd_number'    => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'airline'        => 'required|string|max:255',
            'flight_number'  => 'nullable|string|max:255',
            'ticket_code'    => 'nullable|string|max:255',
            'departure_time' => 'required|date',
            'return_time'    => 'nullable|date',
            'ticket_image'   => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Logika Update File
        if ($request->hasFile('ticket_image')) {
            // Hapus file lama jika ada
            if ($flight->ticket_image && Storage::disk('public')->exists($flight->ticket_image)) {
                Storage::disk('public')->delete($flight->ticket_image);
            }

            // Simpan file baru
            $folderPath = 'flights/tickets/' . date('Y/m');
            $validated['ticket_image'] = $request->file('ticket_image')->store($folderPath, 'public');
        }

        // Update database
        $flight->update($validated);

        return redirect()->route('admin.flights.index')->with('success', 'Data penerbangan berhasil diperbarui.');
    }

    public function edit($id)
    {
        $flight = Flight::findOrFail($id);
        return view('admin.flights.edit', compact('flight'));
    }



    public function destroy($id)
    {
        $flight = Flight::findOrFail($id);

        // Hapus file fisik tiket sebelum menghapus data database
        if ($flight->ticket_image && Storage::disk('public')->exists($flight->ticket_image)) {
            Storage::disk('public')->delete($flight->ticket_image);
        }

        $flight->delete();

        return redirect()->route('admin.flights.index')->with('success', 'Data penerbangan berhasil dihapus.');
    }

    // --- FITUR IMPORT & EXPORT EXCEL ---

    public function exportExcel()
    {
        return Excel::download(new FlightExport, 'Data_Penerbangan_Dinas.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new FlightImport, $request->file('file_excel'));
            return back()->with('success', 'Data penerbangan berhasil diimpor!');
        } catch (ValidationException $e) {
            // (Logika penangkap error baris Excel sama seperti di modul Presensi)
            return back()->with('error', 'Gagal mengimpor. Ada format data yang salah di dalam Excel.');
        } catch (\Exception $e) {
            // Tampilkan error aslinya agar kita tahu salahnya di mana
            return back()->with('error', 'Error dari sistem: ' . $e->getMessage());
        }
    }
}
