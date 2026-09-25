<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; // Tambahan untuk validasi

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $meeting_id;

    public function __construct($meeting_id)
    {
        $this->meeting_id = $meeting_id;
    }

    public function model(array $row)
    {
        return new Attendance([
            'meeting_id'    => $this->meeting_id,
            'jenis_peserta' => ucfirst($row['jenis_peserta'] ?? 'DPR'),
            'member_name'   => $row['nama_lengkap'],
            'fraksi'        => $row['fraksi'] ?? null,
            'status'        => ucfirst($row['status'] ?? 'Hadir'),
            'remarks'       => $row['keterangan'] ?? null,
        ]);
    }

    // Fungsi Aturan Validasi
    public function rules(): array
    {
        return [
            'nama_lengkap'  => 'required|string',
            // .in: membatasi teks yang boleh diinput
            'jenis_peserta' => 'nullable|in:DPR,Umum,dpr,umum',
            'status'        => 'nullable|in:Hadir,Izin,Sakit,Alpa,hadir,izin,sakit,alpa',
        ];
    }

    // Fungsi Pesan Error Kustom (Biar bahasa Indonesia)
    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Kolom "nama_lengkap" tidak boleh kosong.',
            'jenis_peserta.in'      => 'Kolom "jenis_peserta" harus berisi DPR atau Umum.',
            'status.in'             => 'Kolom "status" harus berisi Hadir, Izin, Sakit, atau Alpa.',
        ];
    }
}
