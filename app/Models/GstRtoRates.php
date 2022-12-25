<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstRtoRates extends Model
{
    use HasFactory;


    protected $table = "gst_rto_rates";

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
