<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeModelVariant extends Model
{
    use HasFactory;

    protected $table = 'bike_model_variants';

    protected $fillable = [
        'model_id',
        'variant_name',
        'active_status',
        'is_editable'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->variant_name = !empty($model->variant_name) ? strtoupper($model->variant_name) : "";
        });

        self::updating(function ($model) {
            $model->variant_name = !empty($model->variant_name) ? strtoupper($model->variant_name) : "";
        });
    }

    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'model_id');
    }
}
