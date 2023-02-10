<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'u_districts';

    protected $fillable = [
        'state_id',
        'district_name',
        'district_code',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->district_name = !empty($model->district_name) ? strtoupper($model->district_name) : "";
            $model->district_code = !empty($model->district_code) ? strtoupper($model->district_code) : "";
        });
    }


    /**
     * Relation with states
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
