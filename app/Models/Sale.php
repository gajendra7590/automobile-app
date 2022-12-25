<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

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
        'bike_description',
        'pre_gst_amount',
        'gst_amount',
        'discount_price',
        'grand_total',
        'branch_id',
        'customer_first_name',
        'customer_middle_name',
        'customer_last_name',
        'customer_father_name',
        'customer_address_line',
        'customer_state',
        'customer_district',
        'customer_city',
        'customer_zipcode',
        'customer_mobile_number',
        'customer_email_address',
        'payment_type',
        'is_exchange_avaliable',
        'hyp_financer',
        'hyp_financer_description',
        'purchase_visit_date',
        'purchase_est_date',
        'ex_showroom_price',
        'registration_amount',
        'insurance_amount',
        'hypothecation_amount',
        'accessories_amount',
        'other_charges',
        'total_amount',
        'active_status',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [];

    protected $casts = [];
}
