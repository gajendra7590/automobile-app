<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentAccounts extends Model
{
    use CommonHelper;
    use HasFactory;

    protected $table = 'sale_payment_accounts';

    protected $fillable = [
        'account_uuid',
        'sale_id',
        'sales_total_amount',
        'down_payment',
        'due_payment_source',
        'financier_id',
        'financier_note',
        'finance_terms',
        'no_of_emis',
        'rate_of_interest',
        'processing_fees',
        'status',
        'status_closed_note',
        'status_closed_by',
        'cash_outstaning_balance',
        'cash_paid_balance',
        'cash_status',
        'bank_finance_outstaning_balance',
        'bank_finance_paid_balance',
        'bank_finance_status',
        'personal_finance_paid_balance',
        'cash_paid_balance',
        'personal_finance_status',
        'payment_setup'
    ];


    protected  $hidden = [];

    protected $casts = [];

    //TRANSACTION TYPES
    const TRANS_TYPE_CREDIT = 1;
    const TRANS_TYPE_DEBIT  = 2;

    //TRANSACTION STATUS
    const PAY_STATUS_PENDING = 0;
    const PAY_STATUS_PAID = 1;
    const PAY_STATUS_HOLD = 2;
    const PAY_STATUS_FAILED = 3;

    //NORMAL STATUS
    const STATUS_DUE   = 0;
    const STATUS_PAID  = 1;

    //ACCOUNT STATUS
    const ACC_STATUS_OPEN = 0;
    const ACC_STATUS_CLOSE = 1;



    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->account_uuid = random_uuid('acc');
        });
    }

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if ($branch_id != '0' || $branch_id != 'null') {
            return $query->whereHas('sale', function ($sale) use ($branch_id) {
                $sale->where('branch_id', $branch_id);
            });
        }
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function installments()
    {
        return $this->hasMany(SalePaymentInstallments::class, 'sale_payment_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(SalePaymentTransactions::class, 'sale_payment_account_id');
    }

    public function financier()
    {
        return $this->belongsTo(BankFinancer::class, 'financier_id');
    }

    //financier_id
}
