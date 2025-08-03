<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pickup_location',
        'drop_location',
        'start_date',
        'end_date',
        'booking_date',
        'number_of_persons',
        'contact',
        'email',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'booking_date' => 'date',
        'number_of_persons' => 'integer',
    ];
} 