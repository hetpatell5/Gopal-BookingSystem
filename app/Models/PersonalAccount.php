<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccount extends Model
{
    protected $fillable = [
        'ref_no',
        'slip_date',
        'bus_name',
        'bus_number',
        'manager_name',
        'grease_cost',
        'tax',
        'toll_tax',
        'diesel_liter',
        'diesel_rate',
        'diesel_amount',
        'driver_salary',
        'conductor_salary',
        'parking',
        'parchuran',
        'total_amount'
    ];
}
