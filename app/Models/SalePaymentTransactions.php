<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentTransactions extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_transactions';

    protected $fillable = [
        'sale_id',
        'sale_payment_account_id',
        'transaction_for',
        'transaction_name',
        'transaction_amount',
        'transaction_paid_source',
        'transaction_paid_source_note',
        'transaction_paid_date',
        'trans_type',
        'status',
        'reference_id'
    ];


    protected  $hidden = [];

    protected $casts = [];

    /**
     * MApping With Account
     */
    public function account()
    {
        return $this->belongsTo(SalePaymentAccounts::class, 'sale_payment_account_id');
    }

    /**
     * MApping With Sales
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    /**
     * MApping With User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'payment_collected_by');
    }

    /**
     * MApping With Installment
     */
    public function installment()
    {
        return $this->belongsTo(SalePaymentInstallments::class, 'sale_payment_installment_id');
    }
}
