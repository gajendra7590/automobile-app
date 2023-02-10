<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'u_countries';

    protected $fillable = [
        'country_name',
        'country_code',
        'active_status'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->country_name = !empty($model->country_name) ? strtoupper($model->country_name) : "";
            $model->country_code = !empty($model->country_code) ? strtoupper($model->country_code) : "";
        });
    }

    protected  $hidden = [];

    protected $casts = [];
}
