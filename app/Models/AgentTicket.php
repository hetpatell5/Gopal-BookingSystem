<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTicket extends Model
{
    protected $fillable = [
        'sale_date',
        'agent_name',
        'bus_name',
        'total_seats',
        'seat_price',
        'total_amount',
        'commission_percentage',
        'commission_amount',
        'net_amount',
    ];
}
