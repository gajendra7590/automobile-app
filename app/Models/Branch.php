<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'branch_name',
        'branch_email',
        'branch_phone',
        'branch_phone2',
        'branch_address_line',
        'branch_city',
        'branch_district',
        'branch_state',
        'branch_county',
        'branch_pincode',
        'branch_more_detail',
        'gstin_number',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
