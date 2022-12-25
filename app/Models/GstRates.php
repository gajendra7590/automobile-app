<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstRates extends Model
{
    use HasFactory;

    protected $table = "gst_rates";

    protected $fillable = [
        'gst_rate',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'active_status'
    ];

    protected $hidden = [];

    protected $casts = [];
}
