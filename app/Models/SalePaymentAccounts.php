<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentAccounts extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_accounts';

    protected $fillable = [
        'account_uuid',
        'sale_id',
        'sales_total_amount',
        'deposite_amount',
        'deposite_source',
        'deposite_source_note',
        'deposite_date',
        'deposite_collected_by',
        'due_amount',
        'due_date',
        'due_note',
        'due_payment_source',
        'financier_id',
        'financier_note',
        'finance_terms',
        'no_of_emis',
        'rate_of_interest',
        'processing_fees',
        'status',
        'status_closed_note',
        'status_closed_by'
    ];


    protected  $hidden = [];

    protected $casts = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->account_uuid = random_uuid('acc');
        });
    }
}
