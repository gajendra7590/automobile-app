<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeFinanceType extends Model
{
    use HasFactory;

    protected $table = 'bike_finance_types';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    protected  $hidden = [];

    protected $casts = [];
}
