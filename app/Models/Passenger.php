<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'bus_id', 'seat_number', 'passenger_name', 
        'passenger_mobile', 'traveler_name', 'traveler_number_plate',
        'ac_type', 'journey_date', 'bus_time', 'total_seats', 'per_seat_price', 'total_amount', 
        'payable_amount', 'pickup_stop', 'from_place', 'to_place', 'note', 'status',
        'commission_percentage', 'commission_amount', 'is_hisab_completed'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
