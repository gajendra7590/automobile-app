<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoAgentPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'rto_agent_payment_history';

    protected $fillable = [
        'sno',
        'year',
        'rto_agent_id',
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
            $findModel = SalePaymentTransactions::select('id', 'sno', 'year')->orderBy('id', 'DESC')->first();
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


    public function agent()
    {
        return $this->belongsTo(RtoAgent::class, 'rto_agent_id');
    }
}
