<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'bus_id', 'seat_number', 'passenger_name', 
        'passenger_mobile', 'traveler_name', 'traveler_number_plate',
        'ac_type', 'journey_date', 'bus_time', 'total_seats', 'per_seat_price', 
        'extra_passenger_amount', 'total_amount', 
        'payable_amount', 'payment_method', 'payment_collected_by',
        'pickup_stop', 'from_place', 'to_place', 'note', 'status',
        'commission_percentage', 'commission_amount', 'is_hisab_completed',
        'hisab_person_name', 'hisab_collection_date', 'hisab_mobile_number'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
