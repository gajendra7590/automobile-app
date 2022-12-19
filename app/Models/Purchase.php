<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'uuid',
        'bike_branch',
        'bike_dealer',
        'bike_brand',
        'bike_model',
        'bike_model_color',
        'bike_type',
        'bike_fuel_type',
        'break_type',
        'wheel_type',
        'dc_number',
        'dc_date',
        'vin_number',
        'vin_physical_status',
        'sku',
        'sku_description',
        'hsn_number',
        'model_number',
        'engine_number',
        'key_number',
        'service_book_number',
        'tyre_brand_name',
        'tyre_front_number',
        'tyre_rear_number',
        'battery_brand',
        'battery_number',
        'purchase_invoice_number',
        'purchase_invoice_amount',
        'purchase_invoice_date',
        'final_price',
        'sale_price',
        'bike_description',
        'status',
        'created_by',
        'updated_by'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
