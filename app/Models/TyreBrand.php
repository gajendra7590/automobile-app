<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TyreBrand extends Model
{
    use HasFactory;

    protected $table = 'tyre_brands';

    protected $fillable = [
        'name',
        'description',
        'disable_edit',
        'active_status',
    ];

    protected  $hidden = [];

    protected $casts = [];


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
