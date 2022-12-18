<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'u_districts';

    protected $fillable = [
        'state_id',
        'district_name',
        'district_code',
    ];

    protected  $hidden = [];

    protected $casts = [];
}
