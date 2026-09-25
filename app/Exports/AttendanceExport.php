<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk otomatis mengatur lebar kolom
use Maatwebsite\Excel\Concerns\WithStyles; // Untuk mengatur gaya/style (bold/warna)
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $meeting_id;

    public function __construct($meeting_id)
    {
        $this->meeting_id = $meeting_id;
    }

    public function collection()
    {
        return Attendance::where('meeting_id', $this->meeting_id)->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Peserta',
            'Nama Lengkap',
            'Fraksi',
            'Status',
            'Keterangan'
        ];
    }

    public function map($attendance): array
    {
        static $no = 1;
        return [
            $no++,
            $attendance->jenis_peserta,
            $attendance->member_name,
            $attendance->fraksi ?? '-',
            $attendance->status,
            $attendance->remarks ?? '-'
        ];
    }

    // Fungsi untuk memformat tabel Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header) dibuat Bold dan Background-nya abu-abu muda
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
        ];
    }
}
