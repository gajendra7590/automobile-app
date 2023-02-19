<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDealerPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'purchase_dealer_payment_history';

    protected $fillable = [
        'sno',
        'year',
        'dealer_id',
        'payment_amount',
        'payment_mode',
        'payment_note',
        'payment_date'
    ];

    protected  $hidden = [];

    protected $casts = [];

    protected $appends = ['serial_number'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            //YearWise Genertae Unique ID
            $findModel = PurchaseDealerPaymentHistory::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
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

    public function dealer()
    {
        return $this->belongsTo(BikeDealer::class, 'dealer_id');
    }
}
