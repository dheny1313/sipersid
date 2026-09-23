<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    //
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category',
        'is_published',
        'user_id'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean', // Memastikan is_published bernilai true/false
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

    protected static function booted()
    {
        static::creating(function ($meeting) {
            if (empty($meeting->slug)) {
                $meeting->slug = Str::slug($meeting->title . '-' . now()->timestamp);
            }
        });
    }
}
