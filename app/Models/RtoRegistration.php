<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoRegistration extends Model
{
    use HasFactory;

    protected $table = 'rto_registration';

    protected $fillable = [
        'sale_id',
        'rto_agent_id',
        'contact_name',
        'contact_mobile_number',
        'contact_address_line',
        'contact_state_id',
        'contact_district_id',
        'contact_city_id',
        'contact_zipcode',
        'sku',
        'financer_name',
        'financer_id',
        'gst_rto_rates_id',
        'gst_rto_rates_percentage',
        'ex_showroom_amount',
        'tax_amount',
        'hyp_amount',
        'tr_amount',
        'fees',
        'total_amount',
        'payment_amount',
        'payment_date',
        'outstanding',
        'rc_number',
        'rc_status',
        'submit_date',
        'recieved_date',
        'customer_given_date',
        'bike_number',
        'remark',
        'created_by',
        'updated_by',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function contact_city()
    {
        return $this->belongsTo(City::class, 'contact_city_id');
    }
}
