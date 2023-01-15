<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $table = 'brokers';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'mobile_number2',
        'aadhar_card',
        'pan_card',
        'date_of_birth',
        'highest_qualification',
        'gender',
        'address_line',
        'city',
        'district',
        'state',
        'zipcode',
        'joined_at',
        'more_details',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
