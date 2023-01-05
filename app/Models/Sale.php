<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//
use App\Traits\CommonHelper;

class Sale extends Model
{
    use CommonHelper;

    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'sale_uuid',
        'branch_id',
        'purchase_id',
        'quotation_id',
        'bike_branch',
        'bike_dealer',
        'bike_brand',
        'bike_model',
        'bike_color',
        'bike_type',
        'bike_fuel_type',
        'break_type',
        'wheel_type',
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
        'bike_description',
        'customer_gender',
        'customer_name',
        'customer_relationship',
        'customer_guardian_name',
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
        'sp_account_id',
    ];

    protected $hidden = [];

    protected $casts = [];


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if ($branch_id != '0' || $branch_id != 'null') {
            return $query->where('bike_branch', $branch_id);
        }
    }


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
        return $this->belongsTo(BikeColor::class, 'bike_color');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'customer_state');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'customer_city');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'customer_district');
    }
}
