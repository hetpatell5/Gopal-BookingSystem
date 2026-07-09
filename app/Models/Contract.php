<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['booking_number', 'party_name', 'contract_date', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
