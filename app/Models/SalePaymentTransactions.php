<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentTransactions extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_transactions';

    protected $fillable = [
        'sno',
        'year',
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
        'reference_id',
        'is_dp'
    ];


    protected  $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = SalePaymentTransactions::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
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
