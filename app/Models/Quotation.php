<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'customer_name',
        'customer_mobile_number',
        'customer_address',
        'hyp',
        'model',
        'color',
        'ex_showroom_price',
        'registration_amount',
        'insurance_amount',
        'hypothecation',
        'accessories_amount',
        'other',
        'total_amount',
        'bank_info'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
