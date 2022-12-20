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
        'active_status'
    ];

    public function bike_brand()
    {
        return $this->belongsTo(BikeBrand::class, 'brand_id');
    }

    protected  $hidden = [];

    protected $casts = [];
}
