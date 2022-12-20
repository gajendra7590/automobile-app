<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceHistory extends Model
{
    use HasFactory;

    protected $table = 'finance_histories';

    protected $fillable = [
        'bike_finance_id',
        'payable_amount',
        'interest_amount',
        'principal_amount',
        'remaining_amount',
        'installment_date',
        'payment_date',
        'payment_mode',
        'payment_remark',
        'payment_clear_status',
        'payment_clear_date',
        'payment_pending_remark',
        'payment_status',
        'payment_collected_by',
        'payment_collection_handover_to',
        'payment_collection_handover_date',
        'payment_verification_status',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
