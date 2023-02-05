<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuSalePrice extends Model
{
    use HasFactory;

    protected $table = 'sku_sale_prices';

    protected $fillable = [
        'model_color_id',
        'sku_code',
        'ex_showroom_price',
        'registration_amount',
        'insurance_amount',
        'hypothecation_amount',
        'accessories_amount',
        'other_charges',
        'total_amount',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];


    public static function boot()
    {
        parent::boot();
        self::saving(function ($model) {
            $model->sku_code = strtoupper($model->sku_code);
        });

        self::creating(function ($model) {
            $model->sku_code = strtoupper($model->sku_code);
        });

        self::updated(function ($model) {
            $model->sku_code = strtoupper($model->sku_code);
            BikeColor::where('id', $model->model_color_id)->update(['sku_sale_price_id' => $model->id]);
        });

        //After Create / Update
        self::created(function ($model) {
            BikeColor::where('id', $model->model_color_id)->update(['sku_sale_price_id' => $model->id]);
        });
    }
}
