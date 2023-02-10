<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'u_cities';

    protected $fillable = [
        'district_id',
        'city_name',
        'city_code',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->city_name = !empty($model->city_name) ? strtoupper($model->city_name) : "";
            $model->city_code = !empty($model->city_code) ? strtoupper($model->city_code) : "";
        });
    }


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
