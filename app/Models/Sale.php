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

    protected $appends = ['cust_name', 'serial_number'];

    protected $fillable = [
        'sno',
        'year',
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
        'rto_account_id',
        'sale_date'
    ];

    protected $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = Sale::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if ( isset($findModel->year) && ($year == $findModel->year)) {
                $sno = ($findModel->sno) + 1;
            }
            $model->year = $year;
            $model->sno = $sno;
        });
    }

    public function getSerialNumberAttribute()
    {
        return $this->year . '/' . $this->sno;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if (!empty($branch_id)) {
            return $query->where('branch_id', $branch_id);
        } else {
            return $query;
        }
    }

    public function getCustNameAttribute()
    {

        $str = '';
        if (!empty($this->customer_gender)) {
            $str .= custPrefix($this->customer_gender) . '. ';
        }

        if (!empty($this->customer_name)) {
            $str .= $this->customer_name . ' ';
        }

        if (!empty($this->customer_relationship)) {
            $str .= custRel($this->customer_relationship) . ' ';
        }

        if (!empty($this->customer_guardian_name)) {
            $str .= $this->customer_guardian_name;
        }
        return $str;
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

    public function financer()
    {
        return $this->belongsTo(BankFinancer::class, 'hyp_financer');
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'salesman_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id')
            ->select('id', 'bike_branch', 'bike_dealer', 'bike_brand', 'bike_model', 'bike_model_color', 'sku', 'engine_number', 'vin_number', 'hsn_number', 'key_number', 'transfer_status', 'tyre_brand_id', 'gst_rate', 'battery_brand_id', 'created_at')
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
                },
                'gst_detail' => function ($tax_detail) {
                    $tax_detail->select('id', 'gst_rate', 'cgst_rate', 'sgst_rate');
                }
            ]);
    }
}
