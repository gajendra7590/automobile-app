<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeCategory extends Model
{
    use HasFactory;

    protected $table = 'bike_categories';

    protected $fillable = [
        'name',
        'description'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
