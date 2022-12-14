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
        'color_name',
        'color_code',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'bike_model');
    }
}
