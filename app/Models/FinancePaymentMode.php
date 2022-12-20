<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePaymentMode extends Model
{
    use HasFactory;

    protected $table = 'finance_payment_modes';

    protected $fillable = [
        'name',
        'display_name',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
