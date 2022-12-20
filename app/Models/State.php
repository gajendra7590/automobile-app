<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'u_states';

    protected $fillable = [
        'country_id',
        'state_name',
        'state_code',
        'active_status'
    ];


    protected  $hidden = [];

    protected $casts = [];

    /**
     * Relation with Country
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'id', 'district_id');
    }
}
