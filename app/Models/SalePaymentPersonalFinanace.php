<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentPersonalFinanace extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_personal_finanace';

    protected $fillable = [
        'sale_id',
        'sale_payment_account_id',
        'payment_name',
        'emi_total_amount',
        'emi_principal_amount',
        'emi_intrest_amount',
        'emi_due_date',
        'adjust_amount',
        'adjust_date',
        'adjust_note',
        'emi_due_revised_amount',
        'emi_due_revised_note',
        'amount_paid',
        'amount_paid_date',
        'amount_paid_source',
        'amount_paid_note',
        'collected_by',
        'status'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        self::saved(function ($model) {
            updateDuesOrPaidBalance($model->sale_payment_account_id);
        });

        self::created(function ($model) {
            updateDuesOrPaidBalance($model->sale_payment_account_id);
        });

        self::updated(function ($model) {
            updateDuesOrPaidBalance($model->sale_payment_account_id);
        });

        self::deleted(function ($model) {
            updateDuesOrPaidBalance($model->sale_payment_account_id);
        });
    }
}
