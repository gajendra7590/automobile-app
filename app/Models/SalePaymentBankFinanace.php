<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentBankFinanace extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_bank_finanace';

    protected $fillable = [
        'sale_id',
        'sale_payment_account_id',
        'payment_name',
        'credit_amount',
        'debit_amount',
        'change_balance',
        'due_date',
        'paid_source',
        'paid_date',
        'paid_note',
        'collected_by',
        'trans_type',
        'status'
    ];


    protected  $hidden = [];

    protected $casts = [];
}
