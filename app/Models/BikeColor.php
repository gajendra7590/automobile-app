<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeColor extends Model
{
    use HasFactory;

    protected $table = 'bike_colors';

    protected $fillable = [
        'bike_model',
        'model_variant_id',
        'color_name',
        'color_code',
        'sku_code',
        'active_status',
        'sku_sale_price_id',
        'is_editable'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->sku_code = !empty($model->sku_code) ? strtoupper($model->sku_code) : "";
            $model->color_name = !empty($model->color_name) ? strtoupper($model->color_name) : "";
        });

        self::updating(function ($model) {
            $model->sku_code = !empty($model->sku_code) ? strtoupper($model->sku_code) : "";
            $model->color_name = !empty($model->color_name) ? strtoupper($model->color_name) : "";
        });
    }

    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'bike_model');
    }

    public function variant()
    {
        return $this->belongsTo(BikeModelVariant::class, 'model_variant_id');
    }

    public function price()
    {
        return $this->hasOne(SkuSalePrice::class, 'model_color_id', 'id');
    }

    public function sku_price()
    {
        return $this->hasOne(SkuSalePrice::class, 'sku_code', 'sku_code');
    }
}
