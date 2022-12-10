<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikePurchased extends Model
{
    use HasFactory;

    protected $table = 'bike_purchased';

    protected $fillable = [
        'bike_category',
        'bike_type',
        'bike_fuel_type',
        'bike_dealer',
        'bike_branch',
        'dc_no',
        'model_number',
        'engine_number',
        'key_number',
        'service_book_number',
        'tyre_front_number',
        'tyre_rear_number',
        'battery_number',
        'purchase_price',
        'final_price',
        'sale_price',
        'purchase_date',
        'bike_description',
        'status',
    ];

    protected  $hidden = [];

    protected $casts = [];
}
