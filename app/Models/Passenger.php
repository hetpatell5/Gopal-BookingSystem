<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'bus_id', 'seat_number', 'village_name', 'passenger_name', 
        'passenger_mobile', 'traveler_name', 'traveler_number_plate',
        'ac_type', 'journey_date', 'bus_time', 'total_seats', 'total_amount', 
        'payable_amount', 'pickup_stop', 'note', 'status',
        'commission_percentage', 'commission_amount'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
