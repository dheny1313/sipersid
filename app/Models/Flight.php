<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    //
    protected $fillable = [
        'passenger_name',
        'sppd_number',
        'destination',
        'airline',
        'flight_number',
        'departure_time',
        'return_time',
        'ticket_code',
        'ticket_image' // <-- Tambahkan ini
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'return_time' => 'datetime',
        ];
    }
}
