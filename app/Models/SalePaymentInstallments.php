<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentInstallments extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_installments';

    protected $fillable = [
        'installment_uuid',
        'sale_id',
        'sale_payment_account_id',
        'emi_title',
        'loan_total_amount',
        'emi_due_amount',
        'emi_due_principal',
        'emi_due_intrest',
        'emi_due_date',
        'amount_paid',
        'amount_paid_date',
        'amount_paid_source',
        'amount_paid_note',
        'pay_due',
        'status'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->installment_uuid = random_uuid('inst');
        });
    }
}
