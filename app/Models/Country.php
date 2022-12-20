<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'u_countries';

    protected $fillable = [
        'country_name',
        'country_code',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
