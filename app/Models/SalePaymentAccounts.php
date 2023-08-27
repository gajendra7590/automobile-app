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
        'sno',
        'year',
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
        'bank_finance_amount',
        'personal_finance_paid_balance',
        'cash_paid_balance',
        'personal_finance_status',
        'personal_finance_amount',
        'payment_setup'
    ];


    protected  $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];

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

    //TRANSACTION FOR
    const TRANSACTION_TYPE_CB = 1;
    const TRANSACTION_TYPE_BF = 2;
    const TRANSACTION_TYPE_PF = 3;



    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->account_uuid = random_uuid('acc');

            //YearWise Genertae Unique ID
            $findModel = SalePaymentAccounts::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if (isset($findModel->year) && ($year == $findModel->year)) {
                $sno = ($findModel->sno) + 1;
            }
            $model->year = $year;
            $model->sno = $sno;
        });
    }

    public function getSerialNumberAttribute()
    {
        return $this->year . '/' . $this->sno;
    }

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if (!empty($branch_id)) {
            return $query->whereHas('sale', function ($sale) use ($branch_id) {
                $sale->where('branch_id', $branch_id);
            });
        }
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function downPaymentByCustomer()
    {
        return $this->hasOne(SalePaymentCash::class, 'sale_payment_account_id')->where('is_dp', '1');
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

    public function cash_transactions()
    {
        return $this->hasMany(SalePaymentCash::class, 'sale_payment_account_id', 'id');
    }

    public function bank_transactions()
    {
        return $this->hasMany(SalePaymentBankFinanace::class, 'sale_payment_account_id', 'id');
    }

    public function personal_transactions()
    {
        return $this->hasMany(SalePaymentPersonalFinanace::class, 'sale_payment_account_id', 'id');
    }
}
