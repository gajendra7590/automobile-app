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

    public function model()
    {
        return $this->belongsTo(BikeModel::class, 'model_id');
    }

    protected  $hidden = [];

    protected $casts = [];
}
