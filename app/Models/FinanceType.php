<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceType extends Model
{
    use HasFactory;

    protected $table = 'finance_types';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
