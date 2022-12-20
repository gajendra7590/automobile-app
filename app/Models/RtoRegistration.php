<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoRegistration extends Model
{
    use HasFactory;

    protected $table = 'rto_registration';

    protected $fillable = [
        'bike_purchased_id',
        'bike_sold_id',
        'ex_showroom_amount',
        'tax',
        'hyp',
        'tr_number',
        'fees',
        'total_amount',
        'remark',
        'submit_date',
        'rc_number',
        'rc_status',
        'recieved_date',
        'customer_given_date',
        'bike_number',
        'created_by',
        'updated_by'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
