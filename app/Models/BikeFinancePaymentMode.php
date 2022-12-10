<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeFinancePaymentMode extends Model
{
    use HasFactory;

    protected $table = 'bike_finance_payment_modes';

    protected $fillable = [
        'name',
        'display_name'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
