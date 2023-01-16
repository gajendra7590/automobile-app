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
        'witness_person_name',
        'witness_person_phone',
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
        'status',
        'created_by',
        'updated_by',
        'sp_account_id',
        'rto_account_id'
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
            return $query->where('branch_id', $branch_id);
        }
    }


    /**
     * Mapping with branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
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

    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'salesman_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id')
            ->select('id', 'bike_branch', 'bike_dealer', 'bike_brand', 'bike_model', 'bike_model_color')
            ->with([
                'branch' => function ($model) {
                    $model->select('id', 'branch_name');
                },
                'dealer' => function ($model) {
                    $model->select('id', 'company_name');
                },
                'brand' => function ($model) {
                    $model->select('id', 'name');
                },
                'model' => function ($model) {
                    $model->select('id', 'model_name');
                },
                'modelColor' => function ($model) {
                    $model->select('id', 'color_name');
                },
                'tyreBrand' => function ($model) {
                    $model->select('id', 'name');
                },
                'batteryBrand' => function ($model) {
                    $model->select('id', 'name');
                }
            ]);
    }
}
