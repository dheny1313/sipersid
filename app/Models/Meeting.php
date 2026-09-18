<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\MeetingAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    //
    protected $fillable = [
        'title', 'slug', 'meeting_date', 'location',
        'description', 'result_summary', 'status', 'user_id'
    ];

    // Relasi ke User (Admin yang menginput)
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Lampiran Dokumen (Multi-file)
    public function attachments(): HasMany
    {
        return $this->hasMany(MeetingAttachment::class);
    }

    // Relasi ke Presensi Sidang
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
