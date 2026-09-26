<?php

namespace App\Imports;

use App\Models\Flight;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Wajib ditambahkan untuk membaca tanggal Excel

class FlightImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan baris ini ada nama penumpangnya (mencegah error baris kosong)
        if (!isset($row['nama_penumpang'])) {
            return null;
        }

        return new Flight([
            'passenger_name' => $row['nama_penumpang'],
            'sppd_number'    => $row['no_sppd'],
            'destination'    => $row['tujuan'],
            'airline'        => $row['maskapai'],
            'flight_number'  => $row['no_penerbangan'] ?? null,

            // Gunakan fungsi pembantu untuk tanggal agar kebal error
            'departure_time' => $this->parseDate($row['waktu_berangkat']),
            'return_time'    => $this->parseDate($row['waktu_kembali']),

        // Menangkap berbagai kemungkinan nama header dari Excel
            'ticket_code'    => $row['kode_booking_tiket'] ?? ($row['kode_booking'] ?? null),
            'ticket_image'   => null
        ]);
    }

    /**
     * Fungsi kustom untuk menangani Format Tanggal ajaib dari Excel
     */
    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            // Jika formatnya angka (Format Date bawaan Excel)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            }

            // Jika formatnya teks biasa (YYYY-MM-DD)
            return Carbon::parse($value);
        } catch (\Exception $e) {
            // Jika benar-benar tidak bisa dibaca, kembalikan tanggal saat ini
            // agar sistem tidak error (atau bisa diatur null)
            return now();
        }
    }
}
