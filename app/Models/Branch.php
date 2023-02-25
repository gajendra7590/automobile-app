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
        'active_status'
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
