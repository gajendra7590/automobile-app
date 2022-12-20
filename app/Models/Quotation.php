<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'bike_purchase_id',
        'customer_first_name',
        'customer_middle_name',
        'customer_last_name',
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
        'bike_brand',
        'bike_model',
        'bike_color',
        'ex_showroom_price',
        'registration_amount',
        'insurance_amount',
        'hypothecation_amount',
        'accessories_amount',
        'other_charges',
        'total_amount',
        'active_status',
        'created_by',
        'updated_by'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function state()
    {
        return $this->belongsTo(State::class, 'customer_state');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'customer_district');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'customer_city');
    }

    public function brand()
    {
        return $this->belongsTo(BikeBrand::class, 'bike_brand');
    }

    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'bike_model');
    }

    public function color()
    {
        return $this->belongsTo(BikeColor::class, 'bike_color');
    }

    public function financer()
    {
        return $this->belongsTo(BankFinancer::class, 'hyp_financer');
    }
}
