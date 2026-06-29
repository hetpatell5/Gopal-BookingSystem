<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'name', 'plate_number', 'bus_type', 'total_seats', 'ac_non_ac', 'seat_layout'
    ];

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }
}
