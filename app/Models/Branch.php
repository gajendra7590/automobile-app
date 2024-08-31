<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'branch_name',
        'branch_email',
        'branch_phone',
        'branch_phone2',
        'branch_address_line',
        'branch_city',
        'branch_district',
        'branch_state',
        'branch_county',
        'branch_pincode',
        'branch_more_detail',
        'gstin_number',
        'account_number',
        'ifsc_code',
        'bank_name',
        'bank_branch',
        'branch_logo',
        'active_status',
        'is_editable',
        'mapping_brand_id'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->branch_name = !empty($model->branch_name) ? strtoupper($model->branch_name) : "";
        });

        self::updating(function ($model) {
            $model->branch_name = !empty($model->branch_name) ? strtoupper($model->branch_name) : "";
        });
    }


    public function mappedBrand() {
        return $this->belongsTo(BikeBrand::class,'mapping_brand_id');
    }

    public function brand() {
        return $this->hasOne(BikeBrand::class,'branch_id','id');
    }

    /**
     * Function for assesor
     */
    public function getBranchLogoAttribute($logo)
    {
        if (!empty($logo)) {
            return url($logo);
        } else {
            return "";
        }
    }
}
