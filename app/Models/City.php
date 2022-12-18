<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'u_cities';

    protected $fillable = [
        'district_id',
        'city_name',
        'city_code'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
