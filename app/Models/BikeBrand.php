<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeBrand extends Model
{
    use HasFactory;

    protected $table = 'bike_brands';

    protected $fillable = [
        'name',
        'code',
        'description',
        'active_status'
    ];

    public function bike_modals()
    {
        return $this->hasMany(BikeModel::class, 'brand_id');
    }

    protected  $hidden = [];

    protected $casts = [];
}
