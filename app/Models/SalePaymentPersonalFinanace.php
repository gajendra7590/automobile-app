<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentPersonalFinanace extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_personal_finanace';

    protected $fillable = [
        'sno',
        'year',
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
        'status',
        'received_in_bank'
    ];


    protected  $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = SalePaymentPersonalFinanace::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if (isset($findModel->year) && ($year == $findModel->year)) {
                $sno = ($findModel->sno) + 1;
            }
            $model->year = $year;
            $model->sno = $sno;
        });

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
     * MApping With salesman
     */
    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'collected_by');
    }

    /**
     * Bank account
     */
    public function receivedBank()
    {
        return $this->belongsTo(BankAccounts::class, 'received_in_bank');
    }
}
