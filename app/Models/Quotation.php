<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $appends = ['cust_name'];

    protected $fillable = [
        'branch_id',
        'salesman_id',
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
        'customer_mobile_number_alt',
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
        'status',
        'close_note',
        'closed_by',
        'created_by',
        'updated_by',
    ];

    protected  $hidden = [];

    protected $casts = [];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = 'enq/' . date('dmY') . '/' . rand(11111, 55555);
        });
    }

    public function getGenderAttribute()
    {
        switch ($this->customer_gender) {
            case 1:
                return 'Mr.';
                break;
            case 2:
                return 'Mrs.';
                break;
            case 3:
                return 'Miss';
                break;
            default:
                return $this->customer_gender;
                break;
        }
    }

    public function getRelationshipAttribute()
    {
        switch ($this->customer_relationship) {
            case 1:
                return 'S/O';
                break;
            case 2:
                return 'W/O';
                break;
            case 3:
                return 'D/O';
                break;
            default:
                return $this->customer_relationship;
                break;
        }
    }

    public function getPaymentTypeAccessorAttribute()
    {
        switch ($this->payment_type) {
            case 1:
                return 'By Cash';
                break;
            case 2:
                return 'Bank Finance';
                break;
            case 3:
                return 'Personal Finance';
                break;
            default:
                return $this->payment_type;
                break;
        }
    }

    public function getCustNameAttribute()
    {
        $str = '';
        if (!empty($this->customer_gender)) {
            $str .= $this->gender . ' ';
        }

        if (!empty($this->customer_name)) {
            $str .= $this->customer_name . ' ';
        }

        if (!empty($this->customer_relationship)) {
            $str .= $this->relationship . ' ';
        }

        if (!empty($this->customer_guardian_name)) {
            $str .= $this->customer_guardian_name;
        }
        return $str;
    }

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

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'salesman_id');
    }
}
