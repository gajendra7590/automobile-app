<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReturnSaleRefund extends Model
{
    use HasFactory;
    protected $table = 'customer_return_sale_refunds';

    protected $fillable = [
        'sno',
        'year',
        'sale_id',
        'sale_account_id',
        'amount_refund',
        'amount_refund_source',
        'amount_refund_date',
        'payment_refund_note',
        'payment_collected_by'
    ];

    protected $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = CustomerReturnSale::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
            $year = date('Y');
            $sno  = 1;
            if ($year == $findModel->year) {
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
}
