<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryBrand extends Model
{
    use HasFactory;

    protected $table = 'battery_brands';

    protected $fillable = [
        'name',
        'description',
        'disable_edit',
        'active_status',
    ];

    protected  $hidden = [];

    protected $casts = [];


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
