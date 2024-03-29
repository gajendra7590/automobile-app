<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoRegistration extends Model
{

    use HasFactory, CommonHelper;

    protected $table = 'rto_registration';

    protected $appends = ['cust_name', 'serial_number'];

    protected $fillable = [
        'sno',
        'year',
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
        'chasis_number',
        'engine_number',
        'broker_name',
        'broker_id',
        'financer_name',
        'financer_id',
        'gst_rto_rate_id',
        'gst_rto_rate_percentage',
        'ex_showroom_amount',
        'tax_amount',
        'hyp_amount',
        'tr_amount',
        'fees',
        'total_amount',
        'rc_number',
        'rc_status',
        'submit_date',
        'recieved_date',
        'customer_given_name',
        'customer_given_date',
        'customer_given_note',
        'remark',
        'created_by',
        'updated_by',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = RtoRegistration::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if (isset($findModel->year) && ($year == $findModel->year)) {
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

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if (!empty($branch_id)) {
            return $query->whereHas('sale', function ($sale) use ($branch_id) {
                $sale->where('branch_id', $branch_id);
            });
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

    public function agent()
    {
        return $this->belongsTo(RtoAgent::class, 'rto_agent_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'contact_state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'contact_district_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'contact_city_id');
    }

    public function tax()
    {
        return $this->belongsTo(GstRtoRates::class, 'gst_rto_rate_id');
    }
}
