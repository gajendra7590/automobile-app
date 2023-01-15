<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;

    protected $table = "salesmans";

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'active_status',
        'is_editable'
    ];

    protected $hidden = [];

    protected $casts = [];
}
