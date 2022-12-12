<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeBrand extends Model
{
    use HasFactory;

    protected $table = 'bike_brands';

    protected $fillable = [
        'name',
        'description'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
