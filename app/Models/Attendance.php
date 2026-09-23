<?php

namespace App\Models;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // Tambahkan 'jenis_peserta' ke dalam fillable
    protected $fillable = [
        'meeting_id',
        'jenis_peserta',
        'member_name',
        'fraksi',
        'status',
        'remarks'
    ];


    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
