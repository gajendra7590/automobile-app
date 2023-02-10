<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeModel extends Model
{
    use HasFactory;

    protected $table = 'bike_models';

    protected $fillable = [
        'brand_id',
        'model_name',
        'model_code',
        'variant_code',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->model_name = !empty($model->model_name) ? strtoupper($model->model_name) : "";
            $model->model_code = !empty($model->model_code) ? strtoupper($model->model_code) : "";
        });

        self::updating(function ($model) {
            $model->model_name = !empty($model->model_name) ? strtoupper($model->model_name) : "";
            $model->model_code = !empty($model->model_code) ? strtoupper($model->model_code) : "";
        });
    }

    public function bike_brand()
    {
        return $this->belongsTo(BikeBrand::class, 'brand_id');
    }
}
