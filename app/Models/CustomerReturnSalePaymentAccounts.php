<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReturnSalePaymentAccounts extends Model
{
    use CommonHelper;
    use HasFactory;

    protected $table = 'customer_return_sale_payment_accounts';

    protected $fillable = [
        'account_uuid',
        'sale_id',
        'old_sale_id',
        'old_id',
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
        'total_pay_with_intrest',
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
        return $this->belongsTo(ReturnSale::class, 'sale_id');
    }

    public function installments()
    {
        return $this->hasMany(ReturnSalePaymentInstallments::class, 'sale_payment_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(ReturnSalePaymentTransactions::class, 'sale_payment_account_id');
    }

    public function financier()
    {
        return $this->belongsTo(BankFinancer::class, 'financier_id');
    }

    //financier_id
}
