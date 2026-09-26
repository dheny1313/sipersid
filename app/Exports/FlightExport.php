<?php

namespace App\Exports;

use App\Models\Flight;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FlightExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengecualikan kolom gambar agar Excel bersih, hanya ekspor data logistik
        return Flight::select('passenger_name', 'sppd_number', 'destination', 'airline', 'flight_number', 'departure_time', 'return_time', 'ticket_code')->get();
    }

    public function headings(): array
    {
        return ['Nama Penumpang', 'No. SPPD', 'Tujuan', 'Maskapai', 'No. Penerbangan', 'Waktu Berangkat', 'Waktu Kembali', 'Kode Booking/Tiket'];
    }
}
