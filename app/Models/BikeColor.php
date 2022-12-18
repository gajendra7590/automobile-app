<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeColor extends Model
{
    use HasFactory;

    protected $table = 'bike_colors';

    protected $fillable = [
        'color_name',
        'color_code'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
