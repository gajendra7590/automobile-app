<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'branch_manager_name',
        'branch_manager_phone',
        'branch_name',
        'branch_phone',
        'branch_address_line',
        'branch_city',
        'branch_district',
        'branch_state',
        'branch_county',
        'branch_pincode',
        'branch_more_detail'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
