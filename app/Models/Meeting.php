<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\MeetingAttachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Meeting extends Model
{

    protected static function booted()
    {
        static::creating(function ($meeting) {
            if (empty($meeting->slug)) {
                $meeting->slug = Str::slug($meeting->title . '-' . now()->timestamp);
            }
        });
    }
    //
    protected $fillable = [
        'title',
        'slug',
        'meeting_date',
        'location',
        'description',
        'result_summary',
        'status',
        'user_id',
        'is_attendance_open',
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

    // Tambahkan method casts
    protected function casts(): array
    {
        return [
            'meeting_date' => 'datetime', // Agar nanti di view bisa langsung: $meeting->meeting_date->format('d M Y')
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
