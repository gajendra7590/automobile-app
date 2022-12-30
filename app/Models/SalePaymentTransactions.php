<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentTransactions extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_transactions';

    protected $fillable = [
        'transaction_uuid',
        'sale_id',
        'sale_payment_account_id',
        'transaction_title',
        'amount_paid',
        'amount_paid_source',
        'amount_paid_source_note',
        'amount_paid_date',
        'status',
        'payment_collected_by',
        'sale_payment_installment_id',
        'pay_due',
        'status'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_uuid = random_uuid('tran');
        });
    }
}
