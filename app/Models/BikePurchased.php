<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikePurchased extends Model
{
    use HasFactory;

    protected $table = 'bike_purchased';

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


    /**
     * Mapping with branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'bike_branch');
    }

    /**
     * Mapping with dealers
     */
    public function dealer()
    {
        return $this->belongsTo(BikeDealer::class, 'bike_dealer');
    }

    /**
     * Mapping with brand
     */
    public function brand()
    {
        return $this->belongsTo(BikeBrand::class, 'bike_brand');
    }

    /**
     * Mapping with model
     */
    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'bike_model');
    }

    /**
     * Mapping with color
     */
    public function modelColor()
    {
        return $this->belongsTo(BikeColor::class, 'bike_model_color');
    }
}
