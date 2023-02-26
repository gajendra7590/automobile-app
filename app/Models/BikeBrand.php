<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeBrand extends Model
{
    use HasFactory;

    protected $table = 'bike_brands';

    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'description',
        'active_status'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->name = !empty($model->name) ? strtoupper($model->name) : "";
        });

        self::updating(function ($model) {
            $model->name = !empty($model->name) ? strtoupper($model->name) : "";
        });
    }

    public function bike_modals()
    {
        return $this->hasMany(BikeModel::class, 'brand_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
