<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'u_countries';

    protected $fillable = [
        'country_name',
        'country_code',
    ];

    protected  $hidden = [];

    protected $casts = [];
}
